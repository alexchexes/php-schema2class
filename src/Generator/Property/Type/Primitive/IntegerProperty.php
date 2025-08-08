<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Primitive;

use Helmich\Schema2Class\Generator\Property\Type\AbstractProperty;
use Helmich\Schema2Class\Util\EnumUtils;
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
        if (isset($this->schema['enum'])) {
            return EnumUtils::typeAnnotation($this->schema['enum']);
        }
        return "int";
    }

    public function typeHint(): ?string
    {
        if (isset($this->schema['enum'])) {
            return EnumUtils::typeHint($this->schema['enum'], $this->request->getTargetPHPVersion());
        }
        return $this->request->isAtLeastPHP('7.0') ? 'int' : null;
    }

    public function typeAssertionExpr(string $expr): string
    {
        if (isset($this->schema['enum'])) {
            return EnumUtils::assertionExpr($this->schema['enum'], $expr);
        }
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
        if (isset($this->schema['enum'])) {
            return true;
        }
        if (!$this->request->isAtLeastPHP('7.0')) {
            return true;
        }
        return SchemaKeywords::hasAny($this->schema, SchemaKeywords::NUMERIC_VALIDATION);
    }

}
