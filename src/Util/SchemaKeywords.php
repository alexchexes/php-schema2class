<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

final class SchemaKeywords
{
    /**
     * Keywords that influence validation for string schemas.
     */
    public const STRING_VALIDATION = [
        'minLength',
        'maxLength',
        'pattern',
        'format',
        'enum',
        'const',
    ];
    
    /**
     * Keywords that influence validation for numeric schemas (integer/number).
     */
    public const NUMERIC_VALIDATION = [
        'minimum',
        'maximum',
        'exclusiveMinimum',
        'exclusiveMaximum',
        'multipleOf',
        'enum',
        'const',
    ];
    
    /**
     * Keywords that influence validation for array schemas.
     */
    public const ARRAY_VALIDATION = [
        'items',
        'additionalProperties',
        'minItems',
        'maxItems',
        'uniqueItems',
        'contains',
        'enum',
        'const',
    ];

    public const BOOLEAN = [
        'enum',
        'const',
    ];

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
