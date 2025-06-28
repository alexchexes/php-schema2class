<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Command;

use Helmich\Schema2Class\Generator\GenerationRunner;
use Helmich\Schema2Class\Loader\LoadingException;
use Helmich\Schema2Class\Spec\Specification;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\OptionsDefaults;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;


class GenerateSpecCommand extends Command
{
    private GenerationRunner $runner;

    public function __construct(GenerationRunner $runner)
    {
        parent::__construct();

        $this->runner = $runner;
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

        $this->runner->generateFromSpecification($specification, $output, $dryRun);

        return 0;
    }

}
