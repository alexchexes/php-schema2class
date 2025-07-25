<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollectionFilterFactory;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\Decorator\PropertyDecoratorInterface;
use Helmich\Schema2Class\Generator\Property\PropertyBuilder;
use Helmich\Schema2Class\Generator\Property\PropertyQuery;
use Helmich\Schema2Class\Generator\Property\RenameablePropertyInterface;
use Helmich\Schema2Class\Generator\Property\Type\ObjectArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\PrimitiveArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Generator\Property\Type\ReferenceArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\TypedArrayProperty;
use Helmich\Schema2Class\Generator\PropertyGenerator;
use Helmich\Schema2Class\Util\SchemaUtils;
use Helmich\Schema2Class\Util\StringUtils;
use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\DeclareStatement;
use Laminas\Code\Generator\ClassGenerator as LaminasClassGenerator;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlock\Tag\ThrowsTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\FileGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generates the `Laminas\Code` representation of a PHP class for a single schema.
 *
 * Called by {@see SchemaToClass} (which also prepares and hands the {@see GeneratorRequest} here)
 * after all {@see PropertyInterface} objects are collected.
 * 
 * This class is responsible only for building the Laminas\Code representation;
 * the actual writing of files happens outside of this class.
 */
class ClassGenerator
{
    public function __construct(
        private GeneratorRequest $generatorRequest,
        private WriterInterface $writer,
        private OutputInterface $output
    ) {}
    
    public function generateClass(array $schema)
    {
        // remove metadata like descriptions from schema if such option is set, but keep them
        // for building property documentation
        $validationSchema = $schema;
        if ($this->generatorRequest->getOptions()->getNoSchemaMetadata()) {
            $this->stripSchemaMetadata($validationSchema);
        }

        $classProperties = [];

        $schemaProperty = $this->createSchemaProperty($validationSchema);
        $classProperties[] = $schemaProperty;

        $defaults      = self::collectDefaults($schema, $this->generatorRequest);
        $hasDefaults   = !empty($defaults);
        $this->generatorRequest->setCurrReqHasDefaults($hasDefaults);

        if ($defaults) {
            $classProperties[] = $this->createDefaultsProperty($defaults);
        }

        $schemaProperties = $this->collectPropertiesFromSchema($schema, $this->generatorRequest);

        $this->ensureUniquePropertyNames(
            $schemaProperties,
            $this->generatorRequest->getOptions()->getPreservePropertyNames(),
        );

        foreach ($schemaProperties as $property) {
            $property->generateSubTypes($this->writer, $this->output);
        }

        $hasOptionalNullable = $this->hasOptionalNullable($schemaProperties);

        if ($hasOptionalNullable) {
            $classProperties[] = $this->createProvidedOptionalsProperty();
        }

        $classProperties = [
            ...$classProperties,
            ...$this->generateProperties($schemaProperties),
        ];

        $methods = [
            $this->generateConstructor($schemaProperties),
            ...$this->generateGetterMethods($schemaProperties),
            ...$this->generateSetterMethods($schemaProperties),
            $this->generateBuildMethod($schemaProperties, $defaults, $hasOptionalNullable),
            $this->generateToArrayMethod($schemaProperties, $defaults),
            $this->generateToStdClassMethod($schemaProperties, $defaults),
            $this->generateValidateMethod(),
            $this->generateCloneMethod($schemaProperties),
            $hasOptionalNullable ? $this->generateIsProvidedMethod() : null,
        ];
        $methods = array_values(array_filter($methods));
        $this->ensureUniqueMethodNames($methods);

        $this->generateClassFile($this->generatorRequest, $schema, $classProperties, $methods, $this->writer);
    }
    
    private function stripSchemaMetadata(array &$node): void
    {
        $metaFields = [
            'description',
            'title',
            'examples',
            'deprecated',
            'default',
            'readOnly',
            'writeOnly',
            '$id',
            '$schema',
            '$comment',
        ];

        foreach ($node as $key => &$value) {
            if (in_array($key, $metaFields, true)) {
                unset($node[$key]);
                continue;
            }
            if (is_array($value)) {
                $this->stripSchemaMetadata($value);
            }
        }
    }

    private function collectPropertiesFromSchema(array $schema, GeneratorRequest $req): PropertyCollection
    {
        $properties = new PropertyCollection();

        if (isset($schema['properties'])) {
            foreach ($schema['properties'] as $key => $definition) {
                $key = (string) $key;
                $isRequired = isset($schema['required']) && in_array($key, $schema['required']);

                $property = PropertyBuilder::buildPropertyFromSchema($req, $key, $definition, $isRequired);
                $properties->add($property);
            }
        }

        return $properties;
    }

    private static function collectDefaults(array $schema, GeneratorRequest $req): array
    {
        $defaults = [];
        if (!isset($schema['properties']) || !is_array($schema['properties'])) {
            return $defaults;
        }

        $raw = $req->getRawSchema();
        $rawProps = null;
        if ($raw instanceof \stdClass && isset($raw->properties) && $raw->properties instanceof \stdClass) {
            $rawProps = $raw->properties;
        }

        foreach ($schema['properties'] as $key => $def) {
            $found = false;
            $rawKey = (string)$key;
            $rawDef = $rawProps && property_exists($rawProps, $rawKey) ? $rawProps->{$rawKey} : null;
            $d = self::extractDefault($def, $req, $found, $rawDef);
            if ($found) {
                $defaults[$key] = $d;
            }
        }

        return $defaults;
    }

    private static function extractDefault(array $def, GeneratorRequest $req, bool &$found = false, object|null $rawDef = null): array
    {
        if (array_key_exists('default', $def)) {
            $found = true;
            $val = $def['default'];
            $type = null;
            if (is_array($val)) {
                if ($rawDef !== null && property_exists($rawDef, 'default')) {
                    $rawDefault = $rawDef->default;
                    if ($rawDefault instanceof \stdClass) {
                        $type = 'object';
                    } elseif (is_array($rawDefault)) {
                        $type = 'array';
                    }
                } else {
                    $type = SchemaUtils::extractTypeForDefault($def);
                }
            }
            $default = ['default' => $val];
            if ($type !== null && $type !== '') {
                $default['type'] = $type;
            }
            return $default;
        }

        if (isset($def['$ref'])) {
            $schema = $req->lookupSchema($def['$ref']);
            $d = self::extractDefault($schema, $req, $found);
            if ($found) {
                return $d;
            }
        }

        foreach (['anyOf', 'oneOf', 'allOf'] as $k) {
            if (isset($def[$k]) && is_array($def[$k])) {
                foreach ($def[$k] as $i => $sub) {
                    $rawSub = null;
                    if ($rawDef !== null && isset($rawDef->{$k}) && is_array($rawDef->{$k})) {
                        $rawSub = $rawDef->{$k}[$i] ?? null;
                    }
                    if (isset($sub['$ref'])) {
                        $sub = $req->lookupSchema($sub['$ref']);
                    }
                    if (is_array($sub)) {
                        $d = self::extractDefault($sub, $req, $found, $rawSub);
                        if ($found) {
                            return $d;
                        }
                    }
                }
            }
        }

        $found = false;
        return ['default' => null, 'type' => null];
    }

    public function createSchemaProperty(array $validationSchema): PropertyGenerator
    {
        $prop = new PropertyGenerator(
            'schema',
            $validationSchema,
            PropertyGenerator::FLAG_PRIVATE | PropertyGenerator::FLAG_STATIC,
        );

        $prop->setDocBlock(new DocBlockGenerator(
            'Schema used to validate input for creating instances of this class',
            null,
            [new GenericTag('var', 'array')],
        ));

        if ($this->generatorRequest->isAtLeastPHP('7.4')) {
            $prop->setTypeHint('array');
        }
        if ($this->generatorRequest->getOptions()->getSingleLineSchema()) {
            $prop->setSingleLineDefaultValue(true);
        }

        return $prop;
    }

    public function createDefaultsProperty(array $defaults): ?PropertyGenerator
    {
        $prop = new PropertyGenerator('_defaults', $defaults, PropertyGenerator::FLAG_PRIVATE | PropertyGenerator::FLAG_STATIC);
        $prop->setDocBlock(new DocBlockGenerator(
            'Default values from the schema',
            null,
            [new GenericTag('var', 'array')],
        ));
        if ($this->generatorRequest->isAtLeastPHP('7.4')) {
            $prop->setTypeHint('array');
        }
        if ($this->generatorRequest->getOptions()->getSingleLineSchema()) {
            $prop->setSingleLineDefaultValue(true);
        }

        return $prop;
    }

    public function createProvidedOptionalsProperty(): ?PropertyGenerator
    {
        $setVisibility = ($this->generatorRequest->getNoGetters() && $this->generatorRequest->getNoSetters())
            ? PropertyGenerator::FLAG_PUBLIC
            : PropertyGenerator::FLAG_PRIVATE;

        $prop = new PropertyGenerator('_providedOptionals', [] , $setVisibility);
        $prop->setDefaultValue([]);
        $prop->setSingleLineDefaultValue(true);

        if ($this->generatorRequest->isAtLeastPHP("7.4")) {
            $prop->setTypeHint("array");
        }

        $prop->setDocBlock(new DocBlockGenerator(
            "Map of optional nullable property names that were explicitly set",
            null,
            [new GenericTag('var', 'array<string,true>')]
        ));

        return $prop;
    }

    /**
     * @return PropertyGenerator[]
     */
    public function generateProperties(PropertyCollection $properties): array
    {
        $propertyGenerators = [];

        $visibility = $this->generatorRequest->getNoGetters()
            ? PropertyGenerator::FLAG_PUBLIC
            : PropertyGenerator::FLAG_PRIVATE;

        foreach ($properties as $property) {
            $schema     = $property->schema();
            $isOptional = false;
            $prop       = new PropertyGenerator(
                $property->name(),
                $property->formatValue(null),
                $visibility
            );

            if ($property instanceof OptionalPropertyDecorator) {
                $isOptional = true;
            }

            $tags = [new GenericTag("var", trim($property->typeAnnotation()))];
            if (PropertyQuery::isDeprecated($property)) {
                $tags[] = new GenericTag("deprecated");
            }

            $docBlock = new DocBlockGenerator(
                $schema["description"] ?? null,
                null,
                $tags
            );
            $docBlock->setWordWrap(false);

            $prop->setDocBlock($docBlock);

            $typeHint = $property->typeHint($this->generatorRequest->getTargetPHPVersion());
            if ($this->generatorRequest->isAtLeastPHP("7.4") && $typeHint !== null) {
                $prop->setTypeHint($typeHint);
            }

            // omit default `null` for every required field, unsless default is specified in the schema
            $prop->omitDefaultValue(!$isOptional);

            $propertyGenerators[] = $prop;
        }

        return $propertyGenerators;
    }

    public function generateConstructor(PropertyCollection $properties): ?MethodGenerator
    {
        $params      = [];
        $tags        = [];
        $assignments = [];

        $requiredProperties = $properties->filter(PropertyCollectionFilterFactory::required());

        foreach ($requiredProperties as $requiredProperty) {
            $paramName = $requiredProperty->name();
            $params[]  = new ParameterGenerator(
                $paramName,
                $requiredProperty->typeHint($this->generatorRequest->getTargetPHPVersion())
            );

            $tags[] = new ParamTag(
                $paramName,
                [$requiredProperty->typeAnnotation()]
            );

            $assignments[] = "\$this->{$requiredProperty->name()} = \${$paramName};";
        }

        if ($assignments === []) {
            return null;
        }

        $docBlock = new DocBlockGenerator("", "", $tags);
        $docBlock->setWordWrap(false);

        return new MethodGenerator(
            "__construct",
            $params,
            MethodGenerator::FLAG_PUBLIC,
            join("\n", $assignments),
            $docBlock
        );
    }

    /**
     * @param PropertyCollection $properties
     * @return MethodGenerator
     */
    public function generateBuildMethod(PropertyCollection $properties, array $defaults = [], bool $hasOptionalNullable = false): MethodGenerator
    {
        // --- Params

        $inputArgName = 'input';
        $validateArgName = 'validate';
        $materializeArgName = 'materializeDefaults';

        $paramType = null;
        if ($this->generatorRequest->isAtLeastPHP("8.0")) {
            $paramType = "array|object";
        }

        $inputParam = new ParameterGenerator($inputArgName, $paramType);
        $validationParam = new ParameterGenerator($validateArgName, "bool", true);
        $materializeParam = $defaults ? new ParameterGenerator($materializeArgName, "bool", false) : null;

        $params = [$inputParam, $validationParam];
        if ($defaults) {
            $params[] = $materializeParam;
        }

        // --- PHPDoc

        $docBlockParams = [
            new ParamTag($inputArgName, ["array|object"], "Input data"),
            new ParamTag($validateArgName, ["bool"], "Set this to false to skip validation; use at own risk"),
        ];
        if ($defaults) {
            $docBlockParams[] = new ParamTag($materializeArgName, ["bool"], "Apply defaults defined in schema when missing");
        }
        $docBlockParams[] = new ReturnTag([$this->generatorRequest->getTargetClass()], "Created instance");
        $docBlockParams[] = new ThrowsTag("\\InvalidArgumentException");

        $docBlock = new DocBlockGenerator(
            "Builds a new instance from an input array",
            null,
            $docBlockParams
        );
        $docBlock->setWordWrap(false);

        // --- BODY

        $body = $this->generateBuildMethodBody(
            inputArgName: $inputArgName,
            validateArgName: $validateArgName,
            materializeArgName: $materializeArgName,
            defaults: $defaults,
            properties: $properties,
            hasOptionalNullable: $hasOptionalNullable,
        );

        // --- Combine everything into the Laminas `MethodGenerator`

        $method = new MethodGenerator(
            'buildFromInput',
            $params,
            MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC,
            $body,
            $docBlock
        );

        if ($this->generatorRequest->isAtLeastPHP("7.0")) {
            $method->setReturnType(
                $this->generatorRequest->getTargetNamespace() . "\\" . $this->generatorRequest->getTargetClass()
            );
        }

        return $method;
    }

    /** 
     * Generates the whole body for the `buildFromInput` method based on conditions
     * determined from the provided arguments and the current `generatorRequest` options.
     * 
     * Calls `PropertyCollection::generateInputToTypeConversionCode` to generate a full list 
     * of mappings from the schema object to class properties, including all necessary checks and conversions.
     * 
     * It also mutatingly sets some `generatorRequest` properties to propagate information required
     * by nested contexts so that property conversion code is generated properly.
     */
    private function generateBuildMethodBody(
        string $inputArgName,
        string $validateArgName,
        string $materializeArgName,
        array $defaults,
        PropertyCollection $properties,
        bool $hasOptionalNullable,
    ): string
    {
        $objVarName = 'obj';
        if ($properties->hasPropertyWithName($objVarName)) {
            $objVarName = '_obj';
            $i = 2;
            while ($properties->hasPropertyWithName($objVarName)) {
                $objVarName = '_obj' . $i;
                $i++;
            }
        }

        $optionalProperties = $properties->filter(PropertyCollectionFilterFactory::optional());
        $assignments = [];
        foreach ($optionalProperties as $optionalProperty) {
            $name = $optionalProperty->name();
            $assignments[] = "\${$objVarName}->{$name} = \${$name};";
        }
        
        $inputArgAlias = $inputArgName;
        if ($properties->hasPropertyWithKey($inputArgAlias)) {
            $inputArgAlias = '_input';
            $i = 2;
            while ($properties->hasPropertyWithKey($inputArgAlias)) {
                $inputArgAlias = '_input' . $i;
                $i++;
            }
        }
        
        $validateArgAlias = $validateArgName;
        if ($properties->hasPropertyWithName($validateArgAlias)) {
            $validateArgAlias = '_validate';
            $i = 2;
            while ($properties->hasPropertyWithName($validateArgAlias)) {
                $validateArgAlias = '_validate' . $i;
                $i++;
            }
        }

        $materializeArgAlias = $materializeArgName;
        if ($defaults && $properties->hasPropertyWithName($materializeArgAlias)) {
            $materializeArgAlias = '_materializeDefaults';
            $i = 2;
            while ($properties->hasPropertyWithName($materializeArgAlias)) {
                $materializeArgAlias = '_materializeDefaults' . $i;
                $i++;
            }
        }

        // Store validate/materialize aliases globally on the request, from where they are read by
        // property generators, unlike the `input` alias, which is passed directly to every nested
        // mapping call since it may change at certain levels
        $this->generatorRequest->setCurrValidateArgAlias($validateArgAlias);
        $this->generatorRequest->setCurrMaterializeArgAlias($defaults ? $materializeArgAlias : null);

        $requiredProperties = $properties->filter(PropertyCollectionFilterFactory::required());
        $constructorParams = [];
        foreach ($requiredProperties as $requiredProperty) {
            $constructorParams[] = '$' . $requiredProperty->name();
        }

        $aliasesLines = '';
        if ($inputArgName !== $inputArgAlias) {
            $aliasesLines .= "\${$inputArgAlias} = \${$inputArgName};\nunset(\${$inputArgName});\n";
        }
        if ($validateArgName !== $validateArgAlias) {
            $aliasesLines .= "\${$validateArgAlias} = \${$validateArgName};\nunset(\${$validateArgName});\n";
        }
        if ($materializeArgName !== $materializeArgAlias) {
            $aliasesLines .= "\${$materializeArgAlias} = \${$materializeArgName};\nunset(\${$materializeArgName});\n";
        }
        if ($aliasesLines) {
            $aliasesLines .= "\n";
        }

        $inputGuard = '';
        if (!$this->generatorRequest->isAtLeastPHP('8.0')) {
            $inputGuard = 
                "if (!is_array(\$$inputArgAlias) && !is_object(\$$inputArgAlias)) {\n" .
                "    throw new \\InvalidArgumentException(\n" .
                "        'Input to buildFromInput must be array or object, got ' . gettype(\$$inputArgAlias)\n" .
                "    );\n" .
                "}\n\n";
        }

        $materializeLine = '';

        if ($defaults) {
            // Conversion into object if input is array
            $convertInputLine =
                "\$$inputArgAlias = is_array(\$$inputArgAlias)\n" .
                "    ? \\JsonSchema\\Validator::arrayToObjectRecursive(\$$inputArgAlias)\n" .
                // If generating the "$materializeDefaults" param, we must ensure that
                // if the input is an object, it is cloned when the param is true, as it might be modified
                "    : (\$$materializeArgAlias ? clone \$$inputArgAlias : \$$inputArgAlias);\n\n";

            $materializeLine =
                "if (\$$materializeArgAlias) {\n" .
                "    foreach (self::\$_defaults as \$__k => \$__v) {\n" .
                "        if (!property_exists(\$$inputArgAlias, (string) \$__k)) {\n" .
                "           \${$inputArgAlias}->{\$__k} = (\$__v['type'] ?? null) === 'object'\n" .
                "               ? \\JsonSchema\\Validator::arrayToObjectRecursive(\$__v['default'])\n" .
                "               : \$__v['default'];\n" .
                "        }\n" .
                "    }\n" .
                "}\n\n";
        } else {
            $convertInputLine =
                "\$$inputArgAlias = is_array(\$$inputArgAlias) ? \\JsonSchema\\Validator::arrayToObjectRecursive(\$$inputArgAlias) : \$$inputArgAlias;\n";
        }

        $body =
            $aliasesLines .
            $inputGuard .
            $convertInputLine .
            $materializeLine .

            // Conditional schema validation
            "if (\${$validateArgAlias}) {\n" .
            "    static::validateInput(\$$inputArgAlias);\n" .
            "}\n\n" .

            ($hasOptionalNullable ? "\$__providedOptionals = [];\n" : '') .

            // Property‐by‐property mapping
            $properties->generateInputToTypeConversionCode($inputArgAlias, object: true) . "\n\n" .

            // Construct & assign optional props
            "\${$objVarName} = new self(" . join(", ", $constructorParams) . ");" . "\n" .
            join("\n", $assignments) . "\n" .
            ($hasOptionalNullable ? "\${$objVarName}->_providedOptionals = \$__providedOptionals;\n" : '') .

            // Return
            "return \${$objVarName};"
        ;

        return $body;
    }

    /**
     * @param PropertyCollection $properties
     * @return MethodGenerator
     */
    public function generateToArrayMethod(PropertyCollection $properties, array $defaults = []): MethodGenerator
    {
        $tags = [];
        if ($defaults) {
            $tags[] = new ParamTag('includeDefaults', ['bool'], 'Add defaults for missing properties');
        }
        $tags[] = new ReturnTag(["array"], "Converted array");

        $docBlock = new DocBlockGenerator(
            "Converts this object back to a simple array that can be JSON-serialized",
            null,
            $tags
        );
        $docBlock->setWordWrap(false);

        $params = [];
        if ($defaults) {
            $params[] = new ParameterGenerator('includeDefaults', 'bool', false);
        }

        $body = '$output = [];' . "\n" .
            $properties->generateTypeToArrayConversionCode('output') . "\n";

        if ($defaults) {
            $body .= "\nif (\$includeDefaults) {\n" .
            "    foreach (self::\$_defaults as \$k => \$v) {\n" .
            "        if (!array_key_exists(\$k, \$output)) {\n" .
            "            \$output[\$k] = \$v['default'];\n" .
            "        }\n" .
            "    }\n" .
            "}\n";
        }

        $body .= "\nreturn \$output;";

        $method = new MethodGenerator(
            'toArray',
            $params,
            MethodGenerator::FLAG_PUBLIC,
            $body,
            $docBlock
        );

        if ($this->generatorRequest->isAtLeastPHP("7.0")) {
            $method->setReturnType("array");
        }

        return $method;
    }

    /**
     * @param PropertyCollection $properties
     * @return MethodGenerator
     */
    public function generateToStdClassMethod(PropertyCollection $properties, array $defaults = []): MethodGenerator
    {
        $tags = [];
        if ($defaults) {
            $tags[] = new ParamTag('includeDefaults', ['bool'], 'Add defaults for missing properties');
        }
        $tags[] = new ReturnTag(['\\stdClass'], 'Converted object');

        $docBlock = new DocBlockGenerator(
            'Converts this object to a stdClass that can be JSON-serialized',
            null,
            $tags
        );
        $docBlock->setWordWrap(false);

        $params = [];
        if ($defaults) {
            $params[] = new ParameterGenerator('includeDefaults', 'bool', false);
        }

        $body = '$output = new \\stdClass();' . "\n" .
            $properties->generateTypeToStdClassConversionCode('output') . "\n";

        if ($defaults) {
            $body .= "\nif (\$includeDefaults) {\n" .
            "    foreach (self::\$_defaults as \$k => \$v) {\n" .
            "        if (!property_exists(\$output, (string) \$k)) {\n" .
            "            \$output->{\$k} = (isset(\$v['type']) && \$v['type'] === 'object')\n" .
            "               ? \\JsonSchema\\Validator::arrayToObjectRecursive(\$v['default'])\n" .
            "               : \$v['default'];\n" .
            "        }\n" .
            "    }\n" .
            "}\n";
        }

        $body .= "\nreturn \$output;";

        $method = new MethodGenerator(
            'toStdClass',
            $params,
            MethodGenerator::FLAG_PUBLIC,
            $body,
            $docBlock
        );

        if ($this->generatorRequest->isAtLeastPHP("7.0")) {
            $method->setReturnType('\\stdClass');
        }

        return $method;
    }

    /**
     * @return MethodGenerator
     */
    public function generateValidateMethod(): MethodGenerator
    {
        $docBlock = new DocBlockGenerator(
            "Validates an input array",
            null,
            [
                new ParamTag("input", ["array|object"], "Input data"),
                new ParamTag("return", ["bool"], "Return instead of throwing errors"),
                new ReturnTag(["bool"], "Validation result"),
                new ThrowsTag("\\InvalidArgumentException"),
            ]
        );
        $docBlock->setWordWrap(false);

        $newValidatorClassExpr = $this->generatorRequest->getOptions()->getNewValidatorClassExpr();

        $method = new MethodGenerator(
            'validateInput',
            [
                new ParameterGenerator("input", $this->generatorRequest->isAtLeastPHP("8.0") ? "array|object" : null),
                new ParameterGenerator("return", $this->generatorRequest->isAtLeastPHP("7.0") ? "bool" : null, false),
            ],
            MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC,
            '$validator = ' . $newValidatorClassExpr . ";\n" .
            '$input = is_array($input) ? \\JsonSchema\\Validator::arrayToObjectRecursive($input) : $input;' . "\n" .
            '$validator->validate($input, self::$schema);' . "\n\n" .
            'if (!$validator->isValid() && !$return) {' . "\n" .
            (
                $this->generatorRequest->isAtLeastPHP("7.0")
                ? '    $errors = array_map(function(array $e): string {' . "\n"
                : '    $errors = array_map(function($e) {' . "\n"
            ) .
            '        return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];' . "\n" .
            '    }, $validator->getErrors());' . "\n" .
            '    throw new \\InvalidArgumentException(join(".\n", $errors));' . "\n" .
            '}' . "\n\n" .
            'return $validator->isValid();',
            $docBlock
        );
        if ($this->generatorRequest->isAtLeastPHP("7.0")) {
            $method->setReturnType("bool");
        }

        return $method;
    }

    /**
     * @param PropertyCollection $properties
     * @return ?MethodGenerator
     */
    public function generateCloneMethod(PropertyCollection $properties): ?MethodGenerator
    {
        $clones = [];

        foreach ($properties as $property) {
            $c = $property->cloneProperty();
            if ($c !== null) {
                $clones[] = $c;
            }
        }

        if ($clones === []) {
            return null;
        }

        return new MethodGenerator(
            '__clone',
            [],
            MethodGenerator::FLAG_PUBLIC,
            join("\n", $clones)
        );
    }

    public function generateIsProvidedMethod(): MethodGenerator
    {
        $doc = new DocBlockGenerator(
            'Checks if an optional nullable property was explicitly set',
            null,
            [
                new ParamTag('propertyName', ['string'], "Property name to check (exactly as it appears in the schema)"),
                new ReturnTag('bool'),
            ]
        );
        $doc->setWordWrap(false);

        $method = new MethodGenerator(
            'isOptionalProvided',
            [new ParameterGenerator('propertyName', 'string')],
            MethodGenerator::FLAG_PUBLIC,
            'return array_key_exists($propertyName, $this->_providedOptionals);',
            $doc
        );

        if ($this->generatorRequest->isAtLeastPHP("7.0")) {
            $method->setReturnType('bool');
        }

        return $method;
    }

    /**
     * @param PropertyCollection $properties
     * @return MethodGenerator[]
     */
    public function generateGetterMethods(PropertyCollection $properties): array
    {
        if ($this->generatorRequest->getNoGetters()) {
            return [];
        }

        $methods = [];

        $properties = $properties->filter(PropertyCollectionFilterFactory::withoutDeprecatedAndSameName($properties));

        foreach ($properties as $property) {
            $methods[] = $this->generateGetterMethod($property);
        }

        return $methods;
    }

    /**
     * @param PropertyInterface $property
     * @return MethodGenerator
     */
    public function generateGetterMethod(PropertyInterface $property): MethodGenerator
    {

        $name           = $property->name();
        if ($this->generatorRequest->getOptions()->getPreservePropertyNames()) {
            $camelCasedName = StringUtils::pascalCasePreserveOuterUnderscores($property->name());
        } else {
            $camelCasedName = StringUtils::safePascalCase($property->name());
        }
        $annotatedType  = $property->typeAnnotation();

        $tags = [new ReturnTag($annotatedType)];
        if (PropertyQuery::isDeprecated($property)) {
            $tags[] = new GenericTag("deprecated");
        }

        $docBlockGenerator = new DocBlockGenerator(null, $property->description(), $tags);
        $docBlockGenerator->setWordWrap(false);  // needs to be disabled because its fundamentally broken

        $getMethod = new MethodGenerator(
            name: 'get' . $camelCasedName,
            parameters: [],
            flags: MethodGenerator::FLAG_PUBLIC,
            body: "return \$this->$name;",
            docBlock: $docBlockGenerator,
        );

        if ($this->generatorRequest->isAtLeastPHP("7.0")) {
            $typeHint = $property->typeHint($this->generatorRequest->getTargetPHPVersion());
            if ($typeHint !== null) {
                $getMethod->setReturnType($typeHint);

                if ($typeHint[0] === '?') {
                    $getMethod->setBody("return \$this->{$name} ?? null;");
                }
            }
        }

        return $getMethod;
    }

    /**
     * @param PropertyCollection $properties
     * @return MethodGenerator[]
     */
    public function generateSetterMethods(PropertyCollection $properties): array
    {
        if ($this->generatorRequest->getNoSetters()) {
            return [];
        }

        $mutable = $this->generatorRequest->getMutableSetters();

        $methods    = [];
        $properties = $properties->filter(PropertyCollectionFilterFactory::withoutDeprecatedAndSameName($properties));

        foreach ($properties as $property) {
            if ($mutable !== null) {
                $methods[] = $this->generateMutableSetterMethod($property, $mutable === 'chainable');
                if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
                    $methods[] = $this->generateMutableUnsetterMethod($property, $mutable === 'chainable');
                }
            } else {
                $methods[] = $this->generateSetterMethod($property);

                if ($property instanceof OptionalPropertyDecorator) {
                    $methods[] = $this->generateUnsetterMethod($property);
                }
            }
        }

        return $methods;
    }

    public function generateSetterMethod(PropertyInterface $property): MethodGenerator
    {
        $key  = $property->key();
        $keyStr = var_export($key, true);
        $name = $property->name();
        if ($this->generatorRequest->getOptions()->getPreservePropertyNames()) {
            $camelCaseName = StringUtils::pascalCasePreserveOuterUnderscores($name);
        } else {
            $camelCaseName = StringUtils::safePascalCase($name);
        }

        $requiredProperty = ($property instanceof OptionalPropertyDecorator) ? $property->unwrap() : $property;

        $annotatedType = $requiredProperty->typeAnnotation();
        $typeHint      = $requiredProperty->typeHint($this->generatorRequest->getTargetPHPVersion());

        $base = $property;
        while ($base instanceof PropertyDecoratorInterface) {
            /** @var PropertyDecoratorInterface $base */
            $base = $base->unwrap();
        }

        $isArray = $base instanceof PrimitiveArrayProperty
            || $base instanceof ObjectArrayProperty
            || $base instanceof ReferenceArrayProperty
            || $base instanceof TypedArrayProperty;

        $newValidatorClassExpr = $this->generatorRequest->getOptions()->getNewValidatorClassExpr();

        if ($property->isComplex() && !$isArray) {
            $setterValidation = "";
        } else {
            $setterValidation = 
                "if (\$validate) {\n"
                . "    \$validator = {$newValidatorClassExpr};\n"
                . "    \$validator->validate(\$$name, self::\$schema['properties'][{$keyStr}]);\n"
                . "    if (!\$validator->isValid()) {\n"
                . "        throw new \\InvalidArgumentException(\$validator->getErrors()[0]['message']);\n"
                . "    }\n"
                . "}\n\n";
        }

        $tags = [
            new ParamTag($name, [str_replace("|null", "", $annotatedType)]),
            new ReturnTag("self"),
        ];

        if (PropertyQuery::isDeprecated($property)) {
            $tags[] = new GenericTag("deprecated");
        }

        $docBlock = new DocBlockGenerator(null, null, $tags);
        $docBlock->setWordWrap(false);

        $parameters = [new ParameterGenerator($name, $typeHint)];
        if ($setterValidation !== "") {
            $validateParam = new ParameterGenerator('validate', 'bool');
            $validateParam->setDefaultValue(true);
            $parameters[] = $validateParam;

            $tags[] = new ParamTag('validate', ['bool']);
            $docBlock = new DocBlockGenerator(null, null, $tags);
            $docBlock->setWordWrap(false);
        }

        $body = $setterValidation . "\$clone = clone \$this;\n" .
            "\$clone->$name = \$$name;\n";

        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $body .= "\$clone->_providedOptionals[{$keyStr}] = true;\n";
        }

        $body .= "\nreturn \$clone;";

        $setMethod = new MethodGenerator(
            'with' . $camelCaseName,
            $parameters,
            MethodGenerator::FLAG_PUBLIC,
            $body,
            $docBlock
        );

        if ($this->generatorRequest->isAtLeastPHP("7.0")) {
            $setMethod->setReturnType("self");
        }

        return $setMethod;
    }

    private function generateMutableSetterMethod(PropertyInterface $property, bool $chainable): MethodGenerator
    {
        $key  = $property->key();
        $keyStr = var_export($key, true);
        $name = $property->name();
        $camelCaseName = $this->generatorRequest->getOptions()->getPreservePropertyNames()
            ? StringUtils::pascalCasePreserveOuterUnderscores($name)
            : StringUtils::safePascalCase($name);

        $requiredProperty = ($property instanceof OptionalPropertyDecorator) ? $property->unwrap() : $property;
        $annotatedType = $requiredProperty->typeAnnotation();
        $typeHint = $requiredProperty->typeHint($this->generatorRequest->getTargetPHPVersion());

        $base = $property;
        while ($base instanceof PropertyDecoratorInterface) {
            /** @var PropertyDecoratorInterface $base */
            $base = $base->unwrap();
        }

        $isArray = $base instanceof PrimitiveArrayProperty
            || $base instanceof ObjectArrayProperty
            || $base instanceof ReferenceArrayProperty
            || $base instanceof TypedArrayProperty;

        $newValidatorClassExpr = $this->generatorRequest->getOptions()->getNewValidatorClassExpr();

        if ($property->isComplex() && !$isArray) {
            $setterValidation = '';
        } else {
            $setterValidation =
                "if (\$validate) {\n" .
                "    \$validator = {$newValidatorClassExpr};\n" .
                "    \$validator->validate(\$$name, self::\$schema['properties'][{$keyStr}]);\n" .
                "    if (!\$validator->isValid()) {\n" .
                "        throw new \\InvalidArgumentException(\$validator->getErrors()[0]['message']);\n" .
                "    }\n" .
                "}\n\n";
        }

        $tags = [new ParamTag($name, [str_replace('|null', '', $annotatedType)])];
        if ($chainable) {
            $tags[] = new ReturnTag('self');
        }
        if (PropertyQuery::isDeprecated($property)) {
            $tags[] = new GenericTag('deprecated');
        }
        $docBlock = new DocBlockGenerator(null, null, $tags);
        $docBlock->setWordWrap(false);

        $parameters = [new ParameterGenerator($name, $typeHint)];
        if ($setterValidation !== '') {
            $validateParam = new ParameterGenerator('validate', 'bool');
            $validateParam->setDefaultValue(true);
            $parameters[] = $validateParam;

            $tags[] = new ParamTag('validate', ['bool']);
            $docBlock = new DocBlockGenerator(null, null, $tags);
            $docBlock->setWordWrap(false);
        }

        $body = $setterValidation . "\$this->{$name} = \$$name;";
        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $body .= "\n\$this->_providedOptionals[{$keyStr}] = true;";
        }
        if ($chainable) {
            $body .= "\n\nreturn \$this;";
        }

        $setMethod = new MethodGenerator(
            'set' . $camelCaseName,
            $parameters,
            MethodGenerator::FLAG_PUBLIC,
            $body,
            $docBlock
        );

        if ($chainable && $this->generatorRequest->isAtLeastPHP('7.0')) {
            $setMethod->setReturnType('self');
        } elseif (!$chainable) {
            if ($this->generatorRequest->isAtLeastPHP('7.1')) {
                $setMethod->setReturnType('void');
            }
        }

        return $setMethod;
    }

    private function generateMutableUnsetterMethod(PropertyInterface $property, bool $chainable): MethodGenerator
    {
        $key  = $property->key();
        $keyStr = var_export($key, true);
        $name = $property->name();
        $camelCaseName = $this->generatorRequest->getOptions()->getPreservePropertyNames()
            ? StringUtils::pascalCasePreserveOuterUnderscores($name)
            : StringUtils::safePascalCase($name);

        $body = "\$this->{$name} = null;\n";
        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $body .= "unset(\$this->_providedOptionals[{$keyStr}]);\n";
        }
        if ($chainable) {
            $body .= "\nreturn \$this;";
        }

        $unsetMethod = new MethodGenerator(
            'unset' . $camelCaseName,
            [],
            MethodGenerator::FLAG_PUBLIC,
            $body,
            new DocBlockGenerator(null, null, $chainable ? [new ReturnTag('self')] : [])
        );

        if ($chainable && $this->generatorRequest->isAtLeastPHP('7.0')) {
            $unsetMethod->setReturnType('self');
        } elseif (!$chainable && $this->generatorRequest->isAtLeastPHP('7.1')) {
            $unsetMethod->setReturnType('void');
        }

        return $unsetMethod;
    }

    /**
     * @param PropertyInterface $property
     * @return MethodGenerator
     */
    public function generateUnsetterMethod(PropertyInterface $property): MethodGenerator
    {
        $name = $property->name();
        $key  = $property->key();
        $keyStr = var_export($key, true);
        if ($this->generatorRequest->getOptions()->getPreservePropertyNames()) {
            $camelCasedName = StringUtils::pascalCasePreserveOuterUnderscores($name);
        } else {
            $camelCasedName = StringUtils::safePascalCase($name);
        }

        $body = "\$clone = clone \$this;\n";
        $body .= "unset(\$clone->$name);\n";

        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $body .= "unset(\$clone->_providedOptionals[{$keyStr}]);\n";
        }

        $body .= "\nreturn \$clone;";

        $unsetMethod = new MethodGenerator(
            'without' . $camelCasedName,
            [],
            MethodGenerator::FLAG_PUBLIC,
            $body,
            new DocBlockGenerator(null, null, [
                new ReturnTag("self"),
            ])
        );

        if ($this->generatorRequest->isAtLeastPHP("7.0")) {
            $unsetMethod->setReturnType("self");
        }

        return $unsetMethod;
    }

    /**
     * Ensures that property names are unique after sanitization. When a
     * collision is detected, an underscore is prepended until the name is
     * unique within the given property collection.
     */
    public function ensureUniquePropertyNames(PropertyCollection $properties, bool $preservePropertyNames): void
    {
        // Reserved identifiers that should not be used for property names or
        // would collide with generated code identifiers
        $reservedPropertyNames = [
            '_GLOBALS',
            'GLOBALS',
            'GLOBALS_1',
            '_SERVER',
            '_GET',
            '_POST',
            '_FILES',
            '_REQUEST',
            '_SESSION',
            '_ENV',
            '_COOKIE',
            'php_errormsg',
            'http_response_header',
            'argc',
            'argv',
            'schema',
            '_defaults',
            '_providedOptionals',
        ];

        $reservedMethodNames = [
            'buildFromInput',
            'toArray',
            'toStdClass',
            'validateInput',
            'clone',
            '__construct',
            '__destruct',
            '__get',
            '__set',
            '__call',
            '__isset',
            '__unset',
            '__sleep',
            '__wakeup',
            '__toString',
            '__invoke',
            '__debugInfo',
            '__clone',
        ];

        $used = [];
        foreach (array_merge($reservedPropertyNames, $reservedMethodNames) as $n) {
            $used[] = StringUtils::safeCamelCase($n);
            $used[] = StringUtils::sanitizeIdentifier($n);
        }
        $used = array_values(array_unique($used));

        $usedMethods = [];
        foreach ($reservedMethodNames as $n) {
            $usedMethods[] = strtolower(StringUtils::safePascalCase(StringUtils::safeCamelCase($n)));
            $usedMethods[] = strtolower(StringUtils::safePascalCase(StringUtils::sanitizeIdentifier($n)));
        }
        $usedMethods = array_values(array_unique($usedMethods));

        foreach ($properties as $property) {
            $base    = $property->name();
            $unique  = $base;
            $pascal  = strtolower(StringUtils::safePascalCase($unique));

            $needsChange = in_array($unique, $used, true)
                || (!$preservePropertyNames && in_array($pascal, $usedMethods, true));

            if ($needsChange) {
                if ($base[0] !== '_') {
                    $unique = '_' . $base;
                    $pascal = strtolower(StringUtils::safePascalCase($unique));
                }

                $i = 1;
                $baseUnique = $unique;
                while (in_array($unique, $used, true)
                    || (!$preservePropertyNames && in_array($pascal, $usedMethods, true))) {
                    $unique = $baseUnique . '_' . $i;
                    $pascal = strtolower(StringUtils::safePascalCase($unique));
                    $i++;
                }
            }

            if ($unique !== $base && $property instanceof RenameablePropertyInterface) {
                $property->setName($unique);
            }

            $used[]       = $unique;
            $usedMethods[] = $pascal;
        }
    }

    /**
     * Ensures that generated method names are unique. If a collision occurs,
     * an underscore is inserted after the common prefix (get/set/with/without).
     *
     * @param MethodGenerator[] $methods
     */
    public function ensureUniqueMethodNames(array $methods): void
    {
        $reservedMethodNames = [
            'buildFromInput',
            'toArray',
            'toStdClass',
            'validateInput',
            'clone',
            '__construct',
            '__destruct',
            '__get',
            '__set',
            '__call',
            '__isset',
            '__unset',
            '__sleep',
            '__wakeup',
            '__toString',
            '__invoke',
            '__debugInfo',
            '__clone',
        ];

        $reserved = array_map('strtolower', $reservedMethodNames);
        $used = [];

        foreach ($methods as $method) {
            $name   = $method->getName();
            $lcName = strtolower($name);

            if (!in_array($lcName, $used, true) && in_array($lcName, $reserved, true)) {
                $used[] = $lcName;
                continue;
            }

            $candidate = $name;
            $prefix    = '';
            $base      = $name;

            if (preg_match('/^(set|get|without|with)(.+)$/', $name, $m)) {
                $prefix = $m[1];
                $base   = $m[2];
            }

            $i = 1;
            while (in_array(strtolower($candidate), $used, true) || in_array(strtolower($candidate), $reserved, true)) {
                if ($prefix !== '') {
                    $suffix   = $i > 1 ? $base . '_' . ($i - 1) : $base;
                    $candidate = $prefix . '_' . $suffix;
                } else {
                    $candidate = $name . '_' . $i;
                }
                $i++;
            }

            if ($candidate !== $name) {
                $method->setName($candidate);
            }

            $used[] = strtolower($candidate);
        }
    }

    public function hasOptionalNullable(PropertyCollection $properties): bool
    {
        foreach ($properties as $p) {
            if ($p instanceof OptionalPropertyDecorator && $p->isOptionalNullable()) {
                return true;
            }
        }
        return false;
    }

    public function generateClassFile(GeneratorRequest $req, array $schema, array $properties, array $methods, WriterInterface $writer): void
    {
        $cls = new LaminasClassGenerator(
            $req->getTargetClass(),
            $req->getTargetNamespace(),
            null,
            null,
            [],
            $properties,
            $methods,
            null,
        );

        if (isset($schema['description']) && is_string($schema['description']) && $schema['description'] !== '') {
            $doc = new DocBlockGenerator($schema['description']);
            $doc->setWordWrap(false);
            $cls->setDocBlock($doc);
        }

        $req->onClassCreated($cls);

        $filename = $req->getTargetDirectory() . '/' . $req->getTargetClass() . '.php';

        $file = new FileGenerator();
        $file->setClasses([$cls]);

        $req->onFileCreated($filename, $file);

        if ($req->isAtLeastPHP('7.0') && !$req->getOptions()->getDisableStrictTypes()) {
            $file->setDeclares([DeclareStatement::strictTypes(1)]);
        }

        $content = $file->generate();

        // Do some corrections because the Zend code generation library is not smart enough.
        $content = preg_replace('/: \\\\self/', ': self', $content);

        // Remove current namespace from all class names
        $content = preg_replace('/\\\\' . preg_quote($req->getTargetNamespace(), '/') . '\\\\/', '', $content);

        // Remove "\" before class names that we just generated (as they're all in the current namespace)
        $ownClasses = $req->getGeneratedClassNames();
        if ($ownClasses) {
            $escapedOwnClasses = array_map(fn ($n) => preg_quote($n, '/'), $ownClasses);
            $pattern = '/\\\\(' . join('|', $escapedOwnClasses) . ')(?=\s|[,;)|]|$)/';
            $content = preg_replace($pattern, '$1', $content);
        }

        $writer->writeFile($filename, $content);
    }
}
