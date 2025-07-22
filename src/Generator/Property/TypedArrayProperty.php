<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\PropertyBuilder;
use Helmich\Schema2Class\Generator\SchemaToClass;

class TypedArrayProperty extends AbstractProperty
{
    private PropertyInterface $itemType;
    private array $itemSchema;

    public function __construct(string $key, array $schema, GeneratorRequest $generatorRequest)
    {
        $this->itemSchema = $schema['items'];
        $this->itemType = PropertyBuilder::buildPropertyFromSchema(
            $generatorRequest,
            $key . 'Item',
            $this->itemSchema,
            true
        );
        parent::__construct($key, $schema, $generatorRequest);
    }

    public static function canHandleSchema(array $schema): bool
    {
        if (!(isset($schema['type']) && $schema['type'] === 'array') || !isset($schema['items']) || !is_array($schema['items'])) {
            return false;
        }

        if (ReferenceArrayProperty::canHandleSchema($schema) || ObjectArrayProperty::canHandleSchema($schema)) {
            return false;
        }

        $items = $schema['items'];
        if (isset($items['type']) && in_array($items['type'], ['string', 'integer', 'number', 'boolean'], true)) {
            return false;
        }
        if (isset($items['$ref'])) {
            return false;
        }

        return true;
    }

    public function isComplex(): bool
    {
        return true;
    }

    public function generateSubTypes(SchemaToClass $generator): void
    {
        $this->itemType->generateSubTypes($generator);
    }

    public function typeAnnotation(): string
    {
        $inner = $this->itemType->typeAnnotation();
        if (str_contains($inner, '|')) {
            return '(' . $inner . ')[]';
        }
        return $inner . '[]';
    }

    public function typeHint(string $phpVersion): ?string
    {
        return 'array';
    }

    public function generateTypeAssertionExpr(string $expr): string
    {
        $inner = $this->itemType->generateTypeAssertionExpr('$i');
        return "is_array({$expr}) && count(array_filter({$expr}, fn(\$i) => {$inner})) === count({$expr})";
    }

    public function generateInputAssertionExpr(string $expr): string
    {
        $inner = $this->itemType->generateInputAssertionExpr('$i');
        return "is_array({$expr}) && count(array_filter({$expr}, fn(\$i) => {$inner})) === count({$expr})";
    }

    public function generateInputMappingExpr(string $expr, bool $asserted = false): string
    {
        $map = $this->itemType->generateInputMappingExpr('$i');
        if ($this->generatorRequest->isAtLeastPHP('7.4')) {
            return "array_map(fn(\$i) => {$map}, {$expr})";
        }

        $validateArg = $this->generatorRequest->getCurrValidateArgAlias();
        $materializeArg = $this->generatorRequest->getCurrMaterializeArgAlias();

        $use = ['$' . $validateArg];
        if ($materializeArg !== null) {
            $use[] = '$' . $materializeArg;
        }
        return sprintf('array_map(function($i) use (%s) { return %s; }, %s)', implode(', ', $use), $map, $expr);
    }

    public function generateOutputMappingExpr(string $expr): string
    {
        $map = $this->itemType->generateOutputMappingExpr('$i');
        if ($this->generatorRequest->isAtLeastPHP('7.4')) {
            return "array_map(fn(\$i) => {$map}, {$expr})";
        }
        return "array_map(function(\$i) { return {$map}; }, {$expr})";
    }

    public function generateOutputMappingExprStdClass(string $expr): string
    {
        $map = $this->itemType->generateOutputMappingExprStdClass('$i');
        if ($this->generatorRequest->isAtLeastPHP('7.4')) {
            return "array_map(fn(\$i) => {$map}, {$expr})";
        }
        return "array_map(function(\$i) { return {$map}; }, {$expr})";
    }

    public function generateCloneExpr(string $expr): string
    {
        $map = $this->itemType->generateCloneExpr('$i');
        if ($this->generatorRequest->isAtLeastPHP('7.4')) {
            return "array_map(fn(\$i) => {$map}, {$expr})";
        }
        return "array_map(function(\$i) { return {$map}; }, {$expr})";
    }
}
