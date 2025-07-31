<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\PropertyQuery;
use Helmich\Schema2Class\Generator\PropertyGenerator;
use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Builds all property generators for a class including schema, defaults and
 * properties derived from the JSON schema.
 *
 * Returns the property generators as well as collected schema properties and
 * defaults so method generation can reuse that information.
 */
class PropertyGeneratorFactory
{
    private PropertyCollector $collector;

    public function __construct(
        private GeneratorRequest $generatorRequest,
        private WriterInterface $writer,
        private OutputInterface $output,
    ) {
        $this->collector = new PropertyCollector();
    }

    /**
     * Generates property generators for the given schema.
     *
     * @return array{0: array,1: PropertyCollection,2: array,3: bool}
     *   List of [class properties, schema properties, defaults, hasOptionalNullable]
     */
    public function generate(array $schema): array
    {
        $classProperties = [];

        $schemaProperty = $this->createSchemaProperty($schema);
        $classProperties[] = $schemaProperty;

        $defaults    = $this->collector->collectDefaults($schema, $this->generatorRequest);
        $hasDefaults = !empty($defaults);
        $this->generatorRequest->setCurrReqHasDefaults($hasDefaults);
        if ($defaults) {
            $classProperties[] = $this->createDefaultsProperty($defaults);
        }

        $schemaProperties = $this->collector->collectPropertiesFromSchema($schema, $this->generatorRequest);

        $this->collector->ensureUniquePropertyNames(
            $schemaProperties,
            $this->generatorRequest->getOptions()->getPreservePropertyNames(),
        );

        foreach ($schemaProperties as $property) {
            $property->generateSubTypes($this->writer, $this->output);
        }

        $hasOptionalNullable = $this->collector->hasOptionalNullable($schemaProperties);
        if ($hasOptionalNullable) {
            $classProperties[] = $this->createProvidedOptionalsProperty();
        }

        $classProperties = [
            ...$classProperties,
            ...$this->generateProperties($schemaProperties),
        ];

        return [$classProperties, $schemaProperties, $defaults, $hasOptionalNullable];
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

    private function createSchemaProperty(array $schema): PropertyGenerator
    {
        // remove metadata like descriptions from schema if such option is set, but keep them
        // for building property documentation
        $validationSchema = $schema;
        if ($this->generatorRequest->getOptions()->getNoSchemaMetadata()) {
            $this->stripSchemaMetadata($validationSchema);
        }

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

    private function createDefaultsProperty(array $defaults): PropertyGenerator
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

    private function createProvidedOptionalsProperty(): PropertyGenerator
    {
        $setVisibility = ($this->generatorRequest->getNoGetters() && $this->generatorRequest->getNoSetters())
            ? PropertyGenerator::FLAG_PUBLIC
            : PropertyGenerator::FLAG_PRIVATE;

        $prop = new PropertyGenerator('_providedOptionals', [] , $setVisibility);
        $prop->setDefaultValue([]);
        $prop->setSingleLineDefaultValue(true);

        if ($this->generatorRequest->isAtLeastPHP('7.4')) {
            $prop->setTypeHint('array');
        }

        $prop->setDocBlock(new DocBlockGenerator(
            'Map of optional nullable property names that were explicitly set',
            null,
            [new GenericTag('var', 'array<string,true>')]
        ));

        return $prop;
    }

    /**
     * @return PropertyGenerator[]
     */
    private function generateProperties(PropertyCollection $properties): array
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

            $tags = [new GenericTag('var', trim($property->typeAnnotation()))];
            if (PropertyQuery::isDeprecated($property)) {
                $tags[] = new GenericTag('deprecated');
            }

            $docBlock = new DocBlockGenerator(
                $schema['description'] ?? null,
                null,
                $tags
            );
            $docBlock->setWordWrap(false);

            $prop->setDocBlock($docBlock);

            $typeHint = $property->typeHint($this->generatorRequest->getTargetPHPVersion());
            if ($this->generatorRequest->isAtLeastPHP('7.4') && $typeHint !== null) {
                $prop->setTypeHint($typeHint);
            }

            // omit default `null` for every required field, unless default is specified in the schema
            $prop->omitDefaultValue(!$isOptional);

            $propertyGenerators[] = $prop;
        }

        return $propertyGenerators;
    }
}
