<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Decorator;

use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;

/** 
 * Base interface for wrappers that add behaviour around another property.
 */
interface PropertyDecoratorInterface extends PropertyInterface
{
    public function unwrap(): PropertyInterface;
}
