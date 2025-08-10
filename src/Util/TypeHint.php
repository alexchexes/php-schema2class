<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use Composer\Semver\Semver;

/**
 * Utility helpers for adapting type hints to a target PHP version.
 */
class TypeHint
{
    public const TYPE_KIND_RETURN = 'return';
    public const TYPE_KIND_ARG = 'arg';
    public const TYPE_KIND_PROP = 'class-prop';

    /** @var list<string> */
    public const SCALARS = ['int', 'float', 'string', 'bool'];

    /**
     * Takes a desired type hint and returns a type hint that can be used with
     * the given target PHP version. If the exact type hint is unsupported it
     * will be relaxed when possible; otherwise `null` is returned.
     *
     * @param array<string>|string|null $types
     * @param 'arg'|'return'|'class-prop' $kind  What this type will be used for.
     */
    public static function typeHintForPhpVersion(array|string|null $types, string $phpVer, string $kind): ?string
    {
        if ($types === null || $types === '' || $types === []) {
            return null;
        }

        if ($kind === self::TYPE_KIND_RETURN && !Semver::satisfies($phpVer, '>=7.0')) {
            return null;
        }
        if ($kind === self::TYPE_KIND_ARG && !Semver::satisfies($phpVer, '>=7.0')) {
            return null;
        }
        if ($kind === self::TYPE_KIND_PROP && !Semver::satisfies($phpVer, '>=7.4')) {
            return null;
        }

        $types = self::normalizeTypes($types);

        $hasNull   = in_array('null', $types, true);
        $hasMixed  = in_array('mixed', $types, true);
        $hasVoid   = in_array('void', $types, true);
        $hasNever  = in_array('never', $types, true);
        $hasStatic = in_array('static', $types, true);
        $hasTrue   = in_array('true', $types, true);
        $hasFalse  = in_array('false', $types, true);

        $withoutNull = array_values(array_filter($types, fn(string $t): bool => $t !== 'null'));

        // standalone null type
        if (count($withoutNull) === 0) {
            return $hasNull && Semver::satisfies($phpVer, '>=8.2') ? 'null' : null;
        }

        $hasIntersection = false;
        foreach ($withoutNull as $t) {
            if (str_contains($t, '&')) {
                $hasIntersection = true;
                break;
            }
        }
        $hasUnion = count($withoutNull) > 1;

        if ($hasIntersection) {
            if (($hasUnion || $hasNull) && !Semver::satisfies($phpVer, '>=8.2')) {
                return null; // union with intersection or nullable intersection needs PHP 8.2+
            }
            if (!Semver::satisfies($phpVer, '>=8.1')) {
                return null;
            }
        }

        if ($hasUnion && !Semver::satisfies($phpVer, '>=8.0')) {
            return null;
        }

        if ($hasVoid) {
            if (count($withoutNull) > 1) {
                throw new \InvalidArgumentException("Cannot mix 'void' with other types");
            }
            if ($kind !== self::TYPE_KIND_RETURN) {
                throw new \InvalidArgumentException("Cannot use 'void' for anything but return type");
            }
            return Semver::satisfies($phpVer, '>=7.1') ? 'void' : null;
        }

        if ($hasNever) {
            if (count($withoutNull) > 1) {
                throw new \InvalidArgumentException("Cannot mix 'never' with other types");
            }
            if ($kind !== self::TYPE_KIND_RETURN) {
                throw new \InvalidArgumentException("Cannot use 'never' for anything but return type");
            }
            return Semver::satisfies($phpVer, '>=8.1') ? 'never' : null;
        }

        if ($hasStatic) {
            if ($kind !== self::TYPE_KIND_RETURN) {
                throw new \InvalidArgumentException("Cannot use 'static' for anything but return type");
            }
            if (!Semver::satisfies($phpVer, '>=8.0')) {
                return null;
            }
        }

        if ($hasMixed) {
            if (count($withoutNull) > 1) {
                throw new \InvalidArgumentException(
                    "Mixing 'mixed' type with other types other than null might be a severe logic mistake. We would need to omit all types except 'mixed' but this way type information would be lost."
                );
            }
            return Semver::satisfies($phpVer, '>=8.0') ? 'mixed' : null;
        }

        // replace literal true/false types with bool when unsupported
        if ($hasTrue && !Semver::satisfies($phpVer, '>=8.2')) {
            foreach ($withoutNull as &$t) {
                if ($t === 'true') {
                    $t = 'bool';
                }
            }
            unset($t);
        }
        if ($hasFalse && count($withoutNull) === 1 && $withoutNull[0] === 'false' && !Semver::satisfies($phpVer, '>=8.2')) {
            $withoutNull[0] = 'bool';
        }

        if (in_array('iterable', $withoutNull, true) && !Semver::satisfies($phpVer, '>=7.1')) {
            return null;
        }
        if (in_array('object', $withoutNull, true) && !Semver::satisfies($phpVer, '>=7.2')) {
            return null;
        }

        $withoutNull = array_values(array_unique($withoutNull));

        if (count($withoutNull) > 1) {
            sort($withoutNull, SORT_STRING);
            if ($hasNull) {
                if (!Semver::satisfies($phpVer, '>=7.1')) {
                    return null;
                }
                $withoutNull[] = 'null';
            }
            return implode('|', $withoutNull);
        }

        $type = $withoutNull[0];

        if ($hasNull) {
            if ($hasIntersection) {
                return Semver::satisfies($phpVer, '>=8.2') ? $type . '|null' : null;
            }
            if (!Semver::satisfies($phpVer, '>=7.1')) {
                return null;
            }
            return "?{$type}";
        }

        return $type;
    }

    /**
     * Normalize the input type specification to an array of types.
     *
     * @param array<string>|string $types
     * @return list<string>
     */
    private static function normalizeTypes(array|string $types): array
    {
        if (is_array($types)) {
            $types = array_values($types);
        } else {
            if (str_starts_with($types, '?')) {
                $types = [substr($types, 1), 'null'];
            } else {
                $types = explode('|', $types);
            }
        }

        return array_values(array_filter($types, static fn(string $t): bool => $t !== ''));
    }
}
