<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Command;

use Helmich\Schema2Class\Generator\DefinitionsReferenceLookup;
use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\NamespaceInferrer;
use Helmich\Schema2Class\Generator\SchemaToClassFactory;
use Helmich\Schema2Class\Util\StringUtils;
use Helmich\Schema2Class\Generator\Property\NestedObjectProperty;
use Helmich\Schema2Class\Writer\DebugWriter;
use Helmich\Schema2Class\Writer\FileWriter;
use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\NullOutput;
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
     * Remove all files from the given directory.
     */
    private function cleanDirectory(string $directory, OutputInterface $output): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $output->writeln("cleaning directory <comment>{$directory}</comment>");

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST,
        );
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDir()) {
                @rmdir($fileInfo->getPathname());
            } else {
                @unlink($fileInfo->getPathname());
            }
        }
    }

    /**
     * Infer the target namespace or fall back to a default class name.
     */
    private function inferNamespace(
        string $targetDir,
        ?string $givenNamespace = null,
        ?OutputInterface $output = null,
    ): string {
        if ($givenNamespace) {
            return $givenNamespace;
        }

        $output = $output ?? new NullOutput();
        $output->writeln('target namespace not given. inferring from composer.json...');

        try {
            return $this->namespaceInferrer->inferNamespaceFromComposerFile($targetDir);
        } catch (GeneratorException $e) {
            $fallback = StringUtils::pascalCase(basename(str_replace('\\', '/', rtrim($targetDir, '/'))));
            $output->writeln(
                "  ↳ PSR‑4 lookup failed, defaulting to directory name as namespace: <comment>{$fallback}</comment>"
            );
            return $fallback;
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
