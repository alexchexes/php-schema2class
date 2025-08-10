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
        array $useVars = [],
        array|string|null $itemType = null,
        array|string|null $returnType = null,
        int $lengthToWrap = 100,
    ): string
    {
        $itemType = TypeHint::typeHintForPhpVer($itemType, $phpVer, TypeHint::TYPE_KIND_ARG);
        $returnType = TypeHint::typeHintForPhpVer($returnType, $phpVer, TypeHint::TYPE_KIND_RETURN);

        if ($itemType) {
            $itemType = "{$itemType} ";
        }

        if ($returnType) {
            $returnType = ": {$returnType}";
        }

        if (Semver::satisfies($phpVer, '>=7.4')) {
            CallGenerator::make(
                callee: 'array_map',
                arguments: [
                    "fn ({$itemType}{$itemParam}){$returnType} => {$mapExpr}",
                    $arrayExpr,
                ],
                lengthToWrap: $lengthToWrap,
            );
        }
        
        $returnExpr = "return {$mapExpr};";
        $callbackBody = mb_strlen($returnExpr) < 50
            ? " {$returnExpr} "
            : "\n" . StringUtils::indentCode($returnExpr) . "\n";

        $use = self::buildUseClause($useVars);

        return CallGenerator::make(
            callee: 'array_map',
            arguments: [
                "function({$itemType}{$itemParam}){$returnType} {$use}{{$callbackBody}}",
                $arrayExpr,
            ],
            lengthToWrap: $lengthToWrap,
        );
    }

    public static function buildUseClause(array $useVars = []): string
    {
        if ($useVars === []) {
            return '';
        }
        $joined = implode(', ', $useVars);
        if (mb_strlen($joined) > 50) {
            $joined = implode(",\n", $useVars);
            return "use (\n{$joined}\n) ";
        }
        return "use ({$joined}) ";
    }
}
