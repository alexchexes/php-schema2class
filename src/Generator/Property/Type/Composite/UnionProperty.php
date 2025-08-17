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
        $name   = $this->varName();
        $keyStr = $this->keyStr();
        $inputVarName = ArgumentNames::INPUT;
        $accessor = "\${$inputVarName}->{{$keyStr}}";

        $skipMap = $this->request->isAtLeastPHP('8.0') ? null : fn(string $map): bool => $map === $accessor;

        $conversions = $this->collectConversions(
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

                return $discriminator;
            },
            skipMapFn: $skipMap
        );

        if ($this->request->isAtLeastPHP('8.0')) {
            $match = $this->renderMatch(
                $conversions,
                "throw new \\InvalidArgumentException(\"could not build property '{$this->key()}' from JSON\")"
            );
            return "\${$name} = {$match};";
        }

        $fallback = "\${$name} = {$accessor};";
        return $this->renderIfChain(
            $conversions,
            fn(string $map): string => "\${$name} = {$map};",
            $fallback
        );
    }

    public function convertTypeToArray(): string
    {
        $name   = $this->propName();
        $keyStr = $this->keyStr();
        $conversions = $this->collectConversions(
            mappingFn: fn(PropertyInterface $sub): string => $sub->outputMappingExpr("\$this->{$name}"),
            assertFn: fn(PropertyInterface $sub): string => $sub->typeAssertionExpr("\$this->{$name}")
        );

        $outputVarName = VariableNames::OUTPUT;
        if ($this->request->isAtLeastPHP('8.0')) {
            $match = $this->renderMatch($conversions, null);
            return "\${$outputVarName}[{$keyStr}] = {$match};";
        }

        return $this->renderIfChain(
            $conversions,
            fn(string $map): string => "\${$outputVarName}[{$keyStr}] = {$map};"
        );
    }

    public function convertTypeToStdClass(): string
    {
        $name   = $this->propName();
        $keyStr = $this->keyStr();
        $conversions = $this->collectConversions(
            mappingFn: fn(PropertyInterface $sub): string => $sub->outputMappingExprStdClass("\$this->{$name}"),
            assertFn: fn(PropertyInterface $sub): string => $sub->typeAssertionExpr("\$this->{$name}")
        );

        $outputVarName = VariableNames::OUTPUT;
        if ($this->request->isAtLeastPHP('8.0')) {
            $match = $this->renderMatch($conversions, null);
            return "\${$outputVarName}->{{$keyStr}} = {$match};";
        }

        return $this->renderIfChain(
            $conversions,
            fn(string $map): string => "\${$outputVarName}->{{$keyStr}} = {$map};"
        );
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
        $conversions = $this->collectConversions(
            mappingFn: fn(PropertyInterface $sub): string => $sub->inputMappingExpr($expr),
            assertFn: fn(PropertyInterface $sub): string => $sub->inputAssertionExpr($expr)
        );

        if ($this->request->isAtLeastPHP('8.0')) {
            return $this->renderMatch($conversions, 'null');
        }

        return $this->renderTernaries($conversions, 'null');
    }

    public function outputMappingExpr(string $expr): string
    {
        $conversions = $this->collectConversions(
            mappingFn: fn(PropertyInterface $sub): string => $sub->outputMappingExpr($expr),
            assertFn: fn(PropertyInterface $sub): string => $sub->typeAssertionExpr($expr)
        );

        if ($this->request->isAtLeastPHP('8.0')) {
            return $this->renderMatch($conversions, 'null');
        }

        return $this->renderTernaries($conversions, 'null');
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        $skipNull = $this->request->isAtLeastPHP('8.0') ? null : fn(string $map): bool => $map === 'null';

        $conversions = $this->collectConversions(
            mappingFn: fn(PropertyInterface $sub): string => $sub->outputMappingExprStdClass($expr),
            assertFn: fn(PropertyInterface $sub): string => $sub->typeAssertionExpr($expr),
            skipMapFn: $skipNull
        );

        if ($this->request->isAtLeastPHP('8.0')) {
            return $this->renderMatch($conversions, 'null');
        }

        if ($conversions === []) {
            return 'null';
        }

        return $this->renderTernaries($conversions, 'null');
    }

    public function cloneExpr(string $expr): string
    {
        $skipMap = $this->request->isAtLeastPHP('8.0') ? null : fn(string $map): bool => $map === $expr;

        $conversions = $this->collectConversions(
            mappingFn: fn(PropertyInterface $sub): string => $sub->cloneExpr($expr),
            assertFn: fn(PropertyInterface $sub): string => $sub->typeAssertionExpr($expr),
            skipMapFn: $skipMap
        );

        if ($this->request->isAtLeastPHP('8.0')) {
            if (count($conversions) === 1) {
                $map = array_key_first($conversions);
                if ($map === $expr) {
                    return $expr;
                }
                return $map;
            }

            return $this->renderMatch($conversions, null);
        }

        if ($conversions === []) {
            return $expr;
        }

        return $this->renderTernaries($conversions, $expr);
    }

    /**
     * @param callable(PropertyInterface):string $mappingFn
     * @param callable(PropertyInterface):string $assertFn
     * @param (callable(string):bool)|null $skipMapFn
     * @return array<string,string[]>
     */
    private function collectConversions(
        callable $mappingFn,
        callable $assertFn,
        ?callable $skipMapFn = null
    ): array {
        $conversions = [];
        foreach ($this->subProperties as $subProperty) {
            $map = $mappingFn($subProperty);
            if ($skipMapFn !== null && $skipMapFn($map)) {
                continue;
            }
            $assert = $assertFn($subProperty);
            $conversions[$map][] = $assert;
        }
        return $conversions;
    }

    /**
     * @param array<string,string[]> $conversions
     */
    private function renderMatch(array $conversions, ?string $default): string
    {
        $match = new MatchGenerator('true');
        foreach ($conversions as $map => $asserts) {
            $conditions = array_values(array_unique($asserts));
            $match->addArm(OrGenerator::make($conditions, parens: false), $map);
        }
        if ($default !== null) {
            $match->addArm('default', $default);
        }
        return $match->generate();
    }

    /**
     * @param array<string,string[]> $conversions
     * @param callable(string):string $assignmentFormatter
     */
    private function renderIfChain(
        array $conversions,
        callable $assignmentFormatter,
        ?string $fallback = null
    ): string {
        $indent = StringUtils::indentCode(...);

        $branches = [];
        foreach ($conversions as $map => $asserts) {
            $conditions = array_values(array_unique($asserts));
            $parenthesizedCondition = OrGenerator::make($conditions);
            $assignment = $assignmentFormatter($map);
            $keyword = count($branches) ? 'elseif' : 'if';
            $branches[] = <<<PHP
                {$keyword} {$parenthesizedCondition} {
                {$indent($assignment)}
                }
                PHP;
        }

        if ($fallback !== null) {
            if (count($branches) > 0) {
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
     * @param array<string,string[]> $conversions
     */
    private function renderTernaries(array $conversions, string $default): string
    {
        $out = $default;
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
