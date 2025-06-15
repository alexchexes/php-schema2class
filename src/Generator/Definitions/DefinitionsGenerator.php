<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Definitions;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\SchemaToClass;

class DefinitionsGenerator
{
    public function __construct(private SchemaToClass $schemaToClass)
    {
    }

    /**
     * @param array<string, Definition> $definitions
     */
    public function generate(array $definitions, GeneratorRequest $request): void
    {
        $generatedClasses = array_map(static fn(Definition $d) => $d->className, $definitions);
        $generatedClasses[] = $request->getTargetClass();

        foreach ($definitions as $definition) {
            $newRequest = $request
                ->withClass($definition->className)
                ->withSchema($definition->schema)
                ->withNamespace($definition->namespace)
                ->withDirectory($definition->directory)
                ->withGeneratedClassNames($generatedClasses);
            $this->schemaToClass->schemaToClass($newRequest);
        }
    }
}
