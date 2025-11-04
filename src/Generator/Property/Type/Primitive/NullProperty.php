<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Primitive;

use Helmich\Schema2Class\Generator\Property\Type\AbstractProperty;
use Laminas\Code\Generator\PropertyValueGenerator;
use Laminas\Code\Generator\ValueGenerator;

/**
 * Represents a property whose only type is "null".
 */
class NullProperty extends AbstractProperty
{
    public static function canHandleSchema(array $schema): bool
    {
        // we handle  {"type":"null"}  and nothing else
        return ($schema['type'] ?? null) === 'null';
    }

    public function typeAnnotation(): string
    {
        return 'null';
    }

    public function typeHint(): ?string
    {
        // Stand-alone null type hint is valid since PHP 8.2 but we assume it is better for user
        // to not enforce it in runtime.
        return null;
    }

    public function typeAssertionExpr(string $expr): string
    {
        return "{$expr} === null";
    }
    
    public function inputAssertionExpr(string $expr): string
    {
        return "{$expr} === null";
    }

    public function formatValue(mixed $value): PropertyValueGenerator
    {
        // `null` is the only possible literal value.
        return new PropertyValueGenerator(null, ValueGenerator::TYPE_NULL);
    }

    public function needsValidation(): bool
    {
        // We don't use type hints for this type, including setters,
        // so validation is required to enforce the value.
        return true;
    }

    public function inputMappingRequiresNullCheck(): bool
    {
        return false;
    }

    public function outputMappingRequiresNullCheck(): bool
    {
        return false;
    }
}
