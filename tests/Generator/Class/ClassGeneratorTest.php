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
    // SET 1
    
    public function testDefaultsAndProvidedOptionalsProperties(): void
    {
        $schema = [
            'properties' => [
                'foo' => ['type' => 'string', 'default' => 'x'],
                'bar' => ['type' => ['null', 'string']],
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

        $files = $writer->getWrittenFiles();
        $code  = reset($files);

        $this->assertStringContainsString('private static array $_defaults', $code);
        $this->assertStringContainsString('private array $_providedOptionals', $code);
    }

    public function testMethodNamesAreUnique(): void
    {
        $schema = [
            'properties' => [
                'foo_bar' => ['type' => 'string'],
                'foo-bar' => ['type' => 'string'],
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

        $files = $writer->getWrittenFiles();
        $code  = reset($files);

        $this->assertStringContainsString('function getFooBar():', $code);
        $this->assertStringContainsString('function getFooBar1():', $code);
        $this->assertStringContainsString('function withFooBar1(', $code);
        $this->assertStringContainsString('function withoutFooBar1(', $code);
    }

    public function testGeneratedMethodsMatchSnapshot(): void
    {
        $schema = Yaml::parseFile(__DIR__ . '/../Fixtures/Basic/schema.yaml');

        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns\\Basic_8_4', 'MyClass', ''),
            (new SpecificationOptions())->withTargetPHPVersion(GeneratorRequest::DEFAULT_PHP8_VERSION),
        );
        $writer = new DebugWriter(new NullOutput());

        $generator = new ClassGenerator($req, $schema, $writer, new NullOutput());
        $generator->generateClass();

        $files = $writer->getWrittenFiles();
        $generated = trim(reset($files));
        $expected = trim(file_get_contents(__DIR__ . '/../Fixtures/Basic/Output-8.4/MyClass.php'));

        $this->assertSame($expected, $generated);
    }

    // SET 2

     public function testDefaultsAndProvidedOptionalsPropertiesGenerated(): void
    {
        $schema = [
            'properties' => [
                'a' => ['type' => 'string', 'default' => 'x'],
                'b' => ['type' => ['string', 'null']],
            ],
            'required' => ['a'],
        ];

        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns', 'MyClass', sys_get_temp_dir()),
            (new SpecificationOptions())->withTargetPHPVersion(GeneratorRequest::DEFAULT_PHP8_VERSION),
        );

        $writer = new DebugWriter(new NullOutput());
        $generator = new ClassGenerator($req, $schema, $writer, new NullOutput());
        $generator->generateClass();

        $file = $writer->getWrittenFiles()[sys_get_temp_dir() . '/MyClass.php'];

        $this->assertStringContainsString('private static array $_defaults', $file);
        $this->assertStringContainsString('private array $_providedOptionals', $file);
    }

    public function testUniqueGetterAndSetterNames(): void
    {
        $schema = [
            'required' => ['fooBar'],
            'properties' => [
                'fooBar' => ['type' => 'string'],
                'foo-bar' => ['type' => 'string'],
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

        $file = $writer->getWrittenFiles()[sys_get_temp_dir() . '/MyClass.php'];

        $this->assertMatchesRegularExpression('/function getFooBar\(/', $file);
        $this->assertMatchesRegularExpression('/function getFooBar1\(/', $file);
        $this->assertMatchesRegularExpression('/function withFooBar1\(/', $file);
        $this->assertMatchesRegularExpression('/function withoutFooBar1\(/', $file);
    }

    public function testOutputMatchesFixture(): void
    {
        $schema = Yaml::parseFile(__DIR__ . '/../Fixtures/Basic/schema.yaml');

        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns\\Basic_8_4', 'MyClass', sys_get_temp_dir()),
            (new SpecificationOptions())->withTargetPHPVersion(GeneratorRequest::DEFAULT_PHP8_VERSION),
        );

        $writer = new DebugWriter(new NullOutput());
        $generator = new ClassGenerator($req, $schema, $writer, new NullOutput());
        $generator->generateClass();

        $file = $writer->getWrittenFiles()[sys_get_temp_dir() . '/MyClass.php'];
        $expected = trim(file_get_contents(__DIR__ . '/../Fixtures/Basic/Output-8.4/MyClass.php'));
        $this->assertSame($expected, trim($file));
    }

    // SET 3

    public function testIncludesDefaultsAndProvidedOptionalsProperties(): void
    {
        $schema = [
            'properties' => [
                'foo' => ['type' => 'string', 'default' => 'x'],
                'bar' => ['type' => ['string', 'null']],
            ],
        ];

        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns', 'MyClass', sys_get_temp_dir()),
            (new SpecificationOptions())->withTargetPHPVersion(GeneratorRequest::DEFAULT_PHP8_VERSION),
        );

        $writer = new DebugWriter(new NullOutput());
        $gen = new ClassGenerator($req, $schema, $writer, new NullOutput());
        $gen->generateClass();

        $files = $writer->getWrittenFiles();
        $this->assertCount(1, $files);
        $code = current($files);
        $this->assertStringContainsString('private static array $_defaults', $code);
        $this->assertStringContainsString('private array $_providedOptionals', $code);
    }

    public function testGetterSetterNamesAreUniqueForConflictingProperties(): void
    {
        $schema = [
            'required' => ['foo-bar', 'foo bar'],
            'properties' => [
                'foo-bar' => ['type' => 'string'],
                'foo bar' => ['type' => 'string'],
            ],
        ];

        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns', 'MyClass', sys_get_temp_dir()),
            (new SpecificationOptions())->withTargetPHPVersion(GeneratorRequest::DEFAULT_PHP8_VERSION),
        );

        $writer = new DebugWriter(new NullOutput());
        $gen = new ClassGenerator($req, $schema, $writer, new NullOutput());
        $gen->generateClass();

        $code = current($writer->getWrittenFiles());
        $this->assertStringContainsString('function getFooBar()', $code);
        $this->assertStringContainsString('function getFooBar1()', $code);
        $this->assertStringContainsString('function withFooBar(', $code);
        $this->assertStringContainsString('function withFooBar1(', $code);
    }

    public function testGeneratedCodeMatchesFixture(): void
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
        $gen = new ClassGenerator($req, $schema, $writer, new NullOutput());
        $gen->generateClass();

        $files = $writer->getWrittenFiles();
        $this->assertCount(1, $files);
        $filename = __DIR__ . '/MyClass.php';
        $this->assertArrayHasKey($filename, $files);
        $this->assertSame($expected, $files[$filename]);
    }
}
