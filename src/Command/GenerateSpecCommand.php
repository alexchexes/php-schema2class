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
                $className = StringUtils::pascalCase($basename);
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


            // save all classes we generate so we can remove leading slashes before them
            $generatedClasses = array_keys($definitions);
            $generatedClasses[] = $file->getClassName();

            // 1) Emit one class per definition
            foreach ($definitions as $defName => $defSchema) {
                // collect *transitive* dependencies of this definition
                $deps = collect_all_local_refs($defSchema, $definitions);

                // build a trimmed map containing exactly those dependencies
                $trimmedDefs = array_intersect_key($definitions, array_flip($deps));

                $reqDef = $baseRequest
                    ->withClass($defName)
                    ->withSchema($defSchema)
                    ->withRootDefinitions($trimmedDefs)
                    ->withGeneratedClassNames($generatedClasses);

                $this->s2c->build($writer, $output)->schemaToClass($reqDef);
            }



            // 2) Then emit the "main" class only if the schema is an object
            if (NestedObjectProperty::canHandleSchema($schema)) {
                $mainRequest = $baseRequest
                    ->withClass($file->getClassName())
                    ->withRootDefinitions($schema['definitions'] ?? [])
                    ->withGeneratedClassNames($generatedClasses);

                $this->s2c->build($writer, $output)
                    ->schemaToClass($mainRequest);
            }

            // === end definitions loop ===
        }

        return 0;
    }

}

/**
 * Recursively collect *all* local #/definitions/... dependencies of $schema.
 *
 * @param array<string,mixed> $schema          object/definition currently emitted
 * @param array<string,mixed> $allDefinitions  full root‑level "definitions" map
 * @return string[]                            list of definition names actually needed
 */
function collect_all_local_refs(array $schema, array $allDefinitions): array
{
    $needed   = [];            // discovered definition names
    $queue    = [$schema];     // schemas still to scan
    $visited  = [];            // names we have already expanded

    while ($cur = array_pop($queue)) {
        // walk recursively through this schema node
        $iter = function ($node) use (&$iter, &$needed, &$queue, &$visited, $allDefinitions) {
            if (is_array($node)) {
                foreach ($node as $k => $v) {
                    if ($k === '$ref'
                        && is_string($v)
                        && str_starts_with($v, '#/definitions/')) {

                        $name = substr($v, 14);        // strip "#/definitions/"
                        if (!isset($visited[$name])) {
                            $visited[$name] = true;
                            $needed[] = $name;

                            // enqueue the referenced definition’s own schema
                            if (isset($allDefinitions[$name])) {
                                $queue[] = $allDefinitions[$name];
                            }
                        }
                    } elseif (is_array($v)) {
                        $iter($v);                     // drill down
                    }
                }
            }
        };
        $iter($cur);
    }

    return $needed;   // de‑duplicated by $visited already
}
