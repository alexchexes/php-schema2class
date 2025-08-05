<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use Composer\Semver\Semver;

final class EnumUtils
{
    private static function normalizeValue(mixed $v): mixed
    {
        if (is_float($v) && floor($v) == $v) {
            return (int)$v;
        }
        if (is_array($v)) {
            foreach ($v as $k => $vv) {
                $v[$k] = self::normalizeValue($vv);
            }
            return $v;
        }
        if (is_object($v)) {
            foreach ($v as $k => $vv) {
                $v->$k = self::normalizeValue($vv);
            }
            return $v;
        }
        return $v;
    }

    /**
     * @return array{values: array<mixed>, types: array<string,true>}
     */
    private static function analyze(array $values): array
    {
        $types     = [];
        $normalized = [];
        foreach ($values as $v) {
            $v            = self::normalizeValue($v);
            $normalized[] = $v;
            if ($v === null) {
                $types['null'] = true;
            } elseif (is_string($v)) {
                $types['string'] = true;
            } elseif (is_int($v)) {
                $types['int'] = true;
            } elseif (is_float($v)) {
                $types['float'] = true;
            } elseif (is_bool($v)) {
                $types['bool'] = true;
            } elseif (is_array($v)) {
                $types['array'] = true;
            } elseif (is_object($v)) {
                $types['object'] = true;
            } else {
                $types['mixed'] = true;
            }
        }

        return ['values' => $normalized, 'types' => $types];
    }

    /**
     * Normalize enum values (e.g. convert 5.0 to 5)
     *
     * @param array<mixed> $values
     * @return array<mixed>
     */
    public static function normalizeEnumValues(array $values): array
    {
        return self::analyze($values)['values'];
    }

    /**
     * Infer JSON schema type(s) from enum values.
     *
     * @param array<mixed> $values
     * @return string|array<int,string>|null
     */
    public static function inferType(array $values): string|array|null
    {
        $analysis = self::analyze($values);
        $map      = [
            'int'    => 'integer',
            'float'  => 'number',
            'string' => 'string',
            'bool'   => 'boolean',
            'array'  => 'array',
            'object' => 'object',
            'null'   => 'null',
        ];
        $types = [];
        foreach (array_keys($analysis['types']) as $t) {
            if ($t === 'mixed') {
                return null;
            }
            $types[$map[$t]] = true;
        }
        if (isset($types['number']) && isset($types['integer'])) {
            unset($types['integer']);
        }
        $types = array_keys($types);
        return count($types) === 1 ? $types[0] : $types;
    }

    /**
     * @param array<mixed> $values
     */
    public static function typeAnnotation(array $values): string
    {
        $analysis = self::analyze($values);
        $literals = array_map(
            static function ($v): string {
                if ($v === null) {
                    return 'null';
                }
                return var_export($v, true);
            },
            $analysis['values']
        );
        return implode('|', $literals);
    }

    /**
     * @param array<mixed> $enumValues
     */
    public static function typeHint(array $enumValues, string $phpVersion): ?string
    {
        if (!Semver::satisfies($phpVersion, '>=7.0')) {
            return null; // PHP before 7.0 doesn't support scalar types
        }

        $analysis = self::analyze($enumValues);
        $types    = array_keys($analysis['types']);

        if (in_array('mixed', $types, true)) {
            return null;
        }

        $nullable = false;
        if (($k = array_search('null', $types, true)) !== false) {
            $nullable = true;
            unset($types[$k]);
        }

        $types = array_values($types);
        if (count($types) === 0) {
            return null;
        }

        if (count($types) > 1 && !Semver::satisfies($phpVersion, '>=8.0')) {
            return null;
        }

        $hint = implode('|', $types);
        if ($nullable) {
            if (count($types) === 1 && Semver::satisfies($phpVersion, '>=7.1')) {
                return '?' . $hint;
            }
            $hint .= '|null';
        }

        return $hint;
    }

    /**
     * @param array<mixed> $values
     */
    public static function assertionExpr(array $values, string $expr): string
    {
        $analysis = self::analyze($values);
        $export   = var_export($analysis['values'], true);
        return "in_array({$expr}, {$export}, true)";
    }
}
