<?php

namespace Helmich\Schema2Class\Util;

class StringUtils
{
    /**
     * Transliterate a string into ASCII characters, if possible.
     */
    private static function transliterate(string $input): string
    {
        // Use intl extension when available
        if (function_exists('transliterator_transliterate')) {
            $result = transliterator_transliterate('Any-Latin; Latin-ASCII', $input);
            if ($result !== null) {
                return $result;
            }
        }

        // Fallback to iconv if possible
        if (function_exists('iconv')) {
            $result = @iconv('UTF-8', 'ASCII//TRANSLIT', $input);
            if ($result !== false) {
                return $result;
            }
        }

        // Manual transliteration for Cyrillic characters as a last resort
        static $cyrillicMap = [
            'А' => 'A',  'Б' => 'B',  'В' => 'V',  'Г' => 'G',  'Д' => 'D',  'Е' => 'E',
            'Ё' => 'E',  'Ж' => 'Zh', 'З' => 'Z',  'И' => 'I',  'Й' => 'I',  'К' => 'K',
            'Л' => 'L',  'М' => 'M',  'Н' => 'N',  'О' => 'O',  'П' => 'P',  'Р' => 'R',
            'С' => 'S',  'Т' => 'T',  'У' => 'U',  'Ф' => 'F',  'Х' => 'Kh', 'Ц' => 'Ts',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Shch', 'Ъ' => '',  'Ы' => 'Y',  'Ь' => '',
            'Э' => 'E',  'Ю' => 'Yu', 'Я' => 'Ya',
            'а' => 'a',  'б' => 'b',  'в' => 'v',  'г' => 'g',  'д' => 'd',  'е' => 'e',
            'ё' => 'e',  'ж' => 'zh', 'з' => 'z',  'и' => 'i',  'й' => 'i',  'к' => 'k',
            'л' => 'l',  'м' => 'm',  'н' => 'n',  'о' => 'o',  'п' => 'p',  'р' => 'r',
            'с' => 's',  'т' => 't',  'у' => 'u',  'ф' => 'f',  'х' => 'kh', 'ц' => 'ts',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ъ' => '',  'ы' => 'y',  'ь' => '',
            'э' => 'e',  'ю' => 'yu', 'я' => 'ya',
        ];

        return strtr($input, $cyrillicMap);
    }

    /**
     * Sanitize a string so it can be used as a PHP identifier.
     */
    public static function sanitizeIdentifier(string $input): string
    {
        $transliterated = self::transliterate($input);
        // Remove everything that is not a letter, digit or underscore
        $sanitized = preg_replace('/[^A-Za-z0-9_]+/', '', $transliterated);

        if ($sanitized === '') {
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

