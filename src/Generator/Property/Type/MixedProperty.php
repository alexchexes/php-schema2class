<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

/**
 * Fallback property used when no other property class claims it can handle the given schema.
 */
class MixedProperty extends AbstractProperty
{
    public static function canHandleSchema(array $schema): bool
    {
        return true;
    }

    public function typeAnnotation(): string
    {
        return "mixed";
    }

    public function typeHint(): ?string
    {
        return null;
    }

    public function typeAssertionExpr(string $expr): string
    {
        return "true";
    }

}
