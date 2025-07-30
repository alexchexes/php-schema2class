<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use Helmich\Schema2Class\Writer\DebugWriter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Yaml\Yaml;

class ClassGeneratorTest extends TestCase
{
    public function testGeneratedClassContainsDefaultsAndProvidedOptionals(): void
    {
        $schema = [
            'required' => ['foo'],
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

        $generator = new ClassGenerator($req, $schema, $writer, new NullOutput());
        $generator->generateClass();

        $files = $writer->getWrittenFiles();
        $code  = current($files);

        $this->assertStringContainsString('private static array $_defaults', $code);
        $this->assertStringContainsString('private array $_providedOptionals', $code);
    }

    public function testMethodNamesAreUniqueForConflictingProperties(): void
    {
        $schema = [
            'properties' => [
                'foo_bar' => ['type' => 'string'],
                'foo-bar' => ['type' => 'string'],
                'foo bar' => ['type' => 'string'],
                'fooBar'  => ['type' => 'string'],
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
        $code  = current($files);

        $this->assertStringContainsString('function getFooBar():', $code);
        $this->assertStringContainsString('function getFooBar1():', $code);
        $this->assertStringContainsString('function getFooBar2():', $code);
        $this->assertStringContainsString('function getFooBar3():', $code);
        $this->assertStringContainsString('function withFooBar3(', $code);
        $this->assertStringContainsString('function withoutFooBar3(', $code);
    }

    public function testGeneratedCodeMatchesFixture(): void
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

        $generated = trim(current($writer->getWrittenFiles()));
        $expected  = trim(file_get_contents(__DIR__ . '/../Fixtures/Basic/Output-8.4/MyClass.php'));

        $this->assertSame($expected, $generated);
    }
}
