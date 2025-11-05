<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Laminas\Code\Generator\MethodGenerator;

class CloneMethodFactory
{
    public function __construct(
        private PropertyCollection $schemaProperties,
        private bool $additionalsAllowed,
    ) {}

    public function generate(): ?MethodGenerator
    {
        $bodyParts = [];
        
        if ($this->additionalsAllowed) {
            $ADDITIONAL_PROPS = PropertyNames::ADDITIONAL_PROPS;
            $bodyParts[] = "\$this->{$ADDITIONAL_PROPS} = json_decode(json_encode(\$this->{$ADDITIONAL_PROPS}));\n";
        }

        foreach ($this->schemaProperties as $property) {
            $bodyParts[] = $property->cloneAssignment();
        }

        $bodyParts = array_values(array_filter($bodyParts));

        if ($bodyParts === []) {
            return null;
        }
        
        $body = join("\n", $bodyParts);

        return new MethodGenerator(
            name: '__clone',
            parameters: [],
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $body,
        );
    }
}
