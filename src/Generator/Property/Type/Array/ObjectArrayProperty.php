<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Array;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Class\VariableNames;
use Helmich\Schema2Class\Generator\Expression\ArrayMapGenerator;
use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\PropertyBuilder;
use Helmich\Schema2Class\Generator\Property\Type\AbstractProperty;
use Helmich\Schema2Class\Generator\Property\Type\MixedProperty;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
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

        $hasProps = isset($itemSchema["properties"]) && is_array($itemSchema["properties"]) && count($itemSchema["properties"]) > 0;

        return (
            ((isset($itemSchema["type"]) && $itemSchema["type"] === "object") || isset($itemSchema["properties"]))
            && $hasProps
        );
    }

    private function buildSerializeMappingExpr(string $method): string
    {
        $name = $this->propName();

        if ($this->itemType instanceof MixedProperty) {
            return ArrayMapGenerator::make(
                itemParam: '$i',
                mapExpr: '$i',
                arrayExpr: "\$this->{$name}",
                phpVer: $this->request->getTargetPHPVersion(),
            );
        }

        $st = $this->subTypeName();
        $inclDefaultsArg = $this->request->getClassHasDefaults()
            ? '$' . ArgumentNames::INCL_DEFAULTS
            : '';

        return ArrayMapGenerator::make(
            itemParam: '$i',
            mapExpr: "\$i->{$method}({$inclDefaultsArg})",
            arrayExpr: "\$this->{$name}",
            itemType: $st,
            useVars: $inclDefaultsArg ? [$inclDefaultsArg] : null,
            phpVer: $this->request->getTargetPHPVersion(),
        );
    }

    public function convertTypeToArray(): string
    {
        $OUTPUT_VAR = VariableNames::OUTPUT;
        $arrMapExpr = $this->buildSerializeMappingExpr(MethodNames::TO_ARRAY);

        return "\${$OUTPUT_VAR}[{$this->keyStr()}] = {$arrMapExpr};";
    }

    public function convertTypeToStdClass(): string
    {
        $OUTPUT_VAR = VariableNames::OUTPUT;
        $arrMapExpr = $this->buildSerializeMappingExpr(MethodNames::TO_STD_CLASS);

        return "\${$OUTPUT_VAR}->{{$this->keyStr()}} = {$arrMapExpr};";
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

    public function typeAssertionExpr(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return "is_array({$expr})";
        }

        $st = $this->subTypeName();

        return 
            <<<PHP
            is_array({$expr}) && count(array_filter({$expr}, function({$st} \$item) {
                return \$item instanceof {$st};
            })) === count({$expr})
            PHP;
    }

    public function inputAssertionExpr(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return "is_array({$expr})";
        }

        $st = $this->subTypeName();
        $VALIDATE_INPUT = MethodNames::VALIDATE_INPUT;

        return 
            <<<PHP
            is_array({$expr}) && count(array_filter({$expr}, function({$st} \$item) {
                return {$st}::{$VALIDATE_INPUT}(\$item, true);
            })) === count({$expr})
            PHP;
    }

    private function getVarsForUseClause(): array
    {
        $vars = ['$' . ArgumentNames::VALIDATE];
        if ($this->request->getClassHasDefaults()) {
            $vars[] = '$' . ArgumentNames::MATRLZ_DEFAULTS;
        }
        return $vars;
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        $subExpr = $this->itemType->inputMappingExpr('$i');

        if ($this->itemType instanceof MixedProperty) {
            return ArrayMapGenerator::make(
                itemParam: '$i',
                mapExpr: $subExpr,
                arrayExpr: $expr,
                phpVer: $this->request->getTargetPHPVersion(),
            );
        }

        $itemType = 'array|object';
        $returnType = $this->subTypeName();
        
        return ArrayMapGenerator::make(
            itemParam: '$i',
            mapExpr: $subExpr,
            arrayExpr: $expr,
            itemType: $itemType,
            useVars: $this->getVarsForUseClause(),
            returnType: $returnType,
            phpVer: $this->request->getTargetPHPVersion(),
        );
    }

    public function outputMappingExpr(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return ArrayMapGenerator::make(
                itemParam: '$i',
                mapExpr: '$i',
                arrayExpr: $expr,
                phpVer: $this->request->getTargetPHPVersion(),
            );
        }

        $subExpr = $this->itemType->outputMappingExpr('$i');
        $subType = $this->subTypeName();

        return ArrayMapGenerator::make(
            itemParam: '$i',
            mapExpr: $subExpr,
            arrayExpr: $expr,
            itemType: $subType,
            phpVer: $this->request->getTargetPHPVersion(),
        );
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return ArrayMapGenerator::make(
                itemParam: '$i',
                mapExpr: '$i',
                arrayExpr: $expr,
                phpVer: $this->request->getTargetPHPVersion(),
            );
        }

        $subExpr = $this->itemType->outputMappingExprStdClass('$i');
        $subType = $this->subTypeName();
        
        return ArrayMapGenerator::make(
            itemParam: '$i',
            mapExpr: $subExpr,
            arrayExpr: $expr,
            itemType: $subType,
            phpVer: $this->request->getTargetPHPVersion(),
        );
    }

    public function cloneExpr(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return ArrayMapGenerator::make(
                itemParam: '$i',
                mapExpr: '$i',
                arrayExpr: $expr,
                phpVer: $this->request->getTargetPHPVersion(),
            );
        }

        $subType = $this->subTypeName();

        return ArrayMapGenerator::make(
            itemParam: '$i',
            mapExpr: 'clone $i',
            arrayExpr: $expr,
            itemType: $subType,
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
}
