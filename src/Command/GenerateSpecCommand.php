<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Command;

use Helmich\Schema2Class\Generator\Property\NestedObjectProperty;
use Helmich\Schema2Class\Generator\DefinitionsReferenceLookup;
use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\NamespaceInferrer;
use Helmich\Schema2Class\Generator\SchemaToClassFactory;
use Helmich\Schema2Class\Loader\LoadingException;
use Helmich\Schema2Class\Loader\SchemaLoader;
use Helmich\Schema2Class\Spec\Specification;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use Helmich\Schema2Class\Util\StringUtils;
use Helmich\Schema2Class\Writer\DebugWriter;
use Helmich\Schema2Class\Writer\FileWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class GenerateSpecCommand extends Command
{
    private SchemaLoader $loader;

    private NamespaceInferrer $namespaceInferrer;

    private SchemaToClassFactory $s2c;

    public function __construct(SchemaLoader $loader, NamespaceInferrer $namespaceInferrer, SchemaToClassFactory $s2c)
    {
        parent::__construct();

        $this->loader = $loader;
        $this->namespaceInferrer = $namespaceInferrer;
        $this->s2c = $s2c;
    }

    protected function configure(): void
    {
        $this->setName("generate:fromspec");
        $this->setDescription("Generate PHP classes from a StructBuilder specification file");

        $this->addArgument("specfile", InputArgument::OPTIONAL, "Specification file to read");
        $this->addOption("dry-run", null, InputOption::VALUE_NONE, "Print output to console instead of writing to files");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $specFile = $input->getArgument("specfile") ?: getcwd() . "/.s2c.yaml";
        if (!file_exists($specFile)) {
            throw new LoadingException($specFile, "specification file not found");
        }

        $parsed = Yaml::parse(file_get_contents($specFile));
        $specification = Specification::buildFromInput($parsed);

        $writer = $input->getOption("dry-run") ? new DebugWriter($output) : new FileWriter($output);

        // prepare target-PHP version
        $opts = $specification->getOptions() ?? new SpecificationOptions();
        if ($v = $specification->getTargetPHPVersion()) {
            $opts = $opts->withTargetPHPVersion($v);
        }
        $tpv = $opts->getTargetPHPVersion();
        if (is_int($tpv)) {
            $tpv = $tpv === 5 ? "5.6.0" : "7.4.0";
        }
        $opts = $opts->withTargetPHPVersion($tpv);

        foreach ($specification->getFiles() as $file) {
            $schemaFile      = $file->getInput();
            $targetNamespace = $file->getTargetNamespace();
            $targetDirectory = $file->getTargetDirectory();

            $output->writeln("loading schema from <comment>$schemaFile</comment>");

            // -- derive className if missing --
            $className = $file->getClassName();
            if ($className === null) {
                $basename  = pathinfo($schemaFile, PATHINFO_FILENAME);
                $className = StringUtils::capitalizeName($basename);
                $file      = $file->withClassName($className);
            }

            // infer namespace if needed, but fall back to the main class name on error
            if ($file->getTargetNamespace() === null) {
                $output->writeln("target namespace not given. inferring from directory…");
                try {
                    $targetNamespace = $this->namespaceInferrer
                        ->inferNamespaceFromTargetDirectory($targetDirectory);
                } catch (GeneratorException $e) {
                    $output->writeln(
                        "  ↳ PSR‑4 lookup failed, defaulting to class name as namespace: <comment>{$className}</comment>"
                    );
                    $targetNamespace = $className;
                }
                $file = $file->withTargetNamespace($targetNamespace);
            }

            $output->writeln("using target namespace <comment>$targetNamespace</comment> in directory <comment>$targetDirectory</comment>");

            $schema = $this->loader->loadSchema($schemaFile);

            // === definitions‐first loop ===
            $definitions = $schema['definitions'] ?? [];
            $lookup      = new DefinitionsReferenceLookup($definitions);

            $baseRequest = (new GeneratorRequest(
                $schema,
                ValidatedSpecificationFilesItem::fromSpecificationFilesItem($file, $targetNamespace),
                $opts
            ))->withReferenceLookup($lookup);

            // 1) emit all definitions
            foreach ($definitions as $defName => $defSchema) {
                $reqDef = $baseRequest
                    ->withClass($defName)
                    ->withSchema($defSchema);
                $this->s2c->build($writer, $output)
                    ->schemaToClass($reqDef);
            }

            // 2) emit main class if root schema is an object
            if (NestedObjectProperty::canHandleSchema($schema)) {
                $mainReq = $baseRequest->withClass($className);
                $this->s2c->build($writer, $output)
                    ->schemaToClass($mainReq);
            }
            // === end definitions loop ===
        }

        return 0;
    }

}
