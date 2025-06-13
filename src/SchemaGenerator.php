<?php
declare(strict_types=1);

namespace Helmich\Schema2Class;

use Helmich\Schema2Class\Command\GenerateFromRequestTrait;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\NamespaceInferrer;
use Helmich\Schema2Class\Loader\SchemaLoader;
use Helmich\Schema2Class\Generator\SchemaToClassFactory;
use Helmich\Schema2Class\Spec\Specification;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use Helmich\Schema2Class\Util\StringUtils;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Convenience API for generating classes without using the CLI commands.
 *
 * This class can load a StructBuilder specification from a YAML file or from
 * an associative array and generate all classes described therein.
 */
class SchemaGenerator
{
    use GenerateFromRequestTrait;

    private SchemaLoader $loader;
    private NamespaceInferrer $namespaceInferrer;
    private SchemaToClassFactory $s2c;

    public function __construct(
        ?SchemaLoader $loader = null,
        ?NamespaceInferrer $namespaceInferrer = null,
        ?SchemaToClassFactory $factory = null
    ) {
        $this->loader = $loader ?? new SchemaLoader();
        $this->namespaceInferrer = $namespaceInferrer ?? new NamespaceInferrer();
        $this->s2c = $factory ?? new SchemaToClassFactory();
    }

    /**
     * Generate classes from a specification file.
     */
    public function generateFromSpecFile(string $specFile, ?OutputInterface $output = null): void
    {
        $output = $output ?? new NullOutput();
        $config = Yaml::parse(file_get_contents($specFile));
        $this->generateFromArray($config, $output);
    }

    /**
     * Generate classes from a specification provided as an associative array.
     * The array structure is the same as used in .s2c.yaml files.
     */
    public function generateFromArray(array $config, ?OutputInterface $output = null): void
    {
        $output = $output ?? new NullOutput();

        $specification = Specification::buildFromInput($config);
        $opts = $specification->getOptions() ?? new SpecificationOptions();
        if ($v = $specification->getTargetPHPVersion()) {
            $opts = $opts->withTargetPHPVersion($v);
        }

        $tpv = $opts->getTargetPHPVersion();
        if (is_int($tpv)) {
            $tpv = $tpv === 5 ? '5.6.0' : '7.4.0';
        }
        $opts = $opts->withTargetPHPVersion($tpv);

        foreach ($specification->getFiles() as $file) {
            $schemaFile = $file->getInput();
            $targetDirectory = $file->getTargetDirectory();

            $output->writeln("loading schema from <comment>{$schemaFile}</comment>");

            $className = $file->getClassName();
            if ($className === null) {
                $basename = pathinfo($schemaFile, PATHINFO_FILENAME);
                $className = StringUtils::pascalCase($basename);
                $file = $file->withClassName($className);
            }

            $targetNamespace = $this->inferNamespace(
                $output,
                $file->getTargetNamespace(),
                $targetDirectory,
                $className
            );
            $file = $file->withTargetNamespace($targetNamespace);

            $output->writeln(
                "using target namespace <comment>{$targetNamespace}</comment> in directory <comment>{$targetDirectory}</comment>"
            );

            $schema = $this->loader->loadSchema($schemaFile);

            $baseRequest = new GeneratorRequest(
                $schema,
                ValidatedSpecificationFilesItem::fromSpecificationFilesItem($file, $targetNamespace),
                $opts
            );

            $this->generateFromRequest($baseRequest, $output, false);
        }
    }
}
