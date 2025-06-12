<?php

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

        // fallback if empty or underscores-only
        if ($sanitized === '' || ($sanitized === '_' && $input !== '_')) {
            $hash = substr(md5($input), 0, 8);
            $sanitized = '_' . $hash;
        }

        // Identifiers must not start with a digit
        if (preg_match('/^[0-9]/', $sanitized)) {
            $sanitized = '_' . $sanitized;
        }

        return $sanitized;
    }
    public static function capitalizeWord(string $input): string
    {
        if ($input === '') {
            return '';
        }

        return strtoupper($input[0]) . substr($input, 1);
    }

    public static function pascalCase(string $input): string
    {
        return self::capitalizeWord(self::camelCase($input));
    }

    public static function camelCase(string $input): string
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

        $identifier = $first . join('', array_map(fn(string $w) => self::capitalizeWord($w), $rest));

        if (preg_match('/^[0-9]/', $identifier)) {
            $identifier = '_' . $identifier;
        }

        return $identifier;
    }
}

