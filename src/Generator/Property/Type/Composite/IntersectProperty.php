<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Composite;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\Property\Type\AbstractProperty;
use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Represents a property whose type is an intersection of multiple schemas via
 * "allOf". Generates a helper class combining all sub schemas.
 */
class IntersectProperty extends AbstractProperty
{
    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema["allOf"]);
    }

    /**
     * @throws GeneratorException
     */
    public function generateSubTypes(WriterInterface $writer, OutputInterface $output): void
    {
        $propertyTypeName = $this->subTypeName();
        $combined = $this->buildSchemaIntersect();

        $req = $this->request
            ->withSchema($combined)
            ->withClass($propertyTypeName);

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
        $inclDefaultsArg = $this->request->getClassHasDefaults() ? '$includeDefaults' : '';
        $TO_ARRAY = MethodNames::TO_ARRAY;
        return "{$expr}->{$TO_ARRAY}({$inclDefaultsArg})";
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        $inclDefaultsArg = $this->request->getClassHasDefaults() ? '$includeDefaults' : '';
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

    private function buildSchemaUnion(array $schemas): array
    {
        $combined = [
            "required" => [],
            "properties" => [],
        ];

        foreach ($schemas as $i => $schema) {
            $required = isset($schema["required"]) ? $schema["required"] : [];

            if ($i === 0) {
                $combined["required"] = $required;
            } else {
                foreach ($combined["required"] as $j => $req) {
                    if (!in_array($req, $required)) {
                        unset($combined["required"][$j]);
                    }
                }
            }

            if (isset($schema["properties"])) {
                foreach ($schema["properties"] as $name => $def) {
                    $combined["properties"][$name] = $def;
                }
            }
        }

        return $combined;
    }

    public function buildSchemaIntersect(): array
    {
        $schemas = $this->schema["allOf"];
        $combined = [
            "required" => [],
            "properties" => [],
        ];

        foreach ($schemas as $schema) {
            if (isset($schema["oneOf"])) {
                $schema = $this->buildSchemaUnion($schema["oneOf"]);
            }

            if (isset($schema["anyOf"])) {
                $schema = $this->buildSchemaUnion($schema["anyOf"]);
            }

            if (isset($schema['$ref'])) {
                if ($this->request->getOptions()->getInlineAllofReferences()) {
                    $schema = $this->request->lookupSchema($schema['$ref']);
                } else {
                    throw new \Exception("unsupported '\$ref' in 'allOf' type definition");
                }
            }

            if (isset($schema["required"])) {
                $combined["required"] = array_unique(array_merge($combined["required"], $schema["required"]));
            }

            if (isset($schema["properties"])) {
                foreach ($schema["properties"] as $name => $def) {
                    $combined["properties"][$name] = $def;
                }
            }
        }

        return $combined;
    }

    public function needsValidation(): bool
    {
        // Resulting class is type hinted and validated separately.
        return false;
    }
}
