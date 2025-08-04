<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Property;

use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlockGenerator;

/**
 * Factory for creating "$schema" class property that holds the JSON-schema as array used for validation
 */
class DefaultsPropertyFactory
{
    public function __construct(
      private GeneratorRequest $request,
      private array $defaults,
    ) {}

    public function generateDefaultsProperty(): PropertyGenerator
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
}