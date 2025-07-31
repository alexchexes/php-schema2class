<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\PropertyQuery;
use Helmich\Schema2Class\Generator\PropertyGenerator;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlockGenerator;

/**
 * Factory for creating all methods of a generated class.
 * 
 * TODO: write dedicated test
 */
class ClassPropertyFactory
{
    public function __construct(
      private GeneratorRequest $generatorRequest,
      private array $schema,
    ) {}

    public function generateProperties(
      PropertyCollection $schemaProperties,
      array $defaults,
      bool $hasOptionalNullable,
    )
    {
        $schemaProperty = $this->createValidationSchemaProperty();
        $propertyGenerators[] = $schemaProperty;

        if ($defaults) {
            $propertyGenerators[] = $this->createDefaultsProperty($defaults);
        }

        if ($hasOptionalNullable) {
            $propertyGenerators[] = $this->createProvidedOptionalsProperty();
        }

        $propertyGenerators = [
            ...$propertyGenerators,
            ...$this->createSchemaProperties($schemaProperties),
        ];

        return $propertyGenerators;
    }
    
    private function createValidationSchemaProperty(): PropertyGenerator
    {
        // remove metadata like descriptions from schema if such option is set, but keep them
        // for building property documentation
        $validationSchema = $this->schema;
        if ($this->generatorRequest->getOptions()->getNoSchemaMetadata()) {
            $this->stripSchemaMetadata($validationSchema);
        }

        $prop = new PropertyGenerator(
            PropertyNames::SCHEMA_PROP,
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

    private function createDefaultsProperty(array $defaults): PropertyGenerator
    {
        $prop = new PropertyGenerator(
            PropertyNames::DEFAULTS_PROP,
            $defaults,
            PropertyGenerator::FLAG_PRIVATE | PropertyGenerator::FLAG_STATIC
        );

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

        $prop = new PropertyGenerator(PropertyNames::OPTIONALS_PROP, [] , $setVisibility);
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
    private function createSchemaProperties(PropertyCollection $schemaProperties): array
    {
        $propertyGenerators = [];

        $visibility = $this->generatorRequest->getNoGetters()
            ? PropertyGenerator::FLAG_PUBLIC
            : PropertyGenerator::FLAG_PRIVATE;

        foreach ($schemaProperties as $schemaProp) {
            $schema     = $schemaProp->schema();
            $isOptional = false;
            $propGen    = new PropertyGenerator(
                $schemaProp->name(),
                $schemaProp->formatValue(null),
                $visibility
            );

            if ($schemaProp instanceof OptionalPropertyDecorator) {
                $isOptional = true;
            }

            $tags = [new GenericTag("var", trim($schemaProp->typeAnnotation()))];
            if (PropertyQuery::isDeprecated($schemaProp)) {
                $tags[] = new GenericTag("deprecated");
            }

            $docBlock = new DocBlockGenerator(
                $schema["description"] ?? null,
                null,
                $tags
            );
            $docBlock->setWordWrap(false);

            $propGen->setDocBlock($docBlock);

            $typeHint = $schemaProp->typeHint($this->generatorRequest->getTargetPHPVersion());
            if ($this->generatorRequest->isAtLeastPHP("7.4") && $typeHint !== null) {
                $propGen->setTypeHint($typeHint);
            }

            // omit default `null` for every required field, unsless default is specified in the schema
            $propGen->omitDefaultValue(!$isOptional);

            $propertyGenerators[] = $propGen;
        }

        return $propertyGenerators;
    }
}