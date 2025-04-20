<?php
declare(strict_types = 1);

namespace Helmich\Schema2Class\Generator\Property;

use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\SchemaToClass;
use Helmich\Schema2Class\Generator\SchemaToEnum;
use Laminas\Code\Generator\PropertyValueGenerator;
use Laminas\Code\Generator\ValueGenerator;

class StringEnumProperty extends AbstractProperty
{
    use TypeConvert;

    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema["type"], $schema["enum"])
            && $schema["type"] === "string";
    }

    public function isComplex(): bool
    {
        // Only “complex” if we will generate a PHP 8.1+ enum class
        return $this->generatorRequest->isAtLeastPHP("8.1") && isset($this->schema["enum"]);
    }

    /**
     * Generate a real enum class only on PHP 8.1+.
     *
     * @throws GeneratorException
     */
    public function generateSubTypes(SchemaToClass $generator): void
    {
        if (!$this->generatorRequest->isAtLeastPHP("8.1")) {
            // no enum classes on PHP <8.1
            return;
        }
        parent::generateSubTypes($generator);
    }

    public function typeAnnotation(): string
    {
        if ($this->generatorRequest->isAtLeastPHP("8.1")) {
            // will be a real enum class name
            return $this->subTypeName();
        }

        // fallback: a literal‑union of all enum values
        $literals = array_map(
            fn(string|int $v) => "'" . $v . "'",
            $this->schema['enum']
        );

        return implode('|', $literals);
    }

    public function typeHint(string $phpVersion): ?string
    {
        if ($this->generatorRequest->isAtLeastPHP("8.1")) {
            return "\\" . $this->generatorRequest->getTargetNamespace() . "\\" . $this->subTypeName();
        }

        // fallback to plain string
        return 'string';
    }

    public function generateTypeAssertionExpr(string $expr): string
    {
        if ($this->generatorRequest->isAtLeastPHP("8.1")) {
            return "{$expr} instanceof {$this->subTypeName()}";
        }

        // fallback: check it's a string and one of the allowed values
        $values = var_export($this->schema["enum"], true);
        return "is_string({$expr}) && in_array({$expr}, {$values}, true)";
    }

    public function generateInputAssertionExpr(string $expr): string
    {
        if ($this->generatorRequest->isAtLeastPHP("8.1")) {
            return "{$this->subTypeName()}::tryFrom({$expr}) !== null";
        }

        $values = var_export($this->schema["enum"], true);
        return "in_array({$expr}, {$values}, true)";
    }

    public function generateInputMappingExpr(string $expr, bool $asserted = false): string
    {
        if ($this->generatorRequest->isAtLeastPHP("8.1")) {
            return "{$this->subTypeName()}::from({$expr})";
        }

        // fallback: accept raw string
        return $expr;
    }

    public function generateOutputMappingExpr(string $expr): string
    {
        if ($this->generatorRequest->isAtLeastPHP("8.1")) {
            return "({$expr})->value";
        }

        return $expr;
    }

    public function generateCloneExpr(string $expr): string
    {
        // enum or string, same copy semantics
        return $expr;
    }

    public function formatValue(mixed $value): PropertyValueGenerator
    {
        if ($value === null) {
            return new PropertyValueGenerator(null);
        }

        if ($this->generatorRequest->isAtLeastPHP("8.1")) {
            // Use TYPE_CONSTANT for enum-backed constant
            return new PropertyValueGenerator(
                $this->subTypeName() . "::" . SchemaToEnum::enumCaseName($value),
                ValueGenerator::TYPE_CONSTANT
            );
        }

        // fallback: literal string
        return new PropertyValueGenerator($value, ValueGenerator::TYPE_STRING);
    }

    private function subTypeName(): string
    {
        return $this->generatorRequest->getTargetClass() . $this->capitalizedName;
    }
}
