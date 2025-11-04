<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Primitive;

use Composer\Semver\Semver;
use Helmich\Schema2Class\Generator\Property\Type\AbstractProperty;
use Helmich\Schema2Class\Util\EnumUtils;
use Helmich\Schema2Class\Util\SchemaKeywords;

/**
 * Represents a boolean value.
 */
class BooleanProperty extends AbstractProperty
{
    public static function canHandleSchema(array $schema): bool
    {
        if (!isset($schema["type"])) {
            return false;
        }
        return $schema["type"] === "bool"
            || $schema["type"] === "boolean";
        ;
    }

    public function typeAnnotation(): string
    {
        if (isset($this->schema['enum'])) {
            return EnumUtils::typeAnnotation($this->schema['enum']);
        }

        return "bool";
    }

    public function typeHint(): ?string
    {
        if (isset($this->schema['enum'])) {
            return EnumUtils::typeHint($this->schema['enum'], $this->request->getTargetPHPVersion());
        }

        if (Semver::satisfies($this->request->getTargetPHPVersion(), "<7.0")) {
            return null;
        }

        return "bool";
    }

    public function typeAssertionExpr(string $expr): string
    {
        if (isset($this->schema['enum'])) {
            return EnumUtils::assertionExpr($this->schema['enum'], $expr);
        }

        return "is_bool({$expr})";
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        if ($asserted) {
            return $expr;
        }

        return "(bool){$expr}";
    }

    public function needsValidation(): bool
    {
        if (isset($this->schema['enum'])) {
            return true;
        }

        if (!$this->request->isAtLeastPHP('7.0')) {
            return true;
        }

        return SchemaKeywords::hasAny($this->schema, SchemaKeywords::BOOLEAN_VALIDATION);
    }

    public function inputMappingRequiresNullCheck(): bool
    {
        return true;
    }

    public function outputMappingRequiresNullCheck(): bool
    {
        return false;
    }
}
