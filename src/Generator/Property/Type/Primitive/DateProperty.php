<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Primitive;

use Helmich\Schema2Class\Generator\Property\Type\AbstractProperty;

/**
 * Represents schema property of type `"string"` with `"date-time"` format as `\DateTime` objects in PHP.
 */
class DateProperty extends AbstractProperty
{
    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema["type"])
            && isset($schema["format"])
            && $schema["type"] === "string"
            && $schema["format"] === "date-time";
    }

    public function typeAnnotation(): string
    {
        return "\\DateTime";
    }

    public function typeHint(): ?string
    {
        return "\\DateTime";
    }

    public function typeAssertionExpr(string $expr): string
    {
        return "{$expr} instanceof \\DateTime";
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        if ($asserted) {
            return $expr;
        }

        return "new \\DateTime({$expr})";
    }

    public function outputMappingExpr(string $expr): string
    {
        return "{$expr}->format(\\DateTime::ATOM)";
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        return $this->outputMappingExpr($expr);
    }

    public function cloneExpr(string $expr): string
    {
        return "clone {$expr}";
    }

    public function needsValidation(): bool
    {
        // DateTime objects are validated via their type hint.
        return false;
    }

    public function inputMappingRequiresNullCheck(): bool
    {
        return true;
    }

    public function outputMappingRequiresNullCheck(): bool
    {
        return true;
    }
}
