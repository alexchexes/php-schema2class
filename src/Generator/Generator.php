<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Codegen\PropertyGenerator;
use Helmich\Schema2Class\Generator\Property\CodeFormatting;
use Helmich\Schema2Class\Generator\Property\DefaultPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\PropertyCollectionFilterFactory;
use Helmich\Schema2Class\Generator\Property\PropertyInterface;
use Helmich\Schema2Class\Generator\Property\PrimitiveArrayProperty;
use Helmich\Schema2Class\Generator\Property\ObjectArrayProperty;
use Helmich\Schema2Class\Generator\Property\ReferenceArrayProperty;
use Helmich\Schema2Class\Generator\Property\NullablePropertyDecorator;
use Helmich\Schema2Class\Generator\Property\PropertyDecoratorInterface;
use Helmich\Schema2Class\Generator\Property\TypedArrayProperty;
use Helmich\Schema2Class\Util\StringUtils;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlock\Tag\ThrowsTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class Generator
{
    use CodeFormatting;

    private GeneratorRequest $generatorRequest;

    public function __construct(GeneratorRequest $generatorRequest)
    {
        $this->generatorRequest = $generatorRequest;
    }

    /**
     * @param PropertyCollection $properties
     * @return PropertyGenerator[]
     */
    public function generateProperties(PropertyCollection $properties): array
    {
        $propertyGenerators = [];

        $hasOptionalNullable = false;
        foreach ($properties as $p) {
            if ($p instanceof OptionalPropertyDecorator && method_exists($p, 'isOptionalNullable') && $p->isOptionalNullable()) {
                $hasOptionalNullable = true;
                break;
            }
        }

        $visibility = $this->generatorRequest->getNoGetters()
            ? PropertyGenerator::FLAG_PUBLIC
            : PropertyGenerator::FLAG_PRIVATE;

        if ($hasOptionalNullable) {
            $setVisibility = ($this->generatorRequest->getNoGetters() && $this->generatorRequest->getNoSetters())
                ? PropertyGenerator::FLAG_PUBLIC
                : PropertyGenerator::FLAG_PRIVATE;

            $setProp = new PropertyGenerator('_optionalNullableSet', [] , $setVisibility);
            $setProp->setDefaultValue([]);
            $setProp->setDocBlock(new DocBlockGenerator(null, null, [new GenericTag('var', 'array')]));
            $propertyGenerators[] = $setProp;
        }

        foreach ($properties as $property) {
            $schema     = $property->schema();
            $isOptional = false;
            $prop       = new PropertyGenerator(
                $property->name(),
                $property->formatValue($schema["default"] ?? null),
                $visibility
            );

            if ($property instanceof OptionalPropertyDecorator || $property instanceof DefaultPropertyDecorator) {
                $isOptional = true;
                if (isset($schema["default"]) && !$property->allowsNull()) {
                    $property = $property->unwrap();
                }
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
            $hasSchemaDefault = array_key_exists('default', $property->schema());
            $prop->omitDefaultValue(!$isOptional && !$hasSchemaDefault);

            $propertyGenerators[] = $prop;
        }

        return $propertyGenerators;
    }

    /**
     * @param PropertyCollection $properties
     * @return MethodGenerator
     */
    public function generateBuildMethod(PropertyCollection $properties): MethodGenerator
    {
        $requiredProperties = $properties->filter(PropertyCollectionFilterFactory::required());
        $optionalProperties = $properties->filter(PropertyCollectionFilterFactory::optional());

        $hasOptionalNullable = false;
        foreach ($properties as $p) {
            if ($p instanceof OptionalPropertyDecorator && method_exists($p, 'isOptionalNullable') && $p->isOptionalNullable()) {
                $hasOptionalNullable = true;
                break;
            }
        }

        $constructorParams = [];
        
        foreach ($requiredProperties as $requiredProperty) {
            $constructorParams[] = '$' . $requiredProperty->name();
        }

        $inputVarName = 'input';
        if ($properties->hasPropertyWithKey($inputVarName)) {
            $inputVarName = '_input';
            $i = 2;
            while ($properties->hasPropertyWithKey($inputVarName)) {
                $inputVarName = '_input' . $i;
                $i++;
            }
        }

        $paramType = null;
        if ($this->generatorRequest->isAtLeastPHP("8.0")) {
            $paramType = "array|object";
        }

        $validateParamName = 'validate';
        if ($properties->hasPropertyWithName($validateParamName)) {
            $validateParamName = '_validate';
            $i = 2;
            while ($properties->hasPropertyWithName($validateParamName)) {
                $validateParamName = '_validate' . $i;
                $i++;
            }
        }

        $objVarName = 'obj';
        if ($properties->hasPropertyWithName($objVarName)) {
            $objVarName = '_obj';
            $i = 2;
            while ($properties->hasPropertyWithName($objVarName)) {
                $objVarName = '_obj' . $i;
                $i++;
            }
        }

        $assignments = [];
        foreach ($optionalProperties as $optionalProperty) {
            $name = $optionalProperty->name();
            $assignments[] = sprintf('$%s->%s = $%s;', $objVarName, $name, $name);
        }

        $validationParam = new ParameterGenerator(
            name: $validateParamName,
            type: "bool",
            defaultValue: true,
        );

        $docBlock = new DocBlockGenerator(
            "Builds a new instance from an input array",
            null,
            [
                new ParamTag($inputVarName, ["array|object"], "Input data"),
                new ParamTag($validateParamName, ["bool"], "Set this to false to skip validation; use at own risk"),
                new ReturnTag([$this->generatorRequest->getTargetClass()], "Created instance"),
                new ThrowsTag("\\InvalidArgumentException"),
            ]
        );
        $docBlock->setWordWrap(false);

        $inputGuard = '';
        if (!$this->generatorRequest->isAtLeastPHP('8.0')) {
            $inputGuard = 
                "if (!is_array(\$$inputVarName) && !is_object(\$$inputVarName)) {\n" .
                "    throw new \\InvalidArgumentException(\n" .
                "        'Input to buildFromInput must be array or object, got ' . gettype(\$$inputVarName)\n" .
                "    );\n" .
                "}\n\n";
        }
            
        $aliasLine = $validateParamName !== 'validate' ? "\$validate = \${$validateParamName};\n" : '';

        $body = $inputGuard .
            // Conversion into object if input is array
            "\$$inputVarName = is_array(\$$inputVarName) ? \\JsonSchema\\Validator::arrayToObjectRecursive(\$$inputVarName) : \$$inputVarName;\n" .

            // Conditional schema validation
            "if (\${$validateParamName}) {\n" .
            "    static::validateInput(\$$inputVarName);\n" .
            "}\n\n" .

            $aliasLine .

            ($hasOptionalNullable ? "\$__optNullables = [];\n" : '') .

            // Property‐by‐property mapping
            $properties->generateInputToTypeConversionCode($inputVarName, object: true) . "\n\n" .

            // Construct & assign optional props
            "\${$objVarName} = new self(" . join(", ", $constructorParams) . ");" . "\n" .
            join("\n", $assignments) . "\n" .
            ($hasOptionalNullable ? "\${$objVarName}->_optionalNullableSet = \$__optNullables;\n" : '') .

            // Return
            "return \${$objVarName};"
        ;

        $method = new MethodGenerator(
            'buildFromInput',
            [new ParameterGenerator($inputVarName, $paramType), $validationParam],
            MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC,
            $body,
            $docBlock
        );

        if ($this->generatorRequest->isAtLeastPHP("7.0")) {
            $method->setReturnType($this->generatorRequest->getTargetNamespace() . "\\" . $this->generatorRequest->getTargetClass());
        }

        return $method;
    }

    /**
     * @param PropertyCollection $properties
     * @return MethodGenerator
     */
    public function generateToArrayMethod(PropertyCollection $properties): MethodGenerator
    {
        $docBlock = new DocBlockGenerator(
            "Converts this object back to a simple array that can be JSON-serialized",
            null,
            [new ReturnTag(["array"], "Converted array")]
        );
        $docBlock->setWordWrap(false);

        $method = new MethodGenerator(
            'toArray',
            [],
            MethodGenerator::FLAG_PUBLIC,
            '$output = [];' . "\n" .
            $properties->generateTypeToArrayConversionCode('output') . "\n\n" .
            'return $output;',
            $docBlock
        );

        if ($this->generatorRequest->isAtLeastPHP("7.0")) {
            $method->setReturnType("array");
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

    public function generateIsSetMethod(): MethodGenerator
    {
        $doc = new DocBlockGenerator(null, null, [
            new ParamTag('propertyName', ['string']),
            new ReturnTag('bool'),
        ]);
        $doc->setWordWrap(false);

        $method = new MethodGenerator(
            'isSet',
            [new ParameterGenerator('propertyName', 'string')],
            MethodGenerator::FLAG_PUBLIC,
            'return array_key_exists($propertyName, $this->_optionalNullableSet);',
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
        if (isset($property->schema()["default"]) && $property instanceof OptionalPropertyDecorator) {
            $property = $property->unwrap();
        }

        $name           = $property->name();
        if ($this->generatorRequest->getOptions()->getPreservePropertyNames()) {
            $camelCasedName = StringUtils::pascalCasePreserveOuterUnderscores($property->name());
        } else {
            $camelCasedName = StringUtils::pascalCase($property->name());
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
        $name = $property->name();
        if ($this->generatorRequest->getOptions()->getPreservePropertyNames()) {
            $camelCaseName = StringUtils::pascalCasePreserveOuterUnderscores($name);
        } else {
            $camelCaseName = StringUtils::pascalCase($name);
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
                . "    \$validator->validate(\$$name, self::\$schema['properties']['$key']);\n"
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
            $body .= "\$clone->_optionalNullableSet['$key'] = true;\n";
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
        $name = $property->name();
        $camelCaseName = $this->generatorRequest->getOptions()->getPreservePropertyNames()
            ? StringUtils::pascalCasePreserveOuterUnderscores($name)
            : StringUtils::pascalCase($name);

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
                "    \$validator->validate(\$$name, self::\$schema['properties']['$key']);\n" .
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
            $body .= "\n\$this->_optionalNullableSet['$key'] = true;";
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
        $name = $property->name();
        $camelCaseName = $this->generatorRequest->getOptions()->getPreservePropertyNames()
            ? StringUtils::pascalCasePreserveOuterUnderscores($name)
            : StringUtils::pascalCase($name);

        $body = "\$this->{$name} = null;\n";
        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $body .= "unset(\$this->_optionalNullableSet['$key']);\n";
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
        if ($this->generatorRequest->getOptions()->getPreservePropertyNames()) {
            $camelCasedName = StringUtils::pascalCasePreserveOuterUnderscores($name);
        } else {
            $camelCasedName = StringUtils::pascalCase($name);
        }

        $body = "\$clone = clone \$this;\n";
        if (isset($property->schema()["default"])) {
            $value = $property->formatValue($property->schema()["default"])->generate();
            $body .= "\$clone->$name = " . $value . "\n";
        } else {
            $body .= "unset(\$clone->$name);\n";
        }

        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $body .= "unset(\$clone->_optionalNullableSet['$key']);\n";
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
}
