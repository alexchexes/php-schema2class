<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\Class\IdentifierResolver;
use Helmich\Schema2Class\Generator\Class\Method\ClassMethodSuiteFactory;
use Helmich\Schema2Class\Generator\Class\Property\ClassPropertySuiteFactory;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\SchemaDefaultsCollector;
use Helmich\Schema2Class\Generator\SchemaPropertyCollector;
use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\DeclareStatement;
use Laminas\Code\Generator\ClassGenerator as LaminasClassGenerator;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\FileGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\PropertyGenerator;
use Symfony\Component\Console\Output\OutputInterface;

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
    
    public function generateClass(): void
    {
        $defaults = SchemaDefaultsCollector::collectDefaults($this->schema, $this->request);
        $this->request = $this->request->withClassHasDefaults(!empty($defaults));

        $schemaAdditionalProps = $this->schema['additionalProperties'] ?? null;
        $additionalsAllowed =
            $schemaAdditionalProps !== false
            && $schemaAdditionalProps !== ['not' => []]; // {"additionalProperties": {"not": {}}}
            // TODO: resolve other always-invalid schemas like "$ref" to false,
            // {"enum":[]}, {"anyOf":[]}, or {"not":{"allOf":[]}}, etc
        
        $schemaProperties = SchemaPropertyCollector::collectPropertiesFromSchema($this->schema, $this->request);    
        (new IdentifierResolver($this->request))->resolve($schemaProperties);

        foreach ($schemaProperties as $schemaProp) {
            $schemaProp->generateSubTypes($this->writer, $this->output);
        }

        $propertyGenerators = (new ClassPropertySuiteFactory(
            $this->request,
            $this->schema,
            $schemaProperties,
            $defaults,
            $additionalsAllowed,
        ))->generateAll();

        $methodGenerators = (new ClassMethodSuiteFactory(
            $this->request,
            $this->schema,
            $schemaProperties,
            $defaults,
            $additionalsAllowed,
        ))->generateAll();

        $classFileGenerator = $this->prepareFileGenerator($propertyGenerators, $methodGenerators);

        $filePath = $this->request->getTargetDirectory() . '/' . $this->request->getTargetClass() . '.php';
        
        // Invoke a hook where user can modify FileGenerator
        $this->request->onFileCreated($filePath, $classFileGenerator);

        $content = $classFileGenerator->generate();
        $content = $this->postProcessContents($content);

        $this->writer->writeFile($filePath, $content);
    }
    
    /**
     * @param PropertyGenerator[] $propertyGenerators
     * @param MethodGenerator[]   $methodGenerators
     */
    private function prepareFileGenerator(array $propertyGenerators, array $methodGenerators): FileGenerator
    {
        $docBlock = null;
        $description = $this->schema['description'] ?? '';
        if ($description !== '') {
            $docBlock = new DocBlockGenerator($this->schema['description']);
            $docBlock->setWordWrap(false);
        }

        $classGenerator = new LaminasClassGenerator(
            name:           $this->request->getTargetClass(),
            namespaceName:  $this->request->getTargetNamespace(),
            flags:          null,
            extends:        null,
            interfaces:     [],
            properties:     $propertyGenerators,
            methods:        $methodGenerators,
            docBlock:       $docBlock,
        );

        // Invoke a hook where user can modify class generator
        $this->request->onClassCreated($classGenerator);

        $fileGenerator = new FileGenerator();
        $fileGenerator->setClasses([$classGenerator]);

        if ($this->request->isAtLeastPHP('7.0') && !$this->request->getOptions()->getDisableStrictTypes()) {
            $fileGenerator->setDeclares([DeclareStatement::strictTypes(1)]);
        }

        return $fileGenerator;
    }

    /** 
     * Performs adjustements that Laminas can't handle:
     * Removes redundant namespace specifiers, namespace separators, etc.
     */
    private function postProcessContents(string $content)
    {
        // remove redundant `\` before `self`
        $content = preg_replace('/: \\\self/', ': self', $content);

        // remove current namespace everywhere
        $targetNs = $this->request->getTargetNamespace();
        $nsPattern = '/\\\\' . preg_quote($targetNs, '/') . '\\\\/';
        $content = preg_replace($nsPattern, '', $content);

        // remove `\` before classes in the current namespace
        // TODO: Make sure this works when different classes have different namespaces in Spec options!
        $ownClasses = $this->request->getGeneratedClassNames();
        if ($ownClasses) {
            $escapedOwnClasses = array_map(fn ($n) => preg_quote($n, '/'), $ownClasses);
            $classesPattern = '/\\\\(' . join('|', $escapedOwnClasses) . ')(?=\s|[,;)|]|$)/';
            $content = preg_replace($classesPattern, '$1', $content);
        }

        return $content;
    }
}
