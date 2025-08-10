<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use Composer\Semver\Semver;

class TypeHint
{
    public const TYPE_KIND_RETURN = 'return';
    public const TYPE_KIND_ARG = 'arg';
    public const TYPE_KIND_PROP = 'class-prop';

    public const SCALARS = ['int', 'float', 'string', 'bool'];

    /** 
     * Takes a desired type-hint of the given kind (return, argument, property)
     * and returns type-hint POSSIBLE to use in the given PHP version.
     * If target PHP version doesn't support this exact type hint at this place
     * but support loosier, it may be returned relaxed.
     * 
     * @param 'arg'|'return'|'class-prop' $kind  What this type will be used for?
     */
    public static function typeHintForPhpVer(array|string|null $types, string $phpVer, string $kind): ?string
    {
        if ($types === null || $types === '' || $types === []) {
            return null;;
        }

        if ($kind === self::TYPE_KIND_RETURN && !Semver::satisfies($phpVer, '>=7.0')) {
            return null;;
        }

        if ($kind === self::TYPE_KIND_PROP && !Semver::satisfies($phpVer, '>=7.4')) {
            return null;;
        }

        if (is_string($types)) {
            if (str_starts_with($types, '?')) {
                $types = [substr($types, 1), 'null'];
            } else {
                $types = explode('|', $types);
            }
        }

        $hasNull = in_array('null', $types, true);
        $withoutNull = array_filter($types, fn($t) => $t !== 'null');
        if (count($withoutNull) === 0) {
            return null;; // 'null' can't be used as a type hint
        }

        $hasMixed = in_array('mixed', $types, true);
        $hasVoid = in_array('void', $types, true);
        $hasTrue = in_array('true', $types, true);

        if ($hasVoid) {
            if (count($withoutNull) > 1) {
                throw new \InvalidArgumentException("Cannot mix 'void' with other types");
            }
            if ($kind !== self::TYPE_KIND_RETURN) {
                throw new \InvalidArgumentException("Cannot use 'void' for anything but return type");
            }
        }

        // special treatment for 'mixed'
        if ($hasMixed) {
            // even on unsupported phpVer check sanity
            if (count($withoutNull) > 1) {
                throw new \InvalidArgumentException(
                    "Mixing 'mixed' type with other types other than null might be a severe logic mistake. We would need to omit all types except 'mixed' but this way type information would be lost."
                );
            }
            
            if (!Semver::satisfies($phpVer, '>=8.0')) {
                return null;;
            }

            if ($hasNull) {
                // if we have 'mixed' + 'null' drop null and return just 'mixed'
                return 'mixed';
            }
        }

        if (count($withoutNull) > 1) {
            // exit if it's PHP < 8 and multiple types (not counting 'null') are present
            if (!Semver::satisfies($phpVer, '>=8.0')) {
                return null;;
            }
            
            // append null at the end. TODO: we also need sorting
            if ($hasNull) {
                $withoutNull[] = 'null';
            }
            return implode('|', $withoutNull);
        }

        // if we're here that means we have only one type (not counting 'null')
        // check whether the given php version supports certain features.

        $type = $withoutNull[0];

        return $hasNull && Semver::satisfies($phpVer, '>=7.1')
            ? "?{$type}"
            : $type; // no nullable types in php < 7.1
    }
}
