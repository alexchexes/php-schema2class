<?php

declare(strict_types=1);

namespace Helmich\Schema2Class;

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
        $generator->generateFromSchema($schema, 'Person', $dir, 'My\Ns');

        $file = $dir . '/Person.php';
        $this->assertFileExists($file);
        $content = file_get_contents($file);
        $this->assertStringContainsString('namespace My\Ns;', $content);
        $this->assertStringContainsString('class Person', $content);

        unlink($file);
        rmdir($dir);
    }
}
