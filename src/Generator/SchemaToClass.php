<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\Definition\Definition;
use Helmich\Schema2Class\Generator\Definition\DefinitionsCollector;
use Helmich\Schema2Class\Generator\Definition\DefinitionsGenerator;
use Helmich\Schema2Class\Generator\Enum\SchemaToEnum;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Type\IntersectProperty;
use Helmich\Schema2Class\Generator\Property\Type\NestedObjectProperty;
use Helmich\Schema2Class\Generator\Property\PropertyBuilder;
use Helmich\Schema2Class\Generator\Property\RenameablePropertyInterface;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\PropertyGenerator;
use Helmich\Schema2Class\Generator\ReferenceLookup\DefinitionsReferenceLookup;
use Helmich\Schema2Class\Util\SchemaUtils;
use Helmich\Schema2Class\Util\StringUtils;
use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\DeclareStatement;
use Laminas\Code\Generator\ClassGenerator as LaminasClassGenerator;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\FileGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Low-level generator that converts a prepared GeneratorRequest into PHP classes.
 * For a simpler, one-call solution use {@see \Helmich\Schema2Class\Schema2Class}.
 */
class SchemaToClass
{
    private WriterInterface $writer;
    private SchemaToEnum $schemaToEnum;
    private OutputInterface $output;

    public function __construct(WriterInterface $writer, OutputInterface $output)
    {
        $this->writer = $writer;
        $this->schemaToEnum = new SchemaToEnum($writer);
        $this->output = $output;
    }

    /**
     * @param GeneratorRequest $req
     * @throws GeneratorException
     */
    public function schemaToClass(GeneratorRequest $req): void
    {
        // start with whatever schema the request already has
        $schema   = $req->getSchema();
        $this->decodeReferences($schema);
        $rootDefs = array_merge($schema['definitions'] ?? [], $schema['$defs'] ?? []);

        // collect definitions and prepare lookups before dereferencing
        $this->definitionsToSchemas($req);

        // if the caller supplied root definitions, *always* splice them in here
        // (but don't overwrite if the schema already carried its own definitions)
        $this->mergeRootDefinitions($schema, $rootDefs, $req->getRootDefinitions());

        // dereference schemas that consist only of a reference
        $schema = $this->dereferenceSchema($schema, $req, $rootDefs);

        $req    = $req->withSchema($schema);
        $schema = $req->getSchema();

        if ($this->handleEnum($schema, $req)) {
            return;
        }

        $schema = $this->normalizeObjectSchema($schema, $req);
        if ($schema === null) {
            return;
        }

        // remove metadata like descriptions from schema if such option is set, but keep them
        // for building property documentation
        $validationSchema = $schema;
        if ($req->getOptions()->getNoSchemaMetadata()) {
            $this->stripSchemaMetadata($validationSchema);
        }

        $schemaProperty = $this->createSchemaProperty($validationSchema, $req);

        $defaults      = self::collectDefaults($schema, $req);
        $hasDefaults   = !empty($defaults);
        $req->setCurrReqHasDefaults($hasDefaults);

        $properties = [$schemaProperty];
        $defaultsProp = $this->createDefaultsProperty($defaults, $req);
        if ($defaultsProp !== null) {
            $properties[] = $defaultsProp;
        }

        $propertiesFromSchema = $this->collectPropertiesFromSchema($schema, $req);

        $this->ensureUniquePropertyNames(
            $propertiesFromSchema,
            $req->getOptions()->getPreservePropertyNames(),
        );

        foreach ($propertiesFromSchema as $property) {
            $property->generateSubTypes($this);
        }

        $codeGenerator      = new ClassGenerator($req);
        $hasOptionalNullable = $this->hasOptionalNullable($propertiesFromSchema);

        $properties = [
            ...$properties,
            ...$codeGenerator->generateProperties($propertiesFromSchema),
        ];

        $methods = [
            $codeGenerator->generateConstructor($propertiesFromSchema),
            ...$codeGenerator->generateGetterMethods($propertiesFromSchema),
            ...$codeGenerator->generateSetterMethods($propertiesFromSchema),
            $codeGenerator->generateBuildMethod($propertiesFromSchema, $defaults),
            $codeGenerator->generateToArrayMethod($propertiesFromSchema, $defaults),
            $codeGenerator->generateToStdClassMethod($propertiesFromSchema, $defaults),
            $codeGenerator->generateValidateMethod(),
            $codeGenerator->generateCloneMethod($propertiesFromSchema),
            $hasOptionalNullable ? $codeGenerator->generateIsProvidedMethod() : null,
        ];
        $methods = array_values(array_filter($methods));
        $this->ensureUniqueMethodNames($methods);

        $this->generateClassFile($req, $schema, $properties, $methods);
    }

    private function decodeReferences(array &$node): void
    {
        foreach ($node as $k => &$v) {
            if ($k === '$ref' && is_string($v)) {
                $v = rawurldecode($v);
            } elseif (is_array($v)) {
                $this->decodeReferences($v);
            }
        }
    }

    private function usesRootDefinitions(mixed $node): bool
    {
        if (!is_array($node)) {
            return false;
        }
        if (isset($node['$ref']) && is_string($node['$ref'])) {
            $r = rawurldecode($node['$ref']);
            if (str_starts_with($r, '#/definitions/') || str_starts_with($r, '#/$defs/')) {
                return true;
            }
        }
        foreach ($node as $v) {
            if (is_array($v) && $this->usesRootDefinitions($v)) {
                return true;
            }
        }

        return false;
    }

    private function mergeRootDefinitions(array &$schema, array &$rootDefs, ?array $defs): void
    {
        if ($defs === null || count($defs) === 0) {
            return;
        }

        if (!isset($schema['definitions'])) {
            $schema['definitions'] = $defs;
        } else {
            $schema['definitions'] = array_replace($defs, $schema['definitions']);
        }
        $rootDefs = array_replace($rootDefs, $defs);
    }

    private function dereferenceSchema(array $schema, GeneratorRequest $req, array $rootDefs): array
    {
        if (!isset($schema['$ref'])) {
            return $schema;
        }

        $schema = $req->lookupSchema($schema['$ref']);
        $this->decodeReferences($schema);

        $needsDefs = $this->usesRootDefinitions($schema);

        if ($needsDefs) {
            $this->mergeRootDefinitions($schema, $rootDefs, $req->getRootDefinitions());
            if (count($rootDefs) > 0) {
                if (!isset($schema['definitions'])) {
                    $schema['definitions'] = $rootDefs;
                } else {
                    $schema['definitions'] = array_replace($rootDefs, $schema['definitions']);
                }
            }
        }

        return $schema;
    }

    private function handleEnum(array $schema, GeneratorRequest $req): bool
    {
        if (!isset($schema['enum'])) {
            return false;
        }

        if (SchemaToEnum::canGenerateEnum($schema, $req)) {
            $this->schemaToEnum->schemaToEnum($req);
            return true;
        }

        $class = $req->getTargetClass();
        if ($class !== null) {
            $this->output->writeln("skipping generation of enum <comment>{$class}</comment>");
        }

        return false;
    }

    private function normalizeObjectSchema(array $schema, GeneratorRequest $req): ?array
    {
        if (IntersectProperty::canHandleSchema($schema)) {
            return (new IntersectProperty($req->getTargetClass(), $schema, $req))->buildSchemaIntersect();
        }

        if (!NestedObjectProperty::canHandleSchema($schema)) {
            // If the schema does not describe an object we only generate definitions
            $class = $req->getTargetClass();
            if ($class !== null) {
                $this->output->writeln("skipping generation of <comment>{$class}</comment>");
            }

            return null;
        }

        return $schema;
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

    private function createSchemaProperty(array $validationSchema, GeneratorRequest $req): PropertyGenerator
    {
        $schemaProperty = new PropertyGenerator(
            'schema',
            $validationSchema,
            PropertyGenerator::FLAG_PRIVATE | PropertyGenerator::FLAG_STATIC,
        );

        $schemaProperty->setDocBlock(new DocBlockGenerator(
            'Schema used to validate input for creating instances of this class',
            null,
            [new GenericTag('var', 'array')],
        ));

        if ($req->isAtLeastPHP('7.4')) {
            $schemaProperty->setTypeHint('array');
        }
        if ($req->getOptions()->getSingleLineSchema()) {
            $schemaProperty->setSingleLineDefaultValue(true);
        }

        return $schemaProperty;
    }

    private function createDefaultsProperty(array $defaults, GeneratorRequest $req): ?PropertyGenerator
    {
        if ($defaults === []) {
            return null;
        }

        $prop = new PropertyGenerator('_defaults', $defaults, PropertyGenerator::FLAG_PRIVATE | PropertyGenerator::FLAG_STATIC);
        $prop->setDocBlock(new DocBlockGenerator(
            'Default values from the schema',
            null,
            [new GenericTag('var', 'array')],
        ));
        if ($req->isAtLeastPHP('7.4')) {
            $prop->setTypeHint('array');
        }
        if ($req->getOptions()->getSingleLineSchema()) {
            $prop->setSingleLineDefaultValue(true);
        }

        return $prop;
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

    private function hasOptionalNullable(PropertyCollection $properties): bool
    {
        foreach ($properties as $p) {
            if ($p instanceof OptionalPropertyDecorator && $p->isOptionalNullable()) {
                return true;
            }
        }

        return false;
    }

    private function generateClassFile(GeneratorRequest $req, array $schema, array $properties, array $methods): void
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

        $this->writer->writeFile($filename, $content);
    }

    /**
     * Ensures that property names are unique after sanitization. When a
     * collision is detected, an underscore is prepended until the name is
     * unique within the given property collection.
     */
    private function ensureUniquePropertyNames(PropertyCollection $properties, bool $preservePropertyNames): void
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
     * an underscore is inserted after the common prefix (get/with/without).
     *
     * @param MethodGenerator[] $methods
     */
    private function ensureUniqueMethodNames(array $methods): void
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

    private function definitionsToSchemas(GeneratorRequest &$req): void
    {
        if ($req->hasReferenceLookup(DefinitionsReferenceLookup::class)) {
            return;
        }

        $rootRef = $req->getSchema()['$ref'] ?? null;

        $collector = new DefinitionsCollector($req);
        $allDefinitions  = iterator_to_array($collector->collect($req->getSchema()));

        $ns = $req->getTargetNamespace();
        
        $generatedClasses = array_map(static function(Definition $d) use ($ns): string {
            $cls = $d->classFQN;
            if ($ns !== '' && str_starts_with($cls, $ns . '\\')) {
                return substr($cls, strlen($ns) + 1);
            }
            return ltrim($cls, '\\');
        }, $allDefinitions);

        if ($req->getTargetClass() !== null) {
            $generatedClasses[] = $req->getTargetClass();
        }

        $req = $req
            ->withAdditionalReferenceLookup(new DefinitionsReferenceLookup($allDefinitions))
            ->withGeneratedClassNames($generatedClasses);

        $definitionsToGenerate = $allDefinitions;
        if (is_string($rootRef)) {
            $canonical = $rootRef;
            if (!isset($definitionsToGenerate[$canonical])) {
                if (str_starts_with($rootRef, '#/definitions/')) {
                    $alt = '#/$defs/' . substr($rootRef, 14);
                    if (isset($definitionsToGenerate[$alt])) {
                        $canonical = $alt;
                    }
                } elseif (str_starts_with($rootRef, '#/$defs/')) {
                    $alt = '#/definitions/' . substr($rootRef, 7);
                    if (isset($definitionsToGenerate[$alt])) {
                        $canonical = $alt;
                    }
                }
            }
            unset($definitionsToGenerate[$canonical]);
        }

        $generator = new DefinitionsGenerator($this);
        $generator->generate($definitionsToGenerate, $req);
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
            if ($type) {
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
}
