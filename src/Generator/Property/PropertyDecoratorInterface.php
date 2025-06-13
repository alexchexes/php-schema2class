<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property;

interface PropertyDecoratorInterface extends PropertyInterface
{
    public function unwrap(): PropertyInterface;
}
