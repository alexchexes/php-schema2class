<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Decorator;

use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Util\StringUtils;
use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\Generator\PropertyValueGenerator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Decorator that injects default value support to another property.
 */
class DefaultPropertyDecorator implements PropertyDecoratorInterface
{
    private string $key;

    private PropertyInterface $inner;

    public function __construct(string $key, PropertyInterface $inner)
    {
        $this->key   = $key;
        $this->inner = $inner;
    }

    /**
     * @return bool
     */
    public function isComplex(): bool
    {
        return $this->inner->isComplex();
    }

    /**
     * @param array $schema
     * @return bool
     */
    public static function canHandleSchema(array $schema): bool
    {
        return false;
    }

    private function defaultExpr(): string
    {
        $default = $this->schema()["default"];
        return rtrim($this->formatValue($default)->generate(), ";");
    }

    /**
     * @param string $inputVarName
     * @return string
     */
    public function convertInputToType(string $inputVarName, string $optionalsVarName): string
    {
        $key   = $this->key;
        $keyStr = var_export($key, true);
        $name  = $this->name();
        $inner = $this->inner->convertInputToType($inputVarName, $optionalsVarName);

        $defaultExp = $this->defaultExpr();
        $accessor   = "\${$inputVarName}->{{$keyStr}}";

        return "\${$name} = {$defaultExp};\nif (isset($accessor)) {\n" . StringUtils::indentCode($inner, 1) . "\n}";
    }

    public function convertTypeToArray(): string
    {
        return $this->inner->convertTypeToArray();
    }

    public function convertTypeToStdClass(): string
    {
        return $this->inner->convertTypeToStdClass();
    }

    public function generateSubTypes(WriterInterface $writer, OutputInterface $output): void
    {
        $this->inner->generateSubTypes($writer, $output);
    }

    public function typeAnnotation(): string
    {
        return $this->inner->typeAnnotation();
    }

    public function typeHint(string $phpVersion): ?string
    {
        return $this->inner->typeHint($phpVersion);
    }

    public function cloneAssignment(): ?string
    {
        return $this->inner->cloneAssignment();
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

    public function name(): string
    {
        return $this->inner->name();
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

    public function unwrap(): PropertyInterface
    {
        return $this->inner;
    }

    public function genTypeAssertionExpr(string $expr): string
    {
        return $this->inner->genTypeAssertionExpr($expr);
    }

    public function genInputAssertionExpr(string $expr): string
    {
        return "(({$expr}) === null) || ({$this->inner->genInputAssertionExpr($expr)})";
    }

    public function genMappingExpr(string $expr, bool $asserted = false): string
    {
        $inner = $this->inner->genMappingExpr($expr);
        return "({$expr} !== null ? {$inner} : {$this->defaultExpr()})";
    }

    public function genOutputMappingExpr(string $expr): string
    {
        return $this->inner->genOutputMappingExpr($expr);
    }

    public function genOutputMappingExprStdClass(string $expr): string
    {
        return $this->inner->genOutputMappingExprStdClass($expr);
    }

    public function cloneExpr(string $expr): string
    {
        return $this->inner->cloneExpr($expr);
    }

    public function formatValue(mixed $value): PropertyValueGenerator
    {
        return $this->inner->formatValue($value);
    }

    public function allowsNull(): bool { return $this->inner->allowsNull(); }
}
