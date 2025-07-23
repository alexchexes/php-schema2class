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
use Helmich\Schema2Class\Generator\Property\Interface\IntersectProperty;
use Helmich\Schema2Class\Generator\Property\Interface\NestedObjectProperty;
use Helmich\Schema2Class\Writer\DebugWriter;
use Helmich\Schema2Class\Writer\FileWriter;
use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/** 
 * High level orchestration for turning a specification into PHP classes.
 * 
 * It reads a {@see Specification}, resolves option defaults and invokes
 * {@see SchemaToClass} for each source schema.
 * Used by top-level {@see Schema2Class} API class, which in turn used by the CLI commands.
 */
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

    private static function schemaNeedsClass(array $schema): bool
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
        $writer = $dryRun ? new DebugWriter($output) : new FileWriter($output);

        if (!$dryRun) {
            $writer = new \Helmich\Schema2Class\Writer\LintingWriter($writer);
        }

        return $writer;
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
            $fallback = StringUtils::safePascalCase(basename(str_replace('\\', '/', rtrim($targetDir, '/'))));
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

            $output->writeln("Target PHP version <comment>{$opts->getTargetPHPVersion()}</comment>");

            $targetDirectory = $opts->getTargetDirectory() ?? '';
            $targetNamespaceOption = $opts->getTargetNamespace();

            if (is_string($schemaInput)) {
                $output->writeln("Loading schema from <comment>{$schemaInput}</comment>");
            } else {
                $output->writeln('Loading schema from <comment>inline specification</comment>');
            }

            $schema = $this->loader->loadSchema($schemaInput);

            $className = $file->getClassName();
            if ($className === null && self::schemaNeedsClass($schema) && is_string($schemaInput)) {
                $basename = pathinfo($schemaInput, PATHINFO_FILENAME);
                $className = StringUtils::safePascalCase($basename);
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
                "Using target namespace <comment>{$targetNamespace}</comment> in directory <comment>{$targetDirectory}</comment>"
            );


            $validated = ValidatedSpecificationFilesItem::fromSpecificationFilesItem($file, $opts, $targetNamespace);

            if ($validated->getCleanTargetDirectory()) {
                $this->cleanDirectory($validated->getTargetDirectory(), $output);
            }

            $baseRequest = (new GeneratorRequest(
                $schema,
                $validated,
                $opts,
            ))->withRootDefinitions(
                array_merge($schema['definitions'] ?? [], $schema['$defs'] ?? [])
            );

            $this->generateFromRequest($baseRequest, $output, $dryRun);
        }
    }
}
