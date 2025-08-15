<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Array;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Expression\ArrayMapGenerator;
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
        $useVars = ['$' . ArgumentNames::VALIDATE];
        if ($this->request->getClassHasDefaults()) {
            $useVars[] = '$' . ArgumentNames::MATRLZ_DEFAULTS;
        }
        
        return ArrayMapGenerator::make(
            arrayExpr: $expr,
            itemParam: '$i',
            mapExpr: $this->itemType->inputMappingExpr('$i'),
            useVars: $useVars,
            phpVer: $this->request->getTargetPHPVersion(),
        );
    }

    public function outputMappingExpr(string $expr): string
    {
        return ArrayMapGenerator::make(
            arrayExpr: $expr,
            itemParam: '$i',
            mapExpr: $this->itemType->outputMappingExpr('$i'),
            phpVer: $this->request->getTargetPHPVersion(),
        );
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        return ArrayMapGenerator::make(
            arrayExpr: $expr,
            itemParam: '$i',
            mapExpr: $this->itemType->outputMappingExprStdClass('$i'),
            phpVer: $this->request->getTargetPHPVersion(),
        );
    }

    public function cloneExpr(string $expr): string
    {
        return ArrayMapGenerator::make(
            arrayExpr: $expr,
            itemParam: '$i',
            mapExpr: $this->itemType->cloneExpr('$i'),
            phpVer: $this->request->getTargetPHPVersion(),
        );
    }

    public function needsValidation(): bool
    {
        // Typed arrays have `array` PHP type so run-time check of each element is needed.
        return true;
    }
}
