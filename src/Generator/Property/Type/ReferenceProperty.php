<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeInterface;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeEnum;

/**
 * Property that refers to another generated PHP artifact (class, Enum) via `$ref`.
 */
class ReferenceProperty extends AbstractProperty
{
    private ReferencedTypeInterface $refType;

    public function __construct(string $key, array $schema, GeneratorRequest $generatorRequest)
    {
        parent::__construct($key, $schema, $generatorRequest);
        $this->refType = $generatorRequest->lookupReference($schema['$ref']);
    }

    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema['$ref']);
    }

    public function typeAnnotation(): string
    {
        return $this->refType->typeAnnotation($this->generatorRequest);
    }

    public function typeHint(string $phpVersion): ?string
    {
        return $this->refType->typeHint($this->generatorRequest);
    }

    public function genTypeAssertionExpr(string $expr): string
    {
        return $this->refType->generateTypeAssertionExpr($this->generatorRequest, $expr);
    }

    public function genInputAssertionExpr(string $expr): string
    {
        return $this->refType->generateInputAssertionExpr($this->generatorRequest, $expr);
    }

    public function genMappingExpr(string $expr, bool $asserted = false): string
    {
        return $this->refType->generateInputMappingExpr($this->generatorRequest, expr: $expr);
    }

    public function genOutputMappingExpr(string $expr): string
    {
        return $this->refType->generateOutputMappingExpr($this->generatorRequest, $expr);
    }

    public function genOutputMappingExprStdClass(string $expr): string
    {
        return $this->refType->generateOutputMappingExprStdClass($this->generatorRequest, $expr);
    }

    public function isComplex(): bool
    {
        if ($this->refType instanceof ReferencedTypeEnum) {
            return $this->refType->usesNativeEnum($this->generatorRequest);
        }

        return true;
    }

}