<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollectionFilterFactory;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\Decorator\PropertyDecoratorInterface;
use Helmich\Schema2Class\Generator\Property\PropertyBuilder;
use Helmich\Schema2Class\Generator\Property\PropertyQuery;
use Helmich\Schema2Class\Generator\Property\RenameablePropertyInterface;
use Helmich\Schema2Class\Generator\Property\Type\ObjectArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\PrimitiveArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Generator\Property\Type\ReferenceArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\TypedArrayProperty;
use Helmich\Schema2Class\Generator\PropertyGenerator;
use Helmich\Schema2Class\Util\SchemaUtils;
use Helmich\Schema2Class\Util\StringUtils;
use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Symfony\Component\Console\Output\OutputInterface;
use Helmich\Schema2Class\Generator\Class\PropertyCollector;
use Helmich\Schema2Class\Generator\Class\MethodFactory;
use Helmich\Schema2Class\Generator\Class\ClassFileWriter;

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
        private GeneratorRequest $generatorRequest,
        private WriterInterface $writer,
        private OutputInterface $output,
        private ?PropertyCollector $propertyCollector = null,
        private ?MethodFactory $methodFactory = null,
        private ?ClassFileWriter $fileWriter = null,
    ) {
        $this->propertyCollector ??= new PropertyCollector();
        $this->methodFactory ??= new MethodFactory($this->generatorRequest);
        $this->fileWriter ??= new ClassFileWriter($this->writer);
    }
    
    public function generateClass(array $schema)
    {
        // remove metadata like descriptions from schema if such option is set, but keep them
        // for building property documentation
        $validationSchema = $schema;
        if ($this->generatorRequest->getOptions()->getNoSchemaMetadata()) {
            $this->stripSchemaMetadata($validationSchema);
        }

        $classProperties = [];

        $schemaProperty = $this->createSchemaProperty($validationSchema);
        $classProperties[] = $schemaProperty;

        $defaults      = $this->propertyCollector->collectDefaults($schema, $this->generatorRequest);
        $hasDefaults   = !empty($defaults);
        $this->generatorRequest->setCurrReqHasDefaults($hasDefaults);

        if ($defaults) {
            $classProperties[] = $this->createDefaultsProperty($defaults);
        }

        $schemaProperties = $this->propertyCollector->collectPropertiesFromSchema($schema, $this->generatorRequest);

        $this->propertyCollector->ensureUniquePropertyNames(
            $schemaProperties,
            $this->generatorRequest->getOptions()->getPreservePropertyNames(),
        );

        foreach ($schemaProperties as $property) {
            $property->generateSubTypes($this->writer, $this->output);
        }

        $hasOptionalNullable = $this->propertyCollector->hasOptionalNullable($schemaProperties);

        if ($hasOptionalNullable) {
            $classProperties[] = $this->createProvidedOptionalsProperty();
        }

        $classProperties = [
            ...$classProperties,
            ...$this->generateProperties($schemaProperties),
        ];

        $methods = [
            $this->methodFactory->generateConstructor($schemaProperties),
            ...$this->methodFactory->generateGetterMethods($schemaProperties),
            ...$this->methodFactory->generateSetterMethods($schemaProperties),
            $this->methodFactory->generateBuildMethod($schemaProperties, $defaults, $hasOptionalNullable),
            $this->methodFactory->generateToArrayMethod($schemaProperties, $defaults),
            $this->methodFactory->generateToStdClassMethod($schemaProperties, $defaults),
            $this->methodFactory->generateValidateMethod(),
            $this->methodFactory->generateCloneMethod($schemaProperties),
            $hasOptionalNullable ? $this->methodFactory->generateIsProvidedMethod() : null,
        ];
        $methods = array_values(array_filter($methods));
        $this->methodFactory->ensureUniqueMethodNames($methods);

        $this->fileWriter->write($this->generatorRequest, $schema, $classProperties, $methods);
    }
    
    private function stripSchemaMetadata(array &$node): void
    {
        $metaFields = [
            'description',
            'title',
            'examples',
            'deprecated',
            'default',
            'readOnly',
            'writeOnly',
            '$id',
            '$schema',
            '$comment',
        ];

        foreach ($node as $key => &$value) {
            if (in_array($key, $metaFields, true)) {
                unset($node[$key]);
                continue;
            }
            if (is_array($value)) {
                $this->stripSchemaMetadata($value);
            }
        }
    }


    public function createSchemaProperty(array $validationSchema): PropertyGenerator
    {
        $prop = new PropertyGenerator(
            'schema',
            $validationSchema,
            PropertyGenerator::FLAG_PRIVATE | PropertyGenerator::FLAG_STATIC,
        );

        $prop->setDocBlock(new DocBlockGenerator(
            'Schema used to validate input for creating instances of this class',
            null,
            [new GenericTag('var', 'array')],
        ));

        if ($this->generatorRequest->isAtLeastPHP('7.4')) {
            $prop->setTypeHint('array');
        }
        if ($this->generatorRequest->getOptions()->getSingleLineSchema()) {
            $prop->setSingleLineDefaultValue(true);
        }

        return $prop;
    }

    public function createDefaultsProperty(array $defaults): ?PropertyGenerator
    {
        $prop = new PropertyGenerator('_defaults', $defaults, PropertyGenerator::FLAG_PRIVATE | PropertyGenerator::FLAG_STATIC);
        $prop->setDocBlock(new DocBlockGenerator(
            'Default values from the schema',
            null,
            [new GenericTag('var', 'array')],
        ));
        if ($this->generatorRequest->isAtLeastPHP('7.4')) {
            $prop->setTypeHint('array');
        }
        if ($this->generatorRequest->getOptions()->getSingleLineSchema()) {
            $prop->setSingleLineDefaultValue(true);
        }

        return $prop;
    }

    public function createProvidedOptionalsProperty(): ?PropertyGenerator
    {
        $setVisibility = ($this->generatorRequest->getNoGetters() && $this->generatorRequest->getNoSetters())
            ? PropertyGenerator::FLAG_PUBLIC
            : PropertyGenerator::FLAG_PRIVATE;

        $prop = new PropertyGenerator('_providedOptionals', [] , $setVisibility);
        $prop->setDefaultValue([]);
        $prop->setSingleLineDefaultValue(true);

        if ($this->generatorRequest->isAtLeastPHP("7.4")) {
            $prop->setTypeHint("array");
        }

        $prop->setDocBlock(new DocBlockGenerator(
            "Map of optional nullable property names that were explicitly set",
            null,
            [new GenericTag('var', 'array<string,true>')]
        ));

        return $prop;
    }

    /**
     * @return PropertyGenerator[]
     */
    public function generateProperties(PropertyCollection $properties): array
    {
        $propertyGenerators = [];

        $visibility = $this->generatorRequest->getNoGetters()
            ? PropertyGenerator::FLAG_PUBLIC
            : PropertyGenerator::FLAG_PRIVATE;

        foreach ($properties as $property) {
            $schema     = $property->schema();
            $isOptional = false;
            $prop       = new PropertyGenerator(
                $property->name(),
                $property->formatValue(null),
                $visibility
            );

            if ($property instanceof OptionalPropertyDecorator) {
                $isOptional = true;
            }

            $tags = [new GenericTag("var", trim($property->typeAnnotation()))];
            if (PropertyQuery::isDeprecated($property)) {
                $tags[] = new GenericTag("deprecated");
            }

            $docBlock = new DocBlockGenerator(
                $schema["description"] ?? null,
                null,
                $tags
            );
            $docBlock->setWordWrap(false);

            $prop->setDocBlock($docBlock);

            $typeHint = $property->typeHint($this->generatorRequest->getTargetPHPVersion());
            if ($this->generatorRequest->isAtLeastPHP("7.4") && $typeHint !== null) {
                $prop->setTypeHint($typeHint);
            }

            // omit default `null` for every required field, unsless default is specified in the schema
            $prop->omitDefaultValue(!$isOptional);

            $propertyGenerators[] = $prop;
        }

        return $propertyGenerators;
    }

    }
