<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Util;

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
     * @param array<int|string|null|float|bool> $values
     */
    public static function typeHint(array $values, string $phpVersion): ?string
    {
        $types = [];
        foreach ($values as $v) {
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

        if (isset($types['int']) && isset($types['float'])) {
            $types['int'] = 'int';
            $types['float'] = 'float';
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
