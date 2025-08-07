<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Util\EnumUtils;
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

        // allow only primitive types in that list
        foreach ($schema['type'] as $t) {
            $t = $t === null ? 'null' : $t;
            if (!in_array($t, ['string', 'integer', 'int', 'number', 'boolean', 'null'], true)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Generates PHPDoc literal-union of all enum values
     */
    public function typeAnnotation(): string
    {
        return EnumUtils::typeAnnotation($this->schema['enum']);
    }

    public function typeHint(): ?string
    {
        return EnumUtils::typeHint($this->schema['enum'], $this->request->getTargetPHPVersion());
    }

    /**
     * Runtime checks & mappings (straight-through)
     */
    public function typeAssertionExpr(string $expr): string
    {
        return EnumUtils::assertionExpr($this->schema['enum'], $expr);
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

    public function needsValidation(): bool
    {
        // Enumeration of literal values cannot be enforced via type hints.
        return true;
    }
}
