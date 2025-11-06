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

        $definitionsToGenerate = $this->filterDefinitionsByAllowedList($allDefinitions, $req);

        if (is_string($rootRef)) {
            $decodedRootRef = rawurldecode($rootRef);
            $canonical = $this->resolveDefinitionRef($decodedRootRef, $allDefinitions);
            if ($canonical !== null) {
                unset($definitionsToGenerate[$canonical]);
            }
        }

        $generator = new DefinitionsGenerator($this);
        $generator->generate($definitionsToGenerate, $req);
    }

    /**
     * @param array<string, Definition> $definitions
     * @return array<string, Definition>
     */
    private function filterDefinitionsByAllowedList(array $definitions, GeneratorRequest $req): array
    {
        $allowedNames = $req->getAllowedDefinitionNames();
        if ($allowedNames === null) {
            return $definitions;
        }

        $allowedPaths = $this->resolveAllowedDefinitionPaths($definitions, $allowedNames);
        if ($allowedPaths === []) {
            return [];
        }

        $requiredPaths = $this->expandDefinitionDependencies($allowedPaths, $definitions);

        return array_intersect_key($definitions, array_flip($requiredPaths));
    }

    /**
     * @param array<string, Definition> $definitions
     * @param list<string> $allowedNames
     * @return list<string>
     */
    private function resolveAllowedDefinitionPaths(array $definitions, array $allowedNames): array
    {
        $resolved = [];

        foreach ($allowedNames as $name) {
            if ($name === '') {
                continue;
            }

            if (str_starts_with($name, '#/')) {
                $canonical = $this->resolveDefinitionRef(rawurldecode($name), $definitions);
                if ($canonical !== null) {
                    $resolved[$canonical] = true;
                }
                continue;
            }

            foreach ($definitions as $path => $_definition) {
                if ($this->definitionPathMatchesName($path, $name)) {
                    $resolved[$path] = true;
                }
            }
        }

        return array_keys($resolved);
    }

    private function definitionPathMatchesName(string $path, string $name): bool
    {
        $normalizedPath = $this->normalizeDefinitionName($path);
        $normalizedName = $this->normalizeDefinitionName($name);

        if ($normalizedPath === $normalizedName) {
            return true;
        }

        if (!str_contains($normalizedName, '/')) {
            $lastSegment = $this->lastDefinitionSegment($normalizedPath);
            return $lastSegment === $normalizedName;
        }

        return false;
    }

    private function normalizeDefinitionName(string $value): string
    {
        $trimmed = ltrim($value, '#/');
        if ($trimmed === '') {
            return '';
        }

        $segments = explode('/', $trimmed);
        $filtered = array_values(array_filter($segments, static function (string $segment): bool {
            return $segment !== 'definitions' && $segment !== '$defs';
        }));

        return implode('/', $filtered);
    }

    private function lastDefinitionSegment(string $normalizedPath): string
    {
        $pos = strrpos($normalizedPath, '/');
        if ($pos === false) {
            return $normalizedPath;
        }

        return substr($normalizedPath, $pos + 1);
    }

    /**
     * @param list<string> $allowedPaths
     * @param array<string, Definition> $definitions
     * @return list<string>
     */
    private function expandDefinitionDependencies(array $allowedPaths, array $definitions): array
    {
        $required = [];
        foreach ($allowedPaths as $path) {
            if (isset($definitions[$path])) {
                $required[$path] = true;
            }
        }

        $queue = array_keys($required);

        while (!empty($queue)) {
            $current = array_pop($queue);
            if (!isset($definitions[$current])) {
                continue;
            }

            foreach ($this->collectDefinitionDependencies($definitions[$current], $definitions) as $dependency) {
                if (!isset($required[$dependency]) && isset($definitions[$dependency])) {
                    $required[$dependency] = true;
                    $queue[] = $dependency;
                }
            }
        }

        return array_keys($required);
    }

    /**
     * @param array<string, Definition> $definitions
     * @return list<string>
     */
    private function collectDefinitionDependencies(Definition $definition, array $definitions): array
    {
        $dependencies = [];
        $queue = [$definition->schema];

        while ($current = array_pop($queue)) {
            foreach ($current as $key => $value) {
                if ($key === '$ref' && is_string($value)) {
                    $canonical = $this->resolveDefinitionRef(rawurldecode($value), $definitions);
                    if ($canonical !== null && !isset($dependencies[$canonical])) {
                        $dependencies[$canonical] = true;
                    }
                } elseif (is_array($value)) {
                    $queue[] = $value;
                }
            }
        }

        return array_keys($dependencies);
    }

    /**
     * @param array<string, Definition> $definitions
     */
    private function resolveDefinitionRef(string $ref, array $definitions): ?string
    {
        if (isset($definitions[$ref])) {
            return $ref;
        }

        if (str_starts_with($ref, '#/definitions/')) {
            $alt = '#/$defs/' . substr($ref, 14);
            if (isset($definitions[$alt])) {
                return $alt;
            }
        } elseif (str_starts_with($ref, '#/$defs/')) {
            $alt = '#/definitions/' . substr($ref, 7);
            if (isset($definitions[$alt])) {
                return $alt;
            }
        }

        return null;
    }
}
