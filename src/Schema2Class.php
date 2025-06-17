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
class Schema2Class
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
     * Generate classes from a config provided either as a specification file path,
     * as an associative array or as an instance of `Schema2Class\Spec\Specification` object.
     */
    public function generateFromSpec(string|array|Specification $config, ?OutputInterface $output = null): void
    {
        $output = $output ?? new NullOutput();

        if (!($config instanceof Specification)) {
            if (is_string($config)) {
                $config = Yaml::parse(file_get_contents($config));
            }
            $config = Specification::buildFromInput($config);
        }

        $opts = $config->getOptions() ?? new SpecificationOptions();

        if ($v = $config->getTargetPHPVersion()) {
            $opts = $opts->withTargetPHPVersion($v);
        }

        $tpv = $opts->getTargetPHPVersion();
        if (is_int($tpv)) {
            $tpv = $tpv === 5 ? '5.6.0' : '7.4.0';
        }
        $opts = $opts->withTargetPHPVersion($tpv);

        foreach ($config->getFiles() as $file) {
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

    /**
     * Generate classes from a JSON schema that is already parsed into an array.
     *
     * This bypasses reading the schema from disk and can be useful if the
     * schema was retrieved from another source, e.g. a database or network.
     */
    public function generateFromSchema(
        array $schema,
        string $className,
        string $targetDirectory,
        ?string $targetNamespace = null,
        ?SpecificationOptions $options = null,
        ?OutputInterface $output = null
    ): void {
        $output = $output ?? new NullOutput();
        $options = $options ?? new SpecificationOptions();

        $targetNamespace = $this->inferNamespace($output, $targetNamespace, $targetDirectory, $className);
        $output->writeln(
            "using target namespace <comment>{$targetNamespace}</comment> in directory <comment>{$targetDirectory}</comment>"
        );

        $tpv = $options->getTargetPHPVersion();
        if (is_int($tpv)) {
            $tpv = $tpv === 5 ? '5.6.0' : '7.4.0';
        }
        $options = $options->withTargetPHPVersion($tpv);

        $spec = new ValidatedSpecificationFilesItem($targetNamespace, $className, $targetDirectory);
        $req  = new GeneratorRequest($schema, $spec, $options);

        $this->generateFromRequest($req, $output, false);
    }
}
