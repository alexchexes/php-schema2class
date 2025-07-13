<?php

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\SchemaToEnum;

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

        $literals = array_map(
            static function ($v): string {
                return var_export($v, true);
            },
            $this->schema['enum']
        );
        return implode('|', $literals);
    }

    public function typeHint(GeneratorRequest $req): ?string
    {
        if ($this->useNativeEnum($req)) {
            return $this->relativeName($req);
        }

        $types = [];
        foreach ($this->schema['enum'] as $v) {
            if ($v === null) {
                $types['null'] = 'null';
            } elseif (is_string($v)) {
                $types['string'] = 'string';
            } elseif (is_int($v)) {
                $types['int'] = 'int';
            } elseif (is_float($v)) {
                $types['float'] = 'float';
            } elseif (is_bool($v)) {
                $types['bool'] = 'bool';
            }
        }

        if (!$req->isAtLeastPHP('8.0') && count($types) !== 1) {
            return null;
        }

        if (isset($types['int']) && isset($types['float'])) {
            $types['int'] = 'int';
            $types['float'] = 'float';
        }

        return implode('|', array_values($types));
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

        $values = var_export($this->schema['enum'], true);
        return "in_array({$expr}, {$values}, true)";
    }

    public function inputAssertionExpr(GeneratorRequest $req, string $expr): string
    {
        if ($this->useNativeEnum($req)) {
            return "" . $this->relativeName($req) . "::tryFrom({$expr}) !== null";
        }

        $values = var_export($this->schema['enum'], true);
        return "in_array({$expr}, {$values}, true)";
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

    public function usesNativeEnum(GeneratorRequest $req): bool
    {
        return $this->useNativeEnum($req);
    }

}