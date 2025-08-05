<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use Composer\Semver\Semver;

final class EnumUtils
{
    /**
     * Normalize enum values:
     *  - remove duplicate entries
     *  - convert floats that are equivalent to integers to int
     *
     * @param array<int|string|null|float|bool|array|object> $values
     * @return array<int|string|null|float|bool|array|object>
     */
    private static function normalize(array $values): array
    {
        $normalized = [];
        foreach ($values as $v) {
            if (is_float($v) && floor($v) == $v) {
                $v = (int)$v;
            }
            $normalized[] = $v;
        }

        // remove duplicates while preserving order
        $result   = [];
        $seenKeys = [];
        foreach ($normalized as $v) {
            $key = serialize($v);
            if (!isset($seenKeys[$key])) {
                $result[]        = $v;
                $seenKeys[$key] = true;
            }
        }

        return $result;
    }

    /**
     * @param array<int|string|null|float|bool|array|object> $values
     */
    public static function typeAnnotation(array $values): string
    {
        $values   = self::normalize($values);
        $literals = array_map(
            static function ($v): string {
                return $v === null ? 'null' : var_export($v, true);
            },
            $values
        );
        return implode('|', $literals);
    }

    /**
     * @param array<int|string|null|float|bool|array|object> $enumValues
     * TODO: currently we don't provide type hints when enum contains arrays or objects.
     */
    public static function typeHint(array $enumValues, string $phpVersion): ?string
    {
        if (!Semver::satisfies($phpVersion, '>=7.0')) {
            return null; // PHP before 7.0 doesn't support scalar types
        }

        $enumValues = self::normalize($enumValues);

        $types = [];
        foreach ($enumValues as $v) {
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
            } else {
                // arrays/objects ⇒ cannot build a type hint
                return null;
            }
        }

        $nullable = array_key_exists('null', $types);
        if ($nullable) {
            unset($types['null']);
        }

        if (!Semver::satisfies($phpVersion, '>=8.0')) {
            if (count($types) !== 1) {
                return null;
            }

            $type = array_key_first($types);
            return $nullable && Semver::satisfies($phpVersion, '>=7.1')
                ? '?' . $type
                : $type;
        }

        $ordered = array_values($types);
        sort($ordered);
        if ($nullable) {
            $ordered[] = 'null';
        }

        return implode('|', $ordered);
    }

    /**
     * @param array<int|string|null|float|bool|array|object> $values
     */
    public static function assertionExpr(array $values, string $expr): string
    {
        $values = self::normalize($values);
        $export = var_export($values, true);
        return "in_array({$expr}, {$export}, true)";
    }
}
