<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Expression;

use Helmich\Schema2Class\Util\StringUtils;
use InvalidArgumentException;

/**
 * Helper to build a `match` expression programmatically.
 * 
 * Arms are grouped by return expression so that multiple conditions can map to
 * the same result
 */
class MatchGenerator
{
    private const MAX_LINE_LENGTH = 120;

    private array $arms = [];

    private ?string $defaultReturn = null;

    public function __construct(private string $subjectExpr)
    {
    }

    public function addArm(string $conditionExpr, string $returnExpr): void
    {
        if ($conditionExpr === 'default') {
            if ($this->defaultReturn !== null) {
                throw new InvalidArgumentException("Default arm already added: {$this->defaultReturn}");
            }
            $this->defaultReturn = $returnExpr;
            return;
        }
        $this->arms[$returnExpr][] = $conditionExpr;
        $this->arms[$returnExpr] = array_unique($this->arms[$returnExpr]);
    }

    public function generate(): string
    {
        $matchBody = [];

        // add default to the end
        if ($this->defaultReturn !== null) {
            $this->arms[$this->defaultReturn][] = 'default';
        }
        
        if (!$this->arms) {
            throw new \Exception("Attempt to generate 'match' expression without any arms");
        }
        
        // make sure we don't return match expressions with only default branch
        if (count($this->arms) === 1 && in_array('default', $this->arms[array_key_first($this->arms)])) {
            return (string) array_key_first($this->arms);
        }

        foreach ($this->arms as $returnExpr => $conditionExprs) {
            $arm = in_array("default", $conditionExprs)
                ? "default"
                : implode(",\n", $conditionExprs);

            $lastExpr = $conditionExprs[array_key_last($conditionExprs)];

            if (mb_strlen($lastExpr . $returnExpr) > self::MAX_LINE_LENGTH) {
                $returnExprIndented = StringUtils::indentCode($returnExpr);
                $fullArm = "{$arm} =>\n{$returnExprIndented},";
            } else {
                $fullArm = "{$arm} => {$returnExpr},";
            }

            $matchBody[] = $fullArm;
        }

        $body = implode("\n", $matchBody);

        $indentedBody = StringUtils::indentCode($body);
        return "match ({$this->subjectExpr}) {\n{$indentedBody}\n}";
    }
}