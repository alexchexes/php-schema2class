<?php
declare(strict_types=1);

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
        if (isset($schema["deprecated"]) && $schema["deprecated"]) {
            return true;
        }

        if (isset($schema["allOf"])) {
            foreach ($schema["allOf"] as $subSchema) {
                if (isset($subSchema["deprecated"]) && $subSchema["deprecated"]) {
                    return true;
                }
            }
        }

        return false;
    }
}