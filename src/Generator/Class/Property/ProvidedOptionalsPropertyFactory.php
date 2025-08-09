<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Property;

use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\PropertyGenerator;
use Laminas\Code\Generator\PropertyValueGenerator;
use Laminas\Code\Generator\TypeGenerator;

/** 
 * Factory for generating `$_providedOptionals` class property that is used to keep
 * track on optional nullable schema properties that were explicitly set (to distinguish
 * absent from `null`)
 */
class ProvidedOptionalsPropertyFactory
{
    public function __construct(
      private GeneratorRequest $request,
      private PropertyCollection $schemaProperties,
    ) {}

    public function generate(): ?PropertyGenerator
    {
        if (!$this->schemaProperties->hasOptionalNullable()) {
            return null;
        }

        $visibility = ($this->request->getNoGetters() && $this->request->getNoSetters())
            ? PropertyGenerator::FLAG_PUBLIC
            : PropertyGenerator::FLAG_PRIVATE;

        $propertyGen = new PropertyGenerator(
            name: PropertyNames::PROVIDED_OPTIONALS,
            defaultValue: [],
            flags: $visibility
        );

        if ($this->request->isAtLeastPHP('7.4')) {
            $propertyGen->setType(TypeGenerator::fromTypeString('array'));
        }

        $docBlock = new DocBlockGenerator(
            shortDescription: "Map of optional nullable property names that were explicitly set",
            longDescription: null,
            tags: [new GenericTag('var', 'array<string,true>')]
        );

        $propertyGen->setDocBlock($docBlock);
        
        $propertyGen->getDefaultValue()?->setOutputMode(PropertyValueGenerator::OUTPUT_SINGLE_LINE);

        return $propertyGen;
    }
}
