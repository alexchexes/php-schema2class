<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Array;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Class\VariableNames;
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

        $hasProps = isset($itemSchema["properties"]) && is_array($itemSchema["properties"]) && count($itemSchema["properties"]) > 0;

        return (
            ((isset($itemSchema["type"]) && $itemSchema["type"] === "object") || isset($itemSchema["properties"]))
            && $hasProps
        );
    }

    public function convertTypeToArray(): string
    {
        $name = $this->propName();
        $keyStr = $this->keyStr();
        $st   = $this->subTypeName();
        $outputVarName = VariableNames::OUTPUT;

        if ($this->itemType instanceof MixedProperty) {
            return "\${$outputVarName}[{$keyStr}] = array_map(fn (\$i) => \$i, \$this->{$name});";
        }

        $TO_ARRAY = MethodNames::TO_ARRAY;
        $inclDefaultsArg = $this->request->getClassHasDefaults() ? '$includeDefaults' : '';
        
        if ($this->request->isAtLeastPHP('7.4')) {
            return "\${$outputVarName}[{$keyStr}] = array_map(fn ($st \$i) => \$i->{$TO_ARRAY}({$inclDefaultsArg}), \$this->{$name});";
        }

        $useExpr = $inclDefaultsArg ? "use ({$inclDefaultsArg}) " : '';
        return
            <<<PHP
            \${$outputVarName}[{$keyStr}] = array_map(
                function($st \$i) $useExpr{
                    return \$i->{$TO_ARRAY}({$inclDefaultsArg});
                },
                \$this->{$name}
            );
            PHP;
    }

    public function convertTypeToStdClass(): string
    {
        $name = $this->propName();
        $keyStr = $this->keyStr();
        $st   = $this->subTypeName();
        $outputVarName = VariableNames::OUTPUT;

        if ($this->itemType instanceof MixedProperty) {
            return "\${$outputVarName}->{{$keyStr}} = array_map(fn (\$i) => \$i, \$this->{$name});";
        }

        $TO_STD_CLASS = MethodNames::TO_STD_CLASS;
        $inclDefaultsArg = $this->request->getClassHasDefaults() ? '$includeDefaults' : '';

        if ($this->request->isAtLeastPHP('7.4')) {
            return "\${$outputVarName}->{{$keyStr}} = array_map(fn ($st \$i) => \$i->{$TO_STD_CLASS}({$inclDefaultsArg}), \$this->{$name});";
        }

        $useExpr = $inclDefaultsArg ? "use ({$inclDefaultsArg}) " : '';
        
        return
            <<<PHP
            \${$outputVarName}->{{$keyStr}} = array_map(
                function($st \$i) $useExpr{
                    return \$i->{$TO_STD_CLASS}({$inclDefaultsArg});
                },
                \$this->{$name}
            );
            PHP;
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
    
    private function buildUseClause(): string
    {
        $vars = ['$' . ArgumentNames::VALIDATE];
        if ($this->request->getClassHasDefaults()) {
            $vars[] = '$' . ArgumentNames::MATRLZ_DEFAULTS;
        }
        return implode(', ', $vars);
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        $subExpr = $this->itemType->inputMappingExpr('$i');

        $returnExpr = "return {$subExpr};";
        $callbackBody = mb_strlen($returnExpr) < 50
            ? " {$returnExpr} "
            : "\n" . StringUtils::indentCode($returnExpr) . "\n";

        if ($this->itemType instanceof MixedProperty) {
            if ($this->request->isAtLeastPHP('7.0')) {
                return CallGenerator::make('array_map', [
                    "fn (\$i) => {$subExpr}",
                    $expr,
                ]);
            }

            return CallGenerator::make('array_map', [
                "function(\$i) {{$callbackBody}}",
                $expr,
            ]);
        }

        $typeHint = $this->subTypeName();

        return match (true) {
            $this->request->isAtLeastPHP('8.0') =>
                CallGenerator::make('array_map', [
                    "fn (array|object \$i): {$typeHint} => {$subExpr}",
                    $expr,
                ]),

            $this->request->isAtLeastPHP('7.4') =>
                CallGenerator::make('array_map', [
                    "fn (\$i): {$typeHint} => {$subExpr}",
                    $expr,
                ]),

            $this->request->isAtLeastPHP('7.0') =>
                CallGenerator::make('array_map', [
                    "function(\$i): {$typeHint} use ({$this->buildUseClause()}) {{$callbackBody}}",
                    $expr,
                ]),

            default =>
                CallGenerator::make('array_map', [
                    "function(\$i) use ({$this->buildUseClause()}) {{$callbackBody}}",
                    $expr,
                ]),
        };
    }

    public function outputMappingExpr(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return CallGenerator::make('array_map', [
                "fn (\$i) => \$i",
                $expr,
            ]);
        }

        $subType = $this->subTypeName();
        $subExpr = $this->itemType->outputMappingExpr('$i');

        if ($this->request->isAtLeastPHP('7.4')) {
            return CallGenerator::make('array_map', [
                "fn ($subType \$i) => {$subExpr}",
                $expr,
            ]);
        }

        $returnExpr = "return {$subExpr};";
        $funcBody = mb_strlen($returnExpr) < 50
            ? " {$returnExpr} "
            : "\n" . StringUtils::indentCode($returnExpr) . "\n";

        return CallGenerator::make('array_map', [
            "function($subType \$i) {{$funcBody}}",
            $expr,
        ]);
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return CallGenerator::make('array_map', [
                "fn (\$i) => \$i",
                $expr,
            ]);
        }

        $subType = $this->subTypeName();
        $subExpr = $this->itemType->outputMappingExprStdClass('$i');

        if ($this->request->isAtLeastPHP('7.4')) {
            return CallGenerator::make('array_map', [
                "fn ($subType \$i) => {$subExpr}",
                $expr,
            ]);
        }
        
        $returnExpr = "return {$subExpr};";
        $funcBody = mb_strlen($returnExpr) < 50
            ? " {$returnExpr} "
            : "\n" . StringUtils::indentCode($returnExpr) . "\n";

        return CallGenerator::make('array_map', [
            "function($subType \$i) {{$funcBody}}",
            $expr,
        ]);
    }

    public function cloneExpr(string $expr): string
    {
        if ($this->itemType instanceof MixedProperty) {
            return CallGenerator::make('array_map', [
                "fn (\$i) => \$i",
                $expr,
            ]);
        }

        $st = $this->subTypeName();

        if ($this->request->isAtLeastPHP('7.4')) {
            return CallGenerator::make('array_map', [
                "fn ({$st} \$i) => clone \$i",
                $expr,
            ]);
        }

        return CallGenerator::make('array_map', [
            "function({$st} \$i) { return clone \$i; }",
            $expr,
        ]);
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
