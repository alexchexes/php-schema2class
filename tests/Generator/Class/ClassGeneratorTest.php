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

class ClassGeneratorTest extends TestCase
{
    public function testDefaultsAndProvidedOptionalsProperties(): void
    {
        $schema = [
            'properties' => [
                'foo' => ['type' => 'string', 'default' => 'x'],
                'bar' => ['type' => ['string', 'null']],
            ],
            'required' => ['foo'],
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

    public function testMethodNamesAreUnique(): void
    {
        $schema = [
            'required' => ['foo_bar', 'foo-bar', 'foo bar'],
            'properties' => [
                'foo_bar' => ['type' => 'string'],
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
        $generator = new ClassGenerator($req, $schema, $writer, new NullOutput());
        $generator->generateClass();

        $file = $writer->getWrittenFiles()[sys_get_temp_dir() . '/MyClass.php'];

        $this->assertStringContainsString('function getFooBar()', $file);
        $this->assertStringContainsString('function getFooBar1()', $file);
        $this->assertStringContainsString('function getFooBar2()', $file);
        $this->assertStringContainsString('function withFooBar(', $file);
        $this->assertStringContainsString('function withFooBar1(', $file);
        $this->assertStringContainsString('function withFooBar2(', $file);
    }

    public function testGeneratedCodeMatchesFixture(): void
    {
        $schema = (new SchemaLoader())->loadSchema(__DIR__ . '/../Fixtures/Basic/schema.yaml');
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
