<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property;

use Composer\Semver\Semver;
use Laminas\Code\Generator\PropertyValueGenerator;
use Laminas\Code\Generator\ValueGenerator;

class PrimitiveUnionEnumProperty extends AbstractProperty
{
    use TypeConvert;

    /** ──────── 1. Tell the builder when we can handle a schema ──────── */
    public static function canHandleSchema(array $schema): bool
    {
        // must have an enum and a _list_ of primitive types
        if (!isset($schema['enum'], $schema['type']) || !is_array($schema['type'])) {
            return false;
        }

        // allow only string/int/number/null in that list
        foreach ($schema['type'] as $t) {
            if (!in_array($t, ['string', 'integer', 'int', 'number', 'null'], true)) {
                return false;
            }
        }
        return true;
    }

    /** ──────── 2. PHPDoc literal-union of all enum values ───────────── */
    public function typeAnnotation(): string
    {
        $quote = static fn(string $s): string => "'" . str_replace("'", "\\'", $s) . "'";

        $literals = array_map(static function ($v) use ($quote) {
            if ($v === null) {
                return 'null';          // ← lower-case, unquoted
            }
            if (is_string($v)) {
                return $quote($v);
            }
            return (string) $v;         // int | float
        }, $this->schema['enum']);

        return implode('|', $literals);
    }

    /** ──────── 3. Real type-hint when PHP ≥ 8.0, else none ─────────── */
    public function typeHint(string $phpVersion): ?string
    {
        if (!Semver::satisfies($phpVersion, ">=8.0")) {
            return null;                 // unions not available
        }

        $primitives = array_unique(array_map(
            fn($t) => match ($t) {
                'string'   => 'string',
                'integer',
                'int',
                'number'   => 'int',     // enum values are integral here
                'null'     => 'null',
                default    => 'mixed',   // should never happen
            },
            $this->schema['type']
        ));

        return implode('|', $primitives);
    }

    /** ──────── 4. Runtime checks & mappings (straight-through) ───────── */
    public function generateTypeAssertionExpr(string $expr): string
    {
        $values = var_export($this->schema['enum'], true);
        return "in_array({$expr}, {$values}, true)";
    }

    public function generateInputAssertionExpr(string $expr): string
    {
        return $this->generateTypeAssertionExpr($expr);
    }

    /**
     * We override the default only to keep ints unquoted.
     * Let Laminas pick the correct representation for everything else,
     * especially for `null` (so we don’t end up with an empty string!).
     */
    public function formatValue(mixed $value): PropertyValueGenerator
    {
        if (is_int($value)) {
            return new PropertyValueGenerator($value, ValueGenerator::TYPE_CONSTANT);
        }

        return new PropertyValueGenerator($value);   // auto-detect ⇒ `null` stays null
    }
}
