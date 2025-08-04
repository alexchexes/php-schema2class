<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeInterface;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeEnum;

/**
 * Property that refers to a generated PHP artifact (class, Enum) via `$ref`.
 */
class ReferenceProperty extends AbstractProperty
{
    private ReferencedTypeInterface $refType;

    public function __construct(string $key, array $schema, GeneratorRequest $request)
    {
        parent::__construct($key, $schema, $request);
        $this->refType = $request->lookupReference($schema['$ref']);
    }

    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema['$ref']);
    }

    public function typeAnnotation(): string
    {
        return $this->refType->typeAnnotation();
    }

    public function typeHint(): ?string
    {
        return $this->refType->typeHint();
    }

    public function typeAssertionExpr(string $expr): string
    {
        return $this->refType->typeAssertionExpr($expr);
    }

    public function inputAssertionExpr(string $expr): string
    {
        return $this->refType->inputAssertionExpr($expr);
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        return $this->refType->inputMappingExpr($expr);
    }

    public function outputMappingExpr(string $expr): string
    {
        return $this->refType->outputMappingExpr($expr);
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        return $this->refType->outputMappingExprStdClass($expr);
    }

    public function needsValidation(): bool
    {
        if ($this->refType instanceof ReferencedTypeEnum) {
            // Native enums are enforced by the type system, but when enums are
            // represented as scalars we need explicit validation.
            return !$this->refType->usesNativeEnum();
        }

        // Referenced classes are type hinted.
        return false;
    }

}