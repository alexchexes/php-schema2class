<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Array;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Expression\ArrayMapGenerator;
use Helmich\Schema2Class\Generator\Expression\ArrowFunctionGenerator;
use Helmich\Schema2Class\Generator\Expression\CallGenerator;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\PropertyBuilder;
use Helmich\Schema2Class\Generator\Property\Type\AbstractProperty;
use Helmich\Schema2Class\Generator\Property\Type\Object\NestedObjectProperty;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Util\StringUtils;
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
        if (
            !(isset($schema['type']) && $schema['type'] === 'array')
            || !isset($schema['items'])
            || !is_array($schema['items'])
        ) {
            return false;
        }

        if (ReferenceArrayProperty::canHandleSchema($schema) || ObjectArrayProperty::canHandleSchema($schema)) {
            return false;
        }

        $items = $schema['items'];
        if (
            isset($items['type'])
            && in_array($items['type'], ['string', 'integer', 'number', 'boolean'], true)
        ) {
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
        $innerAssertExpr = $this->itemType->typeAssertionExpr('$i');
        return $this->buildAssertionExpr($expr, $innerAssertExpr);
    }

    public function inputAssertionExpr(string $expr): string
    {
        $innerAssertExpr = $this->itemType->inputAssertionExpr('$i');
        return $this->buildAssertionExpr($expr, $innerAssertExpr);
    }

    private function buildAssertionExpr(string $expr, string $innerAssertExpr): string
    {
        $phpVer = $this->request->getTargetPHPVersion();

        $filterCallback = ArrowFunctionGenerator::make(
            parameters: '$i',
            expr: $innerAssertExpr,
            phpVer: $phpVer,
        );

        $arrayFilter = CallGenerator::make(
            callee: 'array_filter',
            arguments: [$expr, $filterCallback],
            phpVer: $phpVer,
        );

        $countExpr = CallGenerator::make(
            callee: 'count',
            arguments: [$arrayFilter],
            phpVer: $phpVer,
        );
        
        $ind = StringUtils::indentCode(...);
        return "(is_array({$expr})\n{$ind("&& count({$expr}) === {$countExpr}")})";
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
        $useVars = [];
        if ($this->itemType instanceof NestedObjectProperty && $this->request->getClassHasDefaults()) {
            $useVars[] = '$' . ArgumentNames::INCL_DEFAULTS;
        }
        return ArrayMapGenerator::make(
            arrayExpr: $expr,
            itemParam: '$i',
            mapExpr: $this->itemType->outputMappingExpr('$i'),
            phpVer: $this->request->getTargetPHPVersion(),
            useVars: $useVars,
        );
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        $useVars = [];
        if ($this->itemType instanceof NestedObjectProperty && $this->request->getClassHasDefaults()) {
            $useVars[] = '$' . ArgumentNames::INCL_DEFAULTS;
        }
        return ArrayMapGenerator::make(
            arrayExpr: $expr,
            itemParam: '$i',
            mapExpr: $this->itemType->outputMappingExprStdClass('$i'),
            phpVer: $this->request->getTargetPHPVersion(),
            useVars: $useVars,
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
        // Typed arrays always require validation since their type-hint is just 'array'
        return true;
    }
}
