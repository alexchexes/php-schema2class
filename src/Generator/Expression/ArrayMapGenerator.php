<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Expression;

use Composer\Semver\Semver;
use Helmich\Schema2Class\Util\StringUtils;
use Helmich\Schema2Class\Util\TypeHint;

class ArrayMapGenerator
{
    public static function make(
        string $arrayExpr,
        string $itemParam,
        string $mapExpr,
        string $phpVer,
        ?array $useVars = [],
        array|string|null $itemType = null,
        array|string|null $returnType = null,
        int $lengthToWrap = 100,
    ): string
    {
        $callback = ArrowFunctionGenerator::make(
            parameters: [$itemParam => $itemType],
            expr: $mapExpr,
            phpVer: $phpVer,
            useVars: $useVars,
            returnType: $returnType,
            lengthToWrap: $lengthToWrap,
        );

        $forceMultiline = false;

        if (!Semver::satisfies($phpVer, '>=7.4')) {
            // when arrows are not supported, make it multiline by default so it will look like this:
            // array_map(
            //     function($i) { return $expr },
            //     $array
            // )
            $forceMultiline = true;
        }

        if (mb_strlen($mapExpr) > 30 || str_contains($callback, "\n")) {
            // but if it's too long/complex, let the CallGenerator decide on the wrapping style
            $forceMultiline = false;
        }

        return CallGenerator::make(
            callee: 'array_map',
            arguments: [
                $callback,
                $arrayExpr,
            ],
            phpVer: $phpVer,
            lengthToWrap: $lengthToWrap,
            forceMultiline: $forceMultiline,
        );
    }
}
