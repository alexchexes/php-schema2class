<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use Composer\Semver\Semver;
use InvalidArgumentException;

class TypeHint
{
    public const KIND_RETURN = 'return-type';
    public const KIND_ARG = 'argument-type';
    public const KIND_PROP = 'property-type';

    public const ALL_KINDS = [
        self::KIND_RETURN,
        self::KIND_ARG,
        self::KIND_PROP,
        // we don't handle types for constants yet
    ];

    public const LEGACY_NULLABLE_OMIT_TYPE = 'legacy-nullable-omit-type';
    public const LEGACY_NULLABLE_DROP_NULL = 'legacy-nullable-drop-null';

    public const LEGACY_FLAGS = [
        self::LEGACY_NULLABLE_OMIT_TYPE,
        self::LEGACY_NULLABLE_DROP_NULL,
    ];

    public const SCALARS = ['int', 'float', 'string', 'bool'];

    /** Built in types that are recognized case-insensitively */
    private const BUILTIN_TYPES = [
        'static',
        'self',
        'parent',

        'callable',
        'iterable',
        'object',
        'array',

        'string',
        'float',
        'int',
        'bool',

        'true',
        'false',

        'null',

        'mixed',
        'void',
        'never',
    ];

    /** Order for union types */
    private const SORT_ORDER = [
        'static'    => 0,
        'self'      => 1,
        'parent'    => 2,
        // 3 is reserved for class-like types
        'callable'  => 4,
        'iterable'  => 5,
        'object'    => 6,
        'array'     => 7,
        'string'    => 8,
        'float'     => 9,
        'int'       => 10,
        'bool'      => 11,
        'true'      => 12,
        'false'     => 13,
        'null'      => 14,
    ];

    /** Minimal PHP versions for built in types used STANDALONE (`false` has special handling) */
    private const MIN_VERSION = [
        'int'       => [self::KIND_ARG => '7.0', self::KIND_RETURN => '7.0', self::KIND_PROP => '7.4'],
        'float'     => [self::KIND_ARG => '7.0', self::KIND_RETURN => '7.0', self::KIND_PROP => '7.4'],
        'string'    => [self::KIND_ARG => '7.0', self::KIND_RETURN => '7.0', self::KIND_PROP => '7.4'],
        'bool'      => [self::KIND_ARG => '7.0', self::KIND_RETURN => '7.0', self::KIND_PROP => '7.4'],
        'true'      => [self::KIND_ARG => '8.2', self::KIND_RETURN => '8.2', self::KIND_PROP => '8.2'],
        'false'     => [self::KIND_ARG => '8.2', self::KIND_RETURN => '8.2', self::KIND_PROP => '8.2'],
        'array'     => [self::KIND_ARG => '5.0', self::KIND_RETURN => '7.0', self::KIND_PROP => '7.4'],
        'callable'  => [self::KIND_ARG => '5.6', self::KIND_RETURN => '7.0', self::KIND_PROP => '7.4'],
        'iterable'  => [self::KIND_ARG => '7.2', self::KIND_RETURN => '7.2', self::KIND_PROP => '7.4'],
        'object'    => [self::KIND_ARG => '7.2', self::KIND_RETURN => '7.2', self::KIND_PROP => '7.4'],
        'mixed'     => [self::KIND_ARG => '8.0', self::KIND_RETURN => '8.0', self::KIND_PROP => '8.0'],
        'void'      => [self::KIND_RETURN => '7.4'],
        'never'     => [self::KIND_RETURN => '8.1'],
        'null'      => [self::KIND_ARG => '8.2', self::KIND_RETURN => '8.2', self::KIND_PROP => '8.2'],
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
        if (!in_array($kind, self::ALL_KINDS)) {
            throw new InvalidArgumentException("Unsupported kind: {$kind}");
        }
        if ($legacyFlag !== null && !in_array($legacyFlag, self::LEGACY_FLAGS)) {
            throw new InvalidArgumentException("Unknown legacy flag: {$legacyFlag}");
        }
        if (Semver::satisfies($phpVersion, '<5.6')) {
            throw new InvalidArgumentException("Unsupported PHP version: {$phpVersion}");
        }

        $types = self::parseInput($input);

        $types = self::canonicalizeCaseAndDeduplicate($types);
        $types = self::removeRedundantTypes($types);

        // map true/false to bool for older PHP versions
        $types = self::mapLiteralBools($types, $phpVersion);

        if ($types === []) {
            return null;
        }

        if (in_array('never', $types, true)) {
            if (count($types) > 1) {
                throw new InvalidArgumentException("'never' cannot be combined");
            }
            if ($kind !== self::KIND_RETURN) {
                throw new InvalidArgumentException("'never' can be used only as return type. Attempt to use as: {$kind} type");
            }
            if (Semver::satisfies($phpVersion, '>=8.1')) {
                return 'never';
            }
            if (Semver::satisfies($phpVersion, '>=7.4')) {
                return 'void';
            }
            return null;
        }
        if (in_array('void', $types, true)) {
            if (count($types) > 1) {
                throw new InvalidArgumentException("'void' cannot be combined");
            }
            if ($kind !== self::KIND_RETURN) {
                throw new InvalidArgumentException("'void' can be used only as return type. Attempt to use as: {$kind} type");
            }
            if (Semver::satisfies($phpVersion, '>=7.4')) {
                return 'void';
            }
            return null;
        }
        if (in_array('mixed', $types, true)) {
            if (!self::isSupported('mixed', $types, $phpVersion, $kind)) {
                return null;
            }
            return 'mixed';
        }
        
        // after basic input validation, return null if the PHP ver doesn't support types for the given kind
        if ($kind === self::KIND_PROP && Semver::satisfies($phpVersion, '<7.4')) {
            return null;
        }
        if ($kind === self::KIND_RETURN && Semver::satisfies($phpVersion, '<7.0')) {
            return null;
        }

        $hasNull = in_array('null', $types, true);
        $nonNull = self::removeNulls($types);

        if (count($nonNull) > 1 && Semver::satisfies($phpVersion, '<8.0')) {
            $allClassLike = true;
            foreach ($nonNull as $t) {
                if (self::isBuiltin(strtolower($t))) {
                    $allClassLike = false;
                    break;
                }
            }
            if ($allClassLike && Semver::satisfies($phpVersion, '>=7.2')) {
                $nonNull = ['object'];
                $types = $hasNull ? ['object', 'null'] : ['object'];
            } else {
                return null;
            }
        }

        if ($hasNull && $nonNull === []) {
            // standalone null
            return Semver::satisfies($phpVersion, '>=8.2') ? 'null' : null;
        }

        if (count($nonNull) === 1) {
            $type = $nonNull[0];

            if (!self::isSupported($type, $types, $phpVersion, $kind)) {
                return null;
            }

            if (!$hasNull) {
                return $type;
            }

            // nullable
            if (Semver::satisfies($phpVersion, '>=7.1')) {
                return '?' . $type;
            }

            // legacy handling
            if ($legacyFlag === self::LEGACY_NULLABLE_OMIT_TYPE) {
                return null;
            }
            if ($legacyFlag === self::LEGACY_NULLABLE_DROP_NULL) {
                return $type;
            }
            throw new InvalidArgumentException("PHP {$phpVersion} doesn't support nullable type hints, and \$legacyFlag is not provided.");
        }

        // ensure all non-null types supported
        foreach ($nonNull as $t) {
            $lower = strtolower($t);
            if (self::isBuiltin($lower)) {
                if (!self::isSupported($lower, $types, $phpVersion, $kind)) {
                    return null;
                }
            } else {
                if ($kind === self::KIND_RETURN && Semver::satisfies($phpVersion, '<7.0')) {
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
                throw new InvalidArgumentException('Invalid type of type to parse. Variable gettype: ' . gettype($piece));
            }
            $parts = explode('|', $piece);
            foreach ($parts as $p) {
                if ($p === '') {
                    throw new InvalidArgumentException('Type resolved to an empty string');
                }
                if (preg_match('/\s/', $p)) {
                    throw new InvalidArgumentException("Type contains whitespace: {$p}");
                }
                if ($p[0] === '?') {
                    $saved = $p;
                    $p = substr($p, 1);
                    if ($p === '' || preg_match('/\s/', $p)) {
                        throw new InvalidArgumentException("Invalid nullable type: {$saved}");
                    }
                    $types[] = 'null';
                }
                if (!preg_match('/^(?:\\\)?[A-Za-z_][A-Za-z0-9_]*(?:\\\[A-Za-z_][A-Za-z0-9_]*)*$/', $p)) {
                    throw new InvalidArgumentException("Illegal charachters found in type: {$p}");
                }
                $types[] = $p;
            }
        }
        return $types;
    }

    /** @param array<string> $types */
    private static function canonicalizeCaseAndDeduplicate(array $types): array
    {
        $result = [];
        $lowerMap = [];

        foreach ($types as $t) {
            $lower = strtolower($t);
            $canonical = self::isBuiltin($lower) ? $lower : $t;

            if (isset($lowerMap[$lower])) {
                if (!self::isBuiltin($lower) && $lowerMap[$lower] !== $t) {
                    throw new InvalidArgumentException("Duplicate user-defined type with different case: {$t}");
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
            $types = self::canonicalizeCaseAndDeduplicate($types);
            $lower = array_map('strtolower', $types);
        }

        return $types;
    }

    /** 
     * Expects already normalized array of types, when 'null' is lowercase
     */
    private static function removeNulls(array $types): array
    {
        return array_values(array_filter($types, fn($t) => $t !== 'null'));
    }

    private static function mapLiteralBools(array $types, string $phpVersion): array
    {
        $nonNull = self::removeNulls($types);

        if (Semver::satisfies($phpVersion, '<8.2')) {
            foreach ($types as &$t) {
                $lt = strtolower($t);
                if ($lt === 'true' || ($lt === 'false' && count($nonNull) === 1)) {
                    $t = 'bool';
                }
            }
            $types = self::canonicalizeCaseAndDeduplicate($types);
            $types = self::removeRedundantTypes($types);
        }
        return $types;
    }

    private static function isBuiltin(string $lower): bool
    {
        return in_array($lower, self::BUILTIN_TYPES, true);
    }

    private static function isSupported(string $type, array $types, string $phpVersion, string $kind): bool
    {
        $nonNull = self::removeNulls($types);

        if ($type === 'false' && count($nonNull) > 1 && Semver::satisfies($phpVersion, '>=8.0')) {
            // false in unions supported since 8.0
            return true;
        }
        
        $minVer = self::MIN_VERSION[$type][$kind] ?? null;
        if ($minVer === null) {
            return true;
        }
        return Semver::satisfies($phpVersion, ">={$minVer}");
    }

    /** @param array<string> $types */
    private static function sortTypes(array $types): array
    {
        usort($types, function (string $a, string $b): int {
            $lowerA = strtolower($a);
            $lowerB = strtolower($b);
            $indexA = self::SORT_ORDER[$lowerA] ?? 3; // class-like default
            $indexB = self::SORT_ORDER[$lowerB] ?? 3;
            if ($indexA === $indexB) {
                $cmp = strcasecmp($a, $b);
                if ($cmp === 0) {
                    return strcmp($a, $b);
                }
                return $cmp;
            }
            return $indexA <=> $indexB;
        });
        return $types;
    }
}
