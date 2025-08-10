<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Util\StringUtils;

class TernaryGenerator
{
    public const LENGTH_LIMIT = 85;

    static public function make(string $condition, string $ifTrue, string $ifFalse, bool $parens = true): string
    {
        $result = "{$condition} ? {$ifTrue} : {$ifFalse}";
        $multiline = false;

        if (
            str_contains($condition, "\n")
            || str_contains($ifTrue, "\n")
            || str_contains($ifFalse, "\n")
            || mb_strlen($result) > self::LENGTH_LIMIT
        ) {
            $multiline = true;
            $result = implode("\n", [
                $condition,
                StringUtils::indentCode("? {$ifTrue}"),
                StringUtils::indentCode(": {$ifFalse}"),
            ]);
        }

        if ($parens) {
            return $multiline
                ? "({$result}\n)" // Compact style. CS-Fixer is up to user anyway
                : "($result)";
        }

        return $result;
    }
}
