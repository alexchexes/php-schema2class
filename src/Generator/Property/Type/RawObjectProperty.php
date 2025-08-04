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

    public function typeHint(): ?string
    {
        if (Semver::satisfies($this->request->getTargetPHPVersion(), '>=8.0')) {
            return 'array|object';
        }
        return null;
    }

    public function typeAssertionExpr(string $expr): string
    {
        return 'is_array(' . $expr . ') || is_object(' . $expr . ')';
    }

    public function outputMappingExpr(string $expr): string
    {
        return 'json_decode(json_encode(' . $expr . '), true)';
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        return 'json_decode(json_encode(' . $expr . '))';
    }

    public function needsValidation(): bool
    {
        // Schema places no restrictions beyond "object", so PHP type hints are
        // sufficient (or validation would be meaningless for PHP < 8).
        return false;
    }
}
