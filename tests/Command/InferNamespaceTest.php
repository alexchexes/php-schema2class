<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Command;

use Helmich\Schema2Class\Generator\NamespaceInferrer;
use Helmich\Schema2Class\Generator\GenerationRunner;
use Helmich\Schema2Class\Loader\SchemaLoader;
use Helmich\Schema2Class\Generator\SchemaToClassFactory;
use Helmich\Schema2Class\Util\StringUtils;
use PHPUnit\Framework\TestCase;

class InferNamespaceTest extends TestCase
{
    public function testFallsBackToDirectoryName(): void
    {
        $inferrer = new NamespaceInferrer();
        $runner = new GenerationRunner(
            new SchemaLoader(),
            $inferrer,
            new SchemaToClassFactory(),
        );

        $dir = sys_get_temp_dir() . '/s2c_' . uniqid();
        mkdir($dir);
        try {
            $expected = StringUtils::safePascalCase(basename($dir));
            $ns = $runner->inferNamespace($dir);
            $this->assertSame($expected, $ns);
        } finally {
            rmdir($dir);
        }
    }
}
