<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

class StringUtils
{
    /**
     * Transliterate a string into ASCII characters, if possible.
     */
    private static function transliterate(string $input): string
    {
        // Use the voku/portable-ascii library for robust transliteration
        return \voku\helper\ASCII::to_transliterate($input);
    }

    /**
     * Sanitize a string so it can be used as a PHP identifier.
     */
    public static function sanitizeIdentifier(string $input): string
    {
        $transliterated = self::transliterate($input);
        // Replace everything that is not a letter, digit or underscore with underscore
        $sanitized = preg_replace('/[^A-Za-z0-9_]+/', '_', $transliterated);

        // if new name got '_' at the end, but original name didn't have it, remove that trailing '_'
        if (str_ends_with($sanitized, '_') && !str_ends_with($input, '_')) {
            $sanitized = rtrim($sanitized, '_');
        }

        // if empty or underscores-only, prefix something unique (unless original already was just '_')
        if ($sanitized === '' || ($sanitized === '_' && $input !== '_')) {
            if (mb_strlen($input) <= 3 && $input !== '') {
                // use char code for short names
                $suffix = implode('', array_map('ord', str_split($input)));
            } else {
                $suffix = substr(md5($input), 0, 8);
            }
            $sanitized = '_' . $suffix;
        }

        // Identifiers must not start with a digit
        if (preg_match('/^[0-9]/', $sanitized)) {
            $sanitized = '_' . $sanitized;
        }

        return $sanitized;
    }

    public static function capitalize(string $str): string
    {
        if ($str === '') {
            return '';
        }

        return strtoupper($str[0]) . substr($str, 1);
    }

    public static function safePascalCase(string $input): string
    {
        return self::capitalize(self::safeCamelCase($input));
    }

    /**
     * Similar to pascalCase(), but preserves leading and trailing underscores
     * in the input string. Only underscores in the middle of the string are
     * removed.
     */
    public static function pascalCasePreserveOuterUnderscores(string $input): string
    {
        $leading = '';
        $trailing = '';

        $str = $input;
        while (str_starts_with($str, '_')) {
            $leading .= '_';
            $str = substr($str, 1);
        }
        while (str_ends_with($str, '_')) {
            $trailing .= '_';
            $str = substr($str, 0, -1);
        }

        $str = self::safePascalCase($str);

        return $leading . $str . $trailing;
    }

    public static function safeCamelCase(string $input): string
    {
        $input = self::transliterate($input);

        // Normalize separators to spaces
        $canonicalizedName = preg_replace('/[^A-Za-z0-9]+/', ' ', $input);
        $canonicalizedName = trim($canonicalizedName);

        if ($canonicalizedName === '') {
            return '_' . substr(md5($input), 0, 8);
        }

        $words = preg_split('/\s+/', $canonicalizedName);
        $first = $words[0];
        $rest  = array_slice($words, 1);

        $identifier = $first . join('', array_map(fn(string $w) => self::capitalize($w), $rest));

        if (preg_match('/^[0-9]/', $identifier)) {
            $identifier = '_' . $identifier;
        }

        return $identifier;
    }

    public static function indentCode(string $code, int $by = 1): string
    {
        $indent = str_repeat("    ", $by);
        $lines = explode("\n", $code);
        $lines = array_map(fn($l) => $indent . $l, $lines);

        return join("\n", $lines);
    }
    
    /**
     * A simple check that the PHPDoc type annotation and the PHP type hint convey exactly the same
     * information, ignoring the `?type` vs `type|null` style difference and the order of union types.
     */
    static public function isAnnotationSameAsTypeHint(string $annotType, string $typeHint): bool
    {
        $typeHint = preg_replace('/^\?/', 'null|', $typeHint);
        $annotType = preg_replace('/^\?/', 'null|', $annotType);
        // 'array<string|int>' will mangle but it doesn't matter here
        $typeParts = explode('|', $typeHint);
        $annotParts = explode('|', $annotType);
        return empty(array_diff($typeParts, $annotParts)) && empty(array_diff($annotParts, $typeParts));
    }
}

