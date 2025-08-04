<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

final class SchemaKeywords
{
    /**
     * List of schema keywords that imply validation beyond simple PHP type hints.
     * Only a subset of validation keywords that may appear on property level.
     *
     * @var string[]
     */
    public const VALIDATION_KEYWORDS = [
        'enum',
        'const',
        'minimum',
        'maximum',
        'exclusiveMinimum',
        'exclusiveMaximum',
        'multipleOf',
        'minLength',
        'maxLength',
        'pattern',
        'format',
        'minItems',
        'maxItems',
        'uniqueItems',
        'contains',
        'minProperties',
        'maxProperties',
        'required',
        'propertyNames',
        'additionalProperties',
        'items',
        'patternProperties',
        'dependencies',
        'dependentRequired',
        'dependentSchemas',
        'maxContains',
        'minContains',
        'not',
        'if',
        'then',
        'else',
    ];

    /**
     * Detects if the given schema contains keywords that require runtime validation.
     *
     * @param array $schema  Schema fragment to inspect
     * @param array<string> $ignore Keys to ignore when checking
     */
    public static function hasValidationKeywords(array $schema, array $ignore = []): bool
    {
        foreach (self::VALIDATION_KEYWORDS as $keyword) {
            if (in_array($keyword, $ignore, true)) {
                continue;
            }
            if (!array_key_exists($keyword, $schema)) {
                continue;
            }
            if (in_array($keyword, ['items', 'additionalProperties'], true)) {
                if (is_array($schema[$keyword])) {
                    return true;
                }
                continue;
            }
            return true;
        }
        return false;
    }
}
