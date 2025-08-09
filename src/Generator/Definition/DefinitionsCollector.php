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
        if (($cls = $this->generatorRequest->getTargetClass()) !== null) {
            $schema = $this->generatorRequest->getSchema();
            $generatesClass = IntersectProperty::canHandleSchema($schema)
                || NestedObjectProperty::canHandleSchema($schema);

            if ($generatesClass) {
                $ns = trim($this->generatorRequest->getTargetNamespace(), '\\');
                $fqn = $ns !== '' ? $ns . '\\' . $cls : $cls;
                $this->usedClassNames[] = $fqn;
            }
        }
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
