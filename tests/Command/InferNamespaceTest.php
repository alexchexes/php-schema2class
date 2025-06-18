<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Command;

use Helmich\Schema2Class\Generator\NamespaceInferrer;
use Helmich\Schema2Class\Util\StringUtils;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;

class InferNamespaceTest extends TestCase
{
    public function testFallsBackToDirectoryName(): void
    {
        $inferrer = new NamespaceInferrer();
        $obj = new class($inferrer) {
            use GenerateFromRequestTrait;
            private NamespaceInferrer $namespaceInferrer;
            public function __construct(NamespaceInferrer $inf) { $this->namespaceInferrer = $inf; }
            public function infer(string $dir): string {
                return $this->inferNamespace(new NullOutput(), null, $dir, 'Foo');
            }
        };

        $dir = sys_get_temp_dir() . '/s2c_' . uniqid();
        mkdir($dir);
        try {
            $expected = StringUtils::pascalCase(basename($dir));
            $ns = $obj->infer($dir);
            $this->assertSame($expected, $ns);
        } finally {
            rmdir($dir);
        }
    }
}
