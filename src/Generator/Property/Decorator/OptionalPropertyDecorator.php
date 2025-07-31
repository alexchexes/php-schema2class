<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Decorator;

use Helmich\Schema2Class\Generator\Property\RenameablePropertyInterface;
use Helmich\Schema2Class\Util\StringUtils;

/**
 * Decorator that marks a property as optional. Can also track if null was
 * explicitly provided to distinguish between missing and null values.
 */
class OptionalPropertyDecorator extends NullablePropertyDecorator implements RenameablePropertyInterface
{
    private bool $optionalNullable = false;

    public function markOptionalNullable(): void
    {
        $this->optionalNullable = true;
    }

    public function isOptionalNullable(): bool
    {
        return $this->optionalNullable;
    }

    public function generateIssetCheckExpr(string $inputVarName = 'input', bool $object = false): string
    {
        $key = $this->key;
        $keyStr = var_export($key, true);
        $accessor = $object ? "\${$inputVarName}->{{$keyStr}}" : "\${$inputVarName}[{$keyStr}]";

        $existsCheck = "isset($accessor)";
        if ($this->inner->allowsNull() || $this->optionalNullable) {
            $existsCheck = $object
                ? "property_exists(\${$inputVarName}, {$keyStr})"
                : "array_key_exists({$keyStr}, \${$inputVarName})";
        }

        return $existsCheck;
    }

    /**
     * @param string $inputVarName
     * @param bool $object
     * @return string
     */
    public function convertInputToType(string $inputVarName = 'input', bool $object = false): string
    {
        $key   = $this->key;
        $keyStr = var_export($key, true);
        $name  = $this->inner->name();

        // JSON accessor:  $input->{'key'}   or   $input['key']
        $accessor = $object
            ? "\${$inputVarName}->{{$keyStr}}"
            : "\${$inputVarName}[{$keyStr}]";

        // Build mapping expression. Nullable optionals must keep null values
        // intact; if the wrapped property already allows null it will handle the
        // guard itself.
        if ($this->optionalNullable) {
            $innerMap = $this->inner->generateInputMappingExpr($accessor, true);
            $mapped   = "({$accessor} !== null ? {$innerMap} : null)";
        } else {
            $mapped = $this->generateInputMappingExpr($accessor, true);
        }

        $existsCheck = $this->generateIssetCheckExpr($inputVarName, $object);

        $code = "\${$name} = {$existsCheck} ? {$mapped} : null;";

        if ($this->optionalNullable) {
            $code .= "\nif ({$existsCheck}) {\n    \$__providedOptionals['{$this->key}'] = true;\n}";
        }

        return $code;
    }

    /**
     * @param string $outputVarName
     * @return string
     */
    public function convertTypeToArray(string $outputVarName = 'output'): string
    {
        $name  = $this->inner->name();

        if ($this->optionalNullable) {
            $check = "isset(\$this->{$name}) || array_key_exists('{$this->key}', \$this->_providedOptionals)";
            $keyStr = var_export($this->key, true);
            $map = $this->generateOutputMappingExpr("\$this->{$name}");
            $inner = "\${$outputVarName}[{$keyStr}] = {$map};";
            return "if ({$check}) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
        }

        $inner = $this->inner->convertTypeToArray($outputVarName);

        if ($this->inner->allowsNull()) {
            return $inner;
        }

        return "if (isset(\$this->{$name})) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
    }

    public function convertTypeToStdClass(string $outputVarName = 'output'): string
    {
        $name  = $this->inner->name();

        if ($this->optionalNullable) {
            $check = "isset(\$this->{$name}) || array_key_exists('{$this->key}', \$this->_providedOptionals)";
            $keyStr = var_export($this->key, true);
            $map = $this->generateOutputMappingExprStdClass("\$this->{$name}");
            $inner = "\${$outputVarName}->{{$keyStr}} = {$map};";
            return "if ({$check}) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
        }

        $inner = $this->inner->convertTypeToStdClass($outputVarName);

        if ($this->inner->allowsNull()) {
            return $inner;
        }

        return "if (isset(\$this->{$name})) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
    }

    /**
     * @return string|null
     */
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
