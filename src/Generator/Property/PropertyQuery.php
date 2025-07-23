<?php
namespace Helmich\Schema2Class\Generator\Property;

use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;

/**
 * Helpers for quickly obtaining information about property metadata.
 */
class PropertyQuery
{
    /** 
     * Checks whether the given property is marked as deprecated in its schema.
     */
    public static function isDeprecated(PropertyInterface $property): bool
    {
        $schema = $property->schema();
        return isset($schema["deprecated"]) && $schema["deprecated"];
    }
}