<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Property;

use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\PropertyGenerator;
use Laminas\Code\Generator\TypeGenerator;

class NamesMapPropertyFactory
{
    public function __construct(
        private GeneratorRequest $request,
        private PropertyCollection $schemaProperties,
        private bool $additionalsAllowed,
    ) {
    }

    public function generate(): ?PropertyGenerator
    {
        if (!$this->schemaProperties->hasOptionalNullable() && !$this->additionalsAllowed) {
            return null;
        }

        $mapArray = [];
        foreach ($this->schemaProperties as $property) {
            $mapArray[$property->key()] = $property->propName();
        }

        $prop = new PropertyGenerator(
            name: PropertyNames::NAMES_MAP,
            defaultValue: $mapArray,
            flags: PropertyGenerator::FLAG_PRIVATE | PropertyGenerator::FLAG_STATIC
        );

        $docBlock = new DocBlockGenerator("Mapping of schema property names to this class's property names.");

        if ($this->request->isAtLeastPHP('7.4')) {
            $prop->setType(TypeGenerator::fromTypeString('array'));
        } else {
            $docBlock->setTag(new GenericTag('var', 'array'));
        }

        $prop->setDocBlock($docBlock);

        return $prop;
    }
}
