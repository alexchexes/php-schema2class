<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

use Composer\Semver\Semver;
use Helmich\Schema2Class\Example\CustomerAddress;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeClass;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeInterface;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeUnknown;
use Helmich\Schema2Class\Generator\ReferenceLookup\DefinitionsReferenceLookup;
use Helmich\Schema2Class\Generator\ReferenceLookup\ReferenceLookupInterface;
use Helmich\Schema2Class\Generator\SchemaToClassFactory;
use Helmich\Schema2Class\Loader\SchemaLoader;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use Helmich\Schema2Class\Writer\DebugWriter;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Yaml\Yaml;

class ClassGenerationTest extends TestCase
{
    protected function setUp(): void
    {
    }

    private static function removeDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST,
        );

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDir()) {
                @rmdir($fileInfo->getPathname());
            } else {
                @unlink($fileInfo->getPathname());
            }
        }

        @rmdir($dir);
    }

    private static function tryFile(string $baseName, string $fixtureDir): ?string
    {
        $extensions = ['yaml', 'yml', 'json'];
        $foundPath = null;
        foreach ($extensions as $ext) {
            $path = join(DIRECTORY_SEPARATOR, [$fixtureDir, "{$baseName}.{$ext}"]);
            if (file_exists($path)) {
                if ($foundPath) {
                    // found other with different extension
                    throw new \Exception("Found more than one '{$baseName}' file in '{$fixtureDir}'");
                }
                $foundPath = $path;
            }
        }
        return $foundPath;
    }

    public static function loadClassGenerationTestCases(): array
    {
        $testCases   = [];
        $testCaseDir = join(DIRECTORY_SEPARATOR, [__DIR__, 'Fixtures']);

        $dir = opendir($testCaseDir);

        while ($fixtureName = readdir($dir)) {
            if ($fixtureName[0] === '.') {
                continue;
            }

            $fixtureDir = join(DIRECTORY_SEPARATOR, [$testCaseDir, $fixtureName]);

            $schemaFile = self::tryFile('schema', $fixtureDir);

            if (!$schemaFile) {
                trigger_error("Skipping dir without schema file: $fixtureDir", E_USER_WARNING);
                continue;
            }

            $optionsFile  = self::tryFile('options', $fixtureDir);
            $versionsFile = self::tryFile('versions', $fixtureDir);
            
            $inputDir     = join(DIRECTORY_SEPARATOR, [$fixtureDir, 'Input']);

            $schema     = (new SchemaLoader())->loadSchema($schemaFile);
            $inputFiles = [];

            $opts = (new SpecificationOptions)
                ->withTargetPHPVersion(GeneratorRequest::DEFAULT_PHP8_VERSION)
                ->withInlineAllofReferences(true);

            $optsYaml = [];
            if ($optionsFile) {
                $optsYaml = Yaml::parseFile($optionsFile);
                $opts = SpecificationOptions::fromInput($optsYaml);
            }

            if (!isset($optsYaml['inlineAllofReferences'])) {
                $opts = $opts->withInlineAllofReferences(true);
            }

            if (is_dir($inputDir)) {
                foreach (scandir($inputDir) as $in) {
                    if ($in[0] === '.') {
                        continue;
                    }
                    $path = $inputDir . DIRECTORY_SEPARATOR . $in;

                    if (is_dir($path)) {
                        $cls = $in;
                        foreach (scandir($path) as $file) {
                            if ($file[0] === '.') {
                                continue;
                            }
                            $ext = pathinfo($file, PATHINFO_EXTENSION);
                            $inputName = pathinfo($file, PATHINFO_FILENAME);
                            $filePath = $path . DIRECTORY_SEPARATOR . $file;
                            if (in_array($ext, ['json', 'yaml', 'yml'], true)) {
                                if ($ext === 'json') {
                                    $inputFiles[$cls][$inputName] = json_decode(file_get_contents($filePath));
                                } else {
                                    $inputFiles[$cls][$inputName] = Yaml::parseFile($filePath);
                                }
                            }
                        }
                    } else {
                        $ext = pathinfo($in, PATHINFO_EXTENSION);
                        $cls = pathinfo($in, PATHINFO_FILENAME);
                        if (in_array($ext, ['json', 'yaml', 'yml'], true)) {
                            if ($ext === 'json') {
                                $inputFiles[$cls][$cls] = json_decode(file_get_contents($path));
                            } else {
                                $inputFiles[$cls][$cls] = Yaml::parseFile($path);
                            }
                        }
                    }
                }
            }

            $versions = [];

            if (getenv('UPDATE_SNAPSHOTS') === '1') {
                if ($opts->getTargetPHPVersion() !== null && $opts->getTargetPHPVersion() !== GeneratorRequest::DEFAULT_PHP8_VERSION) {
                    $versions = [GeneratorRequest::normalizeTargetVersion($opts->getTargetPHPVersion())];
                } elseif ($versionsFile) {
                    $versions = Yaml::parseFile($versionsFile);
                } else {
                    $versions = [
                        GeneratorRequest::DEFAULT_PHP5_VERSION,
                        GeneratorRequest::DEFAULT_PHP7_VERSION,
                        GeneratorRequest::DEFAULT_PHP8_VERSION
                    ];
                }

                foreach (scandir($fixtureDir) as $dirEntry) {
                    if (preg_match('/^Output-(.+)$/', $dirEntry, $m) && !in_array($m[1], $versions, true)) {
                        self::removeDir($fixtureDir . DIRECTORY_SEPARATOR . $dirEntry);
                    }
                }
            } else {
                $expectedVersions = [];
                if ($versionsFile) {
                    $expectedVersions = array_map('strval', Yaml::parseFile($versionsFile));
                }

                foreach (scandir($fixtureDir) as $dirEntry) {
                    if (preg_match('/^Output-(.+)$/', $dirEntry, $m)) {
                        $versions[] = $m[1];
                    }
                }

                if ($expectedVersions) {
                    $unexpected = array_diff($versions, $expectedVersions);
                    $missing    = array_diff($expectedVersions, $versions);
                    Assert::assertEmpty(
                        $unexpected,
                        sprintf('Unexpected output for PHP versions [%s] in fixture %s', implode(', ', $unexpected), $fixtureName)
                    );
                    Assert::assertEmpty(
                        $missing,
                        sprintf('Missing output for PHP versions [%s] in fixture %s', implode(', ', $missing), $fixtureName)
                    );
                }

                if (!$versions) {
                    $v = $opts->getTargetPHPVersion() ?? GeneratorRequest::DEFAULT_PHP8_VERSION;
                    $versions[] = GeneratorRequest::normalizeTargetVersion($v);
                }
            }

            foreach ($versions as $version) {
                $dirName = 'Output-' . $version;
                $outputDir = join(DIRECTORY_SEPARATOR, [$fixtureDir, $dirName]);

                if (!is_dir($outputDir) && getenv('UPDATE_SNAPSHOTS') === '1') {
                    mkdir($outputDir, 0777, true);
                }

                if (is_dir($outputDir)) {
                    $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($outputDir));
                } else {
                    $iterator = new \ArrayIterator([]);
                }

                $expectedFiles = [];
                foreach ($iterator as $fileInfo) {
                    if (!$fileInfo->isFile() || $fileInfo->getExtension() !== 'php') {
                        continue;
                    }
                    $relativePath = substr($fileInfo->getPathname(), strlen($outputDir) + 1);
                    $relativePath = str_replace('\\', '/', $relativePath);
                    $expectedFiles[$relativePath] = trim(file_get_contents($fileInfo->getPathname()));
                }

                $optsData = $optsYaml;
                if (!isset($optsData['inlineAllofReferences'])) {
                    $optsData['inlineAllofReferences'] = true;
                }
                $optsData['targetPHPVersion'] = $version;
                $specOpts = SpecificationOptions::fromInput($optsData);

                $nsName = $fixtureName . '_' . str_replace('.', '_', $version);
                $testCases[$fixtureName . '-' . $version] = [
                    $fixtureName,
                    $nsName,
                    $schema,
                    $expectedFiles,
                    $specOpts,
                    $inputFiles,
                    $version,
                ];
            }
        }

        return $testCases;
    }

    #[DataProvider("loadClassGenerationTestCases")]
    public function testClassGeneration(
        string $fixtureName,
        string $nsName,
        array $schema,
        array $expectedFiles,
        SpecificationOptions $specOpts,
        array $inputFiles,
        string $version,
    ): void
    {
        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem("Ns\\{$nsName}", "MyClass", __DIR__),
            $specOpts,
        );

        $definitions = $schema['definitions'] ?? [];
        $definitionsLookup = new DefinitionsReferenceLookup($definitions);

        $req = $req->withReferenceLookup(
            new class ($definitionsLookup) implements ReferenceLookupInterface {
                public function __construct(private DefinitionsReferenceLookup $lookup) {}

                public function lookupReference(string $ref, GeneratorRequest $currentRequest): ReferencedTypeInterface
                {
                    if ($ref === "#/properties/address") {
                        return new ReferencedTypeClass(CustomerAddress::class, $currentRequest);
                    }

                    $result = $this->lookup->lookupReference($ref, $currentRequest);
                    if ($result instanceof ReferencedTypeUnknown) {
                        return new ReferencedTypeUnknown($currentRequest);
                    }
                    return $result;
                }

                public function lookupSchema(string $ref): array
                {
                    if ($ref === "#/properties/address") {
                        return [
                            'required' => ['city', 'street'],
                            'properties' => [
                                'city' => ['type' => 'string', 'maxLength' => 32],
                                'street' => ['type' => 'string'],
                            ],
                        ];
                    }

                    return $this->lookup->lookupSchema($ref);
                }
            }
        );

        $output   = new NullOutput();
        $writer   = new DebugWriter($output);
        $factory  = new SchemaToClassFactory();

        $factory->build($writer, $output)->schemaToClass($req);

        $writtenFiles = $writer->getWrittenFiles();

        if (getenv('UPDATE_SNAPSHOTS') !== '1') {
            $this->assertCount(
                expectedCount: count($expectedFiles),
                haystack: $writtenFiles,
                message: sprintf(
                    'Expected file count [%s] does not match the written file count [%s]',
                    implode(', ', array_keys($expectedFiles)),
                    implode(', ', array_keys($writtenFiles)),
                ),
            );

            foreach ($expectedFiles as $file => $content) {
                $filename = __DIR__ . '/' . str_replace('\\', '/', $file);
                $actualContent = $writtenFiles[$filename];
                assertThat($actualContent, equalTo($content));
            }
        } else {
            $dirName = 'Output-' . $version;
            $outputDir = join(DIRECTORY_SEPARATOR, [__DIR__, 'Fixtures', $fixtureName, $dirName]);
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
                $dirName = 'Output-' . $version;
                $outputFilename = join(DIRECTORY_SEPARATOR, [__DIR__, 'Fixtures', $fixtureName, $dirName, $relative]);
                if (!is_dir(dirname($outputFilename))) {
                    mkdir(dirname($outputFilename), 0777, true);
                }
                file_put_contents($outputFilename, $content . "\n");
            }

            $this->addToAssertionCount(1);
        }

        if (getenv('SKIP_EVAL') !== '1') {
            // load classes in memory by evaluating the generated code
            foreach ($writtenFiles as $code) {
                $evalCode = preg_replace('/^<\?php/', '', $code);

                if (Semver::satisfies($version, '<7.0')) {
                    set_error_handler(static fn() => true, E_DEPRECATED);
                    eval($evalCode);
                    restore_error_handler();
                } else {
                    eval($evalCode);
                }
            }

            foreach ($inputFiles as $class => $classInputs) {
                $fqcn = "Ns\\{$nsName}\\{$class}";

                foreach ($classInputs as $inputName => $input) {
                    try {
                        $obj = $fqcn::fromInput($input);
                    } catch (\Throwable $th) {
                        throw new \Exception("Failed to build {$fqcn} from input '{$inputName}'", 0, $th);
                    }

                    $this->assertInstanceOf($fqcn, $obj);
                    $expectedArray = json_decode(json_encode($input), true);
                    $actualArray   = $obj->toArray();
                    ksort($expectedArray);
                    ksort($actualArray);
                    $this->assertSame(
                        $expectedArray,
                        $actualArray,
                        "Array returned from {$fqcn}->toArray() doesn't match input array from file '{$inputName}'."
                    );

                    $expectedObject = json_decode(json_encode($input));
                    $actualObject   = $obj->toStdClass();

                    $this->assertEquals(
                        $expectedObject,
                        $actualObject,
                        "Object returned from {$fqcn}->toStdClass() doesn't match input from file '{$inputName}'."
                    );
                    try {
                        $fqcn::fromInput($actualObject);
                    } catch (\Throwable $th) {
                        throw new \Exception("Failed to build {$fqcn} from input '{$inputName}' after the round trip", 0, $th);
                    }
                }
            }
        }
    }
}
