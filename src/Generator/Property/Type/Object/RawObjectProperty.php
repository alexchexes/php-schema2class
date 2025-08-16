<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Object;

use Composer\Semver\Semver;
use Helmich\Schema2Class\Generator\Expression\TernaryGenerator;
use Helmich\Schema2Class\Generator\Property\Type\AbstractProperty;

/**
 * Represents an object type without defined properties.
 */
class RawObjectProperty extends AbstractProperty
{
    private bool $allowArrays = true;

    public static function canHandleSchema(array $schema): bool
    {
        $isObject = (isset($schema['type']) && $schema['type'] === 'object');

        $hasProps = isset($schema['properties'])
            && is_array($schema['properties'])
            && count($schema['properties']) > 0;

        $hasAdditional = isset($schema['additionalProperties']);

        return $isObject && !$hasProps && !$hasAdditional;
    }

    public function typeAnnotation(): string
    {
        return $this->allowArrays ? 'array|object' : 'object';
    }

    public function typeHint(): ?string
    {
        if (Semver::satisfies($this->request->getTargetPHPVersion(), '>=8.0')) {
            return $this->allowArrays ? 'array|object' : 'object';
        }
        return null;
    }

    public function typeAssertionExpr(string $expr): string
    {
        return $this->allowArrays
            ? 'is_array(' . $expr . ') || is_object(' . $expr . ')'
            : 'is_object(' . $expr . ')';
    }

    public function outputMappingExpr(string $expr): string
    {
        return 'json_decode(json_encode(' . $expr . '), true)';
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        return 'json_decode(json_encode(' . $expr . '))';
    }

    public function cloneExpr(string $expr): string
    {
        // TODO: in fact, when such object stored as array, it can contain nested objects (that's
        // why we deep-clone it in that case too), but they will be converted to arrays as well.
        // Gotta rethink raw objects handling maintaining both consistency and convenience.
        if ($this->allowArrays) {
            return "json_decode(json_encode({$expr}), is_array({$expr}))";
        }
        return "json_decode(json_encode({$expr}))";
    }

    public function needsValidation(): bool
    {
        // Schema places no restrictions beyond "object", so PHP type hints are
        // sufficient (or validation would be meaningless for PHP < 8).
        return false;
    }

    public function allowArrays(bool $allow): void
    {
        $this->allowArrays = $allow;
    }
}
