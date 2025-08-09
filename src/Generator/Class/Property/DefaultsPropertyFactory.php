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

/**
 * Factory for creating "$_defaults" class property that stores default values for schema properties
 */
class DefaultsPropertyFactory
{
    public function __construct(
      private GeneratorRequest $request,
      private array $defaults,
    ) {}

    public function generate(): ?PropertyGenerator
    {
        if (!$this->defaults) {
            return null;
        }

        $prop = new PropertyGenerator(
            name: PropertyNames::DEFAULTS,
            defaultValue: $this->defaults,
            flags: PropertyGenerator::FLAG_PRIVATE | PropertyGenerator::FLAG_STATIC
        );

        $docBlock = new DocBlockGenerator('Default values from the schema');

        if ($this->request->isAtLeastPHP('7.4')) {
            $prop->setType(TypeGenerator::fromTypeString('array'));
        } else {
            $docBlock->setTag(new GenericTag('var', 'array'));
        }

        $prop->setDocBlock($docBlock);

        if ($this->request->getOptions()->getSingleLineSchema()) {
            $prop->getDefaultValue()?->setOutputMode(PropertyValueGenerator::OUTPUT_SINGLE_LINE);
        }

        return $prop;
    }
}