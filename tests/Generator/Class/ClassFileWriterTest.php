<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use Helmich\Schema2Class\Writer\DebugWriter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;

class ClassFileWriterTest extends TestCase
{
    public function testWritesFile(): void
    {
        $req = new GeneratorRequest([], new ValidatedSpecificationFilesItem('Ns','Foo', sys_get_temp_dir()), new SpecificationOptions());
        $writer = new DebugWriter(new NullOutput());
        $fileWriter = new ClassFileWriter($writer);
        $fileWriter->write($req, [], [], []);
        $this->assertCount(1, $writer->getWrittenFiles());
    }
}
