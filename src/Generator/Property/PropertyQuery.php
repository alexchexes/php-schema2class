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
        return isset($schema["deprecated"]) && $schema["deprecated"];
    }

    /**
     * Determines if the given property's schema contains validation constraints
     * that go beyond a simple PHP type declaration.
     *
     * This is used to decide whether setter methods need to perform runtime
     * validation in addition to the type hints. Only schema keywords that
     * actually impose additional restrictions are considered. Metadata like
     * "type" or "description" is ignored. Combinators are inspected
     * recursively.
     */
    public static function hasConstraints(PropertyInterface $property): bool
    {
        return self::schemaHasConstraints($property->schema());
    }

    /**
     * Recursively checks whether a schema contains validation keywords.
     *
     * @param array $schema
     */
    private static function schemaHasConstraints(array $schema): bool
    {
        // Keywords that do not impose validation requirements on their own.
        static $ignored = [
            'type' => true,
            'title' => true,
            'description' => true,
            'default' => true,
            'examples' => true,
            '$id' => true,
            '$schema' => true,
            '$ref' => true,
            'deprecated' => true,
        ];

        foreach ($schema as $key => $value) {
            if (isset($ignored[$key])) {
                continue;
            }

            // Combinators: inspect nested schemas and only treat them as
            // constraints when the sub schemas impose their own restrictions.
            if (in_array($key, ['oneOf', 'anyOf', 'allOf'], true) && is_array($value)) {
                foreach ($value as $sub) {
                    if (is_array($sub) && self::schemaHasConstraints($sub)) {
                        return true;
                    }
                }
                // No additional constraints detected in sub schemas.
                continue;
            }

            // Any other keyword is treated as a constraint.
            return true;
        }

        return false;
    }
}