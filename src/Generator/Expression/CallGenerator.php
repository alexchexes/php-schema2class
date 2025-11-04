<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Expression;

use Composer\Semver\Semver;
use Helmich\Schema2Class\Util\StringUtils;

class CallGenerator
{
    /**
     * Builds and formats call expressions from the given callee expression and its args.
     * 
     * Style is not PSR-compatible (it's up to users to use CS-fixers),
     * but suitable for long and complex expressions and the generator needs.
     * 
     * @param string $callee    Expression to call (e.g. `array_map`, `Foo::bar`, `$obj->run`, `$fn`)
     * @param array<int,string>|array<string,string> $arguments  Arguments for the `callee` as code strings.  
     *                                                      Named arguments are supported on PHP 8+,  
     *                                                      pass assoc array like `['limit' => '10', 'strict' => 'true']`
     */
    public static function make(
        string $callee,
        array $arguments,
        string $phpVer,
        int $lengthToWrap = 100,
        bool $forceMultiline = false
    ): string
    {
        $named = [];
        $positional = [];
        foreach ($arguments as $k => $v) {
            if (is_string($k)) {
                if (!Semver::satisfies($phpVer, '>=8.0')) {
                    throw new \InvalidArgumentException("Cannot use named arguments with 'phpVer' < 8.0");
                }
                $named[] = "{$k}: {$v}";
            } else {
                if (count($named)) {
                    throw new \InvalidArgumentException("Cannot mix positional and named arguments");
                }
                $positional[] = $v;
            }
        }

        $argsToRender = array_merge($named, $positional);
        
        if (!$forceMultiline) {
            $result = "{$callee}(" . implode(', ', $argsToRender) . ")";

            // determine multiline style: if the first arg is something like multiline callback or array
            // which last line is sole "}" or "]", and there's no more than one additional arg - keep it concise
            $rest = $argsToRender;
            $first = array_shift($rest);
            $linesOfFirst = explode("\n", $first);
            if (
                count($rest) <= 1 // only do this when there's one arg remaining
                && mb_strlen($linesOfFirst[array_key_last($linesOfFirst)]) === 1 // the ending "}" or "]"
            ) {
                $makeMultiline = false;
            } else {
                $makeMultiline = str_contains($result, "\n") || mb_strlen($result) >= $lengthToWrap;
            }

            if (!$makeMultiline) {
                return $result;
            }
        }
        
        // multiline

        $argsBlock = implode(",\n", $argsToRender);
        if (Semver::satisfies($phpVer, '>=7.3')) {
            $argsBlock .= ',';
        }
        $argsBlock = StringUtils::indentCode($argsBlock);
        return
            <<<PHP
            {$callee}(
            {$argsBlock}
            )
            PHP;
    }
}
