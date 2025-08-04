<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Composer\Semver\Semver;
use Laminas\Code\Generator\PropertyValueGenerator;
use Laminas\Code\Generator\ValueGenerator;

/**
 * Represents a union of primitive literal values expressed using `enum`.
 */
class PrimitiveUnionEnumProperty extends AbstractProperty
{
    public static function canHandleSchema(array $schema): bool
    {
        // must have an enum and a _list_ of primitive types
        if (!isset($schema['enum'], $schema['type']) || !is_array($schema['type'])) {
            return false;
        }

        // allow only string/int/number/null in that list
        foreach ($schema['type'] as $t) {
            $t = $t === null ? 'null' : $t;
            if (!in_array($t, ['string', 'integer', 'int', 'number', 'null'], true)) {
                return false;
            }
        }
        return true;
    }

    public function needsValidation(): bool
    {
        return true;
    }

    /**
     * Generates PHPDoc literal-union of all enum values
     */
    public function typeAnnotation(): string
    {
        $quote = static fn(string $s): string => "'" . str_replace("'", "\\'", $s) . "'";

        $literals = array_map(
            static function (int|float|string|bool|null $v) use ($quote) {
                if ($v === null) {
                    return 'null';          // ← lower-case, unquoted
                }
                if (is_string($v)) {
                    return $quote($v);
                }
                return (string) $v;         // int | float
            },
            $this->schema['enum']
        );

        return implode('|', $literals);
    }

    /**
     * Generates real type-hint when PHP ≥ 8.0
     */
    public function typeHint(): ?string
    {
        if (!Semver::satisfies($this->request->getTargetPHPVersion(), ">=8.0")) {
            return null;
        }

        $primitives = array_unique(array_map(
            fn(string $t) => match ($t) {
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

    /**
     * Runtime checks & mappings (straight-through)
     */
    public function typeAssertionExpr(string $expr): string
    {
        $values = var_export($this->schema['enum'], true);
        return "in_array({$expr}, {$values}, true)";
    }

    public function inputAssertionExpr(string $expr): string
    {
        return $this->typeAssertionExpr($expr);
    }

    /**
     * We override the default only to keep ints unquoted.
     * Let Laminas pick the correct representation for everything else,
     * especially for `null` (so we don't end up with an empty string!).
     */
    public function formatValue(mixed $value): PropertyValueGenerator
    {
        if (is_int($value)) {
            return new PropertyValueGenerator($value, ValueGenerator::TYPE_CONSTANT);
        }

        return new PropertyValueGenerator($value);   // auto-detect ⇒ `null` stays null
    }
}
