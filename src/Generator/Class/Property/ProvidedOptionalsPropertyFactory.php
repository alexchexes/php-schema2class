<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Property;

use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlockGenerator;

class ProvidedOptionalsPropertyFactory
{
    public function __construct(
      private GeneratorRequest $request,
    ) {}

    public function generateProvidedOptionalsProperty(): PropertyGenerator
    {
        $visibility = ($this->request->getNoGetters() && $this->request->getNoSetters())
            ? PropertyGenerator::FLAG_PUBLIC
            : PropertyGenerator::FLAG_PRIVATE;

        $propertyGen = new PropertyGenerator(PropertyNames::OPTIONALS, [] , $visibility);
        $propertyGen->setDefaultValue([]);
        $propertyGen->setSingleLineDefaultValue(true);

        if ($this->request->isAtLeastPHP("7.4")) {
            $propertyGen->setTypeHint("array");
        }

        $propertyGen->setDocBlock(new DocBlockGenerator(
            "Map of optional nullable property names that were explicitly set",
            null,
            [new GenericTag('var', 'array<string,true>')]
        ));

        return $propertyGen;
    }
}
