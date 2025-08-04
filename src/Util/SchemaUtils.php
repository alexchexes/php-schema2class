<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

class SchemaUtils
{
    public static function extractTypeForAnnotation(array $schema): string
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

    public static function extractTypeForDefault(array $schema): ?string
    {
        if (isset($schema['type'])) {
            if (is_string($schema['type'])) {
                return $schema['type'];
            }
            if (is_array($schema['type'])) {
                if (in_array('object', $schema['type'], true) && !in_array('array', $schema['type'], true)) {
                    return 'object';
                }
                if (in_array('array', $schema['type'], true) && !in_array('object', $schema['type'], true)) {
                    return 'array';
                }
            }
        }

        if (isset($schema['properties']) || isset($schema['additionalProperties']) || isset($schema['required'])) {
            return 'object';
        }

        if (isset($schema['items'])) {
            return 'array';
        }

        return null;
    }
}