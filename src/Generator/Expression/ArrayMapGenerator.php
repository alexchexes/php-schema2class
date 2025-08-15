<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Expression;

use Composer\Semver\Semver;
use Helmich\Schema2Class\Util\StringUtils;
use Helmich\Schema2Class\Util\TypeHint;

class ArrayMapGenerator
{
    public static function make(
        string $itemParam,
        string $mapExpr,
        string $arrayExpr,
        string $phpVer,
        ?array $useVars = [],
        array|string|null $itemType = null,
        array|string|null $returnType = null,
        int $lengthToWrap = 100,
    ): string
    {
        if ($itemType) {
            $itemType = TypeHint::forPhpVer($itemType, $phpVer, TypeHint::KIND_ARG, TypeHint::LEGACY_NULLABLE_DROP_NULL);
        }
        
        if ($returnType) {
            $returnType = TypeHint::forPhpVer($returnType, $phpVer, TypeHint::KIND_RETURN, TypeHint::LEGACY_NULLABLE_DROP_NULL);
        }

        if ($itemType) {
            $itemType = "{$itemType} ";
        }

        if ($returnType) {
            $returnType = ": {$returnType}";
        }

        if (Semver::satisfies($phpVer, '>=7.4')) {
            return CallGenerator::make(
                callee: 'array_map',
                arguments: [
                    "fn ({$itemType}{$itemParam}){$returnType} => {$mapExpr}",
                    $arrayExpr,
                ],
                lengthToWrap: $lengthToWrap,
                phpVer: $phpVer,
            );
        }

        $useClause = '';
        if ($useVars) {
            $useClause = self::buildUseClause($useVars);
        }

        $forceMultiline = true;
        $buildCallBackExpr = fn(string $callbackBody): string =>
            "function({$itemType}{$itemParam}){$useClause}{$returnType} {{$callbackBody}}";

        $returnExpr = "return {$mapExpr};";
        $callbackExpr = $buildCallBackExpr(" {$returnExpr} ");

        if (mb_strlen($mapExpr) > 30 || mb_strlen($callbackExpr) >= $lengthToWrap) {
            $forceMultiline = false; // let CallGenerator decide on the wrapping style
            $callbackExpr = $buildCallBackExpr(
                "\n" . StringUtils::indentCode($returnExpr) . "\n"
            );
        }

        return CallGenerator::make(
            callee: 'array_map',
            arguments: [
                $callbackExpr,
                $arrayExpr,
            ],
            lengthToWrap: $lengthToWrap,
            phpVer: $phpVer,
            forceMultiline: $forceMultiline,
        );
    }

    public static function buildUseClause(array $useVars = []): string
    {
        if ($useVars === []) {
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
