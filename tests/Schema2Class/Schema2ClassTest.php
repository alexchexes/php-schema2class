<?php

declare(strict_types=1);

namespace Helmich\Schema2Class;

use Helmich\Schema2Class\Spec\OptionsDefaults;
use Helmich\Schema2Class\Spec\Specification;
use PHPUnit\Framework\TestCase;

class Schema2ClassTest extends TestCase
{
    public function testGenerateFromSchemaCreatesFile(): void
    {
        $schema = [
            'required' => ['name'],
            'properties' => [
                'name' => ['type' => 'string'],
            ],
        ];

        $dir = sys_get_temp_dir() . '/s2c_' . uniqid();
        mkdir($dir);

        $generator = new Schema2Class();
        $generator->generateFromSchema($schema, $dir, 'Person', 'My\Ns');

        $file = $dir . '/Person.php';
        $this->assertFileExists($file);
        $content = file_get_contents($file);
        $this->assertStringContainsString('namespace My\Ns;', $content);
        $this->assertStringContainsString('class Person', $content);

        unlink($file);
        rmdir($dir);
    }

    public function testGenerateFromSchemaRequiresClassNameForObjects(): void
    {
        $schema = [
            'required' => ['name'],
            'properties' => ['name' => ['type' => 'string']],
        ];

        $dir = sys_get_temp_dir() . '/s2c_' . uniqid();
        mkdir($dir);

        $generator = new Schema2Class();
        $this->expectException(\InvalidArgumentException::class);
        try {
            $generator->generateFromSchema($schema, $dir, null, 'My\\Ns');
        } finally {
            if (is_file($dir . '/.php')) {
                unlink($dir . '/.php');
            }
            rmdir($dir);
        }
    }

    public function testGenerateFromSchemaWithoutClassForDefinitionsOnly(): void
    {
        $schemaFile = __DIR__ . '/../Generator/Fixtures/DefinitionsIndependet/schema.json';
        $schema = json_decode(file_get_contents($schemaFile), true);

        $dir = sys_get_temp_dir() . '/s2c_' . uniqid();
        mkdir($dir);

        $generator = new Schema2Class();
        $generator->generateFromSchema($schema, $dir, null, 'Ns\\Defs');

        $this->assertFileExists($dir . '/Foo.php');
        $this->assertFileExists($dir . '/Bar.php');

        unlink($dir . '/Foo.php');
        unlink($dir . '/Bar.php');
        rmdir($dir);
    }

    public function testGenerateFromSpecArrayAcceptsSpecification(): void
    {
        $schemaFile = __DIR__ . '/../Generator/Fixtures/Basic/schema.yaml';
        $dir = sys_get_temp_dir() . '/s2c_' . uniqid();
        mkdir($dir);

        $config = [
            'files' => [
                [
                    'input' => $schemaFile,
                    'className' => 'Foo',
                    'options' => [
                        'targetDirectory' => $dir,
                        'targetNamespace' => 'My\\Ns',
                    ],
                ],
            ],
        ];

        $spec = Specification::buildFromInput($config);

        $generator = new Schema2Class();
        $generator->generateFromSpec($spec);

        $file = $dir . '/Foo.php';
        $this->assertFileExists($file);
        $content = file_get_contents($file);
        $this->assertStringContainsString('namespace My\\Ns;', $content);
        $this->assertStringContainsString('class Foo', $content);

        unlink($file);
        rmdir($dir);
    }

    public function testGenerateFromSchemaCleansDirectory(): void
    {
        $schema = [
            'required' => ['name'],
            'properties' => ['name' => ['type' => 'string']],
        ];

        $dir = sys_get_temp_dir() . '/s2c_' . uniqid();
        mkdir($dir);
        file_put_contents($dir . '/Old.php', '<?php');

        $generator = new Schema2Class();
        $generator->generateFromSchema($schema, $dir, 'Person', 'My\\Ns', true);

        $this->assertFileDoesNotExist($dir . '/Old.php');

        unlink($dir . '/Person.php');
        rmdir($dir);
    }

    public function testMergeOptionsHonorsExplicitOverrides(): void
    {
        $base = (new \Helmich\Schema2Class\Spec\SpecificationOptions())
            ->withSingleLineSchema(true);

        $override = new \Helmich\Schema2Class\Spec\SpecificationOptions();

        $merged = OptionsDefaults::mergeOptions($base, $override);
        $this->assertTrue($merged->getSingleLineSchema());

        $override = (new \Helmich\Schema2Class\Spec\SpecificationOptions())
            ->withSingleLineSchema(false);

        $merged = OptionsDefaults::mergeOptions($base, $override);
        $this->assertFalse($merged->getSingleLineSchema());
    }
}
