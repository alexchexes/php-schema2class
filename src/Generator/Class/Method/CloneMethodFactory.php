<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Laminas\Code\Generator\MethodGenerator;

class CloneMethodFactory
{
    public function __construct(
        private PropertyCollection $schemaProperties,
    ) {}

    public function generateCloneMethod(): ?MethodGenerator
    {
        $expressions = [];

        foreach ($this->schemaProperties as $property) {
            $expressions[] = $property->cloneProperty();
        }

        $expressions = array_values(array_filter($expressions));

        if ($expressions === []) {
            return null;
        }
        
        $body = join("\n", $expressions);

        return new MethodGenerator(
            name: '__clone',
            parameters: [],
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $body,
        );
    }
}
