<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Decorator;

use Helmich\Schema2Class\Generator\Class\ClassGenerator;
use Helmich\Schema2Class\Generator\Class\Method\SerializeMethodFactory;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\Property\RenameablePropertyInterface;
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
        $key = $this->key;
        $keyStr = var_export($key, true);
        $accessor = "\${$inputVarName}->{{$keyStr}}";

        $existsCheck = "isset($accessor)";
        if ($this->inner->allowsNull() || $this->isOptionalNullable) {
            $existsCheck = "property_exists(\${$inputVarName}, {$keyStr})";
        }

        return $existsCheck;
    }

    public function convertInputToType(string $inputVarName, string $optionalsVarName): string
    {
        $key   = $this->key;
        $keyStr = var_export($key, true);
        $name  = $this->inner->name();

        // JSON accessor:  $input->{'key'}   or   $input['key']
        $accessor = "\${$inputVarName}->{{$keyStr}}";

        // Build mapping expression. Nullable optionals must keep null values
        // intact; if the wrapped property already allows null it will handle the
        // guard itself.
        if ($this->isOptionalNullable) {
            $innerMap = $this->inner->generateInputMappingExpr($accessor, true);
            $mapped   = "({$accessor} !== null ? {$innerMap} : null)";
        } else {
            $mapped = $this->generateInputMappingExpr($accessor, true);
        }

        $existsCheck = $this->generateIssetCheckExpr($inputVarName);

        $code = "\${$name} = {$existsCheck} ? {$mapped} : null;";

        if ($this->isOptionalNullable) {
            $code .= "\nif ({$existsCheck}) {\n    \${$optionalsVarName}['{$this->key}'] = true;\n}";
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
            $map = $this->generateOutputMappingExpr("\$this->{$name}");
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
            $map = $this->generateOutputMappingExprStdClass("\$this->{$name}");
            $inner = "\${$outputVarName}->{{$keyStr}} = {$map};";
            return "if ({$check}) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
        }

        $inner = $this->inner->convertTypeToStdClass();

        if ($this->inner->allowsNull()) {
            return $inner;
        }

        return "if (isset(\$this->{$name})) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
    }

    public function cloneProperty(): ?string
    {
        $name  = $this->name();
        $inner = $this->inner->cloneProperty();

        if ($inner !== null) {
            return "if (isset(\$this->{$name})) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
        }

        return null;
    }

}
