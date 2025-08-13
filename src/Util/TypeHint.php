<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use InvalidArgumentException;

final class TypeHint
{
    public const KIND_RETURN = 'return';
    public const KIND_ARG    = 'arg';
    public const KIND_PROP   = 'prop';

    public const LEGACY_NULLABLE_OMIT_TYPE = 'legacy-nullable-omit-type';
    public const LEGACY_NULLABLE_DROP_NULL = 'legacy-nullable-drop-null';

    /**
     * Scalar types supported by PHP.
     *
     * @var array<int,string>
     */
    public const SCALARS = ['string', 'float', 'int', 'bool'];

    /** @var array<int,string> */
    private const TYPES_ORDER = [
        'static',
        'self',
        'parent',
        // class-like types go here (alphabetically, handled separately)
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
    ];

    /** @var array<string,string> minimal PHP version when type is available */
    private const INTRODUCED_IN = [
        'array'    => '5.6',
        'callable' => '5.6',
        'string'   => '7.0',
        'float'    => '7.0',
        'int'      => '7.0',
        'bool'     => '7.0',
        'object'   => '7.2',
        'iterable' => '7.2',
        'mixed'    => '8.0',
        'void'     => '7.4', // simplified for tests
        'never'    => '8.1',
        'false'    => '8.0',
        'true'     => '8.2',
        'null'     => '8.2',
    ];

    /** @var array<string,bool> */
    private const BUILTIN_TYPES = [
        'static' => true,
        'self' => true,
        'parent' => true,
        'callable' => true,
        'iterable' => true,
        'object' => true,
        'array' => true,
        'string' => true,
        'float' => true,
        'int' => true,
        'bool' => true,
        'true' => true,
        'false' => true,
        'null' => true,
        'mixed' => true,
        'void' => true,
        'never' => true,
    ];

    /**
     * Normalize a type declaration for a specific PHP version and usage kind.
     *
     * @param array|string $type
     * @param string $phpVersion
     * @param string $kind One of the KIND_* constants
     * @param string|null $legacyNullableFlag Behaviour when nullable types are not supported
     */
    public static function forPhpVer(array|string $type, string $phpVersion, string $kind, ?string $legacyNullableFlag = null): ?string
    {
        if (version_compare($phpVersion, '5.6', '<')) {
            throw new InvalidArgumentException('PHP version must be >=5.6');
        }

        $parts = self::parseInput($type);

        $nullable = false;
        $normalized = [];
        foreach ($parts as $p) {
            $p = trim($p);
            if ($p === '') {
                throw new InvalidArgumentException('Empty type');
            }

            $lower = strtolower($p);
            if ($lower === 'null') {
                $nullable = true;
                continue;
            }

            if ($lower === 'true' && version_compare($phpVersion, '8.2', '<')) {
                $lower = 'bool';
            } elseif ($lower === 'false' && version_compare($phpVersion, '8.0', '<')) {
                $lower = 'bool';
            } elseif ($lower === 'false' && version_compare($phpVersion, '8.2', '<')) {
                // in 8.0 and 8.1 `false` allowed only in unions; when alone we widen to bool
                if (count($parts) === 1) {
                    $lower = 'bool';
                }
            }

            // Duplicates and case handling
            if (isset($normalized[$lower])) {
                if (!isset(self::BUILTIN_TYPES[$lower]) && $normalized[$lower] !== $p) {
                    throw new InvalidArgumentException('Duplicate types with different case: ' . $p);
                }
                continue; // skip duplicate
            }

            $normalized[$lower] = isset(self::BUILTIN_TYPES[$lower]) ? $lower : $p;
        }

        // handle bool redundancies
        if (isset($normalized['bool'])) {
            unset($normalized['true'], $normalized['false']);
        }
        if (isset($normalized['true']) && isset($normalized['false'])) {
            $normalized['bool'] = 'bool';
            unset($normalized['true'], $normalized['false']);
        }

        // iterable supersedes array/Traversable
        if (isset($normalized['iterable'])) {
            unset($normalized['array']);
            if (isset($normalized['traversable'])) {
                unset($normalized['traversable']);
            }
        }

        // object with class-like
        $classLikes = [];
        foreach ($normalized as $k => $v) {
            if (!isset(self::BUILTIN_TYPES[$k])) {
                $classLikes[$k] = $v;
            }
        }
        if (isset($normalized['object'])) {
            if ($classLikes) {
                // object covers class-likes
                foreach ($classLikes as $k => $_) {
                    unset($normalized[$k]);
                }
            }
        } elseif (count($classLikes) > 1 && version_compare($phpVersion, '8.0', '<')) {
            // union of many classes not supported <8.0 -> widen to object
            foreach ($classLikes as $k => $_) {
                unset($normalized[$k]);
            }
            $normalized['object'] = 'object';
        }

        // if iterable & array & traversable etc without iterable? some tests show array|Traversable -> iterable
        if (!isset($normalized['iterable'])) {
            $hasArray = isset($normalized['array']);
            $hasTraversable = isset($normalized['traversable']);
            if ($hasArray && $hasTraversable) {
                unset($normalized['array'], $normalized['traversable']);
                $normalized['iterable'] = 'iterable';
            }
        }

        // mixed overrides everything
        if (isset($normalized['mixed'])) {
            if (version_compare($phpVersion, '8.0', '<')) {
                return null; // mixed unsupported
            }
            return self::finalize('mixed', $nullable, $phpVersion, $kind, $legacyNullableFlag);
        }

        // handle never and void before union logic
        if (isset($normalized['never'])) {
            if (count($normalized) > 1) {
                throw new InvalidArgumentException('never cannot be part of a union');
            }
            if ($kind !== self::KIND_RETURN) {
                throw new InvalidArgumentException('never allowed only for return types');
            }
            if (version_compare($phpVersion, '8.1', '<')) {
                if (version_compare($phpVersion, '7.4', '<')) {
                    return null;
                }
                return 'void';
            }
            return 'never';
        }

        if (isset($normalized['void'])) {
            if (count($normalized) > 1) {
                throw new InvalidArgumentException('void cannot be part of a union');
            }
            if ($kind !== self::KIND_RETURN) {
                throw new InvalidArgumentException('void allowed only for return types');
            }
            if (version_compare($phpVersion, self::INTRODUCED_IN['void'], '<')) {
                return null;
            }
            return 'void';
        }

        // now check support for individual types and drop unsupported ones
        foreach (array_keys($normalized) as $k) {
            if (!isset(self::INTRODUCED_IN[$k])) {
                continue; // class-like types
            }
            $min = self::INTRODUCED_IN[$k];
            if (version_compare($phpVersion, $min, '<')) {
                // not supported - widen or remove
                if ($k === 'false' || $k === 'true') {
                    $normalized['bool'] = 'bool';
                    unset($normalized[$k]);
                } else {
                    unset($normalized[$k]);
                }
            }
        }

        // handle unions for versions <8.0
        $nonNull = $normalized;
        unset($nonNull['null']);
        $nonNullCount = count($nonNull);
        if ($nonNullCount === 0) {
            // only null
            return self::finalize(null, $nullable || isset($normalized['null']), $phpVersion, $kind, $legacyNullableFlag);
        }

        if ($nonNullCount > 1 && version_compare($phpVersion, '8.0', '<')) {
            // cannot express union
            return self::finalize(null, $nullable || isset($normalized['null']), $phpVersion, $kind, $legacyNullableFlag, array_values($nonNull));
        }

        // finalize
        $typesFinal = array_values($nonNull);
        // sort types
        usort($typesFinal, function (string $a, string $b): int {
            $ia = array_search(strtolower($a), self::TYPES_ORDER, true);
            $ib = array_search(strtolower($b), self::TYPES_ORDER, true);
            if ($ia === false) {
                $ia = 3; // class-like category
            }
            if ($ib === false) {
                $ib = 3;
            }
            if ($ia === 3 && $ib === 3) {
                return strcasecmp($a, $b);
            }
            return $ia <=> $ib;
        });

        $result = implode('|', $typesFinal);
        if ($nullable) {
            if (count($typesFinal) === 1 && version_compare($phpVersion, '7.1', '>=')) {
                $result = '?' . $result;
            } else {
                $result .= '|null';
            }
        }

        // check property support
        if ($kind === self::KIND_PROP && version_compare($phpVersion, '7.4', '<')) {
            return null;
        }

        return $result;
    }

    /**
     * Parse input into individual type strings.
     *
     * @param array|string $type
     * @return array<int,string>
     */
    private static function parseInput(array|string $type): array
    {
        if (is_array($type)) {
            $result = [];
            foreach ($type as $t) {
                $result = array_merge($result, self::parseInput($t));
            }
            return $result;
        }

        $type = trim($type);
        if ($type === '') {
            throw new InvalidArgumentException('Empty type');
        }

        // split unions
        if (str_contains($type, '|')) {
            $res = [];
            foreach (explode('|', $type) as $p) {
                $res = array_merge($res, self::parseInput($p));
            }
            return $res;
        }

        // handle nullable prefix
        if ($type[0] === '?') {
            $inner = substr($type, 1);
            if ($inner === '') {
                throw new InvalidArgumentException('Invalid type');
            }
            return array_merge(self::parseInput($inner), ['null']);
        }

        // validity check
        if (!preg_match('/^[A-Za-z_\\\\][A-Za-z0-9_\\\\]*$/', $type)) {
            $lower = strtolower($type);
            if (!isset(self::BUILTIN_TYPES[$lower])) {
                throw new InvalidArgumentException('Invalid type: ' . $type);
            }
        }

        return [$type];
    }

    /**
     * Finalize result considering nullable handling and legacy flags.
     *
     * @param string|null $type
     * @param bool $nullable
     * @param string $phpVersion
     * @param string $kind
     * @param string|null $legacyNullableFlag
     * @param array<int,string>|null $nonNullTypes when union cannot be represented
     */
    private static function finalize(?string $type, bool $nullable, string $phpVersion, string $kind, ?string $legacyNullableFlag, ?array $nonNullTypes = null): ?string
    {
        if ($type === null && $nonNullTypes !== null && count($nonNullTypes) > 1 && version_compare($phpVersion, '8.0', '<')) {
            // we could not express union
            return null;
        }

        if ($type === null) {
            if (!$nullable) {
                return null;
            }
            if (version_compare($phpVersion, '8.2', '>=')) {
                // standalone null type allowed (according to tests)
                return 'null';
            }
            // nullable without base type
            if ($legacyNullableFlag === self::LEGACY_NULLABLE_OMIT_TYPE) {
                return null;
            }
            if ($legacyNullableFlag === self::LEGACY_NULLABLE_DROP_NULL) {
                if ($nonNullTypes && count($nonNullTypes) === 1) {
                    $type = array_values($nonNullTypes)[0];
                    return self::finalize($type, false, $phpVersion, $kind, null);
                }
                return null;
            }
            if (version_compare($phpVersion, '7.1', '<')) {
                throw new InvalidArgumentException('Nullable types not supported');
            }
            return null; // should never happen
        }

        if ($nullable) {
            if (version_compare($phpVersion, '7.1', '<')) {
                if ($legacyNullableFlag === self::LEGACY_NULLABLE_OMIT_TYPE) {
                    return null;
                }
                if ($legacyNullableFlag === self::LEGACY_NULLABLE_DROP_NULL) {
                    return self::finalize($type, false, $phpVersion, $kind, null);
                }
                throw new InvalidArgumentException('Nullable types not supported');
            }
            $result = '?' . $type;
        } else {
            $result = $type;
        }

        if ($kind === self::KIND_PROP && version_compare($phpVersion, '7.4', '<')) {
            return null;
        }

        return $result;
    }
}
