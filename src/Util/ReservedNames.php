<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Class\PropertyNames;

/**
 * TODO: Gotta split these groups further and re-shape the logic where we use it
 * to clearly separate "never-allowed", "not allowed for properties", "not allowed for methods"
 * and "not allowed for using as var names inside methods like `fromInput`", etc.
 * 
 * PLUS add list of reserved for namespace / class names (do we even check that now?)
 * 
 * PLUS they should vary depending on target PHP version, for example 7.4+ is less restrictive
 */
class ReservedNames
{
    static public function getBannedVarNames(/* ?string $phpVersion = null */): array
    {
        return [
            '_GLOBALS',
            'GLOBALS',
            '_SERVER',
            '_GET',
            '_POST',
            '_FILES',
            '_REQUEST',
            '_SESSION',
            '_ENV',
            '_COOKIE',
            'php_errormsg',
            'http_response_header',
            'argc',
            'argv',
            'this',
            ...PropertyNames::all(),
        ];
    }

    static public function getBannedMethodNames(/* ?string $phpVersion = null */): array
    {
        return [
            'clone',
            '__construct',
            '__destruct',
            '__get',
            '__set',
            '__call',
            '__isset',
            '__unset',
            '__sleep',
            '__wakeup',
            '__toString',
            '__invoke',
            '__debugInfo',
            '__clone',
            ...MethodNames::all(),
        ];
    }
}
