<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\Enum\SchemaToEnum;
use Helmich\Schema2Class\Util\EnumUtils;
use Helmich\Schema2Class\Util\SchemaKeywords;
use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\Generator\PropertyValueGenerator;
use Laminas\Code\Generator\ValueGenerator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Primitive "integer" type
 */
class IntegerProperty extends AbstractProperty
{
    public static function canHandleSchema(array $schema): bool
    {
        if (!isset($schema["type"])) {
            return false;
        }
        return $schema["type"] === "integer"
            || $schema["type"] === "int"
            || (isset($schema["format"]) && $schema["type"] === "number" && $schema["format"] === "integer")
            || (isset($schema["format"]) && $schema["type"] === "number" && $schema["format"] === "int")
        ;
    }

    public function generateSubTypes(WriterInterface $writer, OutputInterface $output): void
    {
        if (isset($this->schema['enum']) && $this->request->isAtLeastPHP('8.1') && !$this->request->getNoEnums()) {
            $req = $this->request
                ->withSchema($this->schema)
                ->withClass($this->subTypeName());

            $generator = $this->request->getSchemaToClassFactory()->build($writer, $output);
            $generator->schemaToClass($this->propagateRootDefinitions($req));
        }
    }

    public function typeAnnotation(): string
    {
        if (isset($this->schema['enum'])) {
            if ($this->request->isAtLeastPHP('8.1') && !$this->request->getNoEnums()) {
                return $this->subTypeName();
            }
            return EnumUtils::typeAnnotation($this->schema['enum']);
        }
        return 'int';
    }

    public function typeHint(): ?string
    {
        if (isset($this->schema['enum'])) {
            if ($this->request->isAtLeastPHP('8.1') && !$this->request->getNoEnums()) {
                return '\\' . $this->request->getTargetNamespace() . '\\' . $this->subTypeName();
            }
            return EnumUtils::typeHint($this->schema['enum'], $this->request->getTargetPHPVersion());
        }
        return $this->request->isAtLeastPHP('7.0') ? 'int' : null;
    }

    public function typeAssertionExpr(string $expr): string
    {
        if (isset($this->schema['enum'])) {
            if ($this->request->isAtLeastPHP('8.1') && !$this->request->getNoEnums()) {
                return "{$expr} instanceof {$this->subTypeName()}";
            }
            return EnumUtils::assertionExpr($this->schema['enum'], $expr);
        }
        return "is_int({$expr})";
    }

    public function inputAssertionExpr(string $expr): string
    {
        if (isset($this->schema['enum'])) {
            if ($this->request->isAtLeastPHP('8.1') && !$this->request->getNoEnums()) {
                return "{$this->subTypeName()}::tryFrom({$expr}) !== null";
            }
            return EnumUtils::assertionExpr($this->schema['enum'], $expr);
        }
        return "is_int({$expr})";
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        if (isset($this->schema['enum']) && $this->request->isAtLeastPHP('8.1') && !$this->request->getNoEnums()) {
            return "{$this->subTypeName()}::from({$expr})";
        }
        if ($asserted) {
            return $expr;
        }

        return "(int){$expr}";
    }

    public function outputMappingExpr(string $expr): string
    {
        if (isset($this->schema['enum']) && $this->request->isAtLeastPHP('8.1') && !$this->request->getNoEnums()) {
            return "{$expr}->value";
        }
        return $expr;
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        return $this->outputMappingExpr($expr);
    }

    public function formatValue(mixed $value): PropertyValueGenerator
    {
        if ($value === null) {
            return new PropertyValueGenerator(null);
        }
        if (isset($this->schema['enum']) && $this->request->isAtLeastPHP('8.1') && !$this->request->getNoEnums()) {
            return new PropertyValueGenerator(
                $this->subTypeName() . '::' . SchemaToEnum::enumCaseName($value),
                ValueGenerator::TYPE_CONSTANT
            );
        }
        return new PropertyValueGenerator($value, ValueGenerator::TYPE_CONSTANT);
    }

    public function needsValidation(): bool
    {
        if (isset($this->schema['enum'])) {
            if ($this->request->isAtLeastPHP('8.1') && !$this->request->getNoEnums()) {
                return false;
            }
            return true;
        }
        if (!$this->request->isAtLeastPHP('7.0')) {
            return true;
        }
        return SchemaKeywords::hasAny($this->schema, SchemaKeywords::NUMERIC_VALIDATION);
    }

    private function subTypeName(): string
    {
        return $this->request->getTargetClass() . $this->nameForClass;
    }

}
