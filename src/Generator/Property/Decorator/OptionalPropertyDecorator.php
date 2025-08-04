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
        $keyStr = var_export($this->key, true);
        $accessor = "\${$inputVarName}->{{$keyStr}}";

        $existsCheck = "isset($accessor)";
        if ($this->inner->allowsNull() || $this->isOptionalNullable) {
            $existsCheck = "property_exists(\${$inputVarName}, {$keyStr})";
        }

        return $existsCheck;
    }

    public function convertInputToType(): string
    {
        $keyStr = var_export($this->key, true);
        $name  = $this->inner->varName();

        $inputVarName = FromInputMethodFactory::INPUT_ARG_NAME;
        // JSON accessor:  $input->{'key'}   or   $input['key']
        $accessor = "\${$inputVarName}->{{$keyStr}}";

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

        $code = "\${$name} = {$existsCheck} ? {$mapped} : null;";

        if ($this->isOptionalNullable) {
            $OPTIONALS_VAR_NAME = FromInputMethodFactory::OPTIONALS_VAR_NAME();
            $code .= "\nif ({$existsCheck}) {\n    \${$OPTIONALS_VAR_NAME}['{$this->key}'] = true;\n}";
        }

        return $code;
    }

    public function convertTypeToArray(): string
    {
        $name  = $this->inner->name();
        $outputVarName = SerializeMethodFactory::OUTPUT_VAR_NAME;

        if ($this->isOptionalNullable) {
            $OPTIONALS = PropertyNames::OPTIONALS;
            $check = "isset(\$this->{$name}) || array_key_exists('{$this->key}', \$this->{$OPTIONALS})";

            $keyStr = var_export($this->key, true);
            $map = $this->outputMappingExpr("\$this->{$name}");
            $inner = "\${$outputVarName}[{$keyStr}] = {$map};";
            return "if ({$check}) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
        }

        $inner = $this->inner->convertTypeToArray();

        if ($this->inner->allowsNull()) {
            return $inner;
        }

        return "if (isset(\$this->{$name})) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
    }

    public function convertTypeToStdClass(): string
    {
        $name  = $this->inner->name();
        $outputVarName = SerializeMethodFactory::OUTPUT_VAR_NAME;

        if ($this->isOptionalNullable) {
            $OPTIONALS = PropertyNames::OPTIONALS;
            $check = "isset(\$this->{$name}) || array_key_exists('{$this->key}', \$this->{$OPTIONALS})";
            $keyStr = var_export($this->key, true);
            $map = $this->outputMappingExprStdClass("\$this->{$name}");
            $inner = "\${$outputVarName}->{{$keyStr}} = {$map};";
            return "if ({$check}) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
        }

        $inner = $this->inner->convertTypeToStdClass();

        if ($this->inner->allowsNull()) {
            return $inner;
        }

        return "if (isset(\$this->{$name})) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
    }

    public function cloneAssignment(): ?string
    {
        $name  = $this->name();
        $inner = $this->inner->cloneAssignment();

        if ($inner !== null) {
            return "if (isset(\$this->{$name})) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
        }

        return null;
    }

}
