<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property;

use Composer\Semver\Semver;
use Laminas\Code\Generator\PropertyValueGenerator;

/** 
 * Enum schema definitions without a declared type.
 * Type is inferred from a list of literal values found in "enum".
 */
class InferredEnumProperty extends AbstractProperty
{
    private array $valueTypes = [];

    public function __construct(string $key, array $schema, \Helmich\Schema2Class\Generator\GeneratorRequest $generatorRequest)
    {
        parent::__construct($key, $schema, $generatorRequest);
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
        $quote = static fn(string $s): string => "'" . str_replace("'", "\\'", $s) . "'";

        $values = array_map(static function ($v) use ($quote) {
            return match (true) {
                $v === null      => 'null',
                is_bool($v)      => $v ? 'true' : 'false',
                is_string($v)    => $quote($v),
                is_int($v),
                is_float($v)     => (string) $v,
                default          => 'null',
            };
        }, $this->schema['enum']);

        return implode('|', $values);
    }

    public function typeHint(string $phpVersion): ?string
    {
        if (!Semver::satisfies($phpVersion, '>=8.0')) {
            return null;
        }
        if (isset($this->valueTypes['mixed'])) {
            return null;
        }
        return implode('|', array_keys($this->valueTypes));
    }

    public function generateTypeAssertionExpr(string $expr): string
    {
        $values = var_export($this->schema['enum'], true);
        return "in_array({$expr}, {$values}, true)";
    }

    public function formatValue(mixed $value): PropertyValueGenerator
    {
        return new PropertyValueGenerator($value);
    }
}
