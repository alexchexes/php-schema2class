<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\Class\ClassGenerator;
use Helmich\Schema2Class\Generator\Definition\Definition;
use Helmich\Schema2Class\Generator\Definition\DefinitionsCollector;
use Helmich\Schema2Class\Generator\Definition\DefinitionsGenerator;
use Helmich\Schema2Class\Generator\Enum\SchemaToEnum;
use Helmich\Schema2Class\Generator\Property\Type\Composite\IntersectProperty;
use Helmich\Schema2Class\Generator\Property\Type\Object\NestedObjectProperty;
use Helmich\Schema2Class\Generator\ReferenceLookup\DefinitionsReferenceLookup;
use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Helmich\Schema2Class\Generator\GeneratorException;
use Throwable;

/**
 * Low-level generator that converts a prepared GeneratorRequest into PHP classes.
 * For a simpler, one-call solution use {@see \Helmich\Schema2Class\Schema2Class}.
 */
class SchemaToClass
{
    public function __construct(
        private WriterInterface $writer,
        private OutputInterface $output
    ) {}

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

        $classGenerator = new ClassGenerator($req, $schema, $this->writer, $this->output);
        try {
            $classGenerator->generateClass();
        } catch (Throwable $e) {
            $cls = $req->getTargetClass() ?? '<anonymous>';
            $msg = "error generating class '{$cls}': " . $e->getMessage();
            throw new GeneratorException($msg, 0, $e);
        }
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

        $definitionsToGenerate = $allDefinitions;
        $allowedDefinitions = $req->getAllowedDefinitionNames();
        if ($allowedDefinitions !== null) {
            $definitionsToGenerate = $this->filterDefinitionsByAllowed($definitionsToGenerate, $allowedDefinitions);
        }

        $ns = $req->getTargetNamespace();

        $generatedClasses = array_map(static function(Definition $d) use ($ns): string {
            $cls = $d->classFQN;
            if ($ns !== '' && str_starts_with($cls, $ns . '\\')) {
                return substr($cls, strlen($ns) + 1);
            }
            return ltrim($cls, '\\');
        }, $definitionsToGenerate);

        if ($req->getTargetClass() !== null) {
            $generatedClasses[] = $req->getTargetClass();
        }

        $req = $req
            ->withAdditionalReferenceLookup(new DefinitionsReferenceLookup($definitionsToGenerate))
            ->withGeneratedClassNames($generatedClasses);

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

    /**
     * @param array<string,Definition> $definitions
     * @param list<string> $allowedNames
     * @return array<string,Definition>
     */
    private function filterDefinitionsByAllowed(array $definitions, array $allowedNames): array
    {
        if ($allowedNames === []) {
            return [];
        }

        $pointerMap = [];
        foreach ($definitions as $path => $definition) {
            $pointerMap[$this->normalizeDefinitionPointer($path)] = $path;
        }

        $selectedPointers = [];
        foreach ($allowedNames as $name) {
            foreach ($this->matchDefinitionPointers($name, $pointerMap) as $pointer) {
                $selectedPointers[$pointer] = true;
            }
        }

        if ($selectedPointers === []) {
            return [];
        }

        $definitionByPointer = [];
        foreach ($definitions as $path => $definition) {
            $definitionByPointer[$this->normalizeDefinitionPointer($path)] = $definition;
        }

        $resolvedPointers = $this->expandDefinitionDependencies($selectedPointers, $definitionByPointer, $pointerMap);

        $filtered = [];
        foreach ($resolvedPointers as $pointer => $_) {
            if (!isset($pointerMap[$pointer])) {
                continue;
            }
            $path = $pointerMap[$pointer];
            $filtered[$path] = $definitions[$path];
        }

        return $filtered;
    }

    private function normalizeDefinitionPointer(string $path): string
    {
        return ltrim($path, '#/');
    }

    /**
     * @param array<string,string> $pointerMap pointer => original path
     * @return list<string>
     */
    private function matchDefinitionPointers(string $candidate, array $pointerMap): array
    {
        $normalized = ltrim($candidate, '#/');
        if ($normalized === '') {
            return [];
        }

        $matches = [];
        $patternSegments = $this->normalizePointerSegments($normalized);

        foreach ($pointerMap as $pointer => $_) {
            if ($pointer === $normalized) {
                $matches[] = $pointer;
                continue;
            }

            $pointerSegments = $this->normalizePointerSegments($pointer);

            $len = count($patternSegments);
            if ($len === 0) {
                continue;
            }

            if ($len <= count($pointerSegments)
                && array_slice($pointerSegments, -$len) === $patternSegments
            ) {
                $matches[] = $pointer;
            }
        }

        return array_values(array_unique($matches));
    }

    /**
     * @return list<string>
     */
    private function normalizePointerSegments(string $pointer): array
    {
        $segments = array_values(array_filter(explode('/', $pointer), static fn(string $segment): bool => $segment !== ''));

        return array_values(array_filter(
            $segments,
            static fn(string $segment): bool => $segment !== 'definitions' && $segment !== '$defs'
        ));
    }

    /**
     * @param array<string,bool> $selectedPointers pointer => true
     * @param array<string,Definition> $definitionsByPointer
     * @param array<string,string> $pointerMap
     * @return array<string,bool>
     */
    private function expandDefinitionDependencies(
        array $selectedPointers,
        array $definitionsByPointer,
        array $pointerMap
    ): array {
        $queue = array_keys($selectedPointers);

        while ($queue !== []) {
            $pointer = array_pop($queue);
            if (!isset($definitionsByPointer[$pointer])) {
                continue;
            }

            $deps = $this->collectDefinitionDependencies($definitionsByPointer[$pointer]);
            foreach ($deps as $depPointer) {
                if (!isset($pointerMap[$depPointer]) || isset($selectedPointers[$depPointer])) {
                    continue;
                }

                $selectedPointers[$depPointer] = true;
                $queue[] = $depPointer;
            }
        }

        return $selectedPointers;
    }

    /**
     * @return list<string>
     */
    private function collectDefinitionDependencies(Definition $definition): array
    {
        $refs = [];

        $iter = function (mixed $node) use (&$iter, &$refs): void {
            if (!is_array($node)) {
                return;
            }

            foreach ($node as $key => $value) {
                if ($key === '$ref' && is_string($value) && str_starts_with($value, '#/')) {
                    $pointer = substr($value, 2);
                    if (str_starts_with($pointer, 'definitions/') || str_starts_with($pointer, '$defs/')) {
                        $refs[] = $pointer;
                    }
                } elseif (is_array($value)) {
                    $iter($value);
                }
            }
        };

        $iter($definition->schema);

        return array_values(array_unique($refs));
    }
}
