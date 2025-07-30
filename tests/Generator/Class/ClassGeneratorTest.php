<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Loader\SchemaLoader;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use Helmich\Schema2Class\Writer\DebugWriter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Yaml\Yaml;

class ClassGeneratorTest extends TestCase
{
    public function testClassContainsDefaultsAndProvidedOptionals(): void
    {
        $schema = [
            'properties' => [
                'foo' => ['type' => 'string', 'default' => 'x'],
                'bar' => ['type' => ['null', 'string']],
            ],
        ];

        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns', 'MyClass', sys_get_temp_dir()),
            (new SpecificationOptions())->withTargetPHPVersion(GeneratorRequest::DEFAULT_PHP8_VERSION),
        );
        $writer = new DebugWriter(new NullOutput());

        $generator = new ClassGenerator($req, $schema, $writer, new NullOutput());
        $generator->generateClass();

        $files = $writer->getWrittenFiles();
        $this->assertCount(1, $files);
        $code = current($files);

        $this->assertStringContainsString('private static array $_defaults', $code);
        $this->assertStringContainsString('private array $_providedOptionals', $code);
    }

    public function testMethodNamesRemainUniqueForConflictingProperties(): void
    {
        $schema = [
            'properties' => [
                'foo_bar' => ['type' => 'string'],
                'foo-bar' => ['type' => 'string'],
                'foo bar' => ['type' => 'string'],
            ],
        ];

        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns', 'MyClass', ''),
            (new SpecificationOptions())->withTargetPHPVersion(GeneratorRequest::DEFAULT_PHP8_VERSION),
        );
        $writer = new DebugWriter(new NullOutput());

        $generator = new ClassGenerator($req, $schema, $writer, new NullOutput());
        $generator->generateClass();

        $code = current($writer->getWrittenFiles());

        $this->assertStringContainsString('function getFooBar():', $code);
        $this->assertStringContainsString('function getFooBar1(', $code);
        $this->assertStringContainsString('function getFooBar2(', $code);
        $this->assertStringContainsString('function withFooBar(', $code);
        $this->assertStringContainsString('function withFooBar1(', $code);
        $this->assertStringContainsString('function withFooBar2(', $code);
        $this->assertStringContainsString('function withoutFooBar(', $code);
        $this->assertStringContainsString('function withoutFooBar1(', $code);
        $this->assertStringContainsString('function withoutFooBar2(', $code);
    }

    public function testGeneratedOutputMatchesFixture(): void
    {
        $schemaFile = __DIR__ . '/../Fixtures/Basic/schema.yaml';
        $schema = (new SchemaLoader())->loadSchema($schemaFile);
        $expected = trim(file_get_contents(__DIR__ . '/../Fixtures/Basic/Output-8.4/MyClass.php'));

        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns\\Basic_8_4', 'MyClass', __DIR__),
            (new SpecificationOptions())->withTargetPHPVersion(GeneratorRequest::DEFAULT_PHP8_VERSION),
        );

        $writer = new DebugWriter(new NullOutput());
        $generator = new ClassGenerator($req, $schema, $writer, new NullOutput());
        $generator->generateClass();

        $files = $writer->getWrittenFiles();
        $this->assertCount(1, $files);
        $filename = __DIR__ . '/MyClass.php';
        $this->assertArrayHasKey($filename, $files);
        $this->assertSame($expected, trim($files[$filename]));
    }
}

