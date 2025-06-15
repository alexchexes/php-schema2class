<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Definitions;

use Generator as PhpGenerator;
use Helmich\Schema2Class\Generator\GeneratorRequest;

class DefinitionsCollector
{
    public function __construct(private readonly GeneratorRequest $generatorRequest)
    {
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
        $parts = array_map(
            static fn(string $part) => str_replace(' ', '', ucwords(str_replace('_', ' ', $part))),
            explode('/', ltrim(str_replace('$defs', 'Defs', $path), '#/'))
        );

        $className = array_pop($parts);
        $namespace = trim($this->generatorRequest->getTargetNamespace() . '\\' . implode('\\', $parts), '\\');
        $directory = rtrim($this->generatorRequest->getTargetDirectory() . '/' . implode('/', $parts), '/');

        return new Definition(
            namespace: $namespace,
            directory: $directory,
            classFQN: $namespace . '\\' . $className,
            className: $className,
            schema: $schema,
        );
    }
}
