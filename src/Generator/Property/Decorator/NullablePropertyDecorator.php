<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Decorator;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Class\Method\FromInputMethodFactory;
use Helmich\Schema2Class\Generator\Class\VariableNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Type\Primitive\NullProperty;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Generator\Property\Type\Primitive\StringProperty;
use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\Generator\PropertyValueGenerator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Decorator that wraps another property to allow `null` values in addition to
 * its normal type.
 */
class NullablePropertyDecorator implements PropertyDecoratorInterface
{
    public function __construct(
        protected string $key,
        protected PropertyInterface $inner,
        private GeneratorRequest $request,
    ) {}

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

    public function schema(): array
    {
        return $this->inner->schema();
    }

    public function description(): ?string
    {
        return $this->inner->description();
    }

    public function key(): string
    {
        return $this->inner->key();
    }

    public function keyStr(): string
    {
        return $this->inner->keyStr();
    }

    public function propName(): string
    {
        return $this->inner->propName();
    }

    public function varName(): string
    {
        return $this->inner->varName();
    }

    public function methodName(): string
    {
        return $this->inner->methodName();
    }

    public function setName(string $name): void
    {
        $this->inner->setName($name);
    }

    public function setVarName(string $name): void
    {
        $this->inner->setVarName($name);
    }

    public function setMethodName(string $name): void
    {
        $this->inner->setMethodName($name);
    }

    public function needsValidation(): bool
    {
        return $this->inner->needsValidation();
    }

    public function unwrap(): PropertyInterface
    {
        return $this->inner;
    }

    public function allowsNull(): bool
    {
        return true; // that's the point of NullableProperty
    }

    public function convertInputToType(): string
    {
        $varName  = $this->inner->varName();

        $inputVarName = ArgumentNames::INPUT;
        $accessor = "\${$inputVarName}->{{$this->keyStr()}}";

        $mapped = $this->inner->inputMappingExpr($accessor);

        // we don't need null guards for string and null type properties
        $needsGuard = !($this->inner instanceof StringProperty || $this->inner instanceof NullProperty);

        $expr = $needsGuard
            ? "({$accessor} !== null ? {$mapped} : null)"
            : $mapped;               // clean one-liner for strings/nulls

        return "\${$varName} = {$expr};";
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
        if ($ann !== 'mixed' && !preg_match('/(^|\\|)null(\\||$)/', $ann)) {
            $ann .= '|null';
        }

        return $ann;
    }

    /**
     * @param $phpVersion
     * @return string|null
     */
    public function typeHint(): ?string
    {
        $phpVersion = $this->request->getTargetPHPVersion();
        $hint = $this->inner->typeHint();

        if (!$this->request->isAtLeastPHP('7.1')) {
            // `?` prefix was introduced in PHP 7.1, before that version to declare typed
            // parameter as nullable we used `(array $foo = null)`, so return inner type hint as is
            return $hint;
        }

        if ($hint === null) {
            return null;
        }

        if ($this->request->isAtLeastPHP('8.0') && str_contains($hint, "|")) {
            if (!preg_match('/(^|\\|)null(\\||$)/', $hint)) {
                return "{$hint}|null";
            }
            return $hint;
        }

        if ($this->request->isAtLeastPHP('7.1') && strpos($hint, "?") !== 0) { // @phpstan-ignore-line
            if ($hint === "mixed" || $hint === "null") {
                return $hint;
            }

            $hint = "?" . $hint;
        }

        return $hint;
    }

    public function cloneAssignment(): ?string
    {
        return $this->inner->cloneAssignment();
    }

    public function typeAssertionExpr(string $expr): string
    {
        return "(({$expr}) === null) || ({$this->inner->typeAssertionExpr($expr)})";
    }

    public function inputAssertionExpr(string $expr): string
    {
        return "(({$expr}) === null) || ({$this->inner->inputAssertionExpr($expr)})";
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        // Let the inner property build its own mapping (casts, builder calls, …)
        $inner = $this->inner->inputMappingExpr($expr, $asserted);

        // If we're already inside an `isset()` check (asserted === true),
        // the value cannot be null → no extra guard needed.
        if ($asserted) {
            return $inner;
        }

        // Top-level nullable field: we still need the null-guard here.
        return "({$expr} !== null ? {$inner} : null)";
    }

    public function outputMappingExpr(string $expr): string
    {
        $inner = $this->inner->outputMappingExpr($expr);
        return "({$expr} !== null) ? ({$inner}) : null";
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        $inner = $this->inner->outputMappingExprStdClass($expr);
        return "({$expr} !== null) ? ({$inner}) : null";
    }

    public function cloneExpr(string $expr): string
    {
        return "isset({$expr}) ? (clone {$expr}) : null";
    }

    public function formatValue(mixed $value): PropertyValueGenerator
    {
        return $this->inner->formatValue($value);
    }
}
