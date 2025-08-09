<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Array;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\PropertyBuilder;
use Helmich\Schema2Class\Generator\Property\Type\AbstractProperty;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Represents array of complex objects defined "items" schema.
 * Each element is mapped using the same nested property instance.
 */
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

    public function generateSubTypes(WriterInterface $writer, OutputInterface $output): void
    {
        $this->itemType->generateSubTypes($writer, $output);
    }

    public function typeAnnotation(): string
    {
        $inner = $this->itemType->typeAnnotation();
        if (str_contains($inner, '|')) {
            return '(' . $inner . ')[]';
        }
        return $inner . '[]';
    }

    public function typeHint(): ?string
    {
        return 'array';
    }

    public function typeAssertionExpr(string $expr): string
    {
        $inner = $this->itemType->typeAssertionExpr('$i');
        return "is_array({$expr}) && count(array_filter({$expr}, fn(\$i) => {$inner})) === count({$expr})";
    }

    public function inputAssertionExpr(string $expr): string
    {
        $inner = $this->itemType->inputAssertionExpr('$i');
        return "is_array({$expr}) && count(array_filter({$expr}, fn(\$i) => {$inner})) === count({$expr})";
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        $map = $this->itemType->inputMappingExpr('$i');
        if ($this->request->isAtLeastPHP('7.4')) {
            return "array_map(fn(\$i) => {$map}, {$expr})";
        }

        $use = ['$' . ArgumentNames::VALIDATE];
        if ($this->request->getClassHasDefaults()) {
            $use[] = '$' . ArgumentNames::MATRLZ_DEFAULTS;
        }
        $useExpr = implode(', ', $use);
        return "array_map(function(\$i) use ({$useExpr}) { return {$map}; }, {$expr})";
    }

    public function outputMappingExpr(string $expr): string
    {
        $map = $this->itemType->outputMappingExpr('$i');
        if ($this->request->isAtLeastPHP('7.4')) {
            return "array_map(fn(\$i) => {$map}, {$expr})";
        }
        return "array_map(function(\$i) { return {$map}; }, {$expr})";
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        $map = $this->itemType->outputMappingExprStdClass('$i');
        if ($this->request->isAtLeastPHP('7.4')) {
            return "array_map(fn(\$i) => {$map}, {$expr})";
        }
        return "array_map(function(\$i) { return {$map}; }, {$expr})";
    }

    public function cloneExpr(string $expr): string
    {
        $map = $this->itemType->cloneExpr('$i');
        if ($this->request->isAtLeastPHP('7.4')) {
            return "array_map(fn(\$i) => {$map}, {$expr})";
        }
        return "array_map(function(\$i) { return {$map}; }, {$expr})";
    }

    public function needsValidation(): bool
    {
        // Typed arrays have `array` PHP type so run-time check of each element is needed.
        return true;
    }
}
