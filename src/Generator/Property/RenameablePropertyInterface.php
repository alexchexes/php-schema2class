<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property;

/** 
 * Marker for properties where the generated name can be changed during generation
 */
interface RenameablePropertyInterface
{
    public function setName(string $name): void;
}

