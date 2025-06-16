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
     * Recursively collect local #/definitions/... references in the given schema.
     *
     * @param array<string,mixed> $schema
     * @param array<string,mixed> $allDefinitions
     * @return string[]
     */
    private static function collectLocalRefs(array $schema, array $allDefinitions): array
    {
        $needed  = [];
        $queue   = [$schema];
        $visited = [];

        while ($cur = array_pop($queue)) {
            $iter = function ($node) use (&$iter, &$needed, &$queue, &$visited, $allDefinitions) {
                if (is_array($node)) {
                    foreach ($node as $k => $v) {
                        if ($k === '$ref' && is_string($v) && str_starts_with($v, '#/definitions/')) {
                            $name = substr($v, 14);
                            if (!isset($visited[$name])) {
                                $visited[$name] = true;
                                $needed[]       = $name;

                                if (isset($allDefinitions[$name])) {
                                    $queue[] = $allDefinitions[$name];
                                }
                            }
                        } elseif (is_array($v)) {
                            $iter($v);
                        }
                    }
                }
            };
            $iter($cur);
        }

        return $needed;
    }

    /**
     * @param array<string, Definition> $definitions
     */
    public function generate(array $definitions, GeneratorRequest $request): void
    {
        $ns = $request->getTargetNamespace();
        $generatedClasses = array_map(static function(Definition $d) use ($ns): string {
            $cls = $d->classFQN;
            if ($ns !== '' && str_starts_with($cls, $ns . '\\')) {
                return substr($cls, strlen($ns) + 1);
            }
            return ltrim($cls, '\\');
        }, $definitions);
        $generatedClasses[] = $request->getTargetClass();

        $rootDefinitions = $request->getSchema()['definitions'] ?? [];

        foreach ($definitions as $definition) {
            $deps        = self::collectLocalRefs($definition->schema, $rootDefinitions);
            $trimmedDefs = array_intersect_key($rootDefinitions, array_flip($deps));

            $newRequest = $request
                ->withClass($definition->className)
                ->withSchema($definition->schema)
                ->withNamespace($definition->namespace)
                ->withDirectory($definition->directory)
                ->withGeneratedClassNames($generatedClasses)
                ->withRootDefinitions($trimmedDefs);

            $this->schemaToClass->schemaToClass($newRequest);
        }
    }
}
