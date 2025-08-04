<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

final class SchemaKeywords
{
    /**
     * Keywords that impose additional validation on string types.
     * @var string[]
     */
    public const STRING_VALIDATION = ['minLength', 'maxLength', 'pattern', 'format', 'enum', 'const'];

    /**
     * Keywords that impose validation on numeric types (number/integer).
     * @var string[]
     */
    public const NUMERIC_VALIDATION = [
        'minimum', 'maximum', 'exclusiveMinimum', 'exclusiveMaximum', 'multipleOf', 'enum', 'const'
    ];

    /**
     * Keywords that impose validation on boolean types.
     * @var string[]
     */
    public const BOOLEAN_VALIDATION = ['enum', 'const'];

    /**
     * Keywords that impose validation on array types.
     * `items`/`additionalProperties` describe typed arrays and therefore require
     * validation of their elements.
     * @var string[]
     */
    public const ARRAY_VALIDATION = ['minItems', 'maxItems', 'uniqueItems', 'items', 'additionalProperties'];

    /**
     * Checks whether any of the given keywords is present in the schema.
     */
    public static function hasAny(array $schema, array $keywords): bool
    {
        foreach ($keywords as $k) {
            if (array_key_exists($k, $schema)) {
                return true;
            }
        }
        return false;
    }
}
