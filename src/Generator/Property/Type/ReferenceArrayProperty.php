<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeInterface;

/** 
 * Array of references to other generated classes.
 */
class ReferenceArrayProperty extends AbstractProperty
{
    private ReferencedTypeInterface $refType;

    public function __construct(string $key, array $schema, GeneratorRequest $generatorRequest)
    {
        parent::__construct($key, $schema, $generatorRequest);
        $this->refType = $generatorRequest->lookupReference($schema['items']['$ref']);
    }

    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema['type']) && $schema['type'] === 'array' && isset($schema['items']['$ref']);
    }

    public function typeAnnotation(): string
    {
        $inner = $this->refType->typeAnnotation($this->generatorRequest);
        if (str_contains($inner, "|")) {
            return "({$inner})[]";
        }
        return $inner . '[]';
    }

    public function typeHint(string $phpVersion): ?string
    {
        return "array";
    }

    public function genTypeAssertionExpr(string $expr): string
    {
        $typeHint = $this->refType->typeHint($this->generatorRequest);
        $assertExpr = $this->refType->generateTypeAssertionExpr($this->generatorRequest, '$i');

        $map = "array_map(fn({$typeHint} \$i): bool => {$assertExpr}, {$expr})";

        return "array_reduce($map, fn(bool \$carry, bool \$item): bool => \$carry && \$item, true)";
    }

    public function genInputAssertionExpr(string $expr): string
    {
        // Build the inner assertion closure: use union hint only on PHP ≥8.0;
        // on 7.4+, drop the hint so `fn($i)` stays valid.
        $innerAssert = $this->refType->generateInputAssertionExpr($this->generatorRequest, '$i');
        if ($this->generatorRequest->isAtLeastPHP("8.0")) {
            $hint = $this->refType->serializedInputTypeHint($this->generatorRequest);
            $map  = "array_map(fn({$hint} \$i): bool => {$innerAssert}, {$expr})";
        } else {
            $map  = "array_map(fn(\$i): bool => {$innerAssert}, {$expr})";
        }
        return "array_reduce({$map}, fn(bool \$carry, bool \$item): bool => \$carry && \$item, true)";
    }

    public function genMappingExpr(string $expr, bool $asserted = false): string
    {
        // Build the mapping closure: drop union type hints for PHP < 8.0
        $innerMap = $this->refType->generateInputMappingExpr(
            $this->generatorRequest,
            expr: '$i',
        );
        if ($this->generatorRequest->isAtLeastPHP("8.0")) {
            $hint = $this->refType->serializedInputTypeHint($this->generatorRequest);
            $returnHint = $this->refType->typeHint($this->generatorRequest);
            $closure = "fn({$hint} \$i): {$returnHint} => {$innerMap}";
        } else {
            $closure = "fn(\$i) => {$innerMap}";
        }
        return "array_map(\n    {$closure},\n    {$expr}\n)";
    }

    public function genOutputMappingExpr(string $expr): string
    {
        $typeHint = $this->refType->typeHint($this->generatorRequest);
        $serializedTypeHint = $this->refType->serializedTypeHint($this->generatorRequest);
        $outputMappingExpr = $this->refType->generateOutputMappingExpr($this->generatorRequest, '$i');

        return "array_map(fn({$typeHint} \$i): {$serializedTypeHint} => {$outputMappingExpr}, {$expr})";
    }

    public function genOutputMappingExprStdClass(string $expr): string
    {
        $typeHint = $this->refType->typeHint($this->generatorRequest);
        $serializedTypeHint = $this->refType->serializedTypeHintStdClass($this->generatorRequest);
        $outputMappingExpr = $this->refType->generateOutputMappingExprStdClass($this->generatorRequest, '$i');

        return "array_map(fn({$typeHint} \$i): {$serializedTypeHint} => {$outputMappingExpr}, {$expr})";
    }

    public function isComplex(): bool
    {
        return true;
    }

}
