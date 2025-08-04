<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Composer\Semver\Semver;
use Helmich\Schema2Class\Util\EnumUtils;

/** 
 * Represents schema property of type "number".
 * Allows `int|float` in PHP, and uses {@see EnumUtils} when "enum" restriction exists in the schema.
 */
class NumberProperty extends AbstractProperty
{
    public static function canHandleSchema(array $schema): bool
    {
        if (!isset($schema["type"])) {
            return false;
        }
        return $schema["type"] === "number";
    }

    public function typeAnnotation(): string
    {
        if (isset($this->schema['enum'])) {
            return EnumUtils::typeAnnotation($this->schema['enum']);
        }

        return 'int|float';
    }

    public function typeHint(): ?string
    {
        $phpVersion = $this->request->getTargetPHPVersion();
        
        if (isset($this->schema['enum'])) {
            return EnumUtils::typeHint($this->schema['enum'], $phpVersion);
        }

        if (Semver::satisfies($phpVersion, "<8.0")) {
            return null;
        }

        return 'int|float';
    }

    public function typeAssertionExpr(string $expr): string
    {
        if (isset($this->schema['enum'])) {
            return EnumUtils::assertionExpr($this->schema['enum'], $expr);
        }

        return "is_int({$expr}) || is_float({$expr})";
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        if ($asserted) {
            return $expr;
        }

        return "(str_contains((string){$expr}, '.') ? (float){$expr} : (int){$expr})";
    }

    public function needsValidation(): bool
    {
        if (!$this->request->isAtLeastPHP('8.0')) {
            return true;
        }
        return parent::needsValidation();
    }
}
