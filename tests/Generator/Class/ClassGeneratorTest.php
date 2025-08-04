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
            new ValidatedSpecificationFilesItem('Ns', 'MyClass', __DIR__),
            (new SpecificationOptions())->withTargetPHPVersion(GeneratorRequest::DEFAULT_PHP8_VERSION),
        );
        $writer = new DebugWriter(new NullOutput());

        $generator = new ClassGenerator($req, $schema, $writer, new NullOutput());
        $generator->generateClass();

        $files = $writer->getWrittenFiles();
        $this->assertCount(1, $files);
        $code = current($files);

        $this->assertStringContainsString('private static array $_defaults', $code);
        $this->assertStringContainsString('private array $' . PropertyNames::OPTIONALS, $code);
    }

    public function testConflictingGetterSetterNamesAreUnique(): void
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
            new ValidatedSpecificationFilesItem('Ns', 'MyClass', ''),
            (new SpecificationOptions())->withTargetPHPVersion(GeneratorRequest::DEFAULT_PHP8_VERSION),
        );

        $writer = new DebugWriter(new NullOutput());
        $generator = new ClassGenerator($req, $schema, $writer, new NullOutput());
        $generator->generateClass();

        $files = $writer->getWrittenFiles();
        $code  = current($files);

        $this->assertStringContainsString('function getFooBar()', $code);
        $this->assertStringContainsString('function withFooBar(', $code);
        $this->assertStringContainsString('function withoutFooBar()', $code);

        $this->assertStringContainsString('function get_FooBar()', $code);
        $this->assertStringContainsString('function with_FooBar(', $code);
        $this->assertStringContainsString('function without_FooBar()', $code);

        $this->assertStringContainsString('function getFooBar1()', $code);
        $this->assertStringContainsString('function withFooBar1(', $code);
        $this->assertStringContainsString('function withoutFooBar1()', $code);

        $this->assertStringContainsString('function getFooBar2()', $code);
        $this->assertStringContainsString('function withFooBar2(', $code);
        $this->assertStringContainsString('function withoutFooBar2()', $code);
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
        $generator = new ClassGenerator($req, $schema, $writer, new NullOutput());
        $generator->generateClass();

        $files = $writer->getWrittenFiles();
        $this->assertCount(1, $files);
        $generated = trim(current($files));
        $this->assertSame($expected, $generated);
    }
}
