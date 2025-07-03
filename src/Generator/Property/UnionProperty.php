<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property;

use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\MatchGenerator;
use Helmich\Schema2Class\Generator\PropertyBuilder;
use Helmich\Schema2Class\Generator\SchemaToClass;

class UnionProperty extends AbstractProperty
{
    use TypeConvert;

    /** @var PropertyInterface[] */
    private array $subProperties;

    public function __construct(string $key, array $schema, GeneratorRequest $generatorRequest)
    {
        if (isset($schema["anyOf"])) {
            $schema["oneOf"] = $schema["anyOf"];
            unset($schema["anyOf"]);
        }

        $subSchemas = $schema["oneOf"];

        $this->subProperties = array_map(function (int $idx) use ($generatorRequest, $key, $subSchemas): PropertyInterface {
            $subSchema = $subSchemas[$idx];
            return PropertyBuilder::buildPropertyFromSchema($generatorRequest, "{$key}Alternative" . ($idx + 1), $subSchema, true);
        }, array_keys($schema["oneOf"]));

        parent::__construct($key, $schema, $generatorRequest);
    }

    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema["oneOf"]) || isset($schema["anyOf"]);
    }

    public function isComplex(): bool
    {
        return true;
    }

    public function convertInputToTypeMatch(string $inputVarName = 'input', bool $object = false): string
    {
        $name  = $this->name;
        $key   = $this->key;
        $keyStr = var_export($key, true);

        $accessor = $object
            ? "\${$inputVarName}->{{$keyStr}}"
            : "\${$inputVarName}[{$keyStr}]";

        $match = new MatchGenerator("true");

        foreach ($this->subProperties as $subProperty) {
            $mapping       = $subProperty->generateInputMappingExpr($accessor, asserted: true);
            $discriminator = $subProperty->generateInputAssertionExpr($accessor);
            $match->addArm($discriminator, $mapping);
        }

        $match->addArm(
            "default",
            "throw new \\InvalidArgumentException(\"could not build property '{$key}' from JSON\")"
        );

        // assign into the camel‑cased local variable
        return "\${$name} = {$match->generate()};";
    }

    public function convertInputToType(string $inputVarName = 'input', bool $object = false): string
    {
        // PHP 8+ uses match() which already guards correctly
        if ($this->generatorRequest->isAtLeastPHP("8.0")) {
            return $this->convertInputToTypeMatch($inputVarName, $object);
        }
    
        $name   = $this->name;
        $key    = $this->key;
        $keyStr = var_export($key, true);
    
        $accessor = $object
            ? "\${$inputVarName}->{{$keyStr}}"
            : "\${$inputVarName}[{$keyStr}]";
    
        // Start with a "fallback" that just reassigns the raw value
        $conversions = [
            "\$$key = {$accessor};" => ["discriminators" => [], "fallback" => true],
        ];
    
        // Build up per‑arm conversions
        foreach ($this->subProperties as $subProp) {
            $mapping       = $subProp->generateInputMappingExpr($accessor, asserted: true);
            $assignment    = "\$$name = {$mapping};";
            $discriminator = $subProp->generateInputAssertionExpr($accessor);
    
            // If this arm is an "array" type, prefix its test with is_array(...)
            if (
                $subProp instanceof ReferenceArrayProperty
                || $subProp instanceof ObjectArrayProperty
                || $subProp instanceof PrimitiveArrayProperty
            ) {
                $discriminator = "is_array({$accessor}) && ({$discriminator})";
            }
    
            if (! isset($conversions[$assignment])) {
                $conversions[$assignment] = ["discriminators" => [], "fallback" => false];
            }
            $conversions[$assignment]["discriminators"][] = $discriminator;
        }
    
        // Turn those into an if/elseif/else chain
        $ifs      = 0;
        $branches = [];
        $fallback = null;
    
        foreach ($conversions as $assignment => $info) {
            if ($info["fallback"]) {
                $fallback = $assignment;
                continue;
            }
            $cond = "(" . join(") || (", $info["discriminators"]) . ")";
            $branches[] = ($ifs++ > 0 ? "else " : "if ")
                . "($cond) {\n"
                . "    $assignment\n"
                . "}";
        }
    
        // Attach the fallback at the end
        if ($fallback !== null) {
            if (count($branches) > 0) {
                $branches[] = "else {\n    $fallback\n}";
            } else {
                $branches[] = $fallback;
            }
        }
    
        // Join and normalize "}else" → "} else"
        return str_replace("}\nelse", "} else", join("\n", $branches));
    }
    

    private function convertTypeToArrayMatch(string $outputVarName = 'output'): string
    {
        $name   = $this->name;
        $key    = $this->key;
        $keyStr = var_export($key, true);
        $match  = new MatchGenerator("true");

        foreach ($this->subProperties as $subProperty) {
            $mapping       = $subProperty->generateOutputMappingExpr("\$this->{$name}");
            $discriminator = $subProperty->generateTypeAssertionExpr("\$this->{$name}");

            $match->addArm($discriminator, $mapping);
        }

        return "\${$outputVarName}[{$keyStr}] = " . $match->generate() . ";";
    }

    public function convertTypeToArray(string $outputVarName = 'output'): string
    {
        if ($this->generatorRequest->isAtLeastPHP("8.0")) {
            return $this->convertTypeToArrayMatch($outputVarName);
        }

        $name        = $this->name;
        $key         = $this->key;
        $keyStr      = var_export($key, true);
        $conversions = [];

        foreach ($this->subProperties as $subProperty) {
            $mapping       = $subProperty->generateOutputMappingExpr("\$this->{$name}");
            $assignment    = "\${$outputVarName}[{$keyStr}] = {$mapping};";
            $discriminator = $subProperty->generateTypeAssertionExpr("\$this->{$name}");

            if (!isset($conversions[$assignment])) {
                $conversions[$assignment] = ["discriminators" => []];
            }

            $conversions[$assignment]["discriminators"][] = $discriminator;
        }

        $ifs      = 0;
        $branches = [];
        $fallback = null;
        foreach ($conversions as $assignment => $conversion) {
            $condition  = "(" . join(") || (", $conversion["discriminators"]) . ")";
            $branches[] = ($ifs++ > 0 ? "else " : "") . "if ($condition) {\n    $assignment\n}";
        }

        return str_replace("}\nelse", "} else", join("\n", $branches));
    }

    /**
     * @param SchemaToClass $generator
     * @throws GeneratorException
     */
    public function generateSubTypes(SchemaToClass $generator): void
    {
        $def = $this->schema;

        foreach ($def["oneOf"] as $i => $subDef) {
            $propertyTypeName = $this->subTypeName($i);

            $isObject = (isset($subDef["type"]) && $subDef["type"] === "object") || isset($subDef["properties"]);
            $isEnum   = isset($subDef["enum"]);

            if ($isObject || $isEnum) {
                $generator->schemaToClass(
                    $this->generatorRequest
                        ->withSchema($subDef)
                        ->withClass($propertyTypeName)
                );
            }
        }
    }

    public function typeAnnotation(): string
    {
        $types = array_map(fn (PropertyInterface $prop): string => $prop->typeAnnotation(), $this->subProperties);
        $types = array_unique($types);
        return join("|", $types);
    }

    public function typeHint(string $phpVersion): ?string
    {
        if ($this->generatorRequest->isAtLeastPHP("8.0")) {
            $subTypeHints = [];

            foreach ($this->subProperties as $subProp) {
                $th = $subProp->typeHint($phpVersion);
                if ($th === null) {
                    return null;
                }

                if (strpos($th, "?") === 0) {
                    $subTypeHints["null"] = true;
                    $th                   = substr($th, 1);
                }

                $subTypeHints[$th] = true;
            }

            return join("|", array_keys($subTypeHints));
        }

        return null;
    }

    public function generateTypeAssertionExpr(string $expr): string
    {
        $subAssertions = [];

        foreach ($this->subProperties as $prop) {
            $subAssertions[] = $prop->generateTypeAssertionExpr($expr);
        }

        return "(" . join(") || (", $subAssertions) . ")";
    }

    public function generateInputAssertionExpr(string $expr): string
    {
        $subAssertions = [];

        foreach ($this->subProperties as $prop) {
            $subAssertions[] = $prop->generateInputAssertionExpr($expr);
        }

        return "(" . join(") || (", $subAssertions) . ")";
    }

    public function generateInputMappingExpr(string $expr, bool $asserted = false): string
    {
        if ($this->generatorRequest->isAtLeastPHP("8.0")) {
            $match = new MatchGenerator("true");

            foreach ($this->subProperties as $subProperty) {
                $assert = $subProperty->generateInputAssertionExpr($expr);
                $map    = $subProperty->generateInputMappingExpr($expr);
                $match->addArm($assert, $map);
            }
            
            $match->addArm("default", "null");

            return $match->generate();
        }

        $out = "null";

        foreach ($this->subProperties as $subProperty) {
            $assert = $subProperty->generateInputAssertionExpr($expr);
            $map    = $subProperty->generateInputMappingExpr($expr);
            $out    = "({$assert}) ? ({$map}) : ({$out})";
        }

        return $out;
    }

    public function generateOutputMappingExpr(string $expr): string
    {
        if ($this->generatorRequest->isAtLeastPHP("8.0")) {
            $match = new MatchGenerator("true");
            $match->addArm("default", "null");

            foreach ($this->subProperties as $subProperty) {
                $assert = $subProperty->generateTypeAssertionExpr($expr);
                $map    = $subProperty->generateOutputMappingExpr($expr);
                $match->addArm($assert, $map);
            }

            return $match->generate();
        }

        $out = "null";

        foreach ($this->subProperties as $subProperty) {
            $assert = $subProperty->generateTypeAssertionExpr($expr);
            $map    = $subProperty->generateOutputMappingExpr($expr);
            $out    = "({$assert}) ? ({$map}) : ({$out})";
        }

        return $out;
    }

    public function generateCloneExpr(string $expr): string
    {
        if ($this->generatorRequest->isAtLeastPHP("8.0")) {
            $match = new MatchGenerator("true");

            foreach ($this->subProperties as $subProperty) {
                $assert = $subProperty->generateTypeAssertionExpr($expr);
                $map    = $subProperty->generateCloneExpr($expr);
                $match->addArm($assert, $map);
            }

            return $match->generate();
        }

        $out = $expr;

        foreach ($this->subProperties as $subProperty) {
            $assert = $subProperty->generateTypeAssertionExpr($expr);
            $map    = $subProperty->generateCloneExpr($expr);
            $out    = "({$assert}) ? ({$map}) : ({$out})";
        }

        return $out;
    }

    private function subTypeName(int $idx = 0): string
    {
        return $this->generatorRequest->getTargetClass() . $this->capitalizedName . "Alternative" . ($idx + 1);
    }

}
