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

    private function relativeName(GeneratorRequest $req): string
    {
        $ns = $req->getTargetNamespace();
        if ($ns !== '' && str_starts_with($this->className, $ns . '\\')) {
            return substr($this->className, strlen($ns) + 1);
        }

        return '\\' . $this->className;
    }

    public function typeAnnotation(GeneratorRequest $req): string
    {
        return ltrim($this->relativeName($req), '\\');
    }

    public function typeHint(GeneratorRequest $req): ?string
    {
        return $this->relativeName($req);
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
        return "({$expr}) instanceof {$this->className}";
    }

    public function inputAssertionExpr(GeneratorRequest $req, string $expr): string
    {
        return "{$this->className}::validateInput({$expr}, true)";
    }

    public function inputMappingExpr(GeneratorRequest $req, string $expr, ?string $validateExpr): string
    {
        $validateExpr = $validateExpr ?? '$validate';
        if ($req->isAtLeastPHP('8.0')) {
            return "{$this->className}::buildFromInput({$expr}, {$validateExpr})";
        }
        return "{$this->className}::buildFromInput({$expr}, {$validateExpr})";
    }

    public function outputMappingExpr(GeneratorRequest $req, string $expr): string
    {
        return "{$expr}->toJson()";
    }
}
