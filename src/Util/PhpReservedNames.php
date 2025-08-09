<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

class PhpReservedNames
{
    static public function phpPredefined(): array
    {
        return [
            '_COOKIE',              // HTTP Cookies
            '_ENV',                 // Environment variables
            '_FILES',               // HTTP File Upload variables
            '_GET',                 // HTTP GET variables
            '_POST',                // HTTP POST variables
            '_REQUEST',             // HTTP Request variables
            '_SERVER',              // Server and execution environment information
            '_SESSION',             // Session variables
            'argc',                 // The number of arguments passed to script
            'argv',                 // Array of arguments passed to script
            'GLOBALS',              // References all variables available in global scope
            'http_response_header', // HTTP response headers
            'php_errormsg',         // The previous error message
            'this',     // pseudo-variable, not in the official "predefined variables" list
        ];
    }
}
