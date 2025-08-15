<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Object;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\Property\Type\AbstractProperty;
use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Represents a property of type "object" (with nested properties).
 * 
 * When this class claims the schema, another class is generated to represent the property.
 */
class NestedObjectProperty extends AbstractProperty
{
    public static function canHandleSchema(array $schema): bool
    {
        $isObject = (isset($schema["type"]) && $schema["type"] === "object")
            || isset($schema["properties"]);

        $hasProperties = isset($schema["properties"])
            && is_array($schema["properties"])
            && count($schema["properties"]) > 0;

        return $isObject && $hasProperties;
    }

    /**
     * @throws GeneratorException
     */
    public function generateSubTypes(WriterInterface $writer, OutputInterface $output): void
    {
        $req = $this->request
            ->withSchema($this->schema)
            ->withClass($this->subTypeName());

        $generator = $this->request->getSchemaToClassFactory()->build($writer, $output);
        $generator->schemaToClass($this->propagateRootDefinitions($req));
    }

    public function typeAnnotation(): string
    {
        return "\\" . $this->request->getTargetNamespace() . "\\" . $this->subTypeName();
    }

    public function typeHint(): ?string
    {
        return "\\" . $this->request->getTargetNamespace() . "\\" . $this->subTypeName();
    }

    public function typeAssertionExpr(string $expr): string
    {
        return "{$expr} instanceof {$this->subTypeName()}";
    }

    public function inputAssertionExpr(string $expr): string
    {
        $VALIDATE_INPUT = MethodNames::VALIDATE_INPUT;
        return "{$this->subTypeName()}::{$VALIDATE_INPUT}({$expr}, true)";
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        $args = [$expr, '$' . ArgumentNames::VALIDATE];
        if ($this->request->getClassHasDefaults()) {
            $args[] = '$' . ArgumentNames::MATRLZ_DEFAULTS;
        }
        $argsStr = implode(', ', $args);

        $FROM_INPUT = MethodNames::FROM_INPUT;

        return "{$this->subTypeName()}::{$FROM_INPUT}({$argsStr})";
    }

    public function outputMappingExpr(string $expr): string
    {
        $inclDefaultsArg = $this->request->getClassHasDefaults() ? '$' . ArgumentNames::INCL_DEFAULTS : '';
        $TO_ARRAY = MethodNames::TO_ARRAY;
        return "{$expr}->{$TO_ARRAY}({$inclDefaultsArg})";
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        $inclDefaultsArg = $this->request->getClassHasDefaults() ? '$' . ArgumentNames::INCL_DEFAULTS : '';
        $TO_STD_CLASS = MethodNames::TO_STD_CLASS;
        return "{$expr}->{$TO_STD_CLASS}({$inclDefaultsArg})";
    }

    public function cloneExpr(string $expr): string
    {
        return "clone {$expr}";
    }

    private function subTypeName(): string
    {
        return $this->request->getTargetClass() . $this->nameForClass;
    }

    public function needsValidation(): bool
    {
        // Generated sub type has its own validation and PHP enforces the type.
        return false;
    }
}
