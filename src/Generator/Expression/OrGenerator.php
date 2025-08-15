<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Expression;

use Helmich\Schema2Class\Util\StringUtils;

class OrGenerator
{
    /**
     * @param list<mixed> $conditions operands/conditions
     * @param bool $parens Whether to wrap the resulting expression with parenthesis
     */
    public static function make(array $conditions, bool $parens = true, int $lengthToWrap = 100): string
    {
        if ($conditions === []) {
            throw new \InvalidArgumentException("Attempt to generate OR expression without conditions");
        }
        foreach ($conditions as $k => $cond) {
            /** @var mixed $cond */
            $n = $k + 1;
            $type = gettype($cond);
            if (!is_string($cond) || $cond === '') {
                throw new \InvalidArgumentException("Condition #{$n} is not a non-empty string: {$cond} ($type)");
            }
        }

        $result = implode(" || ", $conditions);

        if (count($conditions) < 2) {
            // allow passing 1 condition when conditions are not known in advance
            $multiline = str_contains(implode('', $conditions), "\n");
        } else {
            $multiline = false;
            
            if (str_contains($result, "\n") || mb_strlen($result) >= $lengthToWrap) {
                $multiline = true;
                $head = array_shift($conditions) . "\n";
                $tail  = "|| " . implode("\n|| ", $conditions);
                $result = $head . StringUtils::indentCode($tail);
            }
        }

        if ($parens) {
            return $multiline
                ? "({$result}\n)" // Compact style. CS-Fixer is up to user anyway
                : "($result)";
        }

        return $result;
    }
}
