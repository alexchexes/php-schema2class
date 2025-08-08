<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Primitive;

use Composer\Semver\Semver;
use Helmich\Schema2Class\Generator\Property\Type\AbstractProperty;
use Helmich\Schema2Class\Util\SchemaKeywords;

/**
 * Represents plain string values.
 */
class StringProperty extends AbstractProperty
{
    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema["type"]) && $schema["type"] === "string";
    }

    public function typeAnnotation(): string
    {
        return "string";
    }

    /**
     * @return string|null
     */
    public function typeHint(): ?string
    {
        if (Semver::satisfies($this->request->getTargetPHPVersion(), "<7.0")) {
            return null;
        }

        return "string";
    }

    public function typeAssertionExpr(string $expr): string
    {
        return "is_string({$expr})";
    }

    public function needsValidation(): bool
    {
        if (!$this->request->isAtLeastPHP('7.0')) {
            return true;
        }
        return SchemaKeywords::hasAny($this->schema, SchemaKeywords::STRING_VALIDATION);
    }

}
