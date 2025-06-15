<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Codegen\PropertyGenerator;
use Helmich\Schema2Class\Generator\Definitions\Definition;
use Helmich\Schema2Class\Generator\Definitions\DefinitionsCollector;
use Helmich\Schema2Class\Generator\Definitions\DefinitionsGenerator;
use Helmich\Schema2Class\Generator\DefinitionsReferenceLookup;
use Helmich\Schema2Class\Generator\Property\IntersectProperty;
use Helmich\Schema2Class\Generator\Property\NestedObjectProperty;
use Helmich\Schema2Class\Generator\Property\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\RenameablePropertyInterface;
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

    /**
     * @phpstan-ignore constructor.unusedParameter (kept for backwards compatibility)
     */
    public function __construct(WriterInterface $writer, OutputInterface $output)
    {
        $this->writer = $writer;
        $this->enumGenerator = new SchemaToEnum($writer);
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
            $this->enumGenerator->schemaToEnum($req);
            return;
        }

        if (IntersectProperty::canHandleSchema($schema)) {
            $schema = (new IntersectProperty($req->getTargetClass(), $schema, $req))->buildSchemaIntersect();
        } elseif (!NestedObjectProperty::canHandleSchema($schema)) {
            // If the schema does not describe an object we only generate definitions
            return;
        }

        // remove descriptions from schema if such option is set, but keep them
        // for building property documentation
        $validationSchema = $schema;
        if ($req->getOptions()->getNoDescriptionsInSchema()) {
            $stripDescriptions = function (&$node) use (&$stripDescriptions) {
                if (!is_array($node)) {
                    return;
                }
                foreach ($node as $key => &$value) {
                    if ($key === 'description') {
                        unset($node[$key]);
                    } elseif (is_array($value)) {
                        // recurse into nested arrays
                        $stripDescriptions($value);
                    }
                }
            };
            $stripDescriptions($validationSchema);
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
            $codeGenerator->generateToJSONMethod($propertiesFromSchema),
            $codeGenerator->generateValidateMethod(),
            $codeGenerator->generateCloneMethod($propertiesFromSchema),
        ];

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
        $content = preg_replace('/\\\\' . preg_quote($req->getTargetNamespace(), '/') . '\\\\/', '\\', $content);

        // Remove "\" before class names that we just generated (as they're all in the current namespace)
        $ownClasses = $req->getGeneratedClassNames();
        if ($ownClasses) {
            $escapedOwnClasses = array_map(fn ($n) => preg_quote($n, '/'), $ownClasses);
            $pattern = '/\\\\(' . join('|', $escapedOwnClasses) . ')(?=\s|[,;)]|$)/';
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
        $used = [];
        foreach ($properties as $property) {
            $base = $property->name();
            $unique = $base;
            $i = 1;
            while (in_array($unique, $used, true)) {
                $unique = $base . '_' . $i;
                $i++;
            }
            if ($unique !== $base && $property instanceof RenameablePropertyInterface) {
                $property->setName($unique);
            }
            $used[] = $unique;
        }
    }

    private function definitionsToSchemas(GeneratorRequest &$req): void
    {
        if ($req->hasReferenceLookup(DefinitionsReferenceLookup::class)) {
            return;
        }

        $collector = new DefinitionsCollector($req);
        $collected  = iterator_to_array($collector->collect($req->getSchema()));

        $generatedClasses = array_map(static fn(Definitions\Definition $d) => $d->className, $collected);
        $generatedClasses[] = $req->getTargetClass();

        $req = $req
            ->withAdditionalReferenceLookup(new DefinitionsReferenceLookup($collected))
            ->withGeneratedClassNames($generatedClasses);

        $generator = new DefinitionsGenerator($this);
        $generator->generate($collected, $req);
    }
}
