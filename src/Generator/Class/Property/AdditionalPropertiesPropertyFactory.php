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
 * Factory for creating "$_additionalProperties" class property that holds the JSON-schema as array used for validation
 */
class AdditionalPropertiesPropertyFactory
{
    public function __construct(
      private GeneratorRequest $request,
    ) {}

    public function generate(): PropertyGenerator
    {
        $propGen = new PropertyGenerator(
            name: PropertyNames::ADDITIONAL_PROPS,
            flags: PropertyGenerator::FLAG_PRIVATE,
        );
        $propGen->omitDefaultValue(true);

        $docBlock = new DocBlockGenerator('Map of name/value pairs for properties not specified in the schema.');

        $typeHint = '\stdClass';
        if ($this->request->isAtLeastPHP('7.4')) {
            $propGen->setType(TypeGenerator::fromTypeString($typeHint));
        } else {
            $docBlock->setTags([new GenericTag('var', $typeHint)]);
        }

        $propGen->setDocBlock($docBlock);

        return $propGen;
    }
}
