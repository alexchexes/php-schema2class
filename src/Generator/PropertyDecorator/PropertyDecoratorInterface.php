<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\PropertyDecorator;

use Helmich\Schema2Class\Generator\Property\PropertyInterface;

interface PropertyDecoratorInterface extends PropertyInterface
{
    public function unwrap(): PropertyInterface;
}
