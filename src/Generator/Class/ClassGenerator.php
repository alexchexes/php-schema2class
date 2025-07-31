<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Helmich\Schema2Class\Generator\Class\MethodFactory;
use Helmich\Schema2Class\Generator\Class\ClassFileWriter;
use Helmich\Schema2Class\Generator\Class\PropertyGeneratorFactory;
use Helmich\Schema2Class\Generator\GeneratorRequest;

/**
 * Generates the `Laminas\Code` representation of a PHP class for a single schema.
 *
 * Called by {@see SchemaToClass} (which also prepares and hands the {@see GeneratorRequest} here)
 * after all {@see PropertyInterface} objects are collected.
 * 
 * This class is responsible only for building the Laminas\Code representation;
 * the actual writing of files happens outside of this class.
 */
class ClassGenerator
{
    private PropertyGeneratorFactory $propertyFactory;
    private MethodFactory $methodFactory;
    private ClassFileWriter $fileWriter;

    public function __construct(
        private GeneratorRequest $generatorRequest,
        private array $schema,
        private WriterInterface $writer,
        private OutputInterface $output,
    ) {
        $this->propertyFactory = new PropertyGeneratorFactory($this->generatorRequest, $this->writer, $this->output);
        $this->methodFactory   = new MethodFactory($this->generatorRequest);
        $this->fileWriter      = new ClassFileWriter($this->writer);
    }
    
    public function generateClass()
    {
        [
            $classProperties,
            $schemaProperties,
            $defaults,
            $hasOptionalNullable,
        ] = $this->propertyFactory->generate($this->schema);

        $methods = $this->methodFactory->generateAllMethods(
            $schemaProperties,
            $defaults,
            $hasOptionalNullable,
        );

        $this->fileWriter->write(
            $this->generatorRequest,
            $this->schema,
            $classProperties,
            $methods,
        );
    }
    
}
