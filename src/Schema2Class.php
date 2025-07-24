<?php
declare(strict_types=1);

namespace Helmich\Schema2Class;

use Helmich\Schema2Class\Generator\GenerationRunner;
use Helmich\Schema2Class\Generator\NamespaceInferrer;
use Helmich\Schema2Class\Loader\SchemaLoader;
use Helmich\Schema2Class\Generator\SchemaToClassFactory;
use Helmich\Schema2Class\Loader\LoadingException;
use Helmich\Schema2Class\Spec\Specification;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Convenience API for generating classes without using the CLI commands.
 *
 * This class can load a Schema2Class specification from a YAML or JSON file or from
 * an associative array and generate all classes described therein.
 */
class Schema2Class
{
    private GenerationRunner $runner;

    public function __construct(
        ?SchemaLoader $loader = null,
        ?NamespaceInferrer $namespaceInferrer = null,
        ?SchemaToClassFactory $factory = null
    ) {
        $loader = $loader ?? new SchemaLoader();
        $namespaceInferrer = $namespaceInferrer ?? new NamespaceInferrer();
        $factory = $factory ?? new SchemaToClassFactory();

        $this->runner = new GenerationRunner($loader, $namespaceInferrer, $factory);
    }

    /**
     * Generate classes from a config provided either as a specification file path,
     * as an associative array or as an instance of `Schema2Class\Spec\Specification` object.
     */
    public function generateFromSpec(string|array|Specification $config, ?OutputInterface $output = null, bool $dryRun = false): void
    {
        $output = $output ?? new NullOutput();

        if (!($config instanceof Specification)) {
            if (is_string($config)) {
                if (!file_exists($config)) {
                    throw new LoadingException($config, "specification file not found");
                }
                $contents = file_get_contents($config);
                if ($contents === false) {
                    throw new LoadingException($config, "failed to read file contents");
                }
                $config = Yaml::parse($contents);
            }
            $config = Specification::buildFromInput($config);
        }

        $this->runner->generateFromSpecification($config, $output, $dryRun);
    }

    /**
     * Generate classes from a JSON schema that is already parsed into an array.
     *
     * This bypasses reading the schema from disk and can be useful if the
     * schema was retrieved from another source, e.g. a database or network.
     */
    public function generateFromSchema(
        array $schema,
        ?string $className = null,
        array $options = [],
        bool $dryRun = false,
        ?OutputInterface $output = null,
    ): void {
        $output = $output ?? new NullOutput();
        $options = array_filter($options);

        $fileOptions = [
            'input'     => $schema,
            'className' => $className,
        ];
        $fileOptions = array_filter($fileOptions);

        $specArray = [
            'options' => $options,
            'files'   => [ $fileOptions ],
        ];

        $this->generateFromSpec($specArray, $output, $dryRun);
    }
}
