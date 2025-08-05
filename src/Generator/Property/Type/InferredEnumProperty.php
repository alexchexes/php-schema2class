<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Composer\Semver\Semver;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Laminas\Code\Generator\PropertyValueGenerator;

/** 
 * Enum schema definitions without a declared type.
 * Type is inferred from a list of literal values found in "enum".
 */
class InferredEnumProperty extends AbstractProperty
{
    private array $valueTypes = [];

    public function __construct(
        string $key,
        array $schema,
        GeneratorRequest $request
    )
    {
        parent::__construct($key, $schema, $request);
        foreach ($schema['enum'] as $v) {
            if ($v === null) {
                $this->valueTypes['null'] = true;
            } elseif (is_string($v)) {
                $this->valueTypes['string'] = true;
            } elseif (is_int($v)) {
                $this->valueTypes['int'] = true;
            } elseif (is_float($v)) {
                $this->valueTypes['float'] = true;
            } elseif (is_bool($v)) {
                $this->valueTypes['bool'] = true;
            } else {
                $this->valueTypes['mixed'] = true;
            }
        }
    }

    public static function canHandleSchema(array $schema): bool
    {
        return isset($schema['enum']) && !isset($schema['type']);
    }

    public function typeAnnotation(): string
    {
        $values = array_map(
            static fn (mixed $v) => match (true) {
                $v === null     => 'null',
                is_bool($v)     => $v ? 'true' : 'false',
                is_string($v)   => var_export($v, true),
                is_int($v),
                is_float($v)    => (string) $v,
                default         => 'null',
            },
            $this->schema['enum']
        );

        return implode('|', $values);
    }

    public function typeHint(): ?string
    {
        if (!$this->request->isAtLeastPHP('7.0')) {
            return null; // since here we handle only scalars, nothing left for type hint in PHP < 7
        }
        if (isset($this->valueTypes['mixed'])) {
            return null;
        }
        $valueTypes = $this->valueTypes;
        $nullable = false;
        if (array_key_exists('null', $valueTypes)) {
            $nullable = true;
            unset($valueTypes['null']);
        }
        if (count($valueTypes) > 1 && !$this->request->isAtLeastPHP('8.0')) {
            return null;
        }
        if (count($valueTypes) === 1 && $nullable && $this->request->isAtLeastPHP('7.1')) {
            return '?' . array_key_first($valueTypes);
        }
        $typesForHint = array_keys($valueTypes);
        if ($nullable) {
            $typesForHint[] = 'null';
        }        
        return implode('|', $typesForHint);
    }

    public function typeAssertionExpr(string $expr): string
    {
        $values = var_export($this->schema['enum'], true);
        return "in_array({$expr}, {$values}, true)";
    }

    public function formatValue(mixed $value): PropertyValueGenerator
    {
        return new PropertyValueGenerator($value);
    }

    public function needsValidation(): bool
    {
        return true;
    }
}
