<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Decorator;

use Composer\Semver\Semver;
use Helmich\Schema2Class\Generator\Property\Type\NullProperty;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Generator\Property\Type\StringProperty;
use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\Generator\PropertyValueGenerator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Decorator that wraps another property to allow `null` values in addition to
 * its normal type.
 */
class NullablePropertyDecorator implements PropertyDecoratorInterface
{
    protected string $key;
    protected PropertyInterface $inner;

    public function __construct(string $key, PropertyInterface $inner)
    {
        $this->key   = $key;
        $this->inner = $inner;
    }

    public static function canHandleSchema(array $schema): bool
    {
        return false;
    }

    /**
     * @return void
     */
    public function generateSubTypes(WriterInterface $writer, OutputInterface $output): void
    {
        $this->inner->generateSubTypes($writer, $output);
    }

    public function name(): string
    {
        return $this->inner->name();
    }

    public function key(): string
    {
        return $this->inner->key();
    }

    public function schema(): array
    {
        return $this->inner->schema();
    }

    public function description(): ?string
    {
        return $this->inner->description();
    }

    public function isComplex(): bool
    {
        return $this->inner->isComplex();
    }

    public function unwrap(): PropertyInterface
    {
        return $this->inner;
    }

    public function allowsNull(): bool
    {
        // We *always* allow null once decorated
        return true;
    }

    public function convertInputToType(string $inputVarName, string $optionalsVarName): string
    {
        // Key name in the JSON object
        $key   = $this->key;
        $keyStr  = var_export($key, true);
        $name  = $this->inner->name(); // local variable to assign to

        $accessor = "\${$inputVarName}->{{$keyStr}}";

        $mapped = $this->inner->generateInputMappingExpr($accessor);

        // we don't need null guards for string and null type properties
        $needsGuard = !($this->inner instanceof StringProperty || $this->inner instanceof NullProperty);

        $expr = $needsGuard
            ? "({$accessor} !== null ? {$mapped} : null)"
            : $mapped;               // clean one-liner for strings/nulls

        return "\${$name} = {$expr};";
    }

    public function convertTypeToArray(): string
    {
        return $this->inner->convertTypeToArray();
    }

    public function convertTypeToStdClass(): string
    {
        return $this->inner->convertTypeToStdClass();
    }

    public function typeAnnotation(): string
    {
        $ann = $this->inner->typeAnnotation();

        // Add "null" to PHPDoc & type-hint if missing
        if (!preg_match('/(^|\\|)null(\\||$)/', $ann)) {
            $ann .= '|null';
        }

        return $ann;
    }

    public function cloneProperty(): ?string
    {
        return $this->inner->cloneProperty();
    }

    /**
     * @param $phpVersion
     * @return string|null
     */
    public function typeHint(string $phpVersion): ?string
    {
        $hint = $this->inner->typeHint($phpVersion);

        if (Semver::satisfies($phpVersion, "<7.0")) {
            return $hint;
        }

        if ($hint === null) {
            return null;
        }

        if (Semver::satisfies($phpVersion, ">=8.0") && str_contains($hint, "|")) {
            if (!preg_match('/(^|\\|)null(\\||$)/', $hint)) {
                return "{$hint}|null";
            }
            return $hint;
        }

        if (Semver::satisfies($phpVersion, ">=7.1.0") && strpos($hint, "?") !== 0) {
            if ($hint === "mixed" || $hint === "null") {
                return $hint;
            }

            $hint = "?" . $hint;
        }

        return $hint;
    }

    public function generateTypeAssertionExpr(string $expr): string
    {
        return "(({$expr}) === null) || ({$this->inner->generateTypeAssertionExpr($expr)})";
    }

    public function generateInputAssertionExpr(string $expr): string
    {
        return "(({$expr}) === null) || ({$this->inner->generateInputAssertionExpr($expr)})";
    }

    public function generateInputMappingExpr(string $expr, bool $asserted = false): string
    {
        // Let the inner property build its own mapping (casts, builder calls, …)
        $inner = $this->inner->generateInputMappingExpr($expr, $asserted);

        // If we're already inside an `isset()` check (asserted === true),
        // the value cannot be null → no extra guard needed.
        if ($asserted) {
            return $inner;
        }

        // Top-level nullable field: we still need the null-guard here.
        return "({$expr} !== null ? {$inner} : null)";
    }

    public function generateOutputMappingExpr(string $expr): string
    {
        $inner = $this->inner->generateOutputMappingExpr($expr);
        return "({$expr} !== null) ? ({$inner}) : null";
    }

    public function generateOutputMappingExprStdClass(string $expr): string
    {
        $inner = $this->inner->generateOutputMappingExprStdClass($expr);
        return "({$expr} !== null) ? ({$inner}) : null";
    }

    public function generateCloneExpr(string $expr): string
    {
        return "isset({$expr}) ? (clone {$expr}) : null";
    }

    public function formatValue(mixed $value): PropertyValueGenerator
    {
        return $this->inner->formatValue($value);
    }

    public function setName(string $name): void
    {
        $this->inner->setName($name);
    }
}
