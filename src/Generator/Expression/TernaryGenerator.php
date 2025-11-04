<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Expression;

use Helmich\Schema2Class\Util\StringUtils;

class TernaryGenerator
{
    static public function make(string $condition, string $ifTrue, string $ifFalse, bool $parens = true, int $lengthToWrap = 85): string
    {
        if ($condition === '') {
            throw new \InvalidArgumentException("Empty condition");
        }
        if ($ifTrue === $ifFalse) {
            throw new \InvalidArgumentException("Expressions for 'ifTrue' and 'ifFalse' are identical: {$ifTrue}");
        }

        $result = "{$condition} ? {$ifTrue} : {$ifFalse}";
        $multiline = false;

        if (str_contains($result, "\n") || mb_strlen($result) > $lengthToWrap) {
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
