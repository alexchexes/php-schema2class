<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\ReferencedType;

use Helmich\Schema2Class\Generator\GeneratorRequest;

/**
 * Placeholder type used when a `$ref` cannot be resolved.
 *
 * All generated expressions degrade to `mixed` and simple pass-through
 * conversions. Currently no code outside of type resolution is using this class
 * directly.
 */
readonly class ReferencedTypeUnknown implements ReferencedTypeInterface
{
    public function __construct(
        private GeneratorRequest $request,
    )
    {}

    public function name(): string
    {
        return "unknown";
    }

    public function typeAnnotation(): string
    {
        return "mixed";
    }

    public function typeHint(): ?string
    {
        if ($this->request->isAtLeastPHP("8.0")) {
            return "mixed";
        }

        return null;
    }

    public function serializedInputTypeHint(): ?string
    {
        if ($this->request->isAtLeastPHP("8.0")) {
            return "mixed";
        }

        return null;
    }

    public function serializedTypeHint(): ?string
    {
        if ($this->request->isAtLeastPHP("8.0")) {
            return "mixed";
        }

        return null;
    }

    public function serializedTypeHintStdClass(): ?string
    {
        return $this->serializedTypeHint();
    }

    public function typeAssertionExpr(string $expr): string
    {
        return "true";
    }

    public function inputAssertionExpr(string $expr): string
    {
        return "true";
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        return $expr;
    }

    public function outputMappingExpr(string $expr): string
    {
        return $expr;
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        return $expr;
    }
}
