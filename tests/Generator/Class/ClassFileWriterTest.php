<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use Helmich\Schema2Class\Writer\DebugWriter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Laminas\Code\Generator\ClassGenerator as LaminasClassGenerator;

class ClassFileWriterTest extends TestCase
{
    public function testWritesFile(): void
    {
        $req = new GeneratorRequest(
            [],
            new ValidatedSpecificationFilesItem('Ns','Foo', sys_get_temp_dir()),
            new SpecificationOptions()
        );

        $cls = new LaminasClassGenerator($req->getTargetClass(), $req->getTargetNamespace());

        $writer = new DebugWriter(new NullOutput());
        $fileWriter = new ClassFileWriter($req, $writer);
        $fileWriter->write($cls, [], [], []);
        $this->assertCount(1, $writer->getWrittenFiles());
    }
}
