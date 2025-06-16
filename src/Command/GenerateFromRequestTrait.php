<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Command;

use Helmich\Schema2Class\Generator\DefinitionsReferenceLookup;
use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\NamespaceInferrer;
use Helmich\Schema2Class\Generator\SchemaToClassFactory;
use Helmich\Schema2Class\Generator\Property\NestedObjectProperty;
use Helmich\Schema2Class\Writer\DebugWriter;
use Helmich\Schema2Class\Writer\FileWriter;
use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @property NamespaceInferrer $namespaceInferrer
 * @property SchemaToClassFactory $s2c
 */
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

        $this->s2c->build($writer, $output)->schemaToClass($baseRequest);
    }
}
