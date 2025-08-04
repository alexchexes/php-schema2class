<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Laminas\Code\Generator\PropertyValueGenerator;
use Laminas\Code\Generator\ValueGenerator;

/**
 * Represents a property whose only type is "null".
 */
class NullProperty extends AbstractProperty
{
    /* ---------------------------------------------------------------------
     * (1)  Tell the builder we handle  {"type":"null"}  and nothing else
     * ------------------------------------------------------------------- */
    public static function canHandleSchema(array $schema): bool
    {
        return ($schema['type'] ?? null) === 'null';
    }

    public function needsValidation(): bool
    {
        return true;
    }

    /* ---------------------------------------------------------------------
     * (2)  PHPDoc says simply   null
     * ------------------------------------------------------------------- */
    public function typeAnnotation(): string
    {
        return 'null';
    }

    /* ---------------------------------------------------------------------
     * (3)  Stand-alone  null  is *not* a legal PHP type-hint (PHP 8.1
     *     still requires it to appear in a union), so we return null
     *     to omit the hint for every PHP version.
     * ------------------------------------------------------------------- */
    public function typeHint(): ?string
    {
        return null;
    }

    /* ---------------------------------------------------------------------
     * (4)  Assertions, mappings, cloning – all no-ops for a scalar null
     * ------------------------------------------------------------------- */
    public function typeAssertionExpr(string $expr): string  { return "{$expr} === null"; }
    public function inputAssertionExpr(string $expr): string { return "{$expr} === null"; }
    public function inputMappingExpr(string $expr, bool $asserted = false): string { return $expr; }
    public function outputMappingExpr(string $expr): string { return $expr; }
    public function outputMappingExprStdClass(string $expr): string { return $expr; }
    public function cloneExpr(string $expr): string { return $expr; }

    /* ---------------------------------------------------------------------
     * (5)  `null` is the only possible literal value.
     * ------------------------------------------------------------------- */
    public function formatValue(mixed $value): PropertyValueGenerator
    {
        return new PropertyValueGenerator(null, ValueGenerator::TYPE_NULL);
    }
}
