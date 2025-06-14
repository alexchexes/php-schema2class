<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

readonly class ReferencedTypeClass implements ReferencedType
{
    public function __construct(private string $className)
    {
    }

    public function name(): string
    {
        return $this->className;
    }

    public function typeAnnotation(GeneratorRequest $req): string
    {
        // Use fully-qualified class name so annotations resolve correctly
        return '\\' . ltrim($this->className, '\\');
    }

    public function typeHint(GeneratorRequest $req): ?string
    {
        return '\\' . ltrim($this->className, '\\');
    }

    public function serializedInputTypeHint(GeneratorRequest $req): ?string
    {
        return 'array|object';
    }

    public function serializedTypeHint(GeneratorRequest $req): ?string
    {
        return 'array';
    }

    public function typeAssertionExpr(GeneratorRequest $req, string $expr): string
    {
        $class = '\\' . ltrim($this->className, '\\');
        return "({$expr}) instanceof {$class}";
    }

    public function inputAssertionExpr(GeneratorRequest $req, string $expr): string
    {
        $class = '\\' . ltrim($this->className, '\\');
        return "{$class}::validateInput({$expr}, true)";
    }

    public function inputMappingExpr(GeneratorRequest $req, string $expr, ?string $validateExpr): string
    {
        $validateExpr = $validateExpr ?? '$validate';
        $class = '\\' . ltrim($this->className, '\\');
        if ($req->isAtLeastPHP('8.0')) {
            return "{$class}::buildFromInput({$expr}, {$validateExpr})";
        }
        return "{$class}::buildFromInput({$expr}, {$validateExpr})";
    }

    public function outputMappingExpr(GeneratorRequest $req, string $expr): string
    {
        return "{$expr}->toJson()";
    }
}
