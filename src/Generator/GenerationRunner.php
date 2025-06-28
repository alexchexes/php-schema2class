<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\NamespaceInferrer;
use Helmich\Schema2Class\Generator\SchemaToClassFactory;
use Helmich\Schema2Class\Loader\SchemaLoader;
use Helmich\Schema2Class\Spec\Specification;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\OptionsDefaults;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use Helmich\Schema2Class\Util\StringUtils;
use Helmich\Schema2Class\Generator\Property\IntersectProperty;
use Helmich\Schema2Class\Generator\Property\NestedObjectProperty;
use Helmich\Schema2Class\Writer\DebugWriter;
use Helmich\Schema2Class\Writer\FileWriter;
use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class GenerationRunner
{
    private SchemaLoader $loader;
    private NamespaceInferrer $namespaceInferrer;
    private SchemaToClassFactory $factory;

    /**
     * Determine if the schema describes a top-level class or enum.
     *
     * @param array<string,mixed> $schema
     */
    private static function schemaNeedsClass(array $schema): bool
    {
        return IntersectProperty::canHandleSchema($schema)
            || NestedObjectProperty::canHandleSchema($schema)
            || array_key_exists('enum', $schema);
    }

    public function __construct(
        SchemaLoader $loader,
        NamespaceInferrer $namespaceInferrer,
        SchemaToClassFactory $factory,
    ) {
        $this->loader = $loader;
        $this->namespaceInferrer = $namespaceInferrer;
        $this->factory = $factory;
    }

    private function makeWriter(OutputInterface $output, bool $dryRun): WriterInterface
    {
        return $dryRun ? new DebugWriter($output) : new FileWriter($output);
    }

    public function cleanDirectory(string $directory, OutputInterface $output): void
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

    public function inferNamespace(
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
                "  ↳ PSR-4 lookup failed, defaulting to directory name as namespace: <comment>{$fallback}</comment>"
            );
            return $fallback;
        }
    }

    public function generateFromRequest(GeneratorRequest $request, OutputInterface $output, bool $dryRun): void
    {
        if ($request->getTargetClass() === null && self::schemaNeedsClass($request->getSchema())) {
            throw new \InvalidArgumentException(
                'Class name is required when the schema describes a top-level object or enum.'
            );
        }

        $writer = $this->makeWriter($output, $dryRun);

        $this->factory->build($writer, $output)->schemaToClass($request);
    }

    public function generateFromSpecification(Specification $spec, OutputInterface $output, bool $dryRun): void
    {
        $globalOpts = OptionsDefaults::applyDefaults(
            $spec->getOptions() ?? new SpecificationOptions(),
        );

        foreach ($spec->getFiles() as $file) {
            $schemaInput = $file->getInput();

            $opts = OptionsDefaults::mergeOptions($globalOpts, $file->getOptions());

            $tpv = GeneratorRequest::normalizeTargetVersion($opts->getTargetPHPVersion());
            $opts = $opts->withTargetPHPVersion($tpv);

            $targetDirectory = $opts->getTargetDirectory() ?? '';
            $targetNamespaceOption = $opts->getTargetNamespace();

            if (is_string($schemaInput)) {
                $output->writeln("loading schema from <comment>{$schemaInput}</comment>");
            } else {
                $output->writeln('loading schema from <comment>inline specification</comment>');
            }

            $className = $file->getClassName();
            if ($className === null && is_string($schemaInput)) {
                $basename = pathinfo($schemaInput, PATHINFO_FILENAME);
                $className = StringUtils::pascalCase($basename);
                $file = $file->withClassName($className);
            }

            $targetNamespace = $this->inferNamespace(
                $targetDirectory,
                $targetNamespaceOption,
                $output,
            );
            $opts = $opts->withTargetNamespace($targetNamespace)
                         ->withTargetDirectory($targetDirectory);

            $output->writeln(
                "using target namespace <comment>{$targetNamespace}</comment> in directory <comment>{$targetDirectory}</comment>"
            );

            $schema = $this->loader->loadSchema($schemaInput);

            $validated = ValidatedSpecificationFilesItem::fromSpecificationFilesItem($file, $opts, $targetNamespace);

            if ($validated->getCleanTargetDirectory()) {
                $this->cleanDirectory($validated->getTargetDirectory(), $output);
            }

            $baseRequest = new GeneratorRequest(
                $schema,
                $validated,
                $opts,
            );

            $this->generateFromRequest($baseRequest, $output, $dryRun);
        }
    }
}
