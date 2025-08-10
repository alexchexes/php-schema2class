<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Util\StringUtils;

/**
 * Helper to build a `match` expression programmatically.
 * 
 * Arms are grouped by return expression so that multiple conditions can map to
 * the same result
 */
class MatchGenerator
{
    private array $arms = [];

    public function __construct(private string $subjectExpr)
    {
    }

    public function addArm(string $conditionExpr, string $returnExpr): void
    {
        $this->arms[$returnExpr][] = $conditionExpr;
        $this->arms[$returnExpr] = array_unique($this->arms[$returnExpr]);
    }

    public function generate(): string
    {
        $bodyParts = [];

        foreach ($this->arms as $returnExpr => $conditionExprs) {
            $arm = in_array("default", $conditionExprs)
                ? "default"
                : implode(",\n", $conditionExprs);

            $lastExpr = $conditionExprs[array_key_last($conditionExprs)];

            if (mb_strlen($lastExpr.$returnExpr) > 120) {
                $returnExprIndented = StringUtils::indentCode($returnExpr);
                $complete = "{$arm} =>\n{$returnExprIndented},";
            } else {
                $complete = "{$arm} => {$returnExpr},";
            }

            $bodyParts[] = $complete;
        }

        $body = implode("\n", $bodyParts);

        $indentedBody = StringUtils::indentCode($body);
        return "match ({$this->subjectExpr}) {\n{$indentedBody}\n}";
    }
}