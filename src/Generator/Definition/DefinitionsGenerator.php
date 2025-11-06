<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Definition;

use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\SchemaToClass;
use Throwable;

/**
 * Generates PHP classes for each {@see Definition} produced by a {@see DefinitionsCollector}.
 *
 * The generator is invoked from {@see SchemaToClass} when a schema contains nested definitions.
 * It clones the original request for every discovered definition and
 * invokes {@see SchemaToClass} again to turn the sub-schema into a dedicated class.
 */
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
            $iter = function (mixed $node) use (&$iter, &$needed, &$queue, &$visited, $allDefinitions) {
                if (is_array($node)) {
                    foreach ($node as $k => $v) {
                        if ($k === '$ref' && is_string($v) && str_starts_with($v, '#/')) {
                            $pointer = substr($v, 2);
                            if (str_starts_with($pointer, 'definitions/')) {
                                $refName = substr($pointer, 12);
                            } elseif (str_starts_with($pointer, '$defs/')) {
                                $refName = substr($pointer, 6);
                            } else {
                                $refName = null;
                            }

                            if ($refName !== null) {
                                if (!isset($visited[$refName])) {
                                    $visited[$refName] = true;
                                    $needed[]       = $refName;

                                    if (isset($allDefinitions[$refName])) {
                                        $queue[] = $allDefinitions[$refName];
                                    }
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
        $request = $request->withoutIncludeDefinitions();

        $ns = $request->getTargetNamespace();
        $generatedClasses = array_map(
            static function(Definition $d) use ($ns): string {
                $cls = $d->classFQN;
                if ($ns !== '' && str_starts_with($cls, $ns . '\\')) {
                    return substr($cls, strlen($ns) + 1);
                }
                return ltrim($cls, '\\');
            },
            $definitions
        );

        if ($request->getTargetClass() !== null) {
            $generatedClasses[] = $request->getTargetClass();
        }

        $rootDefinitions = array_merge(
            $request->getSchema()['definitions'] ?? [],
            $request->getSchema()['$defs'] ?? [],
        );

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

            try {
                $this->schemaToClass->schemaToClass($newRequest);
            } catch (Throwable $e) {
                $msg = "error generating definition '{$definition->classFQN}': " . $e->getMessage();
                throw new GeneratorException($msg, 0, $e);
            }
        }
    }
}
