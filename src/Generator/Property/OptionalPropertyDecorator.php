<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property;

class OptionalPropertyDecorator extends NullablePropertyDecorator implements RenameablePropertyInterface
{
    use CodeFormatting;

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

        $default    = isset($this->schema()["default"]) ? $this->schema()["default"] : null;
        $defaultExp = rtrim($this->formatValue($default)->generate(), ";");

        return "\${$name} = isset($accessor) ? {$mapped} : {$defaultExp};";
    }

    /**
     * @param string $outputVarName
     * @return string
     */
    public function convertTypeToArray(string $outputVarName = 'output'): string
    {
        $name  = $this->inner->name();
        $inner = $this->inner->convertTypeToArray($outputVarName);

        return "if (isset(\$this->{$name})) {\n" . $this->indentCode($inner, 1) . "\n}";
    }

    /**
     * @return string|null
     */
    public function cloneProperty(): ?string
    {
        $name  = $this->name();
        $inner = $this->inner->cloneProperty();

        if ($inner !== null) {
            return "if (isset(\$this->{$name})) {\n" . $this->indentCode($inner, 1) . "\n}";
        }

        return null;
    }

}
