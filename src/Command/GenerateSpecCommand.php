<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Command;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\NamespaceInferrer;
use Helmich\Schema2Class\Generator\SchemaToClassFactory;
use Helmich\Schema2Class\Loader\LoadingException;
use Helmich\Schema2Class\Loader\SchemaLoader;
use Helmich\Schema2Class\Spec\Specification;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\OptionsDefaults;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use Helmich\Schema2Class\Schema2Class;
use Helmich\Schema2Class\Util\StringUtils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

use Helmich\Schema2Class\Command\GenerateFromRequestTrait;

class GenerateSpecCommand extends Command
{
    use GenerateFromRequestTrait;

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

        $dryRun = (bool)$input->getOption("dry-run");

        $globalOpts = OptionsDefaults::applyDefaults(
            $specification->getOptions() ?? new SpecificationOptions()
        );
        $output->writeln("target PHP version <comment>{$globalOpts->getTargetPHPVersion()}</comment>");

        foreach ($specification->getFiles() as $file) {
            $schemaFile = $file->getInput();

            $opts = Schema2Class::mergeOptions($globalOpts, $file->getOptions());
            $tpv  = GeneratorRequest::normalizeTargetVersion($opts->getTargetPHPVersion());
            $opts = $opts->withTargetPHPVersion($tpv);

            $targetDirectory = $opts->getTargetDirectory() ?? '';
            $targetNamespaceOption = $opts->getTargetNamespace();

            $output->writeln("loading schema from <comment>$schemaFile</comment>");

            $className = $file->getClassName();
            if ($className === null) {
                $basename  = pathinfo($schemaFile, PATHINFO_FILENAME);
                $className = StringUtils::pascalCase($basename);
                $file      = $file->withClassName($className);
            }

            $targetNamespace = $this->inferNamespace(
                $targetDirectory,
                $targetNamespaceOption,
                $output,
            );
            $opts = $opts->withTargetDirectory($targetDirectory)
                         ->withTargetNamespace($targetNamespace);

            $output->writeln("using target namespace <comment>$targetNamespace</comment> in directory <comment>$targetDirectory</comment>");

            $schema = $this->loader->loadSchema($schemaFile);

            $validated = ValidatedSpecificationFilesItem::fromSpecificationFilesItem($file, $opts, $targetNamespace);

            if ($validated->getCleanTargetDirectory()) {
                $this->cleanDirectory($validated->getTargetDirectory(), $output);
            }

            $baseRequest = new GeneratorRequest(
                $schema,
                $validated,
                $opts
            );

            $this->generateFromRequest($baseRequest, $output, $dryRun);
        }

        return 0;
    }

}
