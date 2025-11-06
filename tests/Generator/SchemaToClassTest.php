<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

use Helmich\Schema2Class\Command\GenerateCommand;
use Helmich\Schema2Class\Example\CustomerAddress;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeInterface;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeClass;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeUnknown;
use Helmich\Schema2Class\Generator\ReferenceLookup\DefinitionsReferenceLookup;
use Helmich\Schema2Class\Generator\ReferenceLookup\ReferenceLookupInterface;
use Helmich\Schema2Class\Generator\SchemaToClassFactory;
use Helmich\Schema2Class\Loader\SchemaLoader;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use Helmich\Schema2Class\Writer\DebugWriter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Yaml\Yaml;

class SchemaToClassTest extends TestCase
{
    protected function setUp(): void
    {
    }

    public function testCliNoEnumsMatchesFixture(): void
    {
        $schemaFile = __DIR__ . '/Fixtures/NoEnums/schema.yaml';
        $expected   = trim(file_get_contents(__DIR__ . '/Fixtures/NoEnums/Output-8.4/MyClass.php'));

        $dir = sys_get_temp_dir() . '/s2c_' . uniqid();
        mkdir($dir);

        $command = new GenerateCommand();

        $tester = new CommandTester($command);
        $tester->execute([
            'schema'                => $schemaFile,
            'target-dir'            => $dir,
            '--target-namespace'    => 'Ns\\NoEnums_8_4',
            '--class'               => 'MyClass',
            '--no-enums'            => true,
        ]);

        $generated = trim(file_get_contents($dir . '/MyClass.php'));
        assertThat($generated, equalTo($expected));

        unlink($dir . '/MyClass.php');
        rmdir($dir);
    }

    public function testCliInfersClassNameFromFilename(): void
    {
        $schemaFile = __DIR__ . '/Fixtures/NoEnums/schema.yaml';

        $dir = sys_get_temp_dir() . '/s2c_' . uniqid();
        mkdir($dir);

        $command = new GenerateCommand();

        $tester = new CommandTester($command);
        $tester->execute([
            'schema' => $schemaFile,
            'target-dir' => $dir,
            '--target-namespace' => 'Ns\\NoEnums',
        ]);

        $file = $dir . '/Schema.php';
        $this->assertFileExists($file);
        $content = file_get_contents($file);
        $this->assertStringContainsString('namespace Ns\\NoEnums;', $content);
        $this->assertStringContainsString('Schema', $content);

        foreach (glob($dir . '/*.php') as $f) {
            unlink($f);
        }
        rmdir($dir);
    }

    public function testSkipsNonObjectDefinitionsWithNotice(): void
    {
        $schemaFile = __DIR__ . '/Fixtures/NonObjectDefs/schema.json';
        $schema = (new SchemaLoader())->loadSchema($schemaFile);

        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns\\NonObjectDefs', null, __DIR__),
            (new SpecificationOptions())->withTargetPHPVersion(GeneratorRequest::DEFAULT_PHP8_VERSION),
        );

        $output = new BufferedOutput();
        $writer = new DebugWriter($output);
        $factory = new SchemaToClassFactory();

        $factory->build($writer, $output)->schemaToClass($req);

        $this->assertStringContainsString('skipping generation of SkippedDef5', $output->fetch());
    }

    public function testGenerateDefinitionsFiltersOutput(): void
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'alpha' => ['$ref' => '#/definitions/Alpha'],
            ],
            'definitions' => [
                'Alpha' => [
                    'type' => 'object',
                    'properties' => [
                        'beta' => ['$ref' => '#/definitions/Beta'],
                    ],
                ],
                'Beta' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                    ],
                ],
                'Gamma' => [
                    'type' => 'object',
                    'properties' => [
                        'label' => ['type' => 'string'],
                    ],
                ],
            ],
        ];

        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns\\Filtered', 'Root', sys_get_temp_dir()),
            (new SpecificationOptions())
                ->withTargetPHPVersion(GeneratorRequest::DEFAULT_PHP8_VERSION)
                ->withGenerateDefinitions(['Alpha'])
        );

        $output = new NullOutput();
        $writer = new DebugWriter($output);
        $factory = new SchemaToClassFactory();

        $factory->build($writer, $output)->schemaToClass($req);

        $writtenFiles = array_map('basename', array_keys($writer->getWrittenFiles()));

        $this->assertContains('Root.php', $writtenFiles);
        $this->assertContains('Alpha.php', $writtenFiles);
        $this->assertContains('Beta.php', $writtenFiles);
        $this->assertNotContains('Gamma.php', $writtenFiles);
    }

    private static function loadTestDefaultsData(): array
    {
        static $testData = null;

        if ($testData) {
            return $testData;
        }

        $schemaFile = __DIR__ . '/Fixtures/MaterializeDefaults/schema.json';
        $expectedFile  = __DIR__ . '/Fixtures/MaterializeDefaults/testDefaults.expected.json';
        $ns = 'Ns\\MaterializeDefaults';
        $className = '_MyClass';

        $schema = (new SchemaLoader())->loadSchema($schemaFile);

        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem($ns, $className, __DIR__),
            (new SpecificationOptions())->withTargetPHPVersion(GeneratorRequest::DEFAULT_PHP8_VERSION),
        );

        $output  = new NullOutput();
        $writer  = new DebugWriter($output);
        $factory = new SchemaToClassFactory();

        $factory->build($writer, $output)->schemaToClass($req);

        // eval code we just generated to load class into memory
        foreach ($writer->getWrittenFiles() as $code) {
            $evalCode = preg_replace('/^<\?php/', '', $code);
            eval($evalCode);
        }

        return $testData = [
            'fqcn' => "{$ns}\\{$className}",
            'expected' => file_get_contents($expectedFile),
        ];
    }

    public function testMaterializeDefaults(): void
    {
        $testData = self::loadTestDefaultsData();
        $fqcn = $testData['fqcn'];
        $expected = $testData['expected'];

        $inputFileMaterialize  = __DIR__ . '/Fixtures/MaterializeDefaults/testDefaultsMaterialize.input.json';
        $inputMaterialize = json_decode(file_get_contents($inputFileMaterialize));

        try {
            $obj1 = $fqcn::fromInput($inputMaterialize, true, true);
        } catch (\Throwable $th) {
            throw new \Exception("Failed to build {$fqcn} from file '$inputFileMaterialize'", 0, $th);
        }

        $this->assertSame(
            json_decode($expected, true),
            $obj1->toArray(),
            "Array returned from {$fqcn}->toArray() built from '$inputFileMaterialize' doesn't match expected JSON"
        );

        $this->assertEquals(
            json_decode($expected),
            $obj1->toStdClass(),
            "Object returned from {$fqcn}->toStdClass() built from '$inputFileMaterialize' doesn't match expected JSON"
        );
    }

    public function testIncludeDefaults(): void
    {
        $testData = self::loadTestDefaultsData();
        $fqcn = $testData['fqcn'];
        $expected = $testData['expected'];
        
        $inputFileInclude  = __DIR__ . '/Fixtures/MaterializeDefaults/testDefaultsInclude.input.json';
        $inputInclude = json_decode(file_get_contents($inputFileInclude));

        try {
            $obj2 = $fqcn::fromInput($inputInclude);
        } catch (\Throwable $th) {
            throw new \Exception("Failed to build {$fqcn} from file '$inputFileInclude'", 0, $th);
        }

        $this->assertEquals(
            json_decode($expected, true),
            $obj2->toArray(true),
            "Array returned from {$fqcn}->toArray() built from '$inputFileInclude' doesn't match expected JSON"
        );

        $this->assertEquals(
            json_decode($expected),
            $obj2->toStdClass(true),
            "Object returned from {$fqcn}->toStdClass() built from '$inputFileInclude' doesn't match expected JSON"
        );
    }
}
