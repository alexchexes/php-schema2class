<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property;

use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\PropertyBuilder;
use Helmich\Schema2Class\Generator\SchemaToClass;

class ObjectArrayProperty extends AbstractProperty
{
    use TypeConvert;

    private function buildUseClause(): string
    {
        $validateArg = $this->generatorRequest->getCurrValidateArgAlias();
        $materializeArg = $this->generatorRequest->getCurrMaterializeArgAlias();
        
        $vars = ['$' . $validateArg];
        if ($materializeArg !== null) {
            $vars[] = '$' . $materializeArg;
        }
        return implode(', ', $vars);
    }

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

        $this->itemType = PropertyBuilder::buildPropertyFromSchema($generatorRequest, $key . "Item", $this->itemSchema, true);
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

    public function convertTypeToArray(string $outputVarName = 'output'): string
    {
        $name = $this->name;
        $key  = $this->key;
        $keyStr = var_export($key, true);
        $st   = $this->subTypeName();

        if ($this->itemType instanceof MixedProperty) {
            return "\${$outputVarName}[{$keyStr}] = array_map(fn (\$i) => \$i, \$this->{$name});";
        }

        if ($this->generatorRequest->isAtLeastPHP('7.4')) {
            return "\${$outputVarName}[{$keyStr}] = array_map(fn ($st \$i) => \$i->toArray(), \$this->{$name});";
        }
        return "\${$outputVarName}[{$keyStr}] = array_map(function($st \$i) { return \$i->toArray(); }, \$this->{$name});";
    }

    public function convertTypeToStdClass(string $outputVarName = 'output'): string
    {
        $name = $this->name;
        $key  = $this->key;
        $keyStr = var_export($key, true);
        $st   = $this->subTypeName();

        if ($this->itemType instanceof MixedProperty) {
            return "\${$outputVarName}->{{$keyStr}} = array_map(fn (\$i) => \$i, \$this->{$name});";
        }

        if ($this->generatorRequest->isAtLeastPHP('7.4')) {
            return "\${$outputVarName}->{{$keyStr}} = array_map(fn ($st \$i) => \$i->toStdClass(), \$this->{$name});";
        }
        return "\${$outputVarName}->{{$keyStr}} = array_map(function($st \$i) { return \$i->toStdClass(); }, \$this->{$name});";
    }

    /**
     * @param SchemaToClass $generator
     * @throws GeneratorException
     */
    public function generateSubTypes(SchemaToClass $generator): void
    {
        if ($this->itemType instanceof MixedProperty) {
            return;
        }

        $req = $this->generatorRequest
            ->withSchema($this->itemSchema)
            ->withClass($this->subTypeName());

        $generator->schemaToClass($this->propagateRootDefinitions($req));
    }

    public function typeAnnotation(): string
    {
        return $this->subTypeName() . "[]";
    }

    public function typeHint(string $phpVersion): ?string
    {
        return "array";
    }

    public function generateTypeAssertionExpr(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return "is_array({$expr})";
        }

        $st = $this->subTypeName();
        return "is_array({$expr}) && count(array_filter({$expr}, function({$st} \$item) {return \$item instanceof {$st};})) === count({$expr})";
    }

    public function generateInputAssertionExpr(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return "is_array({$expr})";
        }

        $st = $this->subTypeName();
        return "is_array({$expr}) && count(array_filter({$expr}, function({$st} \$item) {return {$st}::validateInput(\$item, true)};)) === count({$expr})";
    }

    public function generateInputMappingExpr(string $expr, bool $asserted = false): string
    {
        $sm = $this->itemType->generateInputMappingExpr('$i');

        if ($this->itemType instanceof MixedProperty) {
            return match (true) {
                $this->generatorRequest->isAtLeastPHP('7.0') => "array_map(fn (\$i) => {$sm}, {$expr})",
                default => "array_map(function(\$i) { return {$sm}; }, {$expr})",
            };
        }

        $typeHint = $this->subTypeName();

        return match (true) {
            $this->generatorRequest->isAtLeastPHP('8.0')
                => "array_map(fn (array|object \$i): {$typeHint} => {$sm}, {$expr})",

            $this->generatorRequest->isAtLeastPHP('7.4')
                => "array_map(fn (\$i): {$typeHint} => {$sm}, {$expr})",

            $this->generatorRequest->isAtLeastPHP('7.0')
                => "array_map(function(\$i): {$typeHint} use ({$this->buildUseClause()}) { return {$sm}; }, {$expr})",

            default
                => "array_map(function(\$i) use ({$this->buildUseClause()}) { return {$sm}; }, {$expr})",
        };
    }

    public function generateOutputMappingExpr(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return "array_map(fn (\$i) => \$i, {$expr})";
        }

        $st = $this->subTypeName();
        $sm = $this->itemType->generateOutputMappingExpr('$i');

        if ($this->generatorRequest->isAtLeastPHP('7.4')) {
            return "array_map(fn ($st \$i) => {$sm}, {$expr})";
        }
        return "array_map(function($st \$i) { return {$sm} }, {$expr})";
    }

    public function generateOutputMappingExprStdClass(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return "array_map(fn (\$i) => \$i, {$expr})";
        }

        $st = $this->subTypeName();
        $sm = $this->itemType->generateOutputMappingExprStdClass('$i');

        if ($this->generatorRequest->isAtLeastPHP('7.4')) {
            return "array_map(fn ($st \$i) => {$sm}, {$expr})";
        }
        return "array_map(function($st \$i) { return {$sm} }, {$expr})";
    }

    public function generateCloneExpr(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return "array_map(fn (\$i) => \$i, {$expr})";
        }

        $st = $this->subTypeName();

        if ($this->generatorRequest->isAtLeastPHP('7.4')) {
            return "array_map(fn ({$st} \$i) => clone \$i, {$expr})";
        }
        return "array_map(function({$st} \$i) { return clone \$i; }, {$expr})";
    }

    private function subTypeName(): string
    {
        return $this->generatorRequest->getTargetClass() . $this->capitalizedName . 'Item';
    }

}
