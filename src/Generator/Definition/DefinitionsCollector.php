<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Definition;

use Generator as PhpGenerator;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Type\Composite\IntersectProperty;
use Helmich\Schema2Class\Generator\Property\Type\Object\NestedObjectProperty;

/**
 * Class is used to traverse a schema and collect {@see Definition} objects
 * for every nested entry in `#/definitions` or `#/$defs`.
 *
 * The collector also keeps track of already allocated class names to avoid
 * collisions when multiple definitions resolve to the same PHP identifier.
 * Consumers typically iterate over the generator returned by
 * {@see collect()} to pass each definition to the {@see DefinitionsGenerator}.
 */
class DefinitionsCollector
{
    /** @var array<int,string> */
    private array $usedClassNames = [];

    public function __construct(private readonly GeneratorRequest $generatorRequest)
    {
        if (($cls = $this->generatorRequest->getTargetClass()) !== null
            && self::schemaGeneratesClass($this->generatorRequest->getSchema())
        ) {
            $ns = trim($this->generatorRequest->getTargetNamespace(), '\\');
            $fqn = $ns !== '' ? $ns . '\\' . $cls : $cls;
            $this->usedClassNames[] = $fqn;
        }
    }

    /**
     * Determine if the given schema results in a generated class or enum.
     *
     * @param array<string,mixed> $schema
     */
    private static function schemaGeneratesClass(array $schema): bool
    {
        if (isset($schema['$ref']) && is_string($schema['$ref'])) {
            $resolved = self::dereference($schema, $schema['$ref']);
            if (is_array($resolved)) {
                $schema = $resolved;
            }
        }

        return IntersectProperty::canHandleSchema($schema)
            || NestedObjectProperty::canHandleSchema($schema)
            || array_key_exists('enum', $schema);
    }

    /**
     * Resolve an internal reference within the given schema.
     *
     * @param array<string,mixed> $schema
     */
    private static function dereference(array $schema, string $ref): ?array
    {
        if (!str_starts_with($ref, '#/')) {
            return null;
        }

        $segments = explode('/', substr($ref, 2));
        $node = $schema;
        foreach ($segments as $seg) {
            if (!is_array($node) || !array_key_exists($seg, $node)) {
                return null;
            }
            $node = $node[$seg];
        }

        return is_array($node) ? $node : null;
    }

    /**
     * @return PhpGenerator<string, Definition>
     */
    public function collect(array $schema, string $path = ''): PhpGenerator
    {
        if (isset($schema['definitions'])) {
            yield from $this->findNestedDefinitions($schema['definitions'], ($path ?: '#') . '/definitions');
        }

        if (isset($schema['$defs'])) {
            yield from $this->findNestedDefinitions($schema['$defs'], ($path ?: '#') . '/$defs');
        }
    }

    private function findNestedDefinitions(array $definitions, string $path): PhpGenerator
    {
        foreach ($definitions as $key => $value) {
            $newPath = $path . '/' . $key;
            yield $newPath => $this->pathToDefinition($newPath, is_array($value) ? $value : []);

            if (is_array($value)) {
                yield from $this->collect($value, $newPath);
            }
        }
    }

    private function pathToDefinition(string $path, array $schema): Definition
    {
        $segments = explode('/', ltrim(str_replace('$defs', 'Defs', $path), '#/'));
        $segments = array_values(array_filter($segments, static fn(string $p) => $p !== 'definitions'));
        if (isset($segments[0]) && $segments[0] === 'Defs') {
            array_shift($segments);
        }

        $parts = array_map(
            static function (string $part): string {
                $sanitized = \Helmich\Schema2Class\Util\StringUtils::sanitizeIdentifier($part);
                return \Helmich\Schema2Class\Util\StringUtils::safePascalCase($sanitized);
            },
            $segments
        );

        $className = array_pop($parts);
        $namespace = trim($this->generatorRequest->getTargetNamespace() . '\\' . implode('\\', $parts), '\\');
        $directory = rtrim($this->generatorRequest->getTargetDirectory() . '/' . implode('/', $parts), '/');

        $base = $className;
        $candidate = $base;
        $fqn = ($namespace !== '' ? $namespace . '\\' : '') . $candidate;
        $i = 1;
        while (in_array($fqn, $this->usedClassNames, true)) {
            $candidate = $base . '_' . $i;
            $fqn = ($namespace !== '' ? $namespace . '\\' : '') . $candidate;
            $i++;
        }

        $this->usedClassNames[] = $fqn;

        return new Definition(
            namespace: $namespace,
            directory: $directory,
            classFQN: $fqn,
            className: $candidate,
            schema: $schema,
        );
    }
}
