<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Array;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Expression\ArrayMapGenerator;
use Helmich\Schema2Class\Generator\Expression\ArrowFunctionGenerator;
use Helmich\Schema2Class\Generator\Expression\CallGenerator;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Type\AbstractProperty;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeClass;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeInterface;
use Helmich\Schema2Class\Util\StringUtils;

/**
 * Property that is array where each item refers to a generated PHP artifact (class, Enum) via `$ref`.
 */
class ReferenceArrayProperty extends AbstractProperty
{
    private ReferencedTypeInterface $refType;

    public function __construct(string $key, array $schema, GeneratorRequest $request)
    {
        parent::__construct($key, $schema, $request);
        $this->refType = $request->lookupReference($schema['items']['$ref']);
    }

    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema['type'])
            && $schema['type'] === 'array'
            && isset($schema['items']['$ref']);
    }

    public function typeAnnotation(): string
    {
        $inner = $this->refType->typeAnnotation();

        if (str_contains($inner, "|")) {
            return "({$inner})[]";
        }

        return $inner . '[]';
    }

    public function typeHint(): ?string
    {
        return "array";
    }

    public function typeAssertionExpr(string $expr): string
    {
        $arrayMap = ArrayMapGenerator::make(
            arrayExpr: $expr,
            itemParam: '$i',
            itemType: $this->refType->typeHint(),
            mapExpr: $this->refType->typeAssertionExpr('$i'),
            returnType: 'bool',
            phpVer: $this->request->getTargetPHPVersion(),
        );

        $reduceCallback = ArrowFunctionGenerator::make(
            parameters: ['$carry' => 'bool', '$item' => 'bool'],
            expr: '$carry && $item',
            phpVer: $this->request->getTargetPHPVersion(),
            returnType: 'bool',
        );

        return CallGenerator::make(
            callee: 'array_reduce',
            arguments: [$arrayMap, $reduceCallback, 'true'],
            phpVer: $this->request->getTargetPHPVersion(),
        );
    }

    public function inputAssertionExpr(string $expr): string
    {
        $arrayMap = ArrayMapGenerator::make(
            arrayExpr: $expr,
            itemParam: '$i',
            itemType: $this->refType->serializedInputTypeHint(),
            mapExpr: $this->refType->inputAssertionExpr('$i'),
            returnType: 'bool',
            phpVer: $this->request->getTargetPHPVersion(),
        );

        $reduceCallback = ArrowFunctionGenerator::make(
            parameters: ['$carry' => 'bool', '$item' => 'bool'],
            expr: '$carry && $item',
            phpVer: $this->request->getTargetPHPVersion(),
            returnType: 'bool',
        );

        return CallGenerator::make(
            callee: 'array_reduce',
            arguments: [$arrayMap, $reduceCallback, 'true'],
            phpVer: $this->request->getTargetPHPVersion(),
        );
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        $useVars = [];
        if ($this->refType instanceof ReferencedTypeClass) {
            $useVars = ['$' . ArgumentNames::VALIDATE];
            if ($this->request->getClassHasDefaults()) {
                $useVars[] = '$' . ArgumentNames::MATRLZ_DEFAULTS;
            }
        }
        
        return ArrayMapGenerator::make(
            arrayExpr: $expr,
            itemParam: '$i',
            itemType: $this->refType->serializedInputTypeHint(),
            mapExpr: $this->refType->inputMappingExpr('$i'),
            returnType: $this->refType->typeHint(),
            phpVer: $this->request->getTargetPHPVersion(),
            useVars: $useVars,
        );
    }

    public function outputMappingExpr(string $expr): string
    {
        $useVars = [];
        if ($this->refType instanceof ReferencedTypeClass && $this->request->getClassHasDefaults()) {
            $useVars[] = '$' . ArgumentNames::INCL_DEFAULTS;
        }
        return ArrayMapGenerator::make(
            arrayExpr: $expr,
            itemParam: '$i',
            itemType: $this->refType->typeHint(),
            mapExpr: $this->refType->outputMappingExpr('$i'),
            returnType: $this->refType->serializedTypeHint(),
            phpVer: $this->request->getTargetPHPVersion(),
            useVars: $useVars,
        );
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        $useVars = [];
        if ($this->refType instanceof ReferencedTypeClass && $this->request->getClassHasDefaults()) {
            $useVars[] = '$' . ArgumentNames::INCL_DEFAULTS;
        }
        return ArrayMapGenerator::make(
            arrayExpr: $expr,
            itemParam: '$i',
            itemType: $this->refType->typeHint(),
            mapExpr: $this->refType->outputMappingExprStdClass('$i'),
            returnType: $this->refType->serializedTypeHintStdClass(),
            phpVer: $this->request->getTargetPHPVersion(),
            useVars: $useVars,
        );
    }

    public function cloneExpr(string $expr): string
    {
        if (!($this->refType instanceof ReferencedTypeClass)) {
            return $expr;
        }

        return ArrayMapGenerator::make(
            arrayExpr: $expr,
            itemParam: '$i',
            mapExpr: 'clone $i',
            phpVer: $this->request->getTargetPHPVersion(),
            itemType: $this->refType->typeHint(),
            returnType: $this->refType->typeHint(),
        );
    }

    public function needsValidation(): bool
    {
        // Typed arrays always require validation since their type-hint is just 'array'
        return true;
    }
    
    public function inputMappingRequiresNullCheck(): bool
    {
        return true;
    }

    public function outputMappingRequiresNullCheck(): bool
    {
        return true;
    }
}
