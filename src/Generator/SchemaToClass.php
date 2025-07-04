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

        // 2) if the caller supplied root definitions, *always* splice them in here
        if (($defs = $req->getRootDefinitions()) !== null && count($defs) > 0) {
            // don't overwrite if the schema already carried its own definitions
            if (!isset($schema['definitions'])) {
                $schema['definitions'] = $defs;
            } else {
                // merge – let local keys override, just in case
                $schema['definitions'] = array_replace($defs, $schema['definitions']);
            }
        }

        // dereference schemas that consist only of a reference
        if (isset($schema['$ref'])) {
            $schema = $req->lookupSchema($schema['$ref']);
        }

        $req = $req->withSchema($schema);
        $this->definitionsToSchemas($req);
        $schema = $req->getSchema();

        if (isset($schema["enum"])) {
            if (SchemaToEnum::canGenerateEnum($schema, $req)) {
                $this->enumGenerator->schemaToEnum($req);
            }
            return;
        }

        if (IntersectProperty::canHandleSchema($schema)) {
            $schema = (new IntersectProperty($req->getTargetClass(), $schema, $req))->buildSchemaIntersect();
        } elseif (!NestedObjectProperty::canHandleSchema($schema)) {
            // If the schema does not describe an object we only generate definitions
            $class = $req->getTargetClass();
            if ($class !== null) {
                $this->output->writeln("skipping definition <comment>{$class}</comment>: not an object");
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

        $this->ensureUniquePropertyNames($propertiesFromSchema);

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
    private function ensureUniquePropertyNames(PropertyCollection $properties): void
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
            $base = $property->name();
            $unique = $base;
            $i = 1;
            $pascal = strtolower(StringUtils::pascalCase($unique));

            while (in_array($unique, $used, true) || in_array($pascal, $usedMethods, true)) {
                $unique = $base . '_' . $i;
                $pascal = strtolower(StringUtils::pascalCase($unique));
                $i++;
            }

            if ($unique !== $base && $property instanceof RenameablePropertyInterface) {
                $property->setName($unique);
            }

            $used[] = $unique;
            $usedMethods[] = $pascal;
        }
    }

    private function definitionsToSchemas(GeneratorRequest &$req): void
    {
        if ($req->hasReferenceLookup(DefinitionsReferenceLookup::class)) {
            return;
        }

        $collector = new DefinitionsCollector($req);
        $collected  = iterator_to_array($collector->collect($req->getSchema()));

        $ns = $req->getTargetNamespace();
        
        $generatedClasses = array_map(static function(Definitions\Definition $d) use ($ns): string {
            $cls = $d->classFQN;
            if ($ns !== '' && str_starts_with($cls, $ns . '\\')) {
                return substr($cls, strlen($ns) + 1);
            }
            return ltrim($cls, '\\');
        }, $collected);

        if ($req->getTargetClass() !== null) {
            $generatedClasses[] = $req->getTargetClass();
        }

        $req = $req
            ->withAdditionalReferenceLookup(new DefinitionsReferenceLookup($collected))
            ->withGeneratedClassNames($generatedClasses);

        $generator = new DefinitionsGenerator($this);
        $generator->generate($collected, $req);
    }
}
