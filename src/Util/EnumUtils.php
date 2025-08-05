<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use Composer\Semver\Semver;

final class EnumUtils
{
    /**
     * Normalizes enum values for downstream processing.
     *
     * JSON Schema treats `5` and `5.0` as equal, therefore we collapse
     * integral floats into ints so that `enum: [3, 4, 5.0]` produces a
     * homogeneous list of integers.
     *
     * @param array<int|string|null|float|bool|array|object> $values
     * @return array<int|string|null|float|bool|array|object>
     */
    public static function normalize(array $values): array
    {
        return array_map(
            static function ($v) {
                if (is_float($v) && (int)$v == $v) {
                    return (int) $v;
                }
                return $v;
            },
            $values
        );
    }

    /**
     * @param array<int|string|null|float|bool> $values
     */
    public static function typeAnnotation(array $values): string
    {
        $values = self::normalize($values);
        $literals = array_map(
            static function ($v): string {
                if ($v === null) {
                    return 'null';
                }
                return var_export($v, true);
            },
            $values
        );
        return implode('|', $literals);
    }

    /**
     * @param array<int|string|null|float|bool> $enumValues
     * 
     * TODO: we now don't handle case when enum value is array or object, for example:
     * ```
     * "enum": [42, true, "hello", null, [1, 2, 3]]
     * ```
     * 
     * TODO: check what happens when the only enum value is `null`. Do we get illegal `null` type hint?
     */
    public static function typeHint(array $enumValues, string $phpVersion): ?string
    {
        if (!Semver::satisfies($phpVersion, '>=7.0')) {
            // PHP before 7.0 doesn't support scalar types
            return null;
        }

        $enumValues = self::normalize($enumValues);

        $types = [];
        foreach ($enumValues as $v) {
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
            } else {
                $types['mixed'] = true; // objects/arrays – give up
            }
        }

        if (isset($types['mixed'])) {
            return null; // cannot express complex enums as native types
        }

        $nullable = false;
        if (isset($types['null'])) {
            $nullable = true;
            unset($types['null']);
        }

        if (count($types) > 1 && !Semver::satisfies($phpVersion, '>=8.0')) {
            return null;
        }

        if (count($types) === 1) {
            $type = array_key_first($types);
            if ($nullable) {
                if (Semver::satisfies($phpVersion, '>=7.1')) {
                    return '?' . $type;
                }
                return $type . '|null';
            }
            return $type;
        }

        $typeList = array_keys($types);
        if ($nullable) {
            $typeList[] = 'null';
        }

        return implode('|', $typeList);
    }

    /**
     * @param array<int|string|null|float|bool> $values
     */
    public static function assertionExpr(array $values, string $expr): string
    {
        $values = self::normalize($values);
        $export = var_export($values, true);
        return "in_array({$expr}, {$export}, true)";
    }

    /**
     * Determines JSON schema primitive types represented by a list of enum
     * values. The result contains names compatible with the `type` keyword
     * such as "string", "integer", "number", "boolean" and "null".
     *
     * @param array<int|string|null|float|bool|array|object> $values
     * @return list<string>
     */
    public static function schemaTypes(array $values): array
    {
        $values = self::normalize($values);
        $types  = [];
        foreach ($values as $v) {
            $t = match (true) {
                $v === null      => 'null',
                is_string($v)    => 'string',
                is_int($v)       => 'integer',
                is_float($v)     => 'number',
                is_bool($v)      => 'boolean',
                default          => null,
            };
            if ($t !== null) {
                $types[$t] = true;
            }
        }

        return array_keys($types);
    }
}
