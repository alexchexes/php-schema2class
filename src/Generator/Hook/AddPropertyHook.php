<?php

namespace Helmich\Schema2Class\Generator\Hook;

use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\PropertyGenerator;

/**
 * Hook that adds a preconstructed property to the generated class.
 * 
 * Registered via {@see GeneratorRequest::withProperty}.
 */
readonly class AddPropertyHook implements ClassCreatedHook
{
    public function __construct(private PropertyGenerator $property)
    {
    }

    function onClassCreated(string $className, ClassGenerator $class): void
    {
        $class->addPropertyFromGenerator($this->property);
    }
}