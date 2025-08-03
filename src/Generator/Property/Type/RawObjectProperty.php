<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Composer\Semver\Semver;

/**
 * Represents an object type without defined properties.
 */
class RawObjectProperty extends AbstractProperty
{
    public static function canHandleSchema(array $schema): bool
    {
        $isObject = (isset($schema['type']) && $schema['type'] === 'object');
        $hasProps = isset($schema['properties']) && is_array($schema['properties']) && count($schema['properties']) > 0;
        $hasAdditional = isset($schema['additionalProperties']);

        return $isObject && !$hasProps && !$hasAdditional;
    }

    public function typeAnnotation(): string
    {
        return 'array|object';
    }

    public function typeHint(string $phpVersion): ?string
    {
        if (Semver::satisfies($phpVersion, '>=8.0')) {
            return 'array|object';
        }
        return null;
    }

    public function genTypeAssertionExpr(string $expr): string
    {
        return 'is_array(' . $expr . ') || is_object(' . $expr . ')';
    }

    public function genOutputMappingExpr(string $expr): string
    {
        return 'json_decode(json_encode(' . $expr . '), true)';
    }

    public function genOutputMappingExprStdClass(string $expr): string
    {
        return 'json_decode(json_encode(' . $expr . '))';
    }
}
