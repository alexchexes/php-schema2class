<?php
declare(strict_types = 1);
namespace Helmich\Schema2Class\Generator\Property;

use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\SchemaToClass;

class IntersectProperty extends AbstractProperty
{
    use TypeConvert;

    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema["allOf"]);
    }

    public function isComplex(): bool
    {
        return true;
    }

    /**
     * @param SchemaToClass    $generator
     * @throws GeneratorException
     */
    public function generateSubTypes(SchemaToClass $generator): void
    {
        $propertyTypeName = $this->subTypeName();
        $combined = $this->buildSchemaIntersect();

        $req = $this->generatorRequest
            ->withSchema($combined)
            ->withClass($propertyTypeName);

        $generator->schemaToClass($this->propagateRootDefinitions($req));
    }

    public function typeAnnotation(): string
    {
        return $this->subTypeName();
    }

    public function typeHint(string $phpVersion): ?string
    {
        return "\\" . $this->generatorRequest->getTargetNamespace() . "\\" . $this->subTypeName();
    }

    public function generateTypeAssertionExpr(string $expr): string
    {
        return "{$expr} instanceof {$this->subTypeName()}";
    }

    public function generateInputAssertionExpr(string $expr): string
    {
        return "{$this->subTypeName()}::validateInput({$expr}, true)";
    }

    public function generateInputMappingExpr(string $expr, bool $asserted = false): string
    {
        $validateArg = $this->generatorRequest->getCurrValidateArgAlias();
        $materializeArg = $this->generatorRequest->getCurrMaterializeArgAlias();

        $args = [$expr, '$' . $validateArg];
        if ($materializeArg !== null) {
            $args[] = '$' . $materializeArg;
        }
        $argsStr = implode(', ', $args);

        return "{$this->subTypeName()}::buildFromInput({$argsStr})";
    }

    public function generateOutputMappingExpr(string $expr): string
    {
        $inclDefaultsArg = $this->generatorRequest->getCurrReqHasDefaults() ? '$includeDefaults' : '';
        return "({$expr})->toArray({$inclDefaultsArg})";
    }

    public function generateOutputMappingExprStdClass(string $expr): string
    {
        $inclDefaultsArg = $this->generatorRequest->getCurrReqHasDefaults() ? '$includeDefaults' : '';
        return "({$expr})->toStdClass({$inclDefaultsArg})";
    }

    public function generateCloneExpr(string $expr): string
    {
        return "clone {$expr}";
    }

    private function subTypeName(): string
    {
        return $this->generatorRequest->getTargetClass() . $this->capitalizedName;
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
                if ($this->generatorRequest->getOptions()->getInlineAllofReferences()) {
                    $schema = $this->generatorRequest->lookupSchema($schema['$ref']);
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

}
