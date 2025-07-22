<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Util;

class SchemaUtils
{
    public static function extractPhpTypeFromSchema(array $schema): string
    {
        $type = isset($schema["type"]) ? $schema["type"] : "any";

        if ($type === "string") {
            if (isset($schema["format"]) && $schema["format"] == "date-time") {
                return "\\DateTime";
            }

            return "string";
        } else if ($type === "integer" || $type === "int") {
            return "int";
        } else if ($type === "number") {
            if (isset($schema["format"]) && $schema["format"] === "integer") {
                return "int";
            }

            return "float";
        }

        return "mixed";
    }
}