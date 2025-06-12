<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Example\CustomerAddress;
use Helmich\Schema2Class\Loader\SchemaLoader;
use Helmich\Schema2Class\Command\GenerateSpecCommand;
use Helmich\Schema2Class\Generator\Property\IntersectProperty;
use Helmich\Schema2Class\Generator\Property\NestedObjectProperty;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use Helmich\Schema2Class\Writer\DebugWriter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Yaml\Yaml;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

class SchemaToClassTest extends TestCase
{
    protected function setUp(): void
    {
    }

    public static function loadCodeGenerationTestCases(): array
    {
        $testCases   = [];
        $testCaseDir = join(DIRECTORY_SEPARATOR, [__DIR__, "Fixtures"]);

        $dir = opendir($testCaseDir);

        while ($entry = readdir($dir)) {
            if ($entry[0] === ".") {
                continue;
            }

            $schemaFile = join(DIRECTORY_SEPARATOR, [$testCaseDir, $entry, "schema.yaml"]);
            if (!file_exists($schemaFile)) {
                $schemaFile = join(DIRECTORY_SEPARATOR, [$testCaseDir, $entry, "schema.json"]);
            }

            $optionsFile = join(DIRECTORY_SEPARATOR, [$testCaseDir, $entry, "options.yaml"]);
            $outputDir  = join(DIRECTORY_SEPARATOR, [$testCaseDir, $entry, "Output"]);
            $output     = @opendir($outputDir);

            if ($output === false) {
                throw new \Exception("Could not open output directory for test case '{$entry}'");
            }

            $expectedFiles = [];
            $schema        = (new SchemaLoader())->loadSchema($schemaFile);

            $opts = (new SpecificationOptions)
                ->withTargetPHPVersion("8.2")
                ->withInlineAllofReferences(true);
            if (file_exists($optionsFile)) {
                $optsYaml = Yaml::parseFile($optionsFile);
                $opts = SpecificationOptions::buildFromInput($optsYaml);
            }

            while ($outputEntry = readdir($output)) {
                if (substr($outputEntry, -4) !== ".php") {
                    continue;
                }

                $expectedFiles[$outputEntry] = trim(file_get_contents(join(DIRECTORY_SEPARATOR, [$outputDir, $outputEntry])));
            }

            $testCases[$entry] = [$entry, $schema, $expectedFiles, $opts];
        }

        return $testCases;
    }

    #[DataProvider("loadCodeGenerationTestCases")]
    public function testCodeGeneration(string $name, array $schema, array $expectedOutput, SpecificationOptions $opts): void
    {
        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem("Ns\\{$name}", "Foo", __DIR__),
            $opts,
        );

        $definitions = $schema['definitions'] ?? [];
        $definitionsLookup = new DefinitionsReferenceLookup($definitions);

        $req = $req->withReferenceLookup(new class ($definitionsLookup) implements ReferenceLookup {
            public function __construct(private DefinitionsReferenceLookup $lookup) {}

            public function lookupReference(string $reference): ReferencedType
            {
                if ($reference === "#/properties/address") {
                    return new ReferencedTypeClass(CustomerAddress::class);
                }

                $result = $this->lookup->lookupReference($reference);
                if ($result instanceof ReferencedTypeUnknown) {
                    return new ReferencedTypeUnknown();
                }
                return $result;
            }

            public function lookupSchema(string $reference): array
            {
                if ($reference === "#/properties/address") {
                    return [
                        'required' => [
                            'city',
                            'street',
                        ],
                        'properties' => [
                            'city' => [
                                'type' => 'string',
                                'maxLength' => 32,
                            ],
                            'street' => [
                                'type' => 'string',
                            ],
                        ],
                    ];
                }

                return $this->lookup->lookupSchema($reference);
            }
        });

        $output   = new NullOutput();
        $writer   = new DebugWriter($output);
        $factory  = new SchemaToClassFactory();

        $collectAllLocalRefs = static function (array $schema, array $allDefinitions) {
            $needed  = [];
            $queue   = [$schema];
            $visited = [];

            while ($cur = array_pop($queue)) {
                $iter = function ($node) use (&$iter, &$needed, &$queue, &$visited, $allDefinitions) {
                    if (is_array($node)) {
                        foreach ($node as $k => $v) {
                            if ($k === '$ref'
                                && is_string($v)
                                && str_starts_with($v, '#/definitions/')) {
                                $name = substr($v, 14);
                                if (!isset($visited[$name])) {
                                    $visited[$name] = true;
                                    $needed[]       = $name;

                                    if (isset($allDefinitions[$name])) {
                                        $queue[] = $allDefinitions[$name];
                                    }
                                }
                            } elseif (is_array($v)) {
                                $iter($v);
                            }
                        }
                    }
                };
                $iter($cur);
            }

            return $needed;
        };

        $generatedClasses = array_keys($definitions);
        $generatedClasses[] = $req->getTargetClass();

        foreach ($definitions as $defName => $defSchema) {
            $deps        = $collectAllLocalRefs($defSchema, $definitions);
            $trimmedDefs = array_intersect_key($definitions, array_flip($deps));

            $defReq = $req
                ->withClass($defName)
                ->withSchema($defSchema)
                ->withRootDefinitions($trimmedDefs)
                ->withGeneratedClassNames($generatedClasses);

            $factory->build($writer, $output)->schemaToClass($defReq);
        }

        $req = $req
            ->withRootDefinitions($definitions)
            ->withGeneratedClassNames($generatedClasses);

        if (NestedObjectProperty::canHandleSchema($schema)
            || IntersectProperty::canHandleSchema($schema)
            || isset($schema['enum'])
        ) {
            $factory->build($writer, $output)->schemaToClass($req);
        }

        $this->assertCount(
            expectedCount: count($expectedOutput),
            haystack: $writer->getWrittenFiles(),
            message: sprintf(
                'Expected file count [%s] does not match the written file count [%s]',
                implode(', ', array_keys($expectedOutput)),
                implode(', ', array_keys($writer->getWrittenFiles())),
            ),
        );
        foreach ($expectedOutput as $file => $content) {
            $filename      = __DIR__ . '/' . $file;
            $actualContent = $writer->getWrittenFiles()[$filename];

            if (getenv("UPDATE_SNAPSHOTS") === "1") {
                $outputFilename = join(DIRECTORY_SEPARATOR, [__DIR__, "Fixtures", $name, "Output", $file]);
                file_put_contents($outputFilename, $actualContent . "\n");
            } else {
                assertThat($actualContent, equalTo($content));
            }
        }
    }
}
