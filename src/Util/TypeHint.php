<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use Composer\Semver\Semver;

final class TypeHint
{
    public const TYPE_KIND_RETURN = 'return';
    public const TYPE_KIND_ARG    = 'arg';
    public const TYPE_KIND_PROP   = 'class-prop';

    public const SCALARS = ['int', 'float', 'string', 'bool'];

    /**
     * Takes a desired type-hint of the given kind (return, argument, property)
     * and returns a type-hint that is compatible with the given PHP version.
     *
     * @param array<string>|string|null $types
     * @param 'arg'|'return'|'class-prop' $kind
     */
    public static function hintForPhpVersion(array|string|null $types, string $phpVersion, string $kind): ?string
    {
        $types = self::parseTypes($types);
        if ($types === null) {
            return null;
        }

        if ($kind === self::TYPE_KIND_RETURN && !Semver::satisfies($phpVersion, '>=7.0')) {
            return null; // return types before PHP 7 are unsupported
        }

        if ($kind === self::TYPE_KIND_PROP && !Semver::satisfies($phpVersion, '>=7.4')) {
            return null; // typed properties added in 7.4
        }

        [$types, $hasNull] = self::separateNull($types);
        if ($types === [] && !$hasNull) {
            return null;
        }

        if ($types === []) {
            return Semver::satisfies($phpVersion, '>=8.2') ? 'null' : null;
        }

        // Downgrade literal boolean types when unsupported
        $types = array_map(
            static function (string $t) use ($phpVersion): string {
                if (($t === 'true' || $t === 'false') && !Semver::satisfies($phpVersion, '>=8.2')) {
                    return 'bool';
                }
                return $t;
            },
            $types
        );
        $types = array_values(array_unique($types));

        // Special handling for mixed
        if (in_array('mixed', $types, true)) {
            if (!Semver::satisfies($phpVersion, '>=8.0')) {
                return null;
            }
            if (count($types) > 1) {
                throw new \InvalidArgumentException(
                    "Mixing 'mixed' with other types other than null might be a severe logic mistake."
                );
            }
            return 'mixed';
        }

        // void, never and static cannot be combined or used in certain places
        if (in_array('void', $types, true)) {
            return self::validateExclusiveType('void', '>=7.1', $phpVersion, $kind, $types, $hasNull);
        }
        if (in_array('never', $types, true)) {
            return self::validateExclusiveType('never', '>=8.1', $phpVersion, $kind, $types, $hasNull);
        }
        if (in_array('static', $types, true)) {
            return self::validateExclusiveType('static', '>=8.0', $phpVersion, $kind, $types, $hasNull);
        }

        // feature checks for individual types
        foreach ($types as $t) {
            if ($t === 'iterable' && !Semver::satisfies($phpVersion, '>=7.1')) {
                return null;
            }
            if ($t === 'object' && !Semver::satisfies($phpVersion, '>=7.2')) {
                return null;
            }
        }

        // unions, intersections and DNF
        $hasIntersection = false;
        foreach ($types as $t) {
            if (str_contains($t, '&')) {
                $hasIntersection = true;
                break;
            }
        }
        $hasUnion = count($types) > 1;

        if ($hasUnion && $hasIntersection) {
            if (!Semver::satisfies($phpVersion, '>=8.2')) {
                return null;
            }
        } elseif ($hasIntersection) {
            if (!Semver::satisfies($phpVersion, '>=8.1')) {
                return null;
            }
        } elseif ($hasUnion) {
            if (!Semver::satisfies($phpVersion, '>=8.0')) {
                return null;
            }
        }

        sort($types);
        if ($hasNull) {
            if (!$hasUnion && !$hasIntersection && Semver::satisfies($phpVersion, '>=7.1') && !Semver::satisfies($phpVersion, '>=8.0')) {
                return '?' . $types[0];
            }
            $types[] = 'null';
        }

        return implode('|', $types);
    }

    /**
     * @param array<string>|string|null $types
     * @return array<string>|null
     */
    private static function parseTypes(array|string|null $types): ?array
    {
        if ($types === null || $types === '' || $types === []) {
            return null;
        }

        if (is_string($types)) {
            if (str_starts_with($types, '?')) {
                return [substr($types, 1), 'null'];
            }
            return explode('|', $types);
        }

        return $types;
    }

    /**
     * @param array<int, string> $types
     * @return array{0: array<int, string>, 1: bool}
     */
    private static function separateNull(array $types): array
    {
        $hasNull   = false;
        $filtered  = [];
        foreach ($types as $t) {
            if ($t === 'null') {
                $hasNull = true;
                continue;
            }
            $filtered[] = $t;
        }

        return [$filtered, $hasNull];
    }

    /**
     * Validate and return an exclusive type such as void, never or static.
     *
     * @param array<int, string> $types
     */
    private static function validateExclusiveType(
        string $type,
        string $versionConstraint,
        string $phpVersion,
        string $kind,
        array $types,
        bool $hasNull
    ): ?string {
        if ($kind !== self::TYPE_KIND_RETURN) {
            throw new \InvalidArgumentException("Cannot use '{$type}' for anything but return type");
        }
        if (!Semver::satisfies($phpVersion, $versionConstraint)) {
            return null;
        }
        if (count($types) > 1 || $hasNull) {
            throw new \InvalidArgumentException("Cannot mix '{$type}' with other types");
        }
        return $type;
    }
}
