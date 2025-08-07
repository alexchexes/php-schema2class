<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Writer;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;

class LintingWriterTest extends TestCase
{
    public function testThrowsExceptionOnInvalidPhp(): void
    {
        $dir = sys_get_temp_dir() . '/s2c_' . uniqid();
        mkdir($dir);
        $file = $dir . '/Invalid.php';

        $writer = new LintingWriter(new FileWriter(new NullOutput()));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/syntax errors/');

        try {
            $writer->writeFile($file, "<?php invalid php");
        } finally {
            if (file_exists($file)) {
                unlink($file);
            }
            rmdir($dir);
        }
    }
}