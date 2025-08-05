<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\Enum\SchemaToEnum;
use Helmich\Schema2Class\Util\EnumUtils;
use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\Generator\PropertyValueGenerator;
use Laminas\Code\Generator\ValueGenerator;
use Symfony\Component\Console\Output\OutputInterface;

/** 
 * Represents string property that may be emitted as PHP enums when supported.
 */
class StringEnumProperty extends AbstractProperty
{
    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema["type"], $schema["enum"])
            && $schema["type"] === "string";
    }

    public function needsValidation(): bool
    {
        // Validation required only when no native enum class will be emitted
        if ($this->request->isAtLeastPHP("8.1") && !$this->request->getNoEnums()) {
            return false;
        }
        return true;
    }

    /**
     * Generate a real enum class only on PHP 8.1+.
     *
     * @throws GeneratorException
     */
    public function generateSubTypes(WriterInterface $writer, OutputInterface $output): void
    {
        if ($this->request->isAtLeastPHP("8.1") && !$this->request->getNoEnums()) {
            $req = $this->request
                ->withSchema($this->schema)
                ->withClass($this->subTypeName());
                
            $generator = $this->request->getSchemaToClassFactory()->build($writer, $output);
            $generator->schemaToClass($this->propagateRootDefinitions($req));
        }
    }

    public function typeAnnotation(): string
    {
        if ($this->request->isAtLeastPHP("8.1") && !$this->request->getNoEnums()) {
            // will be a real enum class name
            return $this->subTypeName();
        }

        // fallback: a literal‑union of all enum values
        $values = array_filter(
            $this->schema['enum'],
            static fn(string|null $v) => $v !== null
        );

        return EnumUtils::typeAnnotation($values);
    }

    public function typeHint(): ?string
    {
        if ($this->request->isAtLeastPHP("8.1") && !$this->request->getNoEnums()) {
            return "\\" . $this->request->getTargetNamespace() . "\\" . $this->subTypeName();
        }
        $values = array_filter(
            $this->schema['enum'],
            static fn(string|null $v) => $v !== null
        );

        return EnumUtils::typeHint($values, $this->request->getTargetPHPVersion());
    }

    public function typeAssertionExpr(string $expr): string
    {
        if ($this->request->isAtLeastPHP("8.1") && !$this->request->getNoEnums()) {
            return "{$expr} instanceof {$this->subTypeName()}";
        }

        // fallback: check the value is one of the allowed ones
        return EnumUtils::assertionExpr($this->schema["enum"], $expr);
    }

    public function inputAssertionExpr(string $expr): string
    {
        if ($this->request->isAtLeastPHP("8.1") && !$this->request->getNoEnums()) {
            return "{$this->subTypeName()}::tryFrom({$expr}) !== null";
        }

        return EnumUtils::assertionExpr($this->schema["enum"], $expr);
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        if ($this->request->isAtLeastPHP("8.1") && !$this->request->getNoEnums()) {
            return "{$this->subTypeName()}::from({$expr})";
        }

        // fallback: accept raw string
        return $expr;
    }

    public function outputMappingExpr(string $expr): string
    {
        if ($this->request->isAtLeastPHP("8.1") && !$this->request->getNoEnums()) {
            return "({$expr})->value";
        }

        return $expr;
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        return $this->outputMappingExpr($expr);
    }

    public function cloneExpr(string $expr): string
    {
        // enum or string, same copy semantics
        return $expr;
    }

    public function formatValue(mixed $value): PropertyValueGenerator
    {
        if ($value === null) {
            return new PropertyValueGenerator(null);
        }

        if ($this->request->isAtLeastPHP("8.1") && !$this->request->getNoEnums()) {
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
        return $this->request->getTargetClass() . $this->nameForClass;
    }
}
