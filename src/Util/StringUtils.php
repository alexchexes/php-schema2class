<?php

namespace Helmich\Schema2Class\Util;

class StringUtils
{
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
        $separatorCharacters = ["-", "_", "/", " "];
        $canonicalizedName = str_replace($separatorCharacters, " ", $input);
        $words = explode(" ", $canonicalizedName);

        $first = $words[0];
        $rest = array_slice($words, 1);

        return $first . join("", array_map(fn (string $w) => self::capitalizeWord($w), $rest));
    }
}