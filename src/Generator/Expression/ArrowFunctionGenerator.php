<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Expression;

use Composer\Semver\Semver;
use Helmich\Schema2Class\Util\StringUtils;
use Helmich\Schema2Class\Util\TypeHint;

class ArrowFunctionGenerator
{
    /**
     * @param string|array<int, string>|array<string, string|null> $parameters  Array of callback params expressions, for example:
     * ```
     * ['$k', '$v', '...$rest']
     * ```
     * or
     * ```
     * [
     *  '$k' => 'string',
     *  '$v' => 'mixed',
     *  '...$rest' => null,
     * ]
     * ```
     * If array key is integer, its value used as param, and no type hint added.
     * If array key is NOT integer, it is used as param, and value used as type hint. Use `null` to exclude type hint in that case.
     */
    public static function make(
        string|array $parameters,
        string $expr,
        string $phpVer,
        ?array $useVars = [],
        array|string|null $returnType = null,
        int $lengthToWrap = 100,
    ): string
    {
        $params = self::buildParams($parameters, $phpVer);
        
        if ($returnType) {
            $returnType = TypeHint::forPhpVer($returnType, $phpVer, TypeHint::KIND_RETURN, TypeHint::LEGACY_NULLABLE_OMIT_TYPE);
            if ($returnType) {
                $returnType = ": {$returnType}";
            }
        }

        if (Semver::satisfies($phpVer, '>=7.4')) {
            return "fn ({$params}){$returnType} => {$expr}";
        }

        $useClause = self::buildUseClause($useVars);

        $buildCallBackExpr = fn(string $callbackBody): string =>
            "function({$params}){$useClause}{$returnType} {{$callbackBody}}";

        $returnExpr = "return {$expr};";
        $callbackExpr = $buildCallBackExpr(" {$returnExpr} ");

        if (mb_strlen($expr) > 30 || mb_strlen($callbackExpr) >= $lengthToWrap) {
            $callbackExpr = $buildCallBackExpr(
                "\n" . StringUtils::indentCode($returnExpr) . "\n"
            );
        }

        return $callbackExpr;
    }

    private static function buildParams(string|array $parameters, string $phpVer): string
    {
        if (!is_array($parameters)) {
            return $parameters;
        }
        $params = [];
        foreach ($parameters as $k => $v) {
            if (is_int($k)) {
                $params[] = $v; // use value as param name; no type
            } else {
                $paramName = $k; // use key as param name and value as type hint
                
                if ($v !== null) {
                    $paramType = TypeHint::forPhpVer($v, $phpVer, TypeHint::KIND_ARG, TypeHint::LEGACY_NULLABLE_OMIT_TYPE);
                    if ($paramType) {
                        $params[] = "{$paramType} {$paramName}";
                    } else {
                        $params[] = $paramName;
                    }
                } else {
                    $params[] = $paramName;
                }
            }
        }
        return implode(', ', $params);
    }

    private static function buildUseClause(?array $useVars = []): string
    {
        if (!$useVars) {
            return '';
        }

        $joined = implode(', ', $useVars);

        if (mb_strlen($joined) > 40) {
            $joined = implode(",\n", $useVars);
            $indented = StringUtils::indentCode($joined);
            return " use (\n{$indented}\n)";
        }

        return " use ({$joined})";
    }
}
