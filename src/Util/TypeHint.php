<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use Composer\Semver\Semver;

class TypeHint
{
    public const TYPE_KIND_RETURN = 'return';
    public const TYPE_KIND_ARG = 'arg';
    public const TYPE_KIND_PROP = 'class-prop';
    public const TYPE_KIND_CONST = 'class-const';

    public const SCALARS = ['int', 'float', 'string', 'bool'];

    /**
     * Takes a desired type-hint of the given kind (return, argument, property)
     * and returns type-hint POSSIBLE to use in the given PHP version.
     * If target PHP version doesn't support this exact type hint at this place
     * but support loosier, it may be returned relaxed.
     *
     * @param array|string|null $types
     * @param 'arg'|'return'|'class-prop'|'class-const' $kind
     */
    public static function typeHintForPhpVer(array|string|null $types, string $phpVer, string $kind): ?string
    {
        $types = self::parseTypes($types);
        if ($types === []) {
            return null;
        }

        if (!self::kindSupportsTypeHints($phpVer, $kind)) {
            return null;
        }

        $types = self::normalizeLiteralTypes($types, $phpVer);
        $types = self::deduplicate($types);

        $types = self::validateSpecialTypes($types, $phpVer, $kind);
        if ($types === []) {
            return null;
        }

        if (!self::areTypeFeaturesSupported($types, $phpVer)) {
            return null;
        }

        return self::renderType($types, $phpVer);
    }

    private static function parseTypes(array|string|null $types): array
    {
        if ($types === null || $types === '' || $types === []) {
            return [];
        }

        if (is_string($types)) {
            if (str_starts_with($types, '?')) {
                $types = substr($types, 1) . '|null';
            }
            $types = explode('|', $types);
        }

        return array_values(array_filter($types, static fn($t) => $t !== ''));
    }

    private static function kindSupportsTypeHints(string $phpVer, string $kind): bool
    {
        return match ($kind) {
            self::TYPE_KIND_RETURN => Semver::satisfies($phpVer, '>=7.0'),
            self::TYPE_KIND_PROP => Semver::satisfies($phpVer, '>=7.4'),
            self::TYPE_KIND_CONST => Semver::satisfies($phpVer, '>=8.3'),
            default => true,
        };
    }

    /**
     * Replace literal types that are not supported on given PHP version with
     * their closest possible counterparts.
     */
    private static function normalizeLiteralTypes(array $types, string $phpVer): array
    {
        $count = count($types);
        foreach ($types as &$type) {
            if ($type === 'true') {
                if (!Semver::satisfies($phpVer, '>=8.2')) {
                    $type = 'bool';
                }
            }
            if ($type === 'false') {
                if (!Semver::satisfies($phpVer, '>=8.0')) {
                    $type = 'bool';
                } elseif (!Semver::satisfies($phpVer, '>=8.2') && $count === 1) {
                    // false is only allowed standalone since 8.2
                    $type = 'bool';
                }
            }
        }
        unset($type);
        return $types;
    }

    private static function deduplicate(array $types): array
    {
        return array_values(array_unique($types));
    }

    private static function validateSpecialTypes(array $types, string $phpVer, string $kind): array
    {
        $hasNull = in_array('null', $types, true);
        $withoutNull = array_values(array_filter($types, static fn($t) => $t !== 'null'));

        if (in_array('void', $types, true)) {
            if ($kind !== self::TYPE_KIND_RETURN) {
                throw new \InvalidArgumentException("Cannot use 'void' for anything but return type");
            }
            if (!Semver::satisfies($phpVer, '>=7.1')) {
                return [];
            }
            if (count($withoutNull) > 1) {
                throw new \InvalidArgumentException("Cannot mix 'void' with other types");
            }
            return ['void'];
        }

        if (in_array('never', $types, true)) {
            if ($kind !== self::TYPE_KIND_RETURN) {
                throw new \InvalidArgumentException("Cannot use 'never' for anything but return type");
            }
            if (!Semver::satisfies($phpVer, '>=8.1')) {
                return [];
            }
            if (count($withoutNull) > 1) {
                throw new \InvalidArgumentException("Cannot mix 'never' with other types");
            }
            return ['never'];
        }

        if (in_array('static', $types, true)) {
            if ($kind !== self::TYPE_KIND_RETURN) {
                throw new \InvalidArgumentException("Cannot use 'static' for anything but return type");
            }
            if (Semver::satisfies($phpVer, '<8.0')) {
                return [];
            }
        }

        if (in_array('mixed', $types, true)) {
            if (count($withoutNull) > 1) {
                throw new \InvalidArgumentException("Mixing 'mixed' with other types");
            }
            if (!Semver::satisfies($phpVer, '>=8.0')) {
                return [];
            }
            return ['mixed'];
        }

        // types introduced in specific versions
        foreach ($withoutNull as $type) {
            if ($type === 'iterable' && !Semver::satisfies($phpVer, '>=7.1')) {
                return [];
            }
            if ($type === 'object' && !Semver::satisfies($phpVer, '>=7.2')) {
                return [];
            }
        }

        return $types;
    }

    private static function areTypeFeaturesSupported(array $types, string $phpVer): bool
    {
        $hasNull = in_array('null', $types, true);
        $withoutNull = array_values(array_filter($types, static fn($t) => $t !== 'null'));
        $hasIntersection = false;
        foreach ($withoutNull as $t) {
            if (str_contains($t, '&')) {
                $hasIntersection = true;
                break;
            }
        }
        $hasUnion = count($withoutNull) > 1;

        if ($hasIntersection && $hasUnion) {
            return Semver::satisfies($phpVer, '>=8.2');
        }
        if ($hasIntersection) {
            return Semver::satisfies($phpVer, '>=8.1');
        }
        if ($hasUnion) {
            return Semver::satisfies($phpVer, '>=8.0');
        }
        if ($hasNull && count($withoutNull) === 1 && !Semver::satisfies($phpVer, '>=7.1')) {
            return false;
        }
        return true;
    }

    private static function renderType(array $types, string $phpVer): ?string
    {
        $hasNull = in_array('null', $types, true);
        $withoutNull = array_values(array_filter($types, static fn($t) => $t !== 'null'));

        if (count($withoutNull) === 0) {
            return $hasNull && Semver::satisfies($phpVer, '>=8.2') ? 'null' : null;
        }

        if (count($withoutNull) === 1) {
            $type = $withoutNull[0];
            if ($hasNull) {
                if (self::canUseShortNullable($type) && Semver::satisfies($phpVer, '>=7.1')) {
                    return '?' . $type;
                }
                if (Semver::satisfies($phpVer, '>=8.0')) {
                    return $type . '|null';
                }
                return null;
            }
            return $type;
        }

        $withoutNull = self::sortTypes($withoutNull);
        if ($hasNull) {
            $withoutNull[] = 'null';
        }

        return implode('|', $withoutNull);
    }

    private static function canUseShortNullable(string $type): bool
    {
        if (str_contains($type, '&') || str_contains($type, '|')) {
            return false;
        }
        return !in_array($type, ['mixed', 'void', 'never', 'false', 'true', 'null', 'static'], true);
    }

    private static function sortTypes(array $types): array
    {
        usort($types, static function ($a, $b) {
            $aIsScalar = in_array($a, self::SCALARS, true);
            $bIsScalar = in_array($b, self::SCALARS, true);
            if ($aIsScalar !== $bIsScalar) {
                return $aIsScalar ? -1 : 1;
            }
            return $a <=> $b;
        });
        return $types;
    }
}
