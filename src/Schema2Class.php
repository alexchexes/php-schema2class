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

        $tpv = GeneratorRequest::normalizeTargetVersion($opts->getTargetPHPVersion());

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
                $targetDirectory,
                $file->getTargetNamespace(),
                $output,
            );
            $file = $file->withTargetNamespace($targetNamespace);

            $output->writeln(
                "using target namespace <comment>{$targetNamespace}</comment> in directory <comment>{$targetDirectory}</comment>"
            );

            $schema = $this->loader->loadSchema($schemaFile);

            $validated = ValidatedSpecificationFilesItem::fromSpecificationFilesItem($file, $targetNamespace);

            if ($validated->getCleanTargetDirectory()) {
                $this->cleanDirectory($validated->getTargetDirectory(), $output);
            }

            $baseRequest = new GeneratorRequest(
                $schema,
                $validated,
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
        string $targetDirectory,
        string $className,
        ?string $targetNamespace = null,
        bool $cleanTargetDirectory = false,
        ?SpecificationOptions $options = null,
        ?OutputInterface $output = null,
    ): void {
        $output = $output ?? new NullOutput();
        $options = $options ?? new SpecificationOptions();

        $targetNamespace = $this->inferNamespace(
            $targetDirectory,
            $targetNamespace,
            $output,
        );
        $output->writeln(
            "using target namespace <comment>{$targetNamespace}</comment> in directory <comment>{$targetDirectory}</comment>"
        );

        $tpv = GeneratorRequest::normalizeTargetVersion($options->getTargetPHPVersion());
        
        $options = $options->withTargetPHPVersion($tpv);

        if ($cleanTargetDirectory) {
            $this->cleanDirectory($targetDirectory, $output);
        }

        $spec = new ValidatedSpecificationFilesItem($targetNamespace, $className, $targetDirectory, $cleanTargetDirectory);
        $req  = new GeneratorRequest($schema, $spec, $options);

        $this->generateFromRequest($req, $output, false);
    }
}
