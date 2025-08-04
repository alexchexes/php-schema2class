<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Composer\Semver\Semver;

/**
 * Represents plain string values.
 */
class StringProperty extends AbstractProperty
{
    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema["type"]) && $schema["type"] === "string";
    }

    public function needsValidation(): bool
    {
        if (parent::needsValidation()) {
            return true;
        }

        return \Helmich\Schema2Class\Util\SchemaKeywords::hasAny(
            $this->schema,
            \Helmich\Schema2Class\Util\SchemaKeywords::STRING_VALIDATION
        );
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

}
