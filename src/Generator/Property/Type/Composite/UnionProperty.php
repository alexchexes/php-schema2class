<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Composite;

use Helmich\Schema2Class\Generator\Class\Method\FromInputMethodFactory;
use Helmich\Schema2Class\Generator\Class\Method\SerializeMethodFactory;
use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\MatchGenerator;
use Helmich\Schema2Class\Generator\Property\Type\Primitive\NullProperty;
use Helmich\Schema2Class\Generator\Property\PropertyBuilder;
use Helmich\Schema2Class\Generator\Property\Type\AbstractProperty;
use Helmich\Schema2Class\Generator\Property\Type\Array\ObjectArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\Array\PrimitiveArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\Array\ReferenceArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\Object\NestedObjectProperty;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Generator\Property\Type\ReferenceProperty;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeClass;
use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Represents a JSON Schema `oneOf`/`anyOf` union property - i.e. property
 * that can hold several alternative property types.
 * We expand multi-type definitions like "type": ["string", "object"] into an "anyOf" union,
 * so those definitions are handled by this type as well.
 */
class UnionProperty extends AbstractProperty
{
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

    public function convertInputToTypeMatch(): string
    {
        $inputVarName = FromInputMethodFactory::INPUT_ARG_NAME;
        $accessor = "\${$inputVarName}->{{$this->keyStr()}}";

        $match = new MatchGenerator("true");

        foreach ($this->subProperties as $subProperty) {
            $mapping       = $subProperty->inputMappingExpr($accessor, asserted: true);
            $discriminator = $subProperty->inputAssertionExpr($accessor);
            $match->addArm($discriminator, $mapping);
        }

        $match->addArm(
            "default",
            "throw new \\InvalidArgumentException(\"could not build property '{$this->key()}' from JSON\")"
        );

        // assign into the camel‑cased local variable
        return "\${$this->varName()} = {$match->generate()};";
    }

    public function convertInputToType(): string
    {
        // PHP 8+ uses match() which already guards correctly
        if ($this->request->isAtLeastPHP("8.0")) {
            return $this->convertInputToTypeMatch();
        }
    
        $name   = $this->varName();
        $keyStr = $this->keyStr();
    
        $inputVarName = FromInputMethodFactory::INPUT_ARG_NAME;
        $accessor = "\${$inputVarName}->{{$keyStr}}";
    
        // Start with a "fallback" that just reassigns the raw value
        $conversions = [
            "\${$name} = {$accessor};" => ["discriminators" => [], "fallback" => true],
        ];
    
        // Build up per‑arm conversions
        foreach ($this->subProperties as $subProp) {
            $mapping       = $subProp->inputMappingExpr($accessor, asserted: true);
            $assignment    = "\${$name} = {$mapping};";
            $discriminator = $subProp->inputAssertionExpr($accessor);
    
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
    

    private function convertTypeToArrayMatch(): string
    {
        $name   = $this->propName();
        $keyStr = $this->keyStr();
        $match  = new MatchGenerator("true");

        foreach ($this->subProperties as $subProperty) {
            $mapping       = $subProperty->outputMappingExpr("\$this->{$name}");
            $discriminator = $subProperty->typeAssertionExpr("\$this->{$name}");

            $match->addArm($discriminator, $mapping);
        }
        $outputVarName = SerializeMethodFactory::OUTPUT_VAR_NAME;
        return "\${$outputVarName}[{$keyStr}] = {$match->generate()};";
    }

    private function convertTypeToStdClassMatch(): string
    {
        $name   = $this->propName();
        $keyStr = $this->keyStr();
        $match  = new MatchGenerator("true");

        foreach ($this->subProperties as $subProperty) {
            $mapping       = $subProperty->outputMappingExprStdClass("\$this->{$name}");
            $discriminator = $subProperty->typeAssertionExpr("\$this->{$name}");

            $match->addArm($discriminator, $mapping);
        }

        $outputVarName = SerializeMethodFactory::OUTPUT_VAR_NAME;
        return "\${$outputVarName}->{{$keyStr}} = {$match->generate()};";
    }

    public function convertTypeToArray(): string
    {
        $outputVarName = SerializeMethodFactory::OUTPUT_VAR_NAME;
        if ($this->request->isAtLeastPHP("8.0")) {
            return $this->convertTypeToArrayMatch();
        }

        $name   = $this->propName();
        $keyStr = $this->keyStr();
        $conversions = [];

        foreach ($this->subProperties as $subProperty) {
            $mapping       = $subProperty->outputMappingExpr("\$this->{$name}");
            $assignment    = "\${$outputVarName}[{$keyStr}] = {$mapping};";
            $discriminator = $subProperty->typeAssertionExpr("\$this->{$name}");

            if (!isset($conversions[$assignment])) {
                $conversions[$assignment] = ["discriminators" => []];
            }

            $conversions[$assignment]["discriminators"][] = $discriminator;
        }

        $ifs      = 0;
        $branches = [];
        foreach ($conversions as $assignment => $conversion) {
            $condition  = "(" . join(") || (", $conversion["discriminators"]) . ")";
            $branches[] = ($ifs++ > 0 ? "else " : "") . "if ($condition) {\n    $assignment\n}";
        }

        return str_replace("}\nelse", "} else", join("\n", $branches));
    }

    public function convertTypeToStdClass(): string
    {
        $outputVarName = SerializeMethodFactory::OUTPUT_VAR_NAME;
        if ($this->request->isAtLeastPHP("8.0")) {
            return $this->convertTypeToStdClassMatch();
        }

        $name   = $this->propName();
        $keyStr = $this->keyStr();
        $conversions = [];

        foreach ($this->subProperties as $subProperty) {
            $mapping       = $subProperty->outputMappingExprStdClass("\$this->{$name}");
            $assignment    = "\${$outputVarName}->{{$keyStr}} = {$mapping};";
            $discriminator = $subProperty->typeAssertionExpr("\$this->{$name}");

            if (!isset($conversions[$assignment])) {
                $conversions[$assignment] = ["discriminators" => []];
            }

            $conversions[$assignment]["discriminators"][] = $discriminator;
        }

        $ifs      = 0;
        $branches = [];
        foreach ($conversions as $assignment => $conversion) {
            $condition  = "(" . join(") || (", $conversion["discriminators"]) . ")";
            $branches[] = ($ifs++ > 0 ? "else " : "") . "if ($condition) {\n$assignment\n}";
        }

        return str_replace("}\nelse", "} else", join("\n", $branches));
    }

    /**
     * @throws GeneratorException
     */
    public function generateSubTypes(WriterInterface $writer, OutputInterface $output): void
    {
        $def = $this->schema;

        foreach ($def["oneOf"] as $i => $subDef) {
            $propertyTypeName = $this->subTypeName($i);

            $isObject = (isset($subDef["type"]) && $subDef["type"] === "object") || isset($subDef["properties"]);
            $isEnum   = isset($subDef["enum"]);

            if ($isObject || $isEnum) {
                $req = $this->request
                    ->withSchema($subDef)
                    ->withClass($propertyTypeName);

                $generator = $this->request->getSchemaToClassFactory()->build($writer, $output);
                $generator->schemaToClass($this->propagateRootDefinitions($req));
            }
        }
    }

    public function typeAnnotation(): string
    {
        $types = [];

        foreach ($this->subProperties as $prop) {
            if ($this->propName() === 'UnionOfOneNull') {
                echo "\n---\$prop::class:\n";  print_r($prop::class);  echo "\n---";
            }

            $ann = $prop->typeAnnotation();
            foreach (explode('|', $ann) as $t) {
                $types[$t] = true;
            }
        }

        return join('|', array_keys($types));
    }

    public function typeHint(): ?string
    {
        $subTypeHints = [];

        foreach ($this->subProperties as $subProp) {
            $hint = $subProp->typeHint();
            if ($hint === null) {
                if ($subProp instanceof NullProperty) {
                    $subTypeHints['null'] = true;
                    continue;
                }
                $subTypeHints = [];
                break;
            }

            if (str_starts_with($hint, '?')) {
                $subTypeHints['null'] = true;
                $hint = substr($hint, 1);
            }

            foreach (explode('|', $hint) as $part) {
                $subTypeHints[$part] = true;
            }
        }

        if ($this->request->isAtLeastPHP('8.0')) {
            if (isset($subTypeHints['null']) && count($subTypeHints) === 2) {
                unset($subTypeHints['null']);
                return array_key_first($subTypeHints);
            }

            if (count($subTypeHints) === 1) {
                return array_key_first($subTypeHints);
            }

            if (count($subTypeHints) > 0) {
                return join('|', array_keys($subTypeHints));
            }
        } else {
            if (isset($subTypeHints['null']) && count($subTypeHints) === 2) {
                $types = array_keys($subTypeHints);
                $type  = $types[0] === 'null' ? $types[1] : $types[0];
                return $type;
            }

            if (count($subTypeHints) === 1) {
                $type = array_key_first($subTypeHints);
                if ($type !== 'null') {
                    return $type;
                }
            }

            if ($this->request->isAtLeastPHP('7.2')) {
                $onlyObjects = true;
                foreach ($this->subProperties as $subProp) {
                    if (
                        !($subProp instanceof NestedObjectProperty)
                        && !($subProp instanceof ReferenceProperty && $subProp->getRefType() instanceof ReferencedTypeClass)
                    ) {
                        $onlyObjects = false;
                        break;
                    }
                }
                if ($onlyObjects) {
                    return 'object';
                }
            }
        }

        return null;
    }

    public function typeAssertionExpr(string $expr): string
    {
        $subAssertions = [];

        foreach ($this->subProperties as $prop) {
            $subAssertions[] = $prop->typeAssertionExpr($expr);
        }

        return "(" . join(") || (", $subAssertions) . ")";
    }

    public function inputAssertionExpr(string $expr): string
    {
        $subAssertions = [];

        foreach ($this->subProperties as $prop) {
            $subAssertions[] = $prop->inputAssertionExpr($expr);
        }

        $glue = ") || (";
        return "(" . implode($glue, $subAssertions) . ")";
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        if ($this->request->isAtLeastPHP("8.0")) {
            $match = new MatchGenerator("true");

            foreach ($this->subProperties as $subProperty) {
                $assert = $subProperty->inputAssertionExpr($expr);
                $map    = $subProperty->inputMappingExpr($expr);
                $match->addArm($assert, $map);
            }
            
            $match->addArm("default", "null");

            return $match->generate();
        }

        $out = "null";

        foreach ($this->subProperties as $subProperty) {
            $assert = $subProperty->inputAssertionExpr($expr);
            $map    = $subProperty->inputMappingExpr($expr);
            $out    = "(({$assert}) ? {$map} : ({$out}))";
        }

        return $out;
    }

    public function outputMappingExpr(string $expr): string
    {
        if ($this->request->isAtLeastPHP("8.0")) {
            $match = new MatchGenerator("true");
            $match->addArm("default", "null");

            foreach ($this->subProperties as $subProperty) {
                $assert = $subProperty->typeAssertionExpr($expr);
                $map    = $subProperty->outputMappingExpr($expr);
                $match->addArm($assert, $map);
            }

            return $match->generate();
        }

        $out = "null";

        foreach ($this->subProperties as $subProperty) {
            $assert = $subProperty->typeAssertionExpr($expr);
            $map    = $subProperty->outputMappingExpr($expr);
            $out    = "({$assert}) ? ({$map}) : ({$out})";
        }

        return $out;
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        if ($this->request->isAtLeastPHP("8.0")) {
            $match = new MatchGenerator("true");
            $match->addArm("default", "null");

            foreach ($this->subProperties as $subProperty) {
                $assert = $subProperty->typeAssertionExpr($expr);
                $map    = $subProperty->outputMappingExprStdClass($expr);
                $match->addArm($assert, $map);
            }

            return $match->generate();
        }

        $out = "null";

        foreach ($this->subProperties as $subProperty) {
            $assert = $subProperty->typeAssertionExpr($expr);
            $map    = $subProperty->outputMappingExprStdClass($expr);
            $out    = "({$assert}) ? ({$map}) : ({$out})";
        }

        return $out;
    }

    public function cloneExpr(string $expr): string
    {
        if ($this->request->isAtLeastPHP("8.0")) {
            $match = new MatchGenerator("true");

            foreach ($this->subProperties as $subProperty) {
                $assert = $subProperty->typeAssertionExpr($expr);
                $map    = $subProperty->cloneExpr($expr);
                $match->addArm($assert, $map);
            }

            return $match->generate();
        }

        $out = $expr;

        foreach ($this->subProperties as $subProperty) {
            $assert = $subProperty->typeAssertionExpr($expr);
            $map    = $subProperty->cloneExpr($expr);
            $out    = "({$assert} ? {$map} : {$out})";
        }

        return $out;
    }

    public function allowsNull(): bool
    {
        foreach ($this->subProperties as $prop) {
            if ($prop->allowsNull()) {
                return true;
            }
        }

        return parent::allowsNull();
    }

    private function subTypeName(int $idx = 0): string
    {
        return $this->request->getTargetClass() . $this->nameForClass . "Alternative" . ($idx + 1);
    }

    public function needsValidation(): bool
    {
        if (!$this->request->isAtLeastPHP('8.0')) {
            return true;
        }

        foreach ($this->subProperties as $prop) {
            if ($prop->needsValidation()) {
                return true;
            }
        }

        return false;
    }
}
