<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Array;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Expression\ArrayMapGenerator;
use Helmich\Schema2Class\Generator\Expression\ArrowFunctionGenerator;
use Helmich\Schema2Class\Generator\Expression\CallGenerator;
use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\PropertyBuilder;
use Helmich\Schema2Class\Generator\Property\Type\AbstractProperty;
use Helmich\Schema2Class\Generator\Property\Type\MixedProperty;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Util\StringUtils;
use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Represents an array of objects. Each entry is generated using {@see PropertyBuilder}
 * and sub classes may be generated for the item schema.
 */
class ObjectArrayProperty extends AbstractProperty
{
    private PropertyInterface $itemType;
    private array $itemSchema;

    public function __construct(string $key, array $schema, GeneratorRequest $generatorRequest)
    {
        $this->itemSchema = $schema["additionalProperties"] ?? $schema["items"];
        
        $this->itemType = PropertyBuilder::buildPropertyFromSchema(
            req: $generatorRequest,
            name: $key . "Item",
            definition: $this->itemSchema,
            isRequired: true
        );

        parent::__construct($key, $schema, $generatorRequest);
    }


    public static function canHandleSchema(array $schema): bool
    {
        $itemSchema         = null;
        $isAssociativeArray = isset($schema["additionalProperties"]);
        $isArray            = isset($schema["type"]) && $schema["type"] === "array";

        if ($isAssociativeArray) {
            $itemSchema = $schema["additionalProperties"];
        }

        if ($isArray) {
            $itemSchema = $schema["items"] ?? null;
        }

        if (!$isArray && !$isAssociativeArray) {
            return false;
        }

        $hasProps = isset($itemSchema["properties"])
            && is_array($itemSchema["properties"])
            && count($itemSchema["properties"]) > 0;

        return (
            (
                (isset($itemSchema["type"]) && $itemSchema["type"] === "object")
                || isset($itemSchema["properties"])
            )
            && $hasProps
        );
    }

    /**
     * @throws GeneratorException
     */
    public function generateSubTypes(WriterInterface $writer, OutputInterface $output): void
    {
        if ($this->itemType instanceof MixedProperty) {
            return;
        }

        $req = $this->request
            ->withSchema($this->itemSchema)
            ->withClass($this->subTypeName());

        $generator = $this->request->getSchemaToClassFactory()->build($writer, $output);
        $generator->schemaToClass($this->propagateRootDefinitions($req));
    }

    public function typeAnnotation(): string
    {
        return $this->subTypeName() . "[]";
    }

    public function typeHint(): ?string
    {
        return "array";
    }

    /** 
     * In this property, this method used only when generating anyOf/oneOf
     */
    public function typeAssertionExpr(string $expr): string
    {
        $innerAssertExpr = "\$item instanceof {$this->subTypeName()}";

        return $this->buildAssertionExpr($expr, $innerAssertExpr);
    }

    /** 
     * In this property, this method used only when generating anyOf/oneOf
     */
    public function inputAssertionExpr(string $expr): string
    {
        $VALIDATE_INPUT = MethodNames::VALIDATE_INPUT;
        $innerAssertExpr = "{$this->subTypeName()}::{$VALIDATE_INPUT}(\$item, true)";

        return $this->buildAssertionExpr($expr, $innerAssertExpr);
    }

    private function buildAssertionExpr(string $expr, string $innerAssertExpr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return "is_array({$expr})";
        }

        $subClass = $this->subTypeName();
        $phpVer = $this->request->getTargetPHPVersion();

        $filterCallback = ArrowFunctionGenerator::make(
            parameters: ['$item' => $subClass],
            expr: $innerAssertExpr,
            phpVer: $phpVer,
            returnType: 'bool',
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
        $subExpr = $this->itemType->inputMappingExpr('$i');

        if ($this->itemType instanceof MixedProperty) {
            return ArrayMapGenerator::make(
                arrayExpr: $expr,
                itemParam: '$i',
                mapExpr: $subExpr,
                phpVer: $this->request->getTargetPHPVersion(),
            );
        }

        $useVars = ['$' . ArgumentNames::VALIDATE];
        if ($this->request->getClassHasDefaults()) {
            $useVars[] = '$' . ArgumentNames::MATRLZ_DEFAULTS;
        }

        return ArrayMapGenerator::make(
            arrayExpr: $expr,
            itemParam: '$i',
            mapExpr: $subExpr,
            itemType: 'array|object',
            returnType: $this->subTypeName(),
            phpVer: $this->request->getTargetPHPVersion(),
            useVars: $useVars,
        );
    }

    /** 
     * In this property, these ouput mapping methods used only when generating anyOf/oneOf
     */
    public function outputMappingExpr(string $expr): string
    {
        $innerExpr = $this->itemType->outputMappingExpr('$i');
        return $this->buildSerializeMappingExpr($expr, $innerExpr);
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        $innerExpr = $this->itemType->outputMappingExprStdClass('$i');
        return $this->buildSerializeMappingExpr($expr, $innerExpr);
    }

    private function buildSerializeMappingExpr(string $expr, string $innerExpr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return ArrayMapGenerator::make(
                arrayExpr: $expr,
                itemParam: '$i',
                mapExpr: '$i',
                phpVer: $this->request->getTargetPHPVersion(),
            );
        }

        $useVars = [];
        if ($this->request->getClassHasDefaults()) {
            $useVars[] = '$' . ArgumentNames::INCL_DEFAULTS;
        }

        return ArrayMapGenerator::make(
            arrayExpr: $expr,
            itemParam: '$i',
            mapExpr: $innerExpr,
            itemType: $this->subTypeName(),
            phpVer: $this->request->getTargetPHPVersion(),
            useVars: $useVars,
        );
    }


    public function cloneExpr(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return ArrayMapGenerator::make(
                arrayExpr: $expr,
                itemParam: '$i',
                mapExpr: '$i',
                phpVer: $this->request->getTargetPHPVersion(),
            );
        }

        return ArrayMapGenerator::make(
            arrayExpr: $expr,
            itemParam: '$i',
            mapExpr: 'clone $i',
            itemType: $this->subTypeName(),
            phpVer: $this->request->getTargetPHPVersion(),
        );
    }

    private function subTypeName(): string
    {
        return $this->request->getTargetClass() . $this->nameForClass . 'Item';
    }

    public function needsValidation(): bool
    {
        // Arrays of objects have `array` PHP type so run-time check of each element is needed.
        return true;
    }
    
    public function inputMappingRequiresNullCheck(): bool
    {
        return true;
    }

    public function outputMappingRequiresNullCheck(): bool
    {
        return true;
    }
}
