<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Decorator;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Class\Method\FromInputMethodFactory;
use Helmich\Schema2Class\Generator\Class\Method\Serialize\SerializeMethodFactory;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\Class\VariableNames;
use Helmich\Schema2Class\Generator\Expression\OrGenerator;
use Helmich\Schema2Class\Generator\Expression\TernaryGenerator;
use Helmich\Schema2Class\Util\StringUtils;

/**
 * Decorator that marks a property as optional. Can also track if null was
 * explicitly provided to distinguish between missing and null values.
 */
class OptionalPropertyDecorator extends NullablePropertyDecorator
{
    private bool $isOptionalNullable = false;

    public function markOptionalNullable(): void
    {
        $this->isOptionalNullable = true;
    }

    public function isOptionalNullable(): bool
    {
        return $this->isOptionalNullable;
    }

    public function allowsNull(): bool
    {
        return $this->isOptionalNullable || $this->inner->allowsNull();
    }

    public function typeAssertionExpr(string $expr): string
    {
        return $this->allowsNull()
            ? parent::typeAssertionExpr($expr)
            : $this->inner->typeAssertionExpr($expr);
    }

    public function inputAssertionExpr(string $expr): string
    {
        return $this->allowsNull()
            ? parent::inputAssertionExpr($expr)
            : $this->inner->inputAssertionExpr($expr);
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        return $this->allowsNull()
            ? parent::inputMappingExpr($expr, $asserted)
            : $this->inner->inputMappingExpr($expr, $asserted);
    }

    public function outputMappingExpr(string $expr): string
    {
        return $this->allowsNull()
            ? parent::outputMappingExpr($expr)
            : $this->inner->outputMappingExpr($expr);
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        return $this->allowsNull()
            ? parent::outputMappingExprStdClass($expr)
            : $this->inner->outputMappingExprStdClass($expr);
    }

    public function cloneExpr(string $expr): string
    {
        return $this->allowsNull()
            ? parent::cloneExpr($expr)
            : $this->inner->cloneExpr($expr);
    }

    public function generateIssetCheckExpr(string $inputVarName): string
    {
        $keyStr = $this->keyStr();
        $accessor = "\${$inputVarName}->{{$keyStr}}";

        if ($this->inner->allowsNull() || $this->isOptionalNullable) {
            $existsCheck = "property_exists(\${$inputVarName}, {$keyStr})";
        } else {
            $existsCheck = "isset($accessor)";
        }

        return $existsCheck;
    }

    public function convertInputToType(): string
    {
        $varName  = $this->inner->varName();

        $inputVarName = ArgumentNames::INPUT;
        // JSON accessor:  $input->{'key'}   or   $input['key']
        $accessor = "\${$inputVarName}->{{$this->keyStr()}}";

        // Build mapping expression. Nullable optionals must keep null values intact;
        // if the wrapped property already allows null it will handle the guard itself.
        if ($this->isOptionalNullable) {
            $innerMap = $this->inner->inputMappingExpr($accessor, true);
            $mapped = TernaryGenerator::make("{$accessor} !== null", $innerMap, "null");
        } else {
            $mapped = $this->inputMappingExpr($accessor, true);
        }

        $existsCheck = $this->generateIssetCheckExpr($inputVarName);

        if ($this->isOptionalNullable) {
            $OPTIONALS_VAR_NAME = VariableNames::PROVIDED_OPTIONALS;
            $indent = StringUtils::indentCode(...);
            $code =
                <<<PHP
                \${$varName} = null;
                if ({$existsCheck}) {
                {$indent("\${$varName} = {$mapped};")}
                {$indent("\${$OPTIONALS_VAR_NAME}['{$this->key}'] = true;")}
                }
                PHP;
        } else {
            $toAssign = TernaryGenerator::make($existsCheck, $mapped, "null", false);
            $code = "\${$varName} = {$toAssign};";
        }
        return $code;
    }

    public function convertTypeToArray(): string
    {
        $propName  = $this->inner->propName();
        $outputVarName = VariableNames::OUTPUT;

        if ($this->isOptionalNullable) {
            $OPTIONALS = PropertyNames::PROVIDED_OPTIONALS;

            $parenthesizedCond = OrGenerator::make([
                "isset(\$this->{$propName})",
                "array_key_exists('{$this->key}', \$this->{$OPTIONALS})",
            ], lengthToWrap: 110);

            $map = $this->outputMappingExpr("\$this->{$propName}");
            $inner = "\${$outputVarName}[{$this->keyStr()}] = {$map};";
            return "if {$parenthesizedCond} {\n" . StringUtils::indentCode($inner) . "\n}";
        }

        $inner = $this->inner->convertTypeToArray();

        if ($this->inner->allowsNull()) {
            return $inner;
        }

        return "if (isset(\$this->{$propName})) {\n" . StringUtils::indentCode($inner) . "\n}";
    }

    public function convertTypeToStdClass(): string
    {
        $propName  = $this->inner->propName();
        $outputVarName = VariableNames::OUTPUT;

        if ($this->isOptionalNullable) {
            $OPTIONALS = PropertyNames::PROVIDED_OPTIONALS;

            $parenthesizedCond = OrGenerator::make([
                "isset(\$this->{$propName})",
                "array_key_exists('{$this->key}', \$this->{$OPTIONALS})",
            ], lengthToWrap: 110);

            $map = $this->outputMappingExprStdClass("\$this->{$propName}");
            $inner = "\${$outputVarName}->{{$this->keyStr()}} = {$map};";
            return "if {$parenthesizedCond} {\n" . StringUtils::indentCode($inner) . "\n}";
        }

        $inner = $this->inner->convertTypeToStdClass();

        if ($this->inner->allowsNull()) {
            return $inner;
        }

        return "if (isset(\$this->{$propName})) {\n" . StringUtils::indentCode($inner) . "\n}";
    }

    public function cloneAssignment(): ?string
    {
        $propName  = $this->propName();
        $inner = $this->inner->cloneAssignment();

        if ($inner !== null) {
            return "if (isset(\$this->{$propName})) {\n" . StringUtils::indentCode($inner) . "\n}";
        }

        return null;
    }

}
