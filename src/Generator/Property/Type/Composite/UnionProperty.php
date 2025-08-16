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

    /**
     * Deduplicate discriminator conditions across all mapping arms while
     * preserving their evaluation order. Conditions that already appeared in
     * previous arms are removed from subsequent ones to avoid redundant checks.
     *
     * @param array<string,list<string>> $arms
     * @return array<string,list<string>>
     */
    private function deduplicateConditions(array $arms): array
    {
        $used = [];
        $out = [];
        foreach ($arms as $mapping => $conds) {
            $conds = array_values(array_unique($conds));
            $conds = array_values(array_diff($conds, $used));
            if ($conds === []) {
                continue;
            }
            $used = array_merge($used, $conds);
            $out[$mapping] = $conds;
        }

        return $out;
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
            $discriminators = $subProperty->inputAssertionExprs($accessor);

            if (
                $subProperty instanceof ReferenceArrayProperty
                || $subProperty instanceof ObjectArrayProperty
                || $subProperty instanceof PrimitiveArrayProperty
            ) {
                $isArrayCheck = "is_array({$accessor})";
                if (!in_array($isArrayCheck, $discriminators, true)) {
                    $discriminators[] = $isArrayCheck;
                }
            }

            $arms[$mapping] = array_merge($arms[$mapping] ?? [], $discriminators);
        }

        $arms = $this->deduplicateConditions($arms);

        $match = new MatchGenerator("true");
        foreach ($arms as $mapping => $conditions) {
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
            $discriminators = $subProp->inputAssertionExprs($accessor);

            // If this arm is an "array" type, ensure its test guards against non-arrays
            if (
                $subProp instanceof ReferenceArrayProperty
                || $subProp instanceof ObjectArrayProperty
                || $subProp instanceof PrimitiveArrayProperty
            ) {
                $isArrayCheck = "is_array({$accessor})";
                if (!in_array($isArrayCheck, $discriminators, true)) {
                    $discriminators[] = $isArrayCheck;
                }
            }

            if (! isset($conversions[$assignment])) {
                $conversions[$assignment] = ["discriminators" => [], "fallback" => false];
            }
            $conversions[$assignment]["discriminators"] = array_merge(
                $conversions[$assignment]["discriminators"],
                $discriminators,
            );
        }
    
        // Turn those into an if/elseif/else chain
        $branches = [];
        $fallback = null;

        $indent = StringUtils::indentCode(...);
    
        $conditionArms = [];
        foreach ($conversions as $assignment => $info) {
            if ($info["fallback"]) {
                $fallback = $assignment;
                continue;
            }

            $conditionArms[$assignment] = $info["discriminators"];
        }

        $conditionArms = $this->deduplicateConditions($conditionArms);

        foreach ($conditionArms as $assignment => $conditions) {
            $keyword = count($branches) ? "elseif" : "if";
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
            $discriminators = $subProperty->typeAssertionExprs("\$this->{$name}");
            $arms[$mapping] = array_merge($arms[$mapping] ?? [], $discriminators);
        }

        $arms = $this->deduplicateConditions($arms);

        $match = new MatchGenerator("true");
        foreach ($arms as $mapping => $conditions) {
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
            $discriminators = $subProperty->typeAssertionExprs("\$this->{$name}");
            $arms[$mapping] = array_merge($arms[$mapping] ?? [], $discriminators);
        }

        $arms = $this->deduplicateConditions($arms);

        $match = new MatchGenerator("true");
        foreach ($arms as $mapping => $conditions) {
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
            $discriminators = $subProperty->typeAssertionExprs("\$this->{$name}");

            if (!isset($conversions[$assignment])) {
                $conversions[$assignment] = ["discriminators" => []];
            }

            $conversions[$assignment]["discriminators"] = array_merge(
                $conversions[$assignment]["discriminators"],
                $discriminators,
            );
        }

        $indent = StringUtils::indentCode(...);

        $conditionArms = [];
        foreach ($conversions as $assignment => $info) {
            $conditionArms[$assignment] = $info['discriminators'];
        }
        $conditionArms = $this->deduplicateConditions($conditionArms);

        $branches = [];
        foreach ($conditionArms as $assignment => $conditions) {
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
            $discriminators = $subProperty->typeAssertionExprs("\$this->{$name}");

            if (!isset($conversions[$assignment])) {
                $conversions[$assignment] = ["discriminators" => []];
            }

            $conversions[$assignment]["discriminators"] = array_merge(
                $conversions[$assignment]["discriminators"],
                $discriminators,
            );
        }

        $indent = StringUtils::indentCode(...);

        $conditionArms = [];
        foreach ($conversions as $assignment => $info) {
            $conditionArms[$assignment] = $info['discriminators'];
        }
        $conditionArms = $this->deduplicateConditions($conditionArms);

        $branches = [];
        foreach ($conditionArms as $assignment => $conditions) {
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
        return OrGenerator::make($this->typeAssertionExprs($expr));
    }

    public function inputAssertionExpr(string $expr): string
    {
        return OrGenerator::make($this->inputAssertionExprs($expr));
    }

    public function typeAssertionExprs(string $expr): array
    {
        $conditions = [];
        foreach ($this->subProperties as $prop) {
            $conditions = array_merge($conditions, $prop->typeAssertionExprs($expr));
        }
        return array_values(array_unique($conditions));
    }

    public function inputAssertionExprs(string $expr): array
    {
        $conditions = [];
        foreach ($this->subProperties as $prop) {
            $conditions = array_merge($conditions, $prop->inputAssertionExprs($expr));
        }
        return array_values(array_unique($conditions));
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        if ($this->request->isAtLeastPHP("8.0")) {
            $arms = [];
            foreach ($this->subProperties as $subProperty) {
                $map = $subProperty->inputMappingExpr($expr);
                $asserts = $subProperty->inputAssertionExprs($expr);
                $arms[$map] = array_merge($arms[$map] ?? [], $asserts);
            }

            $arms = $this->deduplicateConditions($arms);

            $match = new MatchGenerator("true");
            foreach ($arms as $map => $conditions) {
                $match->addArm(OrGenerator::make($conditions, parens: false), $map);
            }
            $match->addArm("default", "null");

            return $match->generate();
        }

        $conversions = [];
        foreach ($this->subProperties as $subProperty) {
            $map = $subProperty->inputMappingExpr($expr);
            $asserts = $subProperty->inputAssertionExprs($expr);
            $conversions[$map] = array_merge($conversions[$map] ?? [], $asserts);
        }

        $conversions = $this->deduplicateConditions($conversions);

        if ($conversions === []) {
            return 'null';
        }

        $out = 'null';
        foreach (array_reverse($conversions) as $map => $conditions) {
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
                $asserts = $subProperty->typeAssertionExprs($expr);
                $arms[$map] = array_merge($arms[$map] ?? [], $asserts);
            }

            $arms = $this->deduplicateConditions($arms);

            $match = new MatchGenerator("true");
            foreach ($arms as $map => $conditions) {
                $match->addArm(OrGenerator::make($conditions, parens: false), $map);
            }
            $match->addArm("default", "null");

            return $match->generate();
        }

        $conversions = [];
        foreach ($this->subProperties as $subProperty) {
            $map = $subProperty->outputMappingExpr($expr);
            $asserts = $subProperty->typeAssertionExprs($expr);
            $conversions[$map] = array_merge($conversions[$map] ?? [], $asserts);
        }

        $conversions = $this->deduplicateConditions($conversions);

        $out = "null";
        foreach (array_reverse($conversions) as $map => $conditions) {
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
                $asserts = $subProperty->typeAssertionExprs($expr);
                $arms[$map] = array_merge($arms[$map] ?? [], $asserts);
            }

            $arms = $this->deduplicateConditions($arms);

            $match = new MatchGenerator("true");
            foreach ($arms as $map => $conditions) {
                $match->addArm(OrGenerator::make($conditions, parens: false), $map);
            }
            $match->addArm("default", "null");

            return $match->generate();
        }

        $conversions = [];
        foreach ($this->subProperties as $subProperty) {
            $map = $subProperty->outputMappingExprStdClass($expr);
            if ($map === 'null') {
                continue;
            }

            $asserts = $subProperty->typeAssertionExprs($expr);
            $conversions[$map] = array_merge($conversions[$map] ?? [], $asserts);
        }

        $conversions = $this->deduplicateConditions($conversions);

        if ($conversions === []) {
            return 'null';
        }

        $out = 'null';
        foreach (array_reverse($conversions) as $map => $conditions) {
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
                $asserts = $subProperty->typeAssertionExprs($expr);
                $arms[$map] = array_merge($arms[$map] ?? [], $asserts);
            }

            if (count($arms) === 1) {
                $map = array_key_first($arms);
                if ($map === $expr) {
                    return $expr;
                }
                return $map;
            }

            $arms = $this->deduplicateConditions($arms);

            $match = new MatchGenerator("true");
            foreach ($arms as $map => $conditions) {
                $match->addArm(OrGenerator::make($conditions, parens: false), $map);
            }

            return $match->generate();
        }

        $conversions = [];
        foreach ($this->subProperties as $subProperty) {
            $map = $subProperty->cloneExpr($expr);
            if ($map === $expr) {
                // Identity mapping does not require a conditional branch. If the
                // expression does not need to be transformed, it can safely fall
                // through to the default case, avoiding ternaries where both
                // branches are identical.
                continue;
            }

            $asserts = $subProperty->typeAssertionExprs($expr);
            $conversions[$map] = array_merge($conversions[$map] ?? [], $asserts);
        }

        $conversions = $this->deduplicateConditions($conversions);

        if ($conversions === []) {
            return $expr;
        }

        $out = $expr;
        foreach (array_reverse($conversions) as $map => $conditions) {
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
