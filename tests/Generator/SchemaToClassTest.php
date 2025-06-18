<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Example\CustomerAddress;
use Helmich\Schema2Class\Loader\SchemaLoader;
use Helmich\Schema2Class\Command\GenerateCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Helmich\Schema2Class\Generator\NamespaceInferrer;
use Helmich\Schema2Class\Generator\SchemaToClassFactory;
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

            $expectedFiles = [];
            $schema        = (new SchemaLoader())->loadSchema($schemaFile);

            $opts = (new SpecificationOptions)
                ->withTargetPHPVersion("8.2")
                ->withInlineAllofReferences(true);
            if (file_exists($optionsFile)) {
                $optsYaml = Yaml::parseFile($optionsFile);
                $opts = SpecificationOptions::buildFromInput($optsYaml);
            }

            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($outputDir));
            foreach ($iterator as $fileInfo) {
                if (!$fileInfo->isFile() || $fileInfo->getExtension() !== 'php') {
                    continue;
                }

                $relativePath = substr($fileInfo->getPathname(), strlen($outputDir) + 1);
                $relativePath = str_replace('\\', '/', $relativePath);
                $expectedFiles[$relativePath] = trim(file_get_contents($fileInfo->getPathname()));
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

        $factory->build($writer, $output)->schemaToClass($req);

        $writtenFiles = $writer->getWrittenFiles();

        if (getenv('UPDATE_SNAPSHOTS') !== '1') {
            $this->assertCount(
                expectedCount: count($expectedOutput),
                haystack: $writtenFiles,
                message: sprintf(
                    'Expected file count [%s] does not match the written file count [%s]',
                    implode(', ', array_keys($expectedOutput)),
                    implode(', ', array_keys($writtenFiles)),
                ),
            );

            foreach ($expectedOutput as $file => $content) {
                $filename = __DIR__ . '/' . str_replace('\\', '/', $file);
                $actualContent = $writtenFiles[$filename];
                assertThat($actualContent, equalTo($content));
            }
        } else {
            $outputDir = join(DIRECTORY_SEPARATOR, [__DIR__, 'Fixtures', $name, 'Output']);
            if (is_dir($outputDir)) {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($outputDir, \FilesystemIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::CHILD_FIRST,
                );
                foreach ($iterator as $fileInfo) {
                    if ($fileInfo->isFile()) {
                        unlink($fileInfo->getPathname());
                    } else {
                        @rmdir($fileInfo->getPathname());
                    }
                }
            }

            foreach ($writtenFiles as $filename => $content) {
                $relative = substr($filename, strlen(__DIR__) + 1);
                $relative = str_replace('\\', '/', $relative);
                $outputFilename = join(DIRECTORY_SEPARATOR, [__DIR__, 'Fixtures', $name, 'Output', $relative]);
                if (!is_dir(dirname($outputFilename))) {
                    mkdir(dirname($outputFilename), 0777, true);
                }
                file_put_contents($outputFilename, $content . "\n");
            }

            $this->addToAssertionCount(1);
        }
    }

    public function testCliNoEnumsMatchesFixture(): void
    {
        $schemaFile = __DIR__ . '/Fixtures/NoEnums/schema.yaml';
        $expected   = trim(file_get_contents(__DIR__ . '/Fixtures/NoEnums/Output/Foo.php'));

        $dir = sys_get_temp_dir() . '/s2c_' . uniqid();
        mkdir($dir);

        $command = new GenerateCommand(
            new SchemaLoader(),
            new NamespaceInferrer(),
            new SchemaToClassFactory(),
        );

        $tester = new CommandTester($command);
        $tester->execute([
            'schema' => $schemaFile,
            'target-dir' => $dir,
            '--target-namespace' => 'Ns\\NoEnums',
            '--class' => 'Foo',
            '--no-enums' => true,
        ]);

        $generated = trim(file_get_contents($dir . '/Foo.php'));
        assertThat($generated, equalTo($expected));

        unlink($dir . '/Foo.php');
        rmdir($dir);
    }
}
