<?php
namespace Helmich\Schema2Class\Generator\PropertyCollection;

use Helmich\Schema2Class\Generator\Property\PropertyInterface;

/** 
 * Filter applied when iterating over a {@see PropertyCollection}.
 */
interface PropertyCollectionFilter
{
    function apply(PropertyInterface $property): bool;
}