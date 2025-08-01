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
        $clones = [];

        foreach ($this->schemaProperties as $property) {
            $c = $property->cloneProperty();

            if ($c !== null) {
                $clones[] = $c;
            }
        }

        if ($clones === []) {
            return null;
        }

        return new MethodGenerator(
            name: '__clone',
            parameters: [],
            flags: MethodGenerator::FLAG_PUBLIC,
            body: join("\n", $clones),
        );
    }
}
