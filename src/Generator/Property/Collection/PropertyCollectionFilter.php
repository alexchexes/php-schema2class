<?php
namespace Helmich\Schema2Class\Generator\Property\Collection;

use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;

/** 
 * Filter applied when iterating over a {@see PropertyCollection}.
 */
interface PropertyCollectionFilter
{
    function apply(PropertyInterface $property): bool;
}