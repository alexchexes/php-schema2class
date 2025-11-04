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
        if ($this->request->isAtLeastPHP('8.0')) {
            return 'mixed';
        }
        return null;
    }

    public function typeAssertionExpr(string $expr): string
    {
        return "true";
    }

    public function needsValidation(): bool
    {
        return false;
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
