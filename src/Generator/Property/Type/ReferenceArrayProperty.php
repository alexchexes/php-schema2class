<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeInterface;

/**
 * Property that is array where each item refers to a generated PHP artifact (class, Enum) via `$ref`.
 */
class ReferenceArrayProperty extends AbstractProperty
{
    private ReferencedTypeInterface $refType;

    public function __construct(string $key, array $schema, GeneratorRequest $request)
    {
        parent::__construct($key, $schema, $request);
        $this->refType = $request->lookupReference($schema['items']['$ref']);
    }

    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema['type']) && $schema['type'] === 'array' && isset($schema['items']['$ref']);
    }

    public function typeAnnotation(): string
    {
        $inner = $this->refType->typeAnnotation();
        if (str_contains($inner, "|")) {
            return "({$inner})[]";
        }
        return $inner . '[]';
    }

    public function typeHint(): ?string
    {
        return "array";
    }

    public function typeAssertionExpr(string $expr): string
    {
        $typeHint = $this->refType->typeHint();
        $assertExpr = $this->refType->typeAssertionExpr('$i');

        $map = "array_map(fn({$typeHint} \$i): bool => {$assertExpr}, {$expr})";

        return "array_reduce($map, fn(bool \$carry, bool \$item): bool => \$carry && \$item, true)";
    }

    public function inputAssertionExpr(string $expr): string
    {
        // Build the inner assertion closure: use union hint only on PHP ≥8.0;
        // on 7.4+, drop the hint so `fn($i)` stays valid.
        $innerAssert = $this->refType->inputAssertionExpr('$i');
        if ($this->request->isAtLeastPHP("8.0")) {
            $hint = $this->refType->serializedInputTypeHint();
            $map  = "array_map(fn({$hint} \$i): bool => {$innerAssert}, {$expr})";
        } else {
            $map  = "array_map(fn(\$i): bool => {$innerAssert}, {$expr})";
        }
        return "array_reduce({$map}, fn(bool \$carry, bool \$item): bool => \$carry && \$item, true)";
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        // Build the mapping closure: drop union type hints for PHP < 8.0
        $innerMap = $this->refType->inputMappingExpr('$i');
        if ($this->request->isAtLeastPHP("8.0")) {
            $hint = $this->refType->serializedInputTypeHint();
            $returnHint = $this->refType->typeHint();
            $closure = "fn({$hint} \$i): {$returnHint} => {$innerMap}";
        } else {
            $closure = "fn(\$i) => {$innerMap}";
        }
        return "array_map(\n    {$closure},\n    {$expr}\n)";
    }

    public function outputMappingExpr(string $expr): string
    {
        $typeHint = $this->refType->typeHint();
        $serializedTypeHint = $this->refType->serializedTypeHint();
        $outputMappingExpr = $this->refType->outputMappingExpr('$i');

        return "array_map(fn({$typeHint} \$i): {$serializedTypeHint} => {$outputMappingExpr}, {$expr})";
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        $typeHint = $this->refType->typeHint();
        $serializedTypeHint = $this->refType->serializedTypeHintStdClass();
        $outputMappingExpr = $this->refType->outputMappingExprStdClass('$i');

        return "array_map(fn({$typeHint} \$i): {$serializedTypeHint} => {$outputMappingExpr}, {$expr})";
    }

    public function needsValidation(): bool
    {
        if ($this->refType instanceof \Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeEnum) {
            return !$this->refType->usesNativeEnum();
        }

        return false;
    }

}
