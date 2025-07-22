<?php
declare(strict_types = 1);
namespace Helmich\Schema2Class\Generator\Property;

use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\SchemaToClass;

class NestedObjectProperty extends AbstractProperty
{
    public static function canHandleSchema(array $schema): bool
    {
        $isObject = (isset($schema["type"]) && $schema["type"] === "object")
            || isset($schema["properties"]);

        $hasProperties = isset($schema["properties"])
            && is_array($schema["properties"])
            && count($schema["properties"]) > 0;

        $hasAdditionalProperties = isset($schema["additionalProperties"])
            && is_array($schema["additionalProperties"])
            && count($schema["additionalProperties"]) > 0;

        return $isObject && $hasProperties && !$hasAdditionalProperties;
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
        $req = $this->generatorRequest
            ->withSchema($this->schema)
            ->withClass($this->subTypeName());

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

}
