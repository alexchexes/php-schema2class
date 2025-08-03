<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\Class\Method\FromInputMethodFactory;
use Helmich\Schema2Class\Generator\Class\Method\SerializeMethodFactory;
use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\PropertyBuilder;
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

    /**
     * ObjectArrayProperty constructor.
     * @param string $key
     * @param array $schema
     * @param GeneratorRequest $generatorRequest
     */
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

    public function isComplex(): bool
    {
        return true;
    }

    public function convertTypeToArray(): string
    {
        $name = $this->name;
        $key  = $this->key;
        $keyStr = var_export($key, true);
        $st   = $this->subTypeName();
        $outputVarName = SerializeMethodFactory::OUTPUT_VAR_NAME;

        if ($this->itemType instanceof MixedProperty) {
            return "\${$outputVarName}[{$keyStr}] = array_map(fn (\$i) => \$i, \$this->{$name});";
        }

        $TO_ARRAY = MethodNames::TO_ARRAY;

        if ($this->request->isAtLeastPHP('7.4')) {
            return "\${$outputVarName}[{$keyStr}] = array_map(fn ($st \$i) => \$i->{$TO_ARRAY}(), \$this->{$name});";
        }
        return "\${$outputVarName}[{$keyStr}] = array_map(function($st \$i) { return \$i->{$TO_ARRAY}(); }, \$this->{$name});";
    }

    public function convertTypeToStdClass(): string
    {
        $name = $this->name;
        $key  = $this->key;
        $keyStr = var_export($key, true);
        $st   = $this->subTypeName();
        $outputVarName = SerializeMethodFactory::OUTPUT_VAR_NAME;

        if ($this->itemType instanceof MixedProperty) {
            return "\${$outputVarName}->{{$keyStr}} = array_map(fn (\$i) => \$i, \$this->{$name});";
        }

        $inclDefaultsArg = $this->request->getCurrReqHasDefaults() ? '$includeDefaults' : '';

        $TO_STD_CLASS = MethodNames::TO_STD_CLASS;

        if ($this->request->isAtLeastPHP('7.4')) {
            return "\${$outputVarName}->{{$keyStr}} = array_map(fn ($st \$i) => \$i->{$TO_STD_CLASS}({$inclDefaultsArg}), \$this->{$name});";
        }
        return "\${$outputVarName}->{{$keyStr}} = array_map(function($st \$i) { return \$i->{$TO_STD_CLASS}({$inclDefaultsArg}); }, \$this->{$name});";
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
        return "is_array({$expr}) && count(array_filter({$expr}, function({$st} \$item) {return \$item instanceof {$st};})) === count({$expr})";
    }

    public function inputAssertionExpr(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return "is_array({$expr})";
        }

        $st = $this->subTypeName();
        $VALIDATE_INPUT = MethodNames::VALIDATE_INPUT;
        return "is_array({$expr}) && count(array_filter({$expr}, function({$st} \$item) {return {$st}::{$VALIDATE_INPUT}(\$item, true)};)) === count({$expr})";
    }
    
    private function buildUseClause(): string
    {
        $vars = ['$' . FromInputMethodFactory::VALIDATE_ARG_NAME];
        if ($this->request->getCurrReqHasDefaults()) {
            $vars[] = '$' . FromInputMethodFactory::DEFAULTS_ARG_NAME;
        }
        return implode(', ', $vars);
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        $sm = $this->itemType->inputMappingExpr('$i');

        if ($this->itemType instanceof MixedProperty) {
            return match (true) {
                $this->request->isAtLeastPHP('7.0') => "array_map(fn (\$i) => {$sm}, {$expr})",
                default => "array_map(function(\$i) { return {$sm}; }, {$expr})",
            };
        }

        $typeHint = $this->subTypeName();

        return match (true) {
            $this->request->isAtLeastPHP('8.0')
                => "array_map(fn (array|object \$i): {$typeHint} => {$sm}, {$expr})",

            $this->request->isAtLeastPHP('7.4')
                => "array_map(fn (\$i): {$typeHint} => {$sm}, {$expr})",

            $this->request->isAtLeastPHP('7.0')
                => "array_map(function(\$i): {$typeHint} use ({$this->buildUseClause()}) { return {$sm}; }, {$expr})",

            default
                => "array_map(function(\$i) use ({$this->buildUseClause()}) { return {$sm}; }, {$expr})",
        };
    }

    public function outputMappingExpr(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return "array_map(fn (\$i) => \$i, {$expr})";
        }

        $st = $this->subTypeName();
        $sm = $this->itemType->outputMappingExpr('$i');

        if ($this->request->isAtLeastPHP('7.4')) {
            return "array_map(fn ($st \$i) => {$sm}, {$expr})";
        }
        return "array_map(function($st \$i) { return {$sm} }, {$expr})";
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return "array_map(fn (\$i) => \$i, {$expr})";
        }

        $st = $this->subTypeName();
        $sm = $this->itemType->outputMappingExprStdClass('$i');

        if ($this->request->isAtLeastPHP('7.4')) {
            return "array_map(fn ($st \$i) => {$sm}, {$expr})";
        }
        return "array_map(function($st \$i) { return {$sm} }, {$expr})";
    }

    public function cloneExpr(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return "array_map(fn (\$i) => \$i, {$expr})";
        }

        $st = $this->subTypeName();

        if ($this->request->isAtLeastPHP('7.4')) {
            return "array_map(fn ({$st} \$i) => clone \$i, {$expr})";
        }
        return "array_map(function({$st} \$i) { return clone \$i; }, {$expr})";
    }

    private function subTypeName(): string
    {
        return $this->request->getTargetClass() . $this->nameForClass . 'Item';
    }
}
