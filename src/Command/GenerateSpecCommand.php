<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Command;

use Helmich\Schema2Class\Schema2Class;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class GenerateSpecCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName("generate:fromspec");
        $this->setDescription("Generate PHP classes from a Schema2Class specification file");

        $this->addArgument("specfile", InputArgument::OPTIONAL, "Specification file to read (.yml/.yaml or .json)");
        $this->addOption("dry-run", null, InputOption::VALUE_NONE, "Print output to console instead of writing to files");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string */
        $specFile = $input->getArgument("specfile") ?: getcwd() . "/.s2c.yaml";
        $dryRun = (bool) $input->getOption('dry-run');
        (new Schema2Class())->generateFromSpec($specFile, $output, $dryRun);
        return 0;
    }
}
