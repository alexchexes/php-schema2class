<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

final class SchemaKeywords
{
    /**
     * Keywords that influence validation for string schemas.
     * @var string[]
     */
    public const STRING_VALIDATION = [
        'minLength',
        'maxLength',
        'pattern',
        'format',
        'enum',
    ];

    /**
     * Keywords that influence validation for numeric schemas (integer/number).
     * @var string[]
     */
    public const NUMERIC_VALIDATION = [
        'minimum',
        'maximum',
        'exclusiveMinimum',
        'exclusiveMaximum',
        'multipleOf',
        'enum',
    ];

    /**
     * Keywords that influence validation for array schemas.
     * @var string[]
     */
    public const ARRAY_VALIDATION = [
        'items',
        'additionalItems',
        'minItems',
        'maxItems',
        'uniqueItems',
        'contains',
    ];

    /**
     * Helper method to check if any of the given keywords exist in the schema.
     *
     * @param array $schema
     * @param array $keywords
     */
    public static function hasAny(array $schema, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (array_key_exists($keyword, $schema)) {
                return true;
            }
        }
        return false;
    }
}
