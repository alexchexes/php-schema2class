<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedType;

/** 
 * Array of references to other generated classes.
 */
class ReferenceArrayProperty extends AbstractProperty
{
    private ReferencedType $type;

    public function __construct(string $key, array $schema, GeneratorRequest $generatorRequest)
    {
        parent::__construct($key, $schema, $generatorRequest);
        $this->type = $generatorRequest->lookupReference($schema['items']['$ref']);
    }

    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema['type']) && $schema['type'] === 'array' && isset($schema['items']['$ref']);
    }

    public function typeAnnotation(): string
    {
        $inner = $this->type->typeAnnotation($this->generatorRequest);
        if (str_contains($inner, "|")) {
            return "({$inner})[]";
        }
        return $inner . '[]';
    }

    public function typeHint(string $phpVersion): ?string
    {
        return "array";
    }

    public function generateTypeAssertionExpr(string $expr): string
    {
        $map = "array_map(fn({$this->type->typeHint($this->generatorRequest)} \$i): bool => {$this->type->typeAssertionExpr($this->generatorRequest, '$i')}, {$expr})";
        return "array_reduce($map, fn(bool \$carry, bool \$item): bool => \$carry && \$item, true)";
    }

    public function generateInputAssertionExpr(string $expr): string
    {
        // Build the inner assertion closure: use union hint only on PHP ≥8.0;
        // on 7.4+, drop the hint so `fn($i)` stays valid.
        $innerAssert = $this->type->inputAssertionExpr($this->generatorRequest, '$i');
        if ($this->generatorRequest->isAtLeastPHP("8.0")) {
            $hint = $this->type->serializedInputTypeHint($this->generatorRequest);
            $map  = "array_map(fn({$hint} \$i): bool => {$innerAssert}, {$expr})";
        } else {
            $map  = "array_map(fn(\$i): bool => {$innerAssert}, {$expr})";
        }
        return "array_reduce({$map}, fn(bool \$carry, bool \$item): bool => \$carry && \$item, true)";
    }

    public function generateInputMappingExpr(string $expr, bool $asserted = false): string
    {
        // Build the mapping closure: drop union type hints for PHP < 8.0
        $innerMap = $this->type->inputMappingExpr(
            $this->generatorRequest,
            expr: '$i',
        );
        if ($this->generatorRequest->isAtLeastPHP("8.0")) {
            $hint = $this->type->serializedInputTypeHint($this->generatorRequest);
            $returnHint = $this->type->typeHint($this->generatorRequest);
            $closure = "fn({$hint} \$i): {$returnHint} => {$innerMap}";
        } else {
            $closure = "fn(\$i) => {$innerMap}";
        }
        return "array_map(\n    {$closure},\n    {$expr}\n)";
    }

    public function generateOutputMappingExpr(string $expr): string
    {
        $typeHint = $this->type->typeHint($this->generatorRequest);
        $serializedTypeHint = $this->type->serializedTypeHint($this->generatorRequest);
        $outputMappingExpr = $this->type->outputMappingExpr($this->generatorRequest, '$i');

        return "array_map(fn({$typeHint} \$i): {$serializedTypeHint} => {$outputMappingExpr}, {$expr})";
    }

    public function generateOutputMappingExprStdClass(string $expr): string
    {
        $typeHint = $this->type->typeHint($this->generatorRequest);
        $serializedTypeHint = $this->type->serializedTypeHintStdClass($this->generatorRequest);
        $outputMappingExpr = $this->type->outputMappingExprStdClass($this->generatorRequest, '$i');

        return "array_map(fn({$typeHint} \$i): {$serializedTypeHint} => {$outputMappingExpr}, {$expr})";
    }

    public function isComplex(): bool
    {
        return true;
    }

}
