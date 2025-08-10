<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use Composer\Semver\Semver;

/**
 * Utility helpers for reducing type hints to a version-specific subset.
 */
class TypeHint
{
    public const TYPE_KIND_RETURN = 'return';
    public const TYPE_KIND_ARG = 'arg';
    public const TYPE_KIND_PROP = 'class-prop';

    public const SCALARS = ['int', 'float', 'string', 'bool'];

    /**
     * Takes a desired type-hint of the given kind and returns a type-hint that
     * can be used on the provided PHP version. If the version does not support
     * the requested type, a looser type (or null) is returned instead.
     *
     * @param array|string|null $types One or more requested types. Strings can
     *        be given in union notation ("A|B") or nullable form ("?A").
     * @param string $phpVer Target PHP version (e.g. "8.2.0").
     * @param self::TYPE_KIND_* $kind Where the type hint will be used.
     */
    public static function forPhpVersion(array|string|null $types, string $phpVer, string $kind): ?string
    {
        $types = self::normalize($types);
        if ($types === null) {
            return null;
        }

        if (!self::supportsTypeHints($kind, $phpVer)) {
            return null;
        }

        $types = self::expandLiteralTypes($types, $phpVer);

        if (in_array('void', $types, true)) {
            return self::handleVoid($types, $phpVer, $kind);
        }
        if (in_array('never', $types, true)) {
            return self::handleNever($types, $phpVer, $kind);
        }
        if (in_array('mixed', $types, true)) {
            return self::handleMixed($types, $phpVer);
        }

        $types = self::filterUnsupportedSimpleTypes($types, $phpVer, $kind);
        $types = array_values(array_unique($types));

        $hasNull = in_array('null', $types, true);
        $withoutNull = array_values(array_filter($types, fn ($t) => $t !== 'null'));
        if (count($withoutNull) === 0) {
            return null; // 'null' alone cannot be hinted
        }

        if (count($withoutNull) > 1) {
            if (!Semver::satisfies($phpVer, '>=8.0')) {
                return null;
            }

            sort($withoutNull, SORT_STRING);
            if ($hasNull) {
                $withoutNull[] = 'null';
            }

            return implode('|', $withoutNull);
        }

        $type = $withoutNull[0];

        if ($hasNull) {
            if (!Semver::satisfies($phpVer, '>=7.1')) {
                return null;
            }
            return '?' . $type;
        }

        return $type;
    }

    /**
     * @param array|string|null $types
     * @return array<int,string>|null
     */
    private static function normalize(array|string|null $types): ?array
    {
        if ($types === null || $types === '' || $types === []) {
            return null;
        }

        if (is_string($types)) {
            if (str_starts_with($types, '?')) {
                $types = [substr($types, 1), 'null'];
            } else {
                $types = explode('|', $types);
            }
        }

        $types = array_map('trim', $types);
        return array_values(array_filter($types, fn ($t) => $t !== ''));
    }

    private static function supportsTypeHints(string $kind, string $phpVer): bool
    {
        if ($kind === self::TYPE_KIND_RETURN) {
            return Semver::satisfies($phpVer, '>=7.0');
        }
        if ($kind === self::TYPE_KIND_PROP) {
            return Semver::satisfies($phpVer, '>=7.4');
        }
        return true; // arguments are always supported
    }

    /**
     * Replaces literal true/false types with bool on PHP < 8.2.
     *
     * @param array<int,string> $types
     * @return array<int,string>
     */
    private static function expandLiteralTypes(array $types, string $phpVer): array
    {
        if (!Semver::satisfies($phpVer, '>=8.2')) {
            $hasTrue = in_array('true', $types, true);
            $hasFalse = in_array('false', $types, true);
            if ($hasTrue || $hasFalse) {
                $types = array_values(array_diff($types, ['true', 'false']));
                $types[] = 'bool';
            }
        }

        return $types;
    }

    /**
     * @param array<int,string> $types
     */
    private static function handleVoid(array $types, string $phpVer, string $kind): ?string
    {
        if (count(array_filter($types, fn ($t) => $t !== 'null')) > 1) {
            throw new \InvalidArgumentException("Cannot mix 'void' with other types");
        }
        if ($kind !== self::TYPE_KIND_RETURN) {
            throw new \InvalidArgumentException("Cannot use 'void' for anything but return type");
        }
        return Semver::satisfies($phpVer, '>=7.1') ? 'void' : null;
    }

    /**
     * @param array<int,string> $types
     */
    private static function handleNever(array $types, string $phpVer, string $kind): ?string
    {
        if (count(array_filter($types, fn ($t) => $t !== 'null')) > 1) {
            throw new \InvalidArgumentException("Cannot mix 'never' with other types");
        }
        if ($kind !== self::TYPE_KIND_RETURN) {
            throw new \InvalidArgumentException("Cannot use 'never' for anything but return type");
        }
        if (Semver::satisfies($phpVer, '>=8.1')) {
            return 'never';
        }
        return Semver::satisfies($phpVer, '>=7.1') ? 'void' : null;
    }

    /**
     * @param array<int,string> $types
     */
    private static function handleMixed(array $types, string $phpVer): ?string
    {
        $nonNull = array_filter($types, fn ($t) => $t !== 'null');
        if (count($nonNull) > 1) {
            throw new \InvalidArgumentException(
                "Mixing 'mixed' type with other types might be a severe logic mistake"
            );
        }
        if (!Semver::satisfies($phpVer, '>=8.0')) {
            return null;
        }
        return 'mixed';
    }

    /**
     * Filters out types that are not supported at all on the given version.
     *
     * @param array<int,string> $types
     * @return array<int,string>
     */
    private static function filterUnsupportedSimpleTypes(array $types, string $phpVer, string $kind): array
    {
        $result = [];
        foreach ($types as $t) {
            switch ($t) {
                case 'object':
                    if (Semver::satisfies($phpVer, '>=7.2')) {
                        $result[] = $t;
                    }
                    break;
                case 'iterable':
                    if (Semver::satisfies($phpVer, '>=7.1')) {
                        $result[] = $t;
                    }
                    break;
                case 'static':
                    if ($kind === self::TYPE_KIND_RETURN && Semver::satisfies($phpVer, '>=8.0')) {
                        $result[] = $t;
                    }
                    break;
                default:
                    $result[] = $t;
            }
        }
        return $result;
    }
}
