<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Property;

use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\PropertyGenerator;
use Laminas\Code\Generator\PropertyValueGenerator;
use Laminas\Code\Generator\TypeGenerator;

class ProvidedOptionalsPropertyFactory
{
    public function __construct(
      private GeneratorRequest $request,
    ) {}

    public function generate(): PropertyGenerator
    {
        $visibility = ($this->request->getNoGetters() && $this->request->getNoSetters())
            ? PropertyGenerator::FLAG_PUBLIC
            : PropertyGenerator::FLAG_PRIVATE;

        $propertyGen = new PropertyGenerator(
            name: PropertyNames::OPTIONALS,
            defaultValue: [],
            flags: $visibility
        );
        $propertyGen->getDefaultValue()?->setOutputMode(PropertyValueGenerator::OUTPUT_SINGLE_LINE);

        if ($this->request->isAtLeastPHP('7.4')) {
            $propertyGen->setType(TypeGenerator::fromTypeString('array'));
        }

        $propertyGen->setDocBlock(new DocBlockGenerator(
            "Map of optional nullable property names that were explicitly set",
            null,
            [new GenericTag('var', 'array<string,true>')]
        ));

        return $propertyGen;
    }
}
