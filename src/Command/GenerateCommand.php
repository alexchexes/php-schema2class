<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Command;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Schema2Class;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName("generate:fromschema");
        $this->setDescription("Generate PHP classes from a JSON schema");

        $this->addArgument("schema", InputArgument::REQUIRED, "JSON schema file to read");
        $this->addArgument("target-dir", InputArgument::REQUIRED, "Target directory");
        
        $this->addOption("target-namespace", null, InputOption::VALUE_REQUIRED, "Target namespace (will try to determine automatically from composer.json if omitted)");
        $this->addOption("target-php", "p", InputOption::VALUE_REQUIRED, "Target PHP version");
        $this->addOption("dry-run", null, InputOption::VALUE_NONE, "Print output to console instead of writing to files");
        $this->addOption("class", "c", InputOption::VALUE_REQUIRED, "Target class name");
        $this->addOption("disable-strict-types", null, InputOption::VALUE_NONE, "Do not emit strict_types declaration");
        $this->addOption("treat-default-as-optional", null, InputOption::VALUE_NONE, "Treat properties with defaults as optional");
        $this->addOption("inline-allof", null, InputOption::VALUE_NONE, "Inline allOf references");
        $this->addOption("validator-expr", null, InputOption::VALUE_REQUIRED, "Expression used to create validator instance");
        $this->addOption("preserve-property-names", null, InputOption::VALUE_NONE, "Do not convert property names to camelCase");
        $this->addOption("no-getters", null, InputOption::VALUE_NONE, "Do not generate getter methods");
        $this->addOption("no-setters", null, InputOption::VALUE_NONE, "Do not generate withX()/withoutX() methods");
        $this->addOption("no-schema-descriptions", null, InputOption::VALUE_NONE, "Omit description fields from schema property");
        $this->addOption("single-line-schema", null, InputOption::VALUE_NONE, "Store schema property as single line");
        $this->addOption('no-enums', null, InputOption::VALUE_NONE, 'Disable PHP enum generation');
        $this->addOption('clean-dir', null, InputOption::VALUE_NONE, 'Remove all files in target directory before writing');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $options = [
            'targetDirectory'                   => $input->getArgument('target-dir'),
            'targetNamespace'                   => $input->getOption('target-namespace'),
            'targetPHPVersion'                  => $input->getOption('target-php'),
            'cleanTargetDirectory'              => $input->getOption('clean-dir'),
            'disableStrictTypes'                => $input->getOption('disable-strict-types'),
            'treatValuesWithDefaultAsOptional'  => $input->getOption('treat-default-as-optional'),
            'inlineAllofReferences'             => $input->getOption('inline-allof'),
            'newValidatorClassExpr'             => $input->getOption('validator-expr'),
            'preservePropertyNames'             => $input->getOption('preserve-property-names'),
            'noGetters'                         => $input->getOption('no-getters'),
            'noSetters'                         => $input->getOption('no-setters'),
            'noDescriptionsInSchema'            => $input->getOption('no-schema-descriptions'),
            'singleLineSchema'                  => $input->getOption('single-line-schema'),
            'noEnums'                           => $input->getOption('no-enums'),
        ];

        $options = array_filter($options);

        $fileOptions = [
            'input'     => $input->getArgument("schema"),
            'className' => $input->getOption("class"),
        ];

        $fileOptions = array_filter($fileOptions);

        $specArray = [
            'options' => $options,
            'files'   => [ $fileOptions ],
        ];

        (new Schema2Class())->generateFromSpec(
            $specArray,
            $output,
            (bool) $input->getOption('dry-run'),
        );

        return 0;
    }
}
