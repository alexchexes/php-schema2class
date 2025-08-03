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
readonly class ReferencedTypeUnknown implements ReferencedType
{
    public function name(): string
    {
        return "unknown";
    }

    public function typeAnnotation(GeneratorRequest $req): string
    {
        return "mixed";
    }

    public function typeHint(GeneratorRequest $req): ?string
    {
        if ($req->isAtLeastPHP("8.0")) {
            return "mixed";
        }

        return null;
    }

    public function serializedInputTypeHint(GeneratorRequest $req): ?string
    {
        if ($req->isAtLeastPHP("8.0")) {
            return "mixed";
        }

        return null;
    }

    public function serializedTypeHint(GeneratorRequest $req): ?string
    {
        if ($req->isAtLeastPHP("8.0")) {
            return "mixed";
        }

        return null;
    }

    public function serializedTypeHintStdClass(GeneratorRequest $req): ?string
    {
        return $this->serializedTypeHint($req);
    }

    public function typeAssertionExpr(GeneratorRequest $req, string $expr): string
    {
        return "true";
    }

    public function inputAssertionExpr(GeneratorRequest $req, string $expr): string
    {
        return "true";
    }

    public function inputMappingExpr(GeneratorRequest $req, string $expr): string
    {
        return $expr;
    }

    public function outputMappingExpr(GeneratorRequest $req, string $expr): string
    {
        return $expr;
    }

    public function outputMappingExprStdClass(GeneratorRequest $req, string $expr): string
    {
        return $expr;
    }
}
