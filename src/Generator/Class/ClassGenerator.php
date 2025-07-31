<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Laminas\Code\Generator\ClassGenerator as LaminasClassGenerator;
use Laminas\Code\Generator\DocBlockGenerator;

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
    public function __construct(
        private GeneratorRequest $request,
        private array $schema,
        private WriterInterface $writer,
        private OutputInterface $output,
    ) {}
    
    public function generateClass()
    {
        $collector = new SchemaPropertyCollector();
        $schemaProperties = $collector->collectPropertiesFromSchema($this->schema, $this->request);
        $hasOptionalNullable = $collector->hasOptionalNullable($schemaProperties);
        $defaults = $collector->collectDefaults($this->schema, $this->request);

        $this->request->setCurrReqHasDefaults(!empty($defaults));

        foreach ($schemaProperties as $schemaProp) {
            $schemaProp->generateSubTypes($this->writer, $this->output);
        }

        $propertyFactory = new ClassPropertyFactory($this->request, $this->schema);
        $propertyGenerators = $propertyFactory->generateProperties(
            $schemaProperties,
            $defaults,
            $hasOptionalNullable,
        );

        $methodFactory = new ClassMethodFactory($this->request);
        $methodGenerators = $methodFactory->generateMethods(
            $schemaProperties,
            $defaults,
            $hasOptionalNullable,
        );

        $classGenerator = $this->createClassGenerator($propertyGenerators, $methodGenerators);

        $classWriter = new ClassFileWriter($this->request, $this->writer);
        $classWriter->write($classGenerator);
    }
    
    /**
     * @param PropertyGenerator[] $propertyGenerators
     * @param MethodGenerator[] $methodGenerators
     */
    private function createClassGenerator(array $propertyGenerators, array $methodGenerators): LaminasClassGenerator
    {
        $cls = new LaminasClassGenerator(
            $this->request->getTargetClass(),
            $this->request->getTargetNamespace(),
            null,
            null,
            [],
            $propertyGenerators,
            $methodGenerators,
            null,
        );

        // Add PHPDoc class description
        $description = $this->schema['description'] ?? '';
        if ($description !== '') {
            $doc = new DocBlockGenerator($this->schema['description']);
            $doc->setWordWrap(false);
            $cls->setDocBlock($doc);
        }

        // Invoke a hook
        $this->request->onClassCreated($cls);

        return $cls;
    }
}
