<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use Composer\Semver\Semver;
use Laminas\Code\Generator\ValueGenerator;
use Symfony\Component\VarExporter\VarExporter;

final class EnumUtils
{
    private static function normalizeValue(mixed $v): mixed
    {
        if (is_float($v) && fmod($v, 1.0) === 0.0) {
            return (int)$v;
        }

        return $v;
    }

    /**
     * @param array<int|string|null|float|bool> $values
     */
    public static function typeAnnotation(array $values): string
    {
        $normalized = array_map([self::class, 'normalizeValue'], $values);
        $literals   = array_map(
            static function ($v): string {
                if ($v === null) {
                    return 'null';
                }
                return var_export($v, true);
            },
            $normalized
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
            return null; // PHP before 7.0 doesn't support scalar types
        }

        $types = [];
        foreach ($enumValues as $v) {
            $v = self::normalizeValue($v);
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

        if (count($types) === 1 && isset($types['null'])) {
            return null;
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
        $normalized = array_map([self::class, 'normalizeValue'], $values);
        $exported = new ValueGenerator($normalized, ValueGenerator::TYPE_ARRAY_SHORT)
            ->setOutputMode(ValueGenerator::OUTPUT_SINGLE_LINE)
            ->generate();

        return "in_array({$expr}, {$exported}, true)";
    }
}
