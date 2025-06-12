<?php

namespace Helmich\Schema2Class\Util;

class StringUtils
{
    /**
     * Transliterate a string into ASCII characters, if possible.
     */
    private static function transliterate(string $input): string
    {
        if (function_exists('transliterator_transliterate')) {
            $result = transliterator_transliterate('Any-Latin; Latin-ASCII', $input);
            if ($result !== null) {
                return $result;
            }
        }

        if (function_exists('iconv')) {
            $result = @iconv('UTF-8', 'ASCII//TRANSLIT', $input);
            if ($result !== false) {
                return $result;
            }
        }

        return $input;
    }

    /**
     * Sanitize a string so it can be used as a PHP identifier.
     */
    public static function sanitizeIdentifier(string $input): string
    {
        $input = self::transliterate($input);
        // Remove everything that is not a letter, digit or underscore
        $input = preg_replace('/[^A-Za-z0-9_]+/', '', $input);

        if ($input === '') {
            return '_';
        }

        // Identifiers must not start with a digit
        if (preg_match('/^[0-9]/', $input)) {
            $input = '_' . $input;
        }

        return $input;
    }
    public static function capitalizeWord(string $input): string
    {
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
            return '';
        }

        $words = preg_split('/\s+/', $canonicalizedName);
        $first = $words[0];
        $rest  = array_slice($words, 1);

        return $first . join('', array_map(fn(string $w) => self::capitalizeWord($w), $rest));
    }
}

