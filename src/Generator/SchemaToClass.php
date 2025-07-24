<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\Definition\Definition;
use Helmich\Schema2Class\Generator\Definition\DefinitionsCollector;
use Helmich\Schema2Class\Generator\Definition\DefinitionsGenerator;
use Helmich\Schema2Class\Generator\Enum\SchemaToEnum;
use Helmich\Schema2Class\Generator\Property\Type\IntersectProperty;
use Helmich\Schema2Class\Generator\Property\Type\NestedObjectProperty;
use Helmich\Schema2Class\Generator\ReferenceLookup\DefinitionsReferenceLookup;
use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Low-level generator that converts a prepared GeneratorRequest into PHP classes.
 * For a simpler, one-call solution use {@see \Helmich\Schema2Class\Schema2Class}.
 */
class SchemaToClass
{
    private WriterInterface $writer;
    private OutputInterface $output;

    public function __construct(WriterInterface $writer, OutputInterface $output)
    {
        $this->writer = $writer;
        $this->output = $output;
    }

    /**
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
        $req = $req->withSchema($schema);

        if ($this->handleEnum($schema, $req)) {
            return;
        }

        $schema = $this->normalizeObjectSchema($schema, $req);
        if ($schema === null) {
            return;
        }

        $classGenerator = new ClassGenerator($req, $this->writer, $this->output);
        $classGenerator->generateClass($schema);
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
            $schema2enum = new SchemaToEnum($this->writer);
            $schema2enum->schemaToEnum($req);
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
}
