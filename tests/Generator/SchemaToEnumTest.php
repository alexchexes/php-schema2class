<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use Helmich\Schema2Class\Writer\DebugWriter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use function PHPUnit\Framework\assertStringContainsString;

class SchemaToEnumTest extends TestCase
{
    public function testMixedEnumTypesThrows(): void
    {
        $schema = [
            'enum' => [1, '1', 2, 'two'],
            'type' => ['integer', 'string'],
        ];

        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns\\Mixed', 'Foo', __DIR__),
            (new SpecificationOptions())->withTargetPHPVersion('8.2')
        );

        $writer = new DebugWriter(new NullOutput());
        $generator = new SchemaToClassFactory();

        $this->expectException(GeneratorException::class);
        $generator->build($writer, new NullOutput())->schemaToClass($req);
    }

    public function testEnumCustomizationHook(): void
    {
        $schema = [
            'enum' => ['foo'],
            'type' => 'string',
        ];

        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns\\HookEnum', 'Foo', __DIR__),
            (new SpecificationOptions())->withTargetPHPVersion('8.2')
        );

        $writer = new DebugWriter(new NullOutput());

        $hook = new class implements Hook\EnumCreatedHook {
            public function onEnumCreated(string $enumName, PhpParserEnumGenerator $enum): void
            {
                $enum->getBuilder()->addStmt((new \PhpParser\BuilderFactory())->enumCase('BAR')->setValue('bar'));
            }
        };

        $req = $req->withHook($hook);
        (new SchemaToClassFactory())->build($writer, new NullOutput())->schemaToClass($req);

        $written = current($writer->getWrittenFiles());
        assertStringContainsString("case BAR = 'bar';", $written);
    }
}
