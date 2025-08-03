<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Composer\Semver\Semver;

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
        if (isset($this->schema['enum']) && count($this->schema['enum']) === 1) {
            $v = $this->schema['enum'][0];
            if ($v === true || $v === false) {
                return $v ? 'true' : 'false';
            }
        }

        return "bool";
    }

    public function typeHint(): ?string
    {
        if (Semver::satisfies($this->request->getTargetPHPVersion(), "<7.0")) {
            return null;
        }

        return "bool";
    }

    public function typeAssertionExpr(string $expr): string
    {
        return "is_bool({$expr})";
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        if ($asserted) {
            return $expr;
        }

        return "(bool){$expr}";
    }

}
