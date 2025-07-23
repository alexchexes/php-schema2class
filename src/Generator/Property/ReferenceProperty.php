<?php

namespace Helmich\Schema2Class\Generator\Property;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedType;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeEnum;

/**
 * Property that refers to another generated PHP artifact (class, Enum) via `$ref`.
 */
class ReferenceProperty extends AbstractProperty
{
    private ReferencedType $type;

    public function __construct(string $key, array $schema, GeneratorRequest $generatorRequest)
    {
        parent::__construct($key, $schema, $generatorRequest);
        $this->type = $generatorRequest->lookupReference($schema['$ref']);
    }

    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema['$ref']);
    }

    public function typeAnnotation(): string
    {
        return $this->type->typeAnnotation($this->generatorRequest);
    }

    public function typeHint(string $phpVersion): ?string
    {
        return $this->type->typeHint($this->generatorRequest);
    }

    public function generateTypeAssertionExpr(string $expr): string
    {
        return $this->type->typeAssertionExpr($this->generatorRequest, $expr);
    }

    public function generateInputAssertionExpr(string $expr): string
    {
        return $this->type->inputAssertionExpr($this->generatorRequest, $expr);
    }

    public function generateInputMappingExpr(string $expr, bool $asserted = false): string
    {
        return $this->type->inputMappingExpr($this->generatorRequest, expr: $expr);
    }

    public function generateOutputMappingExpr(string $expr): string
    {
        return $this->type->outputMappingExpr($this->generatorRequest, $expr);
    }

    public function generateOutputMappingExprStdClass(string $expr): string
    {
        return $this->type->outputMappingExprStdClass($this->generatorRequest, $expr);
    }

    public function isComplex(): bool
    {
        if ($this->type instanceof ReferencedTypeEnum) {
            return $this->type->usesNativeEnum($this->generatorRequest);
        }

        return true;
    }

}