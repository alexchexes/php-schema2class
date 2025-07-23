<?php
namespace Helmich\Schema2Class\Generator\Property\Collection;

use Helmich\Schema2Class\Generator\Property\Interface\PropertyInterface;

/** 
 * Filter applied when iterating over a {@see PropertyCollection}.
 */
interface PropertyCollectionFilter
{
    function apply(PropertyInterface $property): bool;
}