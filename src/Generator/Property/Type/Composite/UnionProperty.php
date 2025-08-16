<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Composite;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Class\Method\FromInputMethodFactory;
use Helmich\Schema2Class\Generator\Class\Method\Serialize\SerializeMethodFactory;
use Helmich\Schema2Class\Generator\Class\VariableNames;
use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Expression\MatchGenerator;
use Helmich\Schema2Class\Generator\Expression\OrGenerator;
use Helmich\Schema2Class\Generator\Property\Type\Primitive\NullProperty;
use Helmich\Schema2Class\Generator\Property\PropertyBuilder;
use Helmich\Schema2Class\Generator\Property\Type\AbstractProperty;
use Helmich\Schema2Class\Generator\Property\Type\Array\ObjectArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\Array\PrimitiveArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\Array\ReferenceArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\Array\TypedArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\Object\RawObjectProperty;
use Helmich\Schema2Class\Generator\Property\Type\Object\NestedObjectProperty;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Generator\Property\Type\ReferenceProperty;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeClass;
use Helmich\Schema2Class\Generator\Expression\TernaryGenerator;
use Helmich\Schema2Class\Util\StringUtils;
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

    private bool $hasArrayProperty = false;

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

        foreach ($this->subProperties as $prop) {
            if (
                $prop instanceof ReferenceArrayProperty
                || $prop instanceof ObjectArrayProperty
                || $prop instanceof PrimitiveArrayProperty
                || $prop instanceof TypedArrayProperty
            ) {
                $this->hasArrayProperty = true;
                break;
            }
        }

        parent::__construct($key, $schema, $generatorRequest);
    }

    private function inputAssertion(PropertyInterface $subProperty, string $expr): string
    {
        $assert = $subProperty->inputAssertionExpr($expr);
        return $this->refineObjectAssertion($subProperty, $expr, $assert);
    }

    private function typeAssertion(PropertyInterface $subProperty, string $expr): string
    {
        $assert = $subProperty->typeAssertionExpr($expr);
        return $this->refineObjectAssertion($subProperty, $expr, $assert);
    }

    private function refineObjectAssertion(PropertyInterface $subProperty, string $expr, string $assert): string
    {
        if ($this->hasArrayProperty && $subProperty instanceof RawObjectProperty) {
            $prefix = 'is_array(' . $expr . ') || ';
            if (str_starts_with($assert, $prefix)) {
                $assert = substr($assert, strlen($prefix));
            }
        }

        return $assert;
    }

    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema["oneOf"]) || isset($schema["anyOf"]);
    }

    public function convertInputToTypeMatch(): string
    {
        $inputVarName = ArgumentNames::INPUT;
        $accessor = "\${$inputVarName}->{{$this->keyStr()}}";
        $arms = [];
        foreach ($this->subProperties as $subProperty) {
            $mapping       = $subProperty->inputMappingExpr($accessor, asserted: true);
            $discriminator = $this->inputAssertion($subProperty, $accessor);

            if (
                $subProperty instanceof ReferenceArrayProperty
                || $subProperty instanceof ObjectArrayProperty
                || $subProperty instanceof PrimitiveArrayProperty
                || $subProperty instanceof TypedArrayProperty
            ) {
                $isArrayCheck = "is_array({$accessor})";
                if (!str_contains($discriminator, $isArrayCheck)) {
                    $discriminator = "({$isArrayCheck} && {$discriminator})";
                }
            }

            $arms[$mapping][] = $discriminator;
        }

        $match = new MatchGenerator("true");
        foreach ($arms as $mapping => $discriminators) {
            $conditions = array_values(array_unique($discriminators));
            $match->addArm(OrGenerator::make($conditions, parens: false), $mapping);
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
    
        $inputVarName = ArgumentNames::INPUT;
        $accessor = "\${$inputVarName}->{{$keyStr}}";
    
        // Start with a "fallback" that just reassigns the raw value
        $conversions = [
            "\${$name} = {$accessor};" => ["discriminators" => [], "fallback" => true],
        ];
    
        // Build up per‑arm conversions
        foreach ($this->subProperties as $subProp) {
            $mapping       = $subProp->inputMappingExpr($accessor, asserted: true);
            $assignment    = "\${$name} = {$mapping};";
            $discriminator = $this->inputAssertion($subProp, $accessor);
    
            // If this arm is an "array" type, ensure its test guards against non-arrays
            if (
                $subProp instanceof ReferenceArrayProperty
                || $subProp instanceof ObjectArrayProperty
                || $subProp instanceof PrimitiveArrayProperty
                || $subProp instanceof TypedArrayProperty
            ) {
                $isArrayCheck = "is_array({$accessor})";
                if (!str_contains($discriminator, $isArrayCheck)) {
                    $discriminator = "({$isArrayCheck} && {$discriminator})";
                }
            }
    
            if (! isset($conversions[$assignment])) {
                $conversions[$assignment] = ["discriminators" => [], "fallback" => false];
            }
            $conversions[$assignment]["discriminators"][] = $discriminator;
        }
    
        // Turn those into an if/elseif/else chain
        $branches = [];
        $fallback = null;

        $indent = StringUtils::indentCode(...);
    
        foreach ($conversions as $assignment => $info) {
            if ($info["fallback"]) {
                $fallback = $assignment;
                continue;
            }

            $keyword = count($branches) ? "elseif" : "if";
            $conditions = array_values(array_unique($info["discriminators"]));
            $parenthesizedCondition = OrGenerator::make($conditions);

            $branches[] = 
                <<<PHP
                {$keyword} {$parenthesizedCondition} {
                {$indent($assignment)}
                }
                PHP;
        }
    
        // Attach the fallback at the end
        if ($fallback !== null) {
            if (count($branches) > 0) {
                $branches[] =
                    <<<PHP
                    else {
                    {$indent($fallback)}
                    }
                    PHP;
            } else {
                $branches[] = $fallback;
            }
        }
    
        return join(" ", $branches);
    }
    

    private function convertTypeToArrayMatch(): string
    {
        $name   = $this->propName();
        $keyStr = $this->keyStr();
        $arms  = [];

        foreach ($this->subProperties as $subProperty) {
            $mapping       = $subProperty->outputMappingExpr("\$this->{$name}");
            $discriminator = $this->typeAssertion($subProperty, "\$this->{$name}");
            $arms[$mapping][] = $discriminator;
        }

        $match = new MatchGenerator("true");
        foreach ($arms as $mapping => $discriminators) {
            $conditions = array_values(array_unique($discriminators));
            $match->addArm(OrGenerator::make($conditions, parens: false), $mapping);
        }
        $outputVarName = VariableNames::OUTPUT;
        return "\${$outputVarName}[{$keyStr}] = {$match->generate()};";
    }

    private function convertTypeToStdClassMatch(): string
    {
        $name   = $this->propName();
        $keyStr = $this->keyStr();
        $arms  = [];

        foreach ($this->subProperties as $subProperty) {
            $mapping       = $subProperty->outputMappingExprStdClass("\$this->{$name}");
            $discriminator = $this->typeAssertion($subProperty, "\$this->{$name}");
            $arms[$mapping][] = $discriminator;
        }

        $match = new MatchGenerator("true");
        foreach ($arms as $mapping => $discriminators) {
            $conditions = array_values(array_unique($discriminators));
            $match->addArm(OrGenerator::make($conditions, parens: false), $mapping);
        }

        $outputVarName = VariableNames::OUTPUT;
        return "\${$outputVarName}->{{$keyStr}} = {$match->generate()};";
    }

    public function convertTypeToArray(): string
    {
        $outputVarName = VariableNames::OUTPUT;
        if ($this->request->isAtLeastPHP("8.0")) {
            return $this->convertTypeToArrayMatch();
        }

        $name   = $this->propName();
        $keyStr = $this->keyStr();
        $conversions = [];

        foreach ($this->subProperties as $subProperty) {
            $mapping       = $subProperty->outputMappingExpr("\$this->{$name}");
            $assignment    = "\${$outputVarName}[{$keyStr}] = {$mapping};";
            $discriminator = $this->typeAssertion($subProperty, "\$this->{$name}");

            if (!isset($conversions[$assignment])) {
                $conversions[$assignment] = ["discriminators" => []];
            }

            $conversions[$assignment]["discriminators"][] = $discriminator;
        }

        $indent = StringUtils::indentCode(...);

        $branches = [];
        foreach ($conversions as $assignment => $conversion) {
            $conditions = array_values(array_unique($conversion["discriminators"]));
            $parenthesizedCondition = OrGenerator::make($conditions);
            $keyword = count($branches) ? "elseif" : "if";
            $branches[] =
                <<<PHP
                {$keyword} {$parenthesizedCondition} {
                {$indent($assignment)}
                }
                PHP;
        }

        return join(" ", $branches);
    }

    public function convertTypeToStdClass(): string
    {
        $outputVarName = VariableNames::OUTPUT;
        if ($this->request->isAtLeastPHP("8.0")) {
            return $this->convertTypeToStdClassMatch();
        }

        $name   = $this->propName();
        $keyStr = $this->keyStr();
        $conversions = [];

        foreach ($this->subProperties as $subProperty) {
            $mapping       = $subProperty->outputMappingExprStdClass("\$this->{$name}");
            $assignment    = "\${$outputVarName}->{{$keyStr}} = {$mapping};";
            $discriminator = $this->typeAssertion($subProperty, "\$this->{$name}");

            if (!isset($conversions[$assignment])) {
                $conversions[$assignment] = ["discriminators" => []];
            }

            $conversions[$assignment]["discriminators"][] = $discriminator;
        }

        $indent = StringUtils::indentCode(...);

        $branches = [];
        foreach ($conversions as $assignment => $conversion) {
            $conditions = array_values(array_unique($conversion["discriminators"]));
            $parenthesizedCondition = OrGenerator::make($conditions);
            $keyword = count($branches) ? "elseif" : "if";
            $branches[] =
                <<<PHP
                {$keyword} {$parenthesizedCondition} {
                {$indent($assignment)}
                }
                PHP;
        }

        return join(" ", $branches);
    }

    /**
     * @throws GeneratorException
     */
    public function generateSubTypes(WriterInterface $writer, OutputInterface $output): void
    {
        foreach ($this->subProperties as $subProperty) {
            $subProperty->generateSubTypes($writer, $output);
        }
    }

    public function typeAnnotation(): string
    {
        $types = [];

        foreach ($this->subProperties as $prop) {
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
            $subAssertions[] = $this->typeAssertion($prop, $expr);
        }

        return OrGenerator::make($subAssertions);
    }

    public function inputAssertionExpr(string $expr): string
    {
        $subAssertions = [];

        foreach ($this->subProperties as $prop) {
            $subAssertions[] = $this->inputAssertion($prop, $expr);
        }

        return OrGenerator::make($subAssertions);
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        if ($this->request->isAtLeastPHP("8.0")) {
            $arms = [];
            foreach ($this->subProperties as $subProperty) {
                $map = $subProperty->inputMappingExpr($expr);
                $assert = $this->inputAssertion($subProperty, $expr);
                $arms[$map][] = $assert;
            }

            $match = new MatchGenerator("true");
            foreach ($arms as $map => $asserts) {
                $conditions = array_values(array_unique($asserts));
                $match->addArm(OrGenerator::make($conditions, parens: false), $map);
            }
            $match->addArm("default", "null");

            return $match->generate();
        }

        $conversions = [];
        foreach ($this->subProperties as $subProperty) {
            $map = $subProperty->inputMappingExpr($expr);
            $assert = $this->inputAssertion($subProperty, $expr);
            $conversions[$map][] = $assert;
        }

        $out = "null";
        foreach (array_reverse($conversions) as $map => $asserts) {
            $conditions = array_values(array_unique($asserts));
            $cond = OrGenerator::make($conditions);
            $out = TernaryGenerator::make($cond, $map, $out);
        }

        return $out;
    }

    public function outputMappingExpr(string $expr): string
    {
        if ($this->request->isAtLeastPHP("8.0")) {
            $arms = [];
            foreach ($this->subProperties as $subProperty) {
                $map = $subProperty->outputMappingExpr($expr);
                $assert = $this->typeAssertion($subProperty, $expr);
                $arms[$map][] = $assert;
            }

            $match = new MatchGenerator("true");
            foreach ($arms as $map => $asserts) {
                $conditions = array_values(array_unique($asserts));
                $match->addArm(OrGenerator::make($conditions, parens: false), $map);
            }
            $match->addArm("default", "null");

            return $match->generate();
        }

        $conversions = [];
        foreach ($this->subProperties as $subProperty) {
            $map = $subProperty->outputMappingExpr($expr);
            $assert = $this->typeAssertion($subProperty, $expr);
            $conversions[$map][] = $assert;
        }

        $out = "null";
        foreach (array_reverse($conversions) as $map => $asserts) {
            $conditions = array_values(array_unique($asserts));
            $cond = OrGenerator::make($conditions);
            $out = TernaryGenerator::make($cond, $map, $out);
        }

        return $out;
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        if ($this->request->isAtLeastPHP("8.0")) {
            $arms = [];
            foreach ($this->subProperties as $subProperty) {
                $map = $subProperty->outputMappingExprStdClass($expr);
                $assert = $this->typeAssertion($subProperty, $expr);
                $arms[$map][] = $assert;
            }

            $match = new MatchGenerator("true");
            foreach ($arms as $map => $asserts) {
                $conditions = array_values(array_unique($asserts));
                $match->addArm(OrGenerator::make($conditions, parens: false), $map);
            }
            $match->addArm("default", "null");

            return $match->generate();
        }

        $conversions = [];
        foreach ($this->subProperties as $subProperty) {
            $map = $subProperty->outputMappingExprStdClass($expr);
            $assert = $this->typeAssertion($subProperty, $expr);
            $conversions[$map][] = $assert;
        }

        $out = "null";
        foreach (array_reverse($conversions) as $map => $asserts) {
            $conditions = array_values(array_unique($asserts));
            $cond = OrGenerator::make($conditions);
            $out = TernaryGenerator::make($cond, $map, $out);
        }

        return $out;
    }

    public function cloneExpr(string $expr): string
    {
        if ($this->request->isAtLeastPHP("8.0")) {
            $arms = [];
            foreach ($this->subProperties as $subProperty) {
                $map = $subProperty->cloneExpr($expr);
                $assert = $this->typeAssertion($subProperty, $expr);
                $arms[$map][] = $assert;
            }

            $match = new MatchGenerator("true");
            foreach ($arms as $map => $asserts) {
                $conditions = array_values(array_unique($asserts));
                $match->addArm(OrGenerator::make($conditions, parens: false), $map);
            }

            return $match->generate();
        }

        $conversions = [];
        foreach ($this->subProperties as $subProperty) {
            $map = $subProperty->cloneExpr($expr);
            $assert = $this->typeAssertion($subProperty, $expr);
            $conversions[$map][] = $assert;
        }

        $out = $expr;
        foreach (array_reverse($conversions) as $map => $asserts) {
            $conditions = array_values(array_unique($asserts));
            $cond = OrGenerator::make($conditions);
            $out = TernaryGenerator::make($cond, $map, $out);
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
