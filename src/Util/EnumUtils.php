<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use Composer\Semver\Semver;

final class EnumUtils
{
    /**
     * @param array<int|string|null|float|bool> $values
     */
    public static function typeAnnotation(array $values): string
    {
        $literals = array_map(static fn($v): string => var_export($v, true), $values);
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
            return null; // PHP before 7.0 doesn't support scalar types
        }

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
            }
        }

        if (!Semver::satisfies($phpVersion, '>=8.0') && count($types) !== 1) {
            return null;
        }

        return implode('|', array_values($types));
    }

    /**
     * @param array<int|string|null|float|bool> $values
     */
    public static function assertionExpr(array $values, string $expr): string
    {
        $export = var_export($values, true);
        return "in_array({$expr}, {$export}, true)";
    }
}
