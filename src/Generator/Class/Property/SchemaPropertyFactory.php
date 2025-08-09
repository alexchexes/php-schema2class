<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Property;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\PropertyQuery;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Util\StringUtils;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\PropertyGenerator;
use Laminas\Code\Generator\TypeGenerator;

/** 
 * Factory for creating a class property from {@see PropertyInterface} 
 * representation of the schema property
 */
class SchemaPropertyFactory
{
    public function __construct(
      private GeneratorRequest $request,
      private PropertyCollection $schemaProperties,
    ) {}

    /**
     * @return PropertyGenerator[]
     */
    public function generateAll(): array
    {
        $propertyGenerators = [];
        foreach ($this->schemaProperties as $property) {
            $propertyGenerators[] = $this->generateProperty($property);
        }
        return $propertyGenerators;
    }
    
    private function generateProperty(PropertyInterface $property): PropertyGenerator
    {
        $visibility = $this->request->getNoGetters()
            ? PropertyGenerator::FLAG_PUBLIC
            : PropertyGenerator::FLAG_PRIVATE;

        $propGen = new PropertyGenerator(
            name: $property->propName(),
            defaultValue: $property->formatValue(null),
            flags: $visibility
        );

        $typeHint = $property->typeHint();
        if ($this->request->isAtLeastPHP('7.4') && $typeHint !== null) {
            $propGen->setType(TypeGenerator::fromTypeString($typeHint));
        }

        $docBlock = $this->buildDockBlock($property);
        if ($docBlock) {
            $propGen->setDocBlock($docBlock);
        }

        $isOptional = false;
        if ($property instanceof OptionalPropertyDecorator) {
            $isOptional = true;
        }
        // omit default `null` for every required field, unsless default is specified in the schema
        $propGen->omitDefaultValue(!$isOptional);

        return $propGen;
    }

    private function buildDockBlock(PropertyInterface $property): ?DocBlockGenerator
    {
        $docBlock = null;

        $typeHint = $property->typeHint();
        $annotType = $property->typeAnnotation();

        if ($annotType === '') {
            $annotType = null;
        } elseif ($typeHint !== null && StringUtils::isAnnotationSameAsTypeHint($annotType, $typeHint)) {
            $annotType = null;
        }

        $tags = [];

        if ($annotType) {
            $tags[] = new GenericTag("var", $annotType);
        }

        if (PropertyQuery::isDeprecated($property)) {
            $tags[] = new GenericTag("deprecated");
        }

        $description = null;
        if ($this->request->getNoGetters()) {
            $description = $property->description();
        }

        if ($tags || $description) {
            $docBlock = new DocBlockGenerator(
                shortDescription: $description,
                longDescription: null,
                tags: $tags,
            );
            $docBlock->setWordWrap(false);
        }

        return $docBlock;
    }
}