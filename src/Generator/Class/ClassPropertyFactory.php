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
      private GeneratorRequest $request,
      private array $schema,
      private PropertyCollection $schemaProperties,
      private array $defaults,
      private bool $hasOptionalNullable,
    ) {}

    /** 
     * @return PropertyGenerator[]
     */
    public function generateProperties()
    {
        $schemaProperty = $this->generateValidationSchemaProperty();
        $propertyGenerators[] = $schemaProperty;

        if ($this->defaults) {
            $propertyGenerators[] = $this->generateDefaultsProperty();
        }

        if ($this->hasOptionalNullable) {
            $propertyGenerators[] = $this->generateProvidedOptionalsProperty();
        }

        $propertyGenerators = [
            ...$propertyGenerators,
            ...$this->generateSchemaProperties(),
        ];

        return $propertyGenerators;
    }
    
    private function generateValidationSchemaProperty(): PropertyGenerator
    {
        // remove metadata like descriptions from schema if such option is set, but keep them
        // for building property documentation
        $validationSchema = $this->schema;
        if ($this->request->getOptions()->getNoSchemaMetadata()) {
            $this->stripSchemaMetadata($validationSchema);
        }

        $prop = new PropertyGenerator(
            PropertyNames::SCHEMA,
            $validationSchema,
            PropertyGenerator::FLAG_PRIVATE | PropertyGenerator::FLAG_STATIC,
        );

        $prop->setDocBlock(new DocBlockGenerator(
            'Schema used to validate input for creating instances of this class',
            null,
            [new GenericTag('var', 'array')],
        ));

        if ($this->request->isAtLeastPHP('7.4')) {
            $prop->setTypeHint('array');
        }
        if ($this->request->getOptions()->getSingleLineSchema()) {
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

    private function generateDefaultsProperty(): PropertyGenerator
    {
        $prop = new PropertyGenerator(
            PropertyNames::DEFAULTS,
            $this->defaults,
            PropertyGenerator::FLAG_PRIVATE | PropertyGenerator::FLAG_STATIC
        );

        $prop->setDocBlock(new DocBlockGenerator(
            'Default values from the schema',
            null,
            [new GenericTag('var', 'array')],
        ));

        if ($this->request->isAtLeastPHP('7.4')) {
            $prop->setTypeHint('array');
        }

        if ($this->request->getOptions()->getSingleLineSchema()) {
            $prop->setSingleLineDefaultValue(true);
        }

        return $prop;
    }

    private function generateProvidedOptionalsProperty(): PropertyGenerator
    {
        $setVisibility = ($this->request->getNoGetters() && $this->request->getNoSetters())
            ? PropertyGenerator::FLAG_PUBLIC
            : PropertyGenerator::FLAG_PRIVATE;

        $prop = new PropertyGenerator(PropertyNames::OPTIONALS, [] , $setVisibility);
        $prop->setDefaultValue([]);
        $prop->setSingleLineDefaultValue(true);

        if ($this->request->isAtLeastPHP("7.4")) {
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
    private function generateSchemaProperties(): array
    {
        $propertyGenerators = [];

        $visibility = $this->request->getNoGetters()
            ? PropertyGenerator::FLAG_PUBLIC
            : PropertyGenerator::FLAG_PRIVATE;

        foreach ($this->schemaProperties as $schemaProp) {
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

            $docBlockTags = [new GenericTag("var", trim($schemaProp->typeAnnotation()))];
            if (PropertyQuery::isDeprecated($schemaProp)) {
                $docBlockTags[] = new GenericTag("deprecated");
            }

            $docBlock = new DocBlockGenerator(
                $schema["description"] ?? null,
                null,
                $docBlockTags
            );
            $docBlock->setWordWrap(false);

            $propGen->setDocBlock($docBlock);

            $typeHint = $schemaProp->typeHint($this->request->getTargetPHPVersion());
            if ($this->request->isAtLeastPHP("7.4") && $typeHint !== null) {
                $propGen->setTypeHint($typeHint);
            }

            // omit default `null` for every required field, unsless default is specified in the schema
            $propGen->omitDefaultValue(!$isOptional);

            $propertyGenerators[] = $propGen;
        }

        return $propertyGenerators;
    }
}