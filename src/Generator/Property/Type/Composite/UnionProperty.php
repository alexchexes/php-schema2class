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

    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema["oneOf"]) || isset($schema["anyOf"]);
    }

    public function convertInputToType(): string
    {
        $name           = $this->varName();
        $inputVarName   = ArgumentNames::INPUT;
        $accessor       = "\${$inputVarName}->{{$this->keyStr()}}";

        $arms = $this->collectArms(
            mappingFn: fn(PropertyInterface $sub): string => $sub->inputMappingExpr($accessor, asserted: true),
            assertFn: function (PropertyInterface $sub) use ($accessor): string {
                $discriminator = $sub->inputAssertionExpr($accessor);

                if (
                    $sub instanceof ReferenceArrayProperty
                    || $sub instanceof ObjectArrayProperty
                    || $sub instanceof PrimitiveArrayProperty
                ) {
                    $isArrayCheck = "is_array({$accessor})";
                    if (!str_contains($discriminator, $isArrayCheck)) {
                        $discriminator = "({$isArrayCheck} && {$discriminator})";
                    }
                }

                if (
                    $sub instanceof NestedObjectProperty
                    || ($sub instanceof ReferenceProperty && $sub->getRefType() instanceof ReferencedTypeClass)
                ) {
                    $isObjCheck = "(is_object({$accessor}) || is_array({$accessor}))";
                    $discriminator = "{$isObjCheck} && {$discriminator}";
                }

                return $discriminator;
            }
        );

        $assignmentTemplate = "\${$name} = %s;";
        $fallback           = "\${$name} = {$accessor};";
        $matchDefault       = $accessor;
        if (!$this->request->isAtLeastPHP('8.0')) {
            if (isset($arms[$accessor])) {
                unset($arms[$accessor]);
            }
            if ($arms === []) {
                return $fallback;
            }
        }

        return $this->renderAssignments($arms, $assignmentTemplate, $matchDefault, $fallback);
    }

    public function convertTypeToArray(): string
    {
        $name       = $this->propName();
        $keyStr     = $this->keyStr();
        $outputVar  = VariableNames::OUTPUT;

        $expr = "\$this->{$name}";
        $arms = $this->collectArms(
            mappingFn: fn(PropertyInterface $sub): string => $sub->outputMappingExpr($expr),
            assertFn: fn(PropertyInterface $sub): string => $sub->typeAssertionExpr($expr),
            skipMapFn: fn(string $map) => $map === $expr,
        );

        $assignmentTemplate = "\${$outputVar}[{$keyStr}] = %s;";
        $fallback           = "\${$outputVar}[{$keyStr}] = {$expr};";

        return $this->renderAssignments($arms, $assignmentTemplate, $expr, $fallback);
    }

    public function convertTypeToStdClass(): string
    {
        $name       = $this->propName();
        $keyStr     = $this->keyStr();
        $outputVar  = VariableNames::OUTPUT;

        $expr = "\$this->{$name}";
        $arms = $this->collectArms(
            mappingFn: fn(PropertyInterface $sub): string => $sub->outputMappingExprStdClass($expr),
            assertFn: fn(PropertyInterface $sub): string => $sub->typeAssertionExpr($expr),
            skipMapFn: fn(string $map) => $map === $expr,
        );

        $assignmentTemplate = "\${$outputVar}->{{$keyStr}} = %s;";
        $fallback           = "\${$outputVar}->{{$keyStr}} = {$expr};";

        return $this->renderAssignments($arms, $assignmentTemplate, $expr, $fallback);
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
            $subAssertions[] = $prop->typeAssertionExpr($expr);
        }

        return OrGenerator::make($subAssertions);
    }

    public function inputAssertionExpr(string $expr): string
    {
        $subAssertions = [];

        foreach ($this->subProperties as $prop) {
            $subAssertions[] = $prop->inputAssertionExpr($expr);
        }

        return OrGenerator::make($subAssertions);
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        $skipMap = $this->request->isAtLeastPHP('8.0')
            ? null
            : fn(string $map) => $map === $expr;

        $arms = $this->collectArms(
            mappingFn: fn(PropertyInterface $sub): string => $sub->inputMappingExpr($expr),
            assertFn: function (PropertyInterface $sub) use ($expr): string {
                $discriminator = $sub->inputAssertionExpr($expr);

                if (
                    $sub instanceof ReferenceArrayProperty
                    || $sub instanceof ObjectArrayProperty
                    || $sub instanceof PrimitiveArrayProperty
                ) {
                    $isArrayCheck = "is_array({$expr})";
                    if (!str_contains($discriminator, $isArrayCheck)) {
                        $discriminator = "({$isArrayCheck} && {$discriminator})";
                    }
                }

                if (
                    $sub instanceof NestedObjectProperty
                    || ($sub instanceof ReferenceProperty && $sub->getRefType() instanceof ReferencedTypeClass)
                ) {
                    $isObjCheck = "(is_object({$expr}) || is_array({$expr}))";
                    $discriminator = "{$isObjCheck} && {$discriminator}";
                }

                return $discriminator;
            },
            skipMapFn: $skipMap,
        );

        $defaultExpr = $expr;
        if (isset($arms[$expr])) {
            $defaultExpr = "{$expr}";
        }

        return $this->renderConditionalExpr($arms, $defaultExpr);
    }

    public function outputMappingExpr(string $expr): string
    {
        $arms = $this->collectArms(
            mappingFn: fn(PropertyInterface $sub): string => $sub->outputMappingExpr($expr),
            assertFn: fn(PropertyInterface $sub): string => $sub->typeAssertionExpr($expr),
            skipMapFn: fn(string $map) => $map === $expr,
        );

        return $this->renderConditionalExpr($arms, $expr);
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        $arms = $this->collectArms(
            mappingFn: fn(PropertyInterface $sub): string => $sub->outputMappingExprStdClass($expr),
            assertFn: fn(PropertyInterface $sub): string => $sub->typeAssertionExpr($expr),
            skipMapFn: fn(string $map) => $map === $expr,
        );

        if ($arms === []) {
            return $expr;
        }

        return $this->renderConditionalExpr($arms, $expr);
    }

    public function cloneExpr(string $expr): string
    {
        $skipMap = $this->request->isAtLeastPHP('8.0')
            ? null
            : fn(string $map): bool => $map === $expr;

        $arms = $this->collectArms(
            mappingFn: fn(PropertyInterface $sub): string => $sub->cloneExpr($expr),
            assertFn: fn(PropertyInterface $sub): string => $sub->typeAssertionExpr($expr),
            skipMapFn: $skipMap,
        );

        if ($arms === []) {
            return $expr;
        }

        if ($this->request->isAtLeastPHP('8.0') && count($arms) === 1) {
            return array_key_first($arms);
        }

        return $this->renderConditionalExpr($arms, $expr, false);
    }

    /**
     * @param callable(PropertyInterface):string $mappingFn
     * @param callable(PropertyInterface):string $assertFn
     * @param (callable(string):bool)|null $skipMapFn
     * @return array<string,string[]>
     */
    private function collectArms(
        callable $mappingFn,
        callable $assertFn,
        ?callable $skipMapFn = null
    ): array {
        $arms = [];
        foreach ($this->subProperties as $subProperty) {
            $map = $mappingFn($subProperty);
            if ($skipMapFn !== null && $skipMapFn($map)) {
                continue;
            }
            $assert = $assertFn($subProperty);
            $arms[$map][] = $assert;
        }

        return $arms;
    }

    /**
     * @param array<string,string[]> $arms
     */
    private function renderMatch(array $arms, ?string $default): string
    {
        $match = new MatchGenerator('true');
        foreach ($arms as $map => $asserts) {
            $conditions = array_values(array_unique($asserts));
            $match->addArm(OrGenerator::make($conditions, parens: false), $map);
        }
        if ($default !== null) {
            $match->addArm('default', $default);
        }

        return $match->generate();
    }

    /**
     * @param array<string,string[]> $arms
     */
    private function renderTernaries(array $arms, string $default): string
    {
        $out = $default;
        foreach (array_reverse($arms) as $map => $asserts) {
            $conditions = array_values(array_unique($asserts));
            $cond       = OrGenerator::make($conditions);
            $out        = TernaryGenerator::make($cond, $map, $out);
        }

        return $out;
    }

    /**
     * @param array<string,string[]> $arms
     */
    private function renderConditionalExpr(array $arms, string $default, bool $includeMatchDefault = true): string
    {
        if ($this->request->isAtLeastPHP('8.0')) {
            return $this->renderMatch($arms, $includeMatchDefault ? $default : null);
        }

        return $this->renderTernaries($arms, $default);
    }

    /**
     * @param array<string,string[]> $arms
     */
    private function renderIfChain(array $arms, string $assignmentTemplate, ?string $fallback): string
    {
        $indent   = StringUtils::indentCode(...);
        $branches = [];
        foreach ($arms as $map => $asserts) {
            $assignment             = sprintf($assignmentTemplate, $map);
            $conditions             = array_values(array_unique($asserts));
            $parenthesizedCondition = OrGenerator::make($conditions);
            $keyword                = count($branches) ? 'elseif' : 'if';
            $branches[]             = <<<PHP
                {$keyword} {$parenthesizedCondition} {
                {$indent($assignment)}
                }
                PHP;
        }

        if ($fallback !== null) {
            if (count($branches)) {
                $branches[] = <<<PHP
                    else {
                    {$indent($fallback)}
                    }
                    PHP;
            } else {
                $branches[] = $fallback;
            }
        }

        return join(' ', $branches);
    }

    /**
     * @param array<string,string[]> $arms
     */
    private function renderAssignments(
        array $arms,
        string $assignmentTemplate,
        ?string $matchDefault,
        ?string $fallback
    ): string {
        if ($this->request->isAtLeastPHP('8.0')) {
            $expr = $this->renderMatch($arms, $matchDefault);
            return sprintf($assignmentTemplate, $expr);
        }

        return $this->renderIfChain($arms, $assignmentTemplate, $fallback);
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
