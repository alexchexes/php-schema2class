<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property;

class OptionalPropertyDecorator extends NullablePropertyDecorator implements RenameablePropertyInterface
{
    use CodeFormatting;

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
        $accessor = $object ? "\${$inputVarName}->{'$key'}" : "\${$inputVarName}['$key']";

        $existsCheck = "isset($accessor)";
        if ($this->inner->allowsNull() || $this->optionalNullable) {
            $existsCheck = $object
                ? "property_exists(\${$inputVarName}, '$key')"
                : "array_key_exists('$key', \${$inputVarName})";
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
        $name  = $this->inner->name();

        // JSON accessor:  $input->{'key'}   or   $input['key']
        $accessor = $object
            ? "\${$inputVarName}->{'$key'}"
            : "\${$inputVarName}['$key']";

        // Single mapping expression (with null-guard if the property is nullable)
        $mapped   = $this->generateInputMappingExpr($accessor, true);

        $existsCheck = $this->generateIssetCheckExpr($inputVarName, $object);

        $code = "\${$name} = {$existsCheck} ? {$mapped} : null;";

        if ($this->optionalNullable) {
            $code .= "\nif ({$existsCheck}) {\n    \$__explicitNulls['{$this->key}'] = true;\n}";
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
        $inner = $this->inner->convertTypeToArray($outputVarName);

        if ($this->optionalNullable) {
            $check = "isset(\$this->{$name}) || array_key_exists('{$this->key}', \$this->_explicitNulls)";
            return "if ({$check}) {\n{$this->indentCode($inner, 1)}\n}";
        }

        if ($this->inner->allowsNull()) {
            return $inner;
        }

        return "if (isset(\$this->{$name})) {\n{$this->indentCode($inner, 1)}\n}";
    }

    /**
     * @return string|null
     */
    public function cloneProperty(): ?string
    {
        $name  = $this->name();
        $inner = $this->inner->cloneProperty();

        if ($inner !== null) {
            return "if (isset(\$this->{$name})) {\n{$this->indentCode($inner, 1)}\n}";
        }

        return null;
    }

}
