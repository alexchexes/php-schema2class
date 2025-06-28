<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Command;

use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\GenerationRunner;
use Helmich\Schema2Class\Loader\LoadingException;
use Helmich\Schema2Class\Spec\Specification;
use Helmich\Schema2Class\Spec\SpecificationFilesItem;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\OptionsDefaults;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{

    private GenerationRunner $runner;

    public function __construct(GenerationRunner $runner)
    {
        parent::__construct();

        $this->runner = $runner;
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

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int
     *
     * @throws LoadingException
     * @throws GeneratorException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $schemaFile */
        $schemaFile = $input->getArgument("schema");
        /** @var string $targetDirectory */
        $targetDirectory = $input->getArgument("target-dir");
        /** @var string $targetNamespace */
        $targetNamespace = $input->getOption("target-namespace");
        /** @var string|null $class */
        $class = $input->getOption("class");
        /** @var string|null $targetPHPVersion */
        $targetPHPVersion = $input->getOption("target-php");

        $opts = new SpecificationOptions();
        $opts = $opts->withTargetDirectory($targetDirectory);
        if ($targetNamespace) {
            $opts = $opts->withTargetNamespace($targetNamespace);
        }
        $opts = $opts->withCleanTargetDirectory((bool)$input->getOption('clean-dir'));

        if (!$targetPHPVersion) {
            $targetPHPVersion = GeneratorRequest::DEFAULT_PHP8_VERSION;
        }
        $opts = $opts->withTargetPHPVersion($targetPHPVersion);

        if ($input->getOption("disable-strict-types")) {
            $opts = $opts->withDisableStrictTypes(true);
        }
        if ($input->getOption("treat-default-as-optional")) {
            $opts = $opts->withTreatValuesWithDefaultAsOptional(true);
        }
        if ($input->getOption("inline-allof")) {
            $opts = $opts->withInlineAllofReferences(true);
        }
        if ($expr = $input->getOption("validator-expr")) {
            $opts = $opts->withNewValidatorClassExpr((string)$expr);
        }
        if ($input->getOption("preserve-property-names")) {
            $opts = $opts->withPreservePropertyNames(true);
        }
        if ($input->getOption("no-getters")) {
            $opts = $opts->withNoGetters(true);
        }
        if ($input->getOption("no-setters")) {
            $opts = $opts->withNoSetters(true);
        }
        if ($input->getOption("no-schema-descriptions")) {
            $opts = $opts->withNoDescriptionsInSchema(true);
        }
        if ($input->getOption("single-line-schema")) {
            $opts = $opts->withSingleLineSchema(true);
        }
        if ($input->getOption('no-enums')) {
            $opts = $opts->withNoEnums(true);
        }

        $opts = OptionsDefaults::applyDefaults($opts);

        $file = new SpecificationFilesItem($schemaFile);
        if ($class !== null) {
            $file = $file->withClassName($class);
        }

        $spec = (new Specification([$file]))->withOptions($opts);

        $this->runner->generateFromSpecification($spec, $output, (bool)$input->getOption('dry-run'));

        return 0;
    }
}
