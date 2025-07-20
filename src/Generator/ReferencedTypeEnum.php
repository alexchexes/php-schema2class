<?php

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\SchemaToEnum;
use Helmich\Schema2Class\Generator\EnumUtils;

readonly class ReferencedTypeEnum implements ReferencedType
{
    /**
     * @param string $enumName Fully-qualified enum class name
     * @param array<array-key, string|int|array> $schema Schema definition of the enum
     */
    public function __construct(private string $enumName, private array $schema)
    {
    }

    private function useNativeEnum(GeneratorRequest $req): bool
    {
        return SchemaToEnum::canGenerateEnum($this->schema, $req);
    }

    private function relativeName(GeneratorRequest $req): string
    {
        $ns = $req->getTargetNamespace();
        if ($ns !== '' && str_starts_with($this->enumName, $ns . '\\')) {
            return substr($this->enumName, strlen($ns) + 1);
        }

        return '\\' . $this->enumName;
    }

    function name(): string
    {
        return $this->enumName;
    }

    public function typeAnnotation(GeneratorRequest $req): string
    {
        if ($this->useNativeEnum($req)) {
            return $this->relativeName($req);
        }

        return EnumUtils::typeAnnotation($this->schema['enum']);
    }

    public function typeHint(GeneratorRequest $req): ?string
    {
        if ($this->useNativeEnum($req)) {
            return $this->relativeName($req);
        }

        return EnumUtils::typeHint($this->schema['enum'], $req->getTargetPHPVersion());
    }

    function serializedInputTypeHint(GeneratorRequest $req): ?string
    {
        return $this->typeHint($req);
    }

    public function serializedTypeHint(GeneratorRequest $req): ?string
    {
        return $this->typeHint($req);
    }

    public function typeAssertionExpr(GeneratorRequest $req, string $expr): string
    {
        if ($this->useNativeEnum($req)) {
            return "({$expr}) instanceof " . $this->relativeName($req);
        }

        return EnumUtils::assertionExpr($this->schema['enum'], $expr);
    }

    public function inputAssertionExpr(GeneratorRequest $req, string $expr): string
    {
        if ($this->useNativeEnum($req)) {
            return "" . $this->relativeName($req) . "::tryFrom({$expr}) !== null";
        }

        return EnumUtils::assertionExpr($this->schema['enum'], $expr);
    }

    public function inputMappingExpr(GeneratorRequest $req, string $expr, ?string $validateExpr): string
    {
        if ($this->useNativeEnum($req)) {
            return $this->relativeName($req) . "::from({$expr})";
        }

        return $expr;
    }

    public function outputMappingExpr(GeneratorRequest $req, string $expr): string
    {
        if ($this->useNativeEnum($req)) {
            return "{$expr}->value";
        }

        return $expr;
    }

    public function outputObjectMappingExpr(GeneratorRequest $req, string $expr): string
    {
        return $this->outputMappingExpr($req, $expr);
    }

    public function usesNativeEnum(GeneratorRequest $req): bool
    {
        return $this->useNativeEnum($req);
    }

}