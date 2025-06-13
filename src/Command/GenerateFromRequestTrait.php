<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Command;

use Helmich\Schema2Class\Generator\DefinitionsReferenceLookup;
use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\NamespaceInferrer;
use Helmich\Schema2Class\Generator\Property\NestedObjectProperty;
use Helmich\Schema2Class\Writer\DebugWriter;
use Helmich\Schema2Class\Writer\FileWriter;
use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait GenerateFromRequestTrait
{
    /**
     * Create a writer for either dry‑run or normal execution.
     */
    private function makeWriter(OutputInterface $output, bool $dryRun): WriterInterface
    {
        return $dryRun ? new DebugWriter($output) : new FileWriter($output);
    }

    /**
     * Infer the target namespace or fall back to a default class name.
     */
    private function inferNamespace(
        OutputInterface $output,
        ?string $givenNamespace,
        string $targetDir,
        string $fallbackClass
    ): string {
        if ($givenNamespace) {
            return $givenNamespace;
        }

        $output->writeln('target namespace not given. inferring from directory…');

        try {
            /** @var NamespaceInferrer $this->namespaceInferrer */
            return $this->namespaceInferrer->inferNamespaceFromTargetDirectory($targetDir);
        } catch (GeneratorException $e) {
            $output->writeln(
                "  ↳ PSR‑4 lookup failed, defaulting to class name as namespace: <comment>{$fallbackClass}</comment>"
            );
            return $fallbackClass;
        }
    }

    /**
     * Generate classes for a schema described by the given request.
     */
    private function generateFromRequest(GeneratorRequest $baseRequest, OutputInterface $output, bool $dryRun): void
    {
        $writer = $this->makeWriter($output, $dryRun);

        $schema      = $baseRequest->getSchema();
        $definitions = $schema['definitions'] ?? [];

        $lookup      = new DefinitionsReferenceLookup($definitions);
        $baseRequest = $baseRequest->withReferenceLookup($lookup);

        $generatedClasses   = array_keys($definitions);
        $generatedClasses[] = $baseRequest->getTargetClass();

        foreach ($definitions as $defName => $defSchema) {
            $deps        = self::collectAllLocalRefs($defSchema, $definitions);
            $trimmedDefs = array_intersect_key($definitions, array_flip($deps));

            $reqDef = $baseRequest
                ->withClass($defName)
                ->withSchema($defSchema)
                ->withRootDefinitions($trimmedDefs)
                ->withGeneratedClassNames($generatedClasses);

            $this->s2c->build($writer, $output)->schemaToClass($reqDef);
        }

        if (NestedObjectProperty::canHandleSchema($schema)) {
            $mainRequest = $baseRequest
                ->withClass($baseRequest->getTargetClass())
                ->withRootDefinitions($definitions)
                ->withGeneratedClassNames($generatedClasses);

            $this->s2c->build($writer, $output)->schemaToClass($mainRequest);
        }
    }

    /**
     * Recursively collect *all* local #/definitions/... dependencies of $schema.
     *
     * @param array<string,mixed> $schema         object/definition currently emitted
     * @param array<string,mixed> $allDefinitions full root-level "definitions" map
     * @return string[]                           list of definition names actually needed
     */
    private static function collectAllLocalRefs(array $schema, array $allDefinitions): array
    {
        $needed  = [];
        $queue   = [$schema];
        $visited = [];

        while ($cur = array_pop($queue)) {
            $iter = function ($node) use (&$iter, &$needed, &$queue, &$visited, $allDefinitions) {
                if (is_array($node)) {
                    foreach ($node as $k => $v) {
                        if ($k === '$ref'
                            && is_string($v)
                            && str_starts_with($v, '#/definitions/')) {
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
}
