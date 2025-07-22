<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Util;

class CodeFormatUtils
{
    public static function indentCode(string $code, int $by = 1): string
    {
        $indent = str_repeat("    ", $by);
        $lines = explode("\n", $code);
        $lines = array_map(fn($l) => $indent . $l, $lines);

        return join("\n", $lines);
    }

    public static function capitalize(string $str): string
    {
        return strtoupper($str[0]) . substr($str, 1);
    }
}
