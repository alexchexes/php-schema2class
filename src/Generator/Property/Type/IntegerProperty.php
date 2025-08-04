<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Composer\Semver\Semver;
use Helmich\Schema2Class\Util\SchemaKeywords;

/**
 * Primitive "integer" type
 */
class IntegerProperty extends AbstractProperty
{
    public static function canHandleSchema(array $schema): bool
    {
        if (!isset($schema["type"])) {
            return false;
        }
        return $schema["type"] === "integer"
            || $schema["type"] === "int"
            || (isset($schema["format"]) && $schema["type"] === "number" && $schema["format"] === "integer")
            || (isset($schema["format"]) && $schema["type"] === "number" && $schema["format"] === "int")
        ;
    }

    public function typeAnnotation(): string
    {
        return "int";
    }

    public function typeHint(): ?string
    {
        return $this->request->isAtLeastPHP('7.0') ? 'int' : null;
    }

    public function typeAssertionExpr(string $expr): string
    {
        return "is_int({$expr})";
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        if ($asserted) {
            return $expr;
        }

        return "(int){$expr}";
    }

    public function needsValidation(): bool
    {
        if (!$this->request->isAtLeastPHP('7.0')) {
            return true;
        }

        return SchemaKeywords::hasAny($this->schema, SchemaKeywords::NUMERIC_VALIDATION);
    }
}
