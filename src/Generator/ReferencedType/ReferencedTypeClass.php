<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\ReferencedType;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;

/**
 * Reference to another generated PHP class.
 *
 * It resolves type hints and annotations relative to the current namespace
 * so that the generated code can refer to generated classes correctly.
 */
readonly class ReferencedTypeClass implements ReferencedTypeInterface
{
    public function __construct(
        private string $className,
        private GeneratorRequest $request,
    ) {}

    private function relativeName(): string
    {
        $ns = $this->request->getTargetNamespace();
        if ($ns !== '' && str_starts_with($this->className, $ns . '\\')) {
            return substr($this->className, strlen($ns) + 1);
        }

        return '\\' . $this->className;
    }

    public function typeAnnotation(): string
    {
        return $this->relativeName();
    }

    public function typeHint(): ?string
    {
        return $this->relativeName();
    }

    public function serializedInputTypeHint(): ?string
    {
        return 'array|object';
    }

    public function serializedTypeHint(): ?string
    {
        return 'array';
    }

    public function serializedTypeHintStdClass(): ?string
    {
        return 'object';
    }

    public function typeAssertionExpr(string $expr): string
    {
        $cls = $this->relativeName();
        return "{$expr} instanceof {$cls}";
    }

    public function inputAssertionExpr(string $expr): string
    {
        $cls = $this->relativeName();
        $VALIDATE_INPUT = MethodNames::VALIDATE_INPUT;
        return "{$cls}::{$VALIDATE_INPUT}({$expr}, true)";
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        $args = [$expr, 'false' /* no need to validate again */];
        if ($this->request->getClassHasDefaults()) {
            $args[] = '$' . ArgumentNames::MATRLZ_DEFAULTS;
        }
        $argsStr = implode(', ', $args);
        
        $cls = $this->relativeName();
        $FROM_INPUT = MethodNames::FROM_INPUT;
        return "{$cls}::{$FROM_INPUT}({$argsStr})";
    }

    public function outputMappingExpr(string $expr): string
    {
        $inclDefaultsArg = $this->request->getClassHasDefaults() ? '$' . ArgumentNames::INCL_DEFAULTS : '';
        $TO_ARRAY = MethodNames::TO_ARRAY;
        return "{$expr}->{$TO_ARRAY}({$inclDefaultsArg})";
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        $inclDefaultsArg = $this->request->getClassHasDefaults() ? '$' . ArgumentNames::INCL_DEFAULTS : '';
        $TO_STD_CLASS = MethodNames::TO_STD_CLASS;
        return "{$expr}->{$TO_STD_CLASS}({$inclDefaultsArg})";
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
