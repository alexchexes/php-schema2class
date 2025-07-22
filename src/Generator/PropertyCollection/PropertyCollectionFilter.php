<?php
namespace Helmich\Schema2Class\Generator\PropertyCollection;

use Helmich\Schema2Class\Generator\Property\PropertyInterface;

interface PropertyCollectionFilter
{
    function apply(PropertyInterface $property): bool;
}