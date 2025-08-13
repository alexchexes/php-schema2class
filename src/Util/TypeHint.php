<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use InvalidArgumentException;

class TypeHint
{
    public const KIND_RETURN = 'return';
    public const KIND_ARG = 'arg';
    public const KIND_PROP = 'prop';

    public const LEGACY_NULLABLE_OMIT_TYPE = 'omit-type';
    public const LEGACY_NULLABLE_DROP_NULL = 'drop-null';

    /** @var string[] */
    public const SCALARS = ['int', 'float', 'string', 'bool'];

    /** Built in types that are recognized case-insensitively */
    private const BUILTIN_TYPES = [
        'static', 'self', 'parent',
        'callable', 'iterable', 'object', 'array',
        'string', 'float', 'int', 'bool',
        'true', 'false',
        'null',
        'mixed', 'void', 'never',
    ];

    /** Order for union types */
    private const SORT_ORDER = [
        'static' => 0,
        'self' => 1,
        'parent' => 2,
        // 3 is reserved for class-like types
        'callable' => 4,
        'iterable' => 5,
        'object' => 6,
        'array' => 7,
        'string' => 8,
        'float' => 9,
        'int' => 10,
        'bool' => 11,
        'true' => 12,
        'false' => 13,
        'null' => 14,
    ];

    /** Minimal PHP versions for built in types */
    private const MIN_VERSION = [
        'int' => ['arg' => '7.0', 'return' => '7.0', 'prop' => '7.4'],
        'float' => ['arg' => '7.0', 'return' => '7.0', 'prop' => '7.4'],
        'string' => ['arg' => '7.0', 'return' => '7.0', 'prop' => '7.4'],
        'bool' => ['arg' => '7.0', 'return' => '7.0', 'prop' => '7.4'],
        'true' => ['arg' => '8.2', 'return' => '8.2', 'prop' => '8.2'],
        'false' => ['arg' => '8.2', 'return' => '8.2', 'prop' => '8.2'],
        'array' => ['arg' => '5.0', 'return' => '7.0', 'prop' => '7.4'],
        'callable' => ['arg' => '5.6', 'return' => '7.0', 'prop' => '7.4'],
        'iterable' => ['arg' => '7.2', 'return' => '7.2', 'prop' => '7.4'],
        'object' => ['arg' => '7.2', 'return' => '7.2', 'prop' => '7.4'],
        'mixed' => ['arg' => '8.0', 'return' => '8.0', 'prop' => '8.0'],
        'void' => ['return' => '7.4'],
        'never' => ['return' => '8.1'],
        'null' => ['arg' => '8.2', 'return' => '8.2', 'prop' => '8.2'],
    ];

    /**
     * Normalize provided type declaration for the given PHP version and kind.
     *
     * @param array|string $input
     * @param string $phpVersion
     * @param string $kind One of KIND_*
     * @param ?string $legacyFlag LEGACY_NULLABLE_* constants
     */
    public static function forPhpVer(array|string $input, string $phpVersion, string $kind, ?string $legacyFlag = null): ?string
    {
        if (version_compare($phpVersion, '5.6', '<')) {
            throw new InvalidArgumentException('Unsupported PHP version');
        }

        $types = self::parseInput($input);
        $types = self::normalizeTypes($types);

        if ($kind === self::KIND_PROP && version_compare($phpVersion, '7.4', '<')) {
            // typed properties are not supported, but validate input first
            return null;
        }

        $types = self::removeRedundantTypes($types);

        // map true/false to bool for older PHP versions
        if (version_compare($phpVersion, '8.2', '<')) {
            foreach ($types as &$t) {
                $lt = strtolower($t);
                if ($lt === 'true' || $lt === 'false') {
                    $t = 'bool';
                }
            }
            unset($t);
            $types = self::normalizeTypes($types);
            $types = self::removeRedundantTypes($types);
        }

        // handle special cases first
        $lower = array_map('strtolower', $types);
        if (in_array('never', $lower, true)) {
            if (count($types) > 1) {
                throw new InvalidArgumentException('never cannot be combined');
            }
            if ($kind !== self::KIND_RETURN) {
                throw new InvalidArgumentException('never can be used only as return type');
            }
            if (version_compare($phpVersion, '8.1', '>=')) {
                return 'never';
            }
            if (version_compare($phpVersion, '7.4', '>=')) {
                return 'void';
            }
            return null;
        }
        if (in_array('void', $lower, true)) {
            if (count($types) > 1) {
                throw new InvalidArgumentException('void cannot be combined');
            }
            if ($kind !== self::KIND_RETURN) {
                throw new InvalidArgumentException('void can be used only as return type');
            }
            if (version_compare($phpVersion, '7.4', '>=')) {
                return 'void';
            }
            return null;
        }
        if (in_array('mixed', $lower, true)) {
            if (!self::isSupported('mixed', $phpVersion, $kind)) {
                return null;
            }
            return 'mixed';
        }

        // check each type for support
        $types = array_values(array_filter($types, function (string $t) use ($phpVersion, $kind): bool {
            $lt = strtolower($t);
            if ($lt === 'null') {
                return true; // handled later
            }
            if (self::isBuiltin($lt)) {
                return self::isSupported($lt, $phpVersion, $kind);
            }
            // class-like names
            if ($kind === self::KIND_RETURN && version_compare($phpVersion, '7.0', '<')) {
                return false;
            }
            // KIND_ARG has no additional restrictions
            return true;
        }));

        if ($types === []) {
            return null;
        }

        $lower = array_map('strtolower', $types);
        $hasNull = in_array('null', $lower, true);
        $nonNull = array_values(array_filter($types, fn($t) => strtolower($t) !== 'null'));

        if (count($nonNull) > 1 && version_compare($phpVersion, '8.0', '<')) {
            $allClassLike = true;
            foreach ($nonNull as $t) {
                if (self::isBuiltin(strtolower($t))) {
                    $allClassLike = false;
                    break;
                }
            }
            if ($allClassLike && version_compare($phpVersion, '7.2', '>=')) {
                $nonNull = ['object'];
                $types = $hasNull ? ['object', 'null'] : ['object'];
                $lower = array_map('strtolower', $types);
                $hasNull = in_array('null', $lower, true);
            } else {
                return null;
            }
        }

        if ($nonNull === []) {
            // only null
            return version_compare($phpVersion, '8.2', '>=') ? 'null' : null;
        }

        if (count($nonNull) === 1) {
            $type = $nonNull[0];
            if (!$hasNull) {
                return $type;
            }

            // nullable
            if (version_compare($phpVersion, '7.1', '>=')) {
                return '?' . $type;
            }

            // legacy handling
            if ($legacyFlag === self::LEGACY_NULLABLE_OMIT_TYPE) {
                return null;
            }
            if ($legacyFlag === self::LEGACY_NULLABLE_DROP_NULL) {
                return $type;
            }
            throw new InvalidArgumentException('Nullable types are not supported');
        }

        // Union types (>1 non-null)
        if (version_compare($phpVersion, '8.0', '<')) {
            return null;
        }

        // ensure all non-null types supported
        foreach ($nonNull as $t) {
            $lt = strtolower($t);
            if (self::isBuiltin($lt)) {
                if (!self::isSupported($lt, $phpVersion, $kind)) {
                    return null;
                }
            } else {
                if ($kind === self::KIND_RETURN && version_compare($phpVersion, '7.0', '<')) {
                    return null;
                }
            }
        }

        // sort union types
        $sorted = self::sortTypes($types);
        return implode('|', $sorted);
    }

    /** @param array|string $input */
    private static function parseInput(array|string $input): array
    {
        if (is_string($input)) {
            $input = [$input];
        }
        $types = [];
        foreach ($input as $piece) {
            if (!is_string($piece)) {
                throw new InvalidArgumentException('Invalid type declaration');
            }
            $parts = explode('|', $piece);
            foreach ($parts as $p) {
                if ($p === '') {
                    throw new InvalidArgumentException('Invalid type declaration');
                }
                if (preg_match('/\s/', $p)) {
                    throw new InvalidArgumentException('Whitespace not allowed');
                }
                if ($p[0] === '?') {
                    $p = substr($p, 1);
                    if ($p === '' || preg_match('/\s/', $p)) {
                        throw new InvalidArgumentException('Invalid nullable type');
                    }
                    $types[] = 'null';
                }
                if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $p)) {
                    throw new InvalidArgumentException('Invalid type');
                }
                $types[] = $p;
            }
        }
        return $types;
    }

    /** @param array<string> $types */
    private static function normalizeTypes(array $types): array
    {
        $result = [];
        $lowerMap = [];
        foreach ($types as $t) {
            $lower = strtolower($t);
            $canonical = self::isBuiltin($lower) ? $lower : $t;
            if (isset($lowerMap[$lower])) {
                if (!self::isBuiltin($lower) && $lowerMap[$lower] !== $t) {
                    throw new InvalidArgumentException('Duplicate types with different case');
                }
                continue;
            }
            $lowerMap[$lower] = $canonical;
            $result[] = $canonical;
        }
        return $result;
    }

    /** @param array<string> $types */
    private static function removeRedundantTypes(array $types): array
    {
        $lower = array_map('strtolower', $types);

        if (in_array('mixed', $lower, true)) {
            return ['mixed'];
        }

        if (in_array('object', $lower, true)) {
            $types = array_values(array_filter($types, function (string $t): bool {
                $lt = strtolower($t);
                if ($lt === 'object') {
                    return true;
                }
                return self::isBuiltin($lt);
            }));
            $lower = array_map('strtolower', $types);
        }

        if (in_array('iterable', $lower, true)) {
            $types = array_values(array_filter($types, function (string $t): bool {
                $lt = strtolower($t);
                if ($lt === 'array' || $lt === 'traversable') {
                    return false;
                }
                return true;
            }));
            $lower = array_map('strtolower', $types);
        }

        if (in_array('bool', $lower, true)) {
            $types = array_values(array_filter($types, function (string $t): bool {
                $lt = strtolower($t);
                return $lt !== 'true' && $lt !== 'false';
            }));
            $lower = array_map('strtolower', $types);
        } elseif (in_array('true', $lower, true) && in_array('false', $lower, true)) {
            $types = array_values(array_filter($types, function (string $t): bool {
                $lt = strtolower($t);
                return $lt !== 'true' && $lt !== 'false';
            }));
            $types[] = 'bool';
            $types = self::normalizeTypes($types);
            $lower = array_map('strtolower', $types);
        }

        return $types;
    }

    private static function isBuiltin(string $lower): bool
    {
        return in_array($lower, self::BUILTIN_TYPES, true);
    }

    private static function isSupported(string $type, string $phpVersion, string $kind): bool
    {
        $min = self::MIN_VERSION[$type][$kind] ?? null;
        if ($min === null) {
            return true;
        }
        return version_compare($phpVersion, $min, '>=');
    }

    /** @param array<string> $types */
    private static function sortTypes(array $types): array
    {
        usort($types, function (string $a, string $b): int {
            $la = strtolower($a);
            $lb = strtolower($b);
            $ia = self::SORT_ORDER[$la] ?? 3; // class-like default
            $ib = self::SORT_ORDER[$lb] ?? 3;
            if ($ia === $ib) {
                $cmp = strcasecmp($a, $b);
                if ($cmp === 0) {
                    return strcmp($a, $b);
                }
                return $cmp;
            }
            return $ia <=> $ib;
        });
        return $types;
    }
}
