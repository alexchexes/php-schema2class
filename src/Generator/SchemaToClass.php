<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Codegen\PropertyGenerator;
use Helmich\Schema2Class\Generator\Definitions\DefinitionsCollector;
use Helmich\Schema2Class\Generator\Definitions\DefinitionsGenerator;
use Helmich\Schema2Class\Generator\DefinitionsReferenceLookup;
use Helmich\Schema2Class\Generator\SchemaToEnum;
use Helmich\Schema2Class\Generator\Property\IntersectProperty;
use Helmich\Schema2Class\Generator\Property\NestedObjectProperty;
use Helmich\Schema2Class\Generator\Property\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\RenameablePropertyInterface;
use Helmich\Schema2Class\Util\StringUtils;
use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\DeclareStatement;
use Laminas\Code\Generator\ClassGenerator;
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
    private SchemaToEnum $enumGenerator;
    private OutputInterface $output;

    public function __construct(WriterInterface $writer, OutputInterface $output)
    {
        $this->writer = $writer;
        $this->enumGenerator = new SchemaToEnum($writer);
        $this->output = $output;
    }

    /**
     * @param GeneratorRequest $req
     * @throws GeneratorException
     */
    public function schemaToClass(GeneratorRequest $req): void
    {
        // 1) start with whatever schema the request already has
        $schema = $req->getSchema();

        // 2) collect definitions and prepare lookups before dereferencing
        $this->definitionsToSchemas($req);

        // 3) if the caller supplied root definitions, *always* splice them in here
        if (($defs = $req->getRootDefinitions()) !== null && count($defs) > 0) {
            // don't overwrite if the schema already carried its own definitions
            if (!isset($schema['definitions'])) {
                $schema['definitions'] = $defs;
            } else {
                // merge – let local keys override, just in case
                $schema['definitions'] = array_replace($defs, $schema['definitions']);
            }
        }

        // 4) dereference schemas that consist only of a reference
        if (isset($schema['$ref'])) {
            $schema = $req->lookupSchema($schema['$ref']);
        }

        $req = $req->withSchema($schema);
        $schema = $req->getSchema();

        if (isset($schema["enum"])) {
            if (SchemaToEnum::canGenerateEnum($schema, $req)) {
                $this->enumGenerator->schemaToEnum($req);
            } else {
                $class = $req->getTargetClass();
                if ($class !== null) {
                    $this->output->writeln("skipping generation of enum <comment>{$class}</comment>");
                }
            }


            return;
        }

        if (IntersectProperty::canHandleSchema($schema)) {
            $schema = (new IntersectProperty($req->getTargetClass(), $schema, $req))->buildSchemaIntersect();
        } elseif (!NestedObjectProperty::canHandleSchema($schema)) {
            // If the schema does not describe an object we only generate definitions
            $class = $req->getTargetClass();
            if ($class !== null) {
                $this->output->writeln("skipping generation of <comment>{$class}</comment>");
            }
            return;
        }

        // remove descriptions from schema if such option is set, but keep them
        // for building property documentation
        $validationSchema = $schema;
        if ($req->getOptions()->getNoSchemaMetadata()) {
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

            $stripMetadata = function (&$node) use (&$stripMetadata, $metaFields) {
                if (!is_array($node)) {
                    return;
                }
                foreach ($node as $key => &$value) {
                    if (in_array($key, $metaFields, true)) {
                        unset($node[$key]);
                    } elseif (is_array($value)) {
                        // recurse into nested arrays
                        $stripMetadata($value);
                    }
                }
            };
            $stripMetadata($validationSchema);
        }

        $schemaProperty = new PropertyGenerator(
            "schema",
            $validationSchema,
            PropertyGenerator::FLAG_PRIVATE | PropertyGenerator::FLAG_STATIC
        );

        $schemaProperty->setDocBlock(new DocBlockGenerator(
            "Schema used to validate input for creating instances of this class",
            null,
            [new GenericTag("var", "array")]
        ));

        if ($req->isAtLeastPHP("7.4")) {
            $schemaProperty->setTypeHint("array");
        }

        if ($req->getOptions()->getSingleLineSchema()) {
            $schemaProperty->setSingleLineDefaultValue(true);
        }

        $properties = [$schemaProperty];

        $propertiesFromSchema = new PropertyCollection();

        if (isset($schema["properties"])) {
            foreach ($schema["properties"] as $key => $definition) {
                $isRequired = isset($schema["required"]) && in_array($key, $schema["required"]);

                $property = PropertyBuilder::buildPropertyFromSchema($req, $key, $definition, $isRequired);
                $propertiesFromSchema->add($property);
            }
        }

        $this->ensureUniquePropertyNames(
            $propertiesFromSchema,
            $req->getOptions()->getPreservePropertyNames(),
        );

        foreach ($propertiesFromSchema as $property) {
            $property->generateSubTypes($this);
        }

        $codeGenerator = new Generator($req);

        $properties = [
            ...$properties,
            ...$codeGenerator->generateProperties($propertiesFromSchema),
        ];

        $methods = [
            $codeGenerator->generateConstructor($propertiesFromSchema),
            ...$codeGenerator->generateGetterMethods($propertiesFromSchema),
            ...$codeGenerator->generateSetterMethods($propertiesFromSchema),
            $codeGenerator->generateBuildMethod($propertiesFromSchema),
            $codeGenerator->generateToArrayMethod($propertiesFromSchema),
            $codeGenerator->generateValidateMethod(),
            $codeGenerator->generateCloneMethod($propertiesFromSchema),
        ];
        $methods = array_values(array_filter($methods));
        $this->ensureUniqueMethodNames($methods);

        $cls = new ClassGenerator(
            $req->getTargetClass(),
            $req->getTargetNamespace(),
            null,
            null,
            [],
            $properties,
            $methods,
            null
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

        if ($req->isAtLeastPHP("7.0") && !$req->getOptions()->getDisableStrictTypes()) {
            $file->setDeclares([DeclareStatement::strictTypes(1)]);
        }

        $content = $file->generate();

        // Do some corrections because the Zend code generation library is stupid.
        $content = preg_replace('/ : \\\\self/', ' : self', $content);

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
        // would collide with generated method names
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
        ];

        $reservedMethodNames = [
            'buildFromInput',
            'toArray',
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
            $used[] = StringUtils::camelCase($n);
            $used[] = StringUtils::sanitizeIdentifier($n);
        }
        $used = array_values(array_unique($used));

        $usedMethods = [];
        foreach ($reservedMethodNames as $n) {
            $usedMethods[] = strtolower(StringUtils::pascalCase(StringUtils::camelCase($n)));
            $usedMethods[] = strtolower(StringUtils::pascalCase(StringUtils::sanitizeIdentifier($n)));
        }
        $usedMethods = array_values(array_unique($usedMethods));

        foreach ($properties as $property) {
            $base    = $property->name();
            $unique  = $base;
            $pascal  = strtolower(StringUtils::pascalCase($unique));

            $needsChange = in_array($unique, $used, true)
                || (!$preservePropertyNames && in_array($pascal, $usedMethods, true));

            if ($needsChange) {
                if ($base[0] !== '_') {
                    $unique = '_' . $base;
                    $pascal = strtolower(StringUtils::pascalCase($unique));
                }

                $i = 1;
                $baseUnique = $unique;
                while (in_array($unique, $used, true)
                    || (!$preservePropertyNames && in_array($pascal, $usedMethods, true))) {
                    $unique = $baseUnique . '_' . $i;
                    $pascal = strtolower(StringUtils::pascalCase($unique));
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

            if (preg_match('/^(get|without|with)(.+)$/', $name, $m)) {
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
        
        $generatedClasses = array_map(static function(Definitions\Definition $d) use ($ns): string {
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
}
