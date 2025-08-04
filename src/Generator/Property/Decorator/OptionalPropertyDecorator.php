<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Decorator;

use Helmich\Schema2Class\Generator\Class\Method\FromInputMethodFactory;
use Helmich\Schema2Class\Generator\Class\Method\SerializeMethodFactory;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
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

    public function generateIssetCheckExpr(string $inputVarName): string
    {
        $keyStr = $this->keyStr();
        $accessor = "\${$inputVarName}->{{$keyStr}}";

        $existsCheck = "isset($accessor)";
        if ($this->inner->allowsNull() || $this->isOptionalNullable) {
            $existsCheck = "property_exists(\${$inputVarName}, {$keyStr})";
        }

        return $existsCheck;
    }

    public function convertInputToType(): string
    {
        $varName  = $this->inner->varName();

        $inputVarName = FromInputMethodFactory::INPUT_ARG_NAME;
        // JSON accessor:  $input->{'key'}   or   $input['key']
        $accessor = "\${$inputVarName}->{{$this->keyStr()}}";

        // Build mapping expression. Nullable optionals must keep null values
        // intact; if the wrapped property already allows null it will handle the
        // guard itself.
        if ($this->isOptionalNullable) {
            $innerMap = $this->inner->inputMappingExpr($accessor, true);
            $mapped   = "({$accessor} !== null ? {$innerMap} : null)";
        } else {
            $mapped = $this->inputMappingExpr($accessor, true);
        }

        $existsCheck = $this->generateIssetCheckExpr($inputVarName);

        $code = "\${$varName} = {$existsCheck} ? {$mapped} : null;";

        if ($this->isOptionalNullable) {
            $OPTIONALS_VAR_NAME = FromInputMethodFactory::OPTIONALS_VAR_NAME();
            $code .= "\nif ({$existsCheck}) {\n    \${$OPTIONALS_VAR_NAME}['{$this->key}'] = true;\n}";
        }

        return $code;
    }

    public function convertTypeToArray(): string
    {
        $propName  = $this->inner->propName();
        $outputVarName = SerializeMethodFactory::OUTPUT_VAR_NAME;

        if ($this->isOptionalNullable) {
            $OPTIONALS = PropertyNames::OPTIONALS;
            $check = "isset(\$this->{$propName}) || array_key_exists('{$this->key}', \$this->{$OPTIONALS})";

            $map = $this->outputMappingExpr("\$this->{$propName}");
            $inner = "\${$outputVarName}[{$this->keyStr()}] = {$map};";
            return "if ({$check}) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
        }

        $inner = $this->inner->convertTypeToArray();

        if ($this->inner->allowsNull()) {
            return $inner;
        }

        return "if (isset(\$this->{$propName})) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
    }

    public function convertTypeToStdClass(): string
    {
        $propName  = $this->inner->propName();
        $outputVarName = SerializeMethodFactory::OUTPUT_VAR_NAME;

        if ($this->isOptionalNullable) {
            $OPTIONALS = PropertyNames::OPTIONALS;
            $check = "isset(\$this->{$propName}) || array_key_exists('{$this->key}', \$this->{$OPTIONALS})";
            $keyStr = $this->keyStr();
            $map = $this->outputMappingExprStdClass("\$this->{$propName}");
            $inner = "\${$outputVarName}->{{$keyStr}} = {$map};";
            return "if ({$check}) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
        }

        $inner = $this->inner->convertTypeToStdClass();

        if ($this->inner->allowsNull()) {
            return $inner;
        }

        return "if (isset(\$this->{$propName})) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
    }

    public function cloneAssignment(): ?string
    {
        $propName  = $this->propName();
        $inner = $this->inner->cloneAssignment();

        if ($inner !== null) {
            return "if (isset(\$this->{$propName})) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
        }

        return null;
    }

}
