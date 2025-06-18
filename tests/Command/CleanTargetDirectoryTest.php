<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Command;

use Helmich\Schema2Class\Generator\NamespaceInferrer;
use Helmich\Schema2Class\Generator\SchemaToClassFactory;
use Helmich\Schema2Class\Loader\SchemaLoader;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class CleanTargetDirectoryTest extends TestCase
{
    public function testCliCleansTargetDirectory(): void
    {
        $schemaFile = __DIR__ . '/../Generator/Fixtures/Basic/schema.yaml';

        $dir = sys_get_temp_dir() . '/s2c_' . uniqid();
        mkdir($dir);
        file_put_contents($dir . '/Old.php', '<?php');

        $command = new GenerateCommand(
            new SchemaLoader(),
            new NamespaceInferrer(),
            new SchemaToClassFactory(),
        );

        $tester = new CommandTester($command);
        $tester->execute([
            'schema' => $schemaFile,
            'target-dir' => $dir,
            '--target-namespace' => 'Ns\\Clean',
            '--class' => 'Foo',
            '--clean-target-dir' => true,
        ]);

        $this->assertFileDoesNotExist($dir . '/Old.php');
        $this->assertFileExists($dir . '/Foo.php');

        unlink($dir . '/Foo.php');
        rmdir($dir);
    }
}
