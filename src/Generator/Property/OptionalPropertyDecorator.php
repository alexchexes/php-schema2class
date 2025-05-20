<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property;

class OptionalPropertyDecorator extends NullablePropertyDecorator
{
    use CodeFormatting;

    /**
     * @param string $inputVarName
     * @param bool $object
     * @return string
     */
    public function convertJSONToType(string $inputVarName = 'input', bool $object = false): string
    {
        $key   = $this->key;
        $name  = $this->inner->name();
        $inner = $this->inner->convertJSONToType($inputVarName, $object);

        $default    = isset($this->schema()["default"]) ? $this->schema()["default"] : null;
        $defaultExp = rtrim($this->formatValue($default)->generate(), ";");

        $accessor = $object ? "\${$inputVarName}->{'$key'}" : "\${$inputVarName}['$key']";

        return "\${$name} = {$defaultExp};\nif (isset($accessor)) {\n" . $this->indentCode($inner, 1) . "\n}";
    }

    /**
     * @param string $outputVarName
     * @return string
     */
    public function convertTypeToJSON(string $outputVarName = 'output'): string
    {
        $name  = $this->inner->name();
        $inner = $this->inner->convertTypeToJSON($outputVarName);

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
