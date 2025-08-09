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

    /**
     * Return true if changing a single property may require validating the entire object,
     * based on top-level JSON Schema keywords that couple properties or impose object-level rules.
     *
     * Recurses into `allOf`/`anyOf`/`oneOf`.
     *
     * @param array $schema             The object-level JSON Schema (or subschema) to inspect.
     * @param bool  $isPropertyOptional When `true`, also consider object-cardinality limits
     *                                  (`minProperties`, `maxProperties`), which matter only
     *                                  if the property can be absent.
     * @param bool  $isPropertyDefined  When `false`, also consider constraints set by
     *                                  additional-/pattern-/unevaluated Properties.
     * 
     */
    public static function needsRevalidationOnPropertyChange(
        array $schema,
        bool $isPropertyOptional,
        bool $isPropertyDefined,
    ): bool {
        // check if meaningfull conditional constraints are set
        if (
            array_key_exists('if', $schema) && $schema['if'] !== []
            && (
                (array_key_exists('then', $schema) && $schema['then'] !== [] && $schema['then'] !== true)
                || (array_key_exists('else', $schema) && $schema['else'] !== [] && $schema['else'] !== true)
            )
        ) {
            return true;
        }

        /** Object-level coupling keywords (map to list of "neutral" values to ignore) */
        $keywords = [
            'not' => [], // may contain "properties" with rules for any property defined in schema
            'dependencies' => [[]], // empty array (object) is ignored
            'dependentRequired' => [[]],
            'dependentSchemas' => [[]],
        ];

        if ($isPropertyOptional) {
            $keywords = array_merge($keywords, [
                'maxProperties' => [],
                'minProperties' => [0], // "0" is the same as "no constraint"
            ]);
        }

        // Take these into account only for properties that are not explicitly defined
        if (!$isPropertyDefined) {
            $keywords = array_merge($keywords, [
                'additionalProperties' => [true, []],
                'unevaluatedProperties' => [true, []],
                'patternProperties' => [],
            ]);
        }

        // Top-level check
        foreach ($keywords as $keyword => $ignoredValues) {
            if (array_key_exists($keyword, $schema) && !in_array($schema[$keyword], $ignoredValues, true)) {
                return true;
            }
        }

        // Recurse into applicators that do not change instance focus
        foreach (['allOf', 'anyOf', 'oneOf'] as $k) {
            if (!empty($schema[$k]) && is_array($schema[$k])) {
                foreach ($schema[$k] as $sub) {
                    if (
                        is_array($sub)
                        && self::needsRevalidationOnPropertyChange($sub, $isPropertyOptional, $isPropertyDefined)
                    ) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Checks whether schema has "$ref" on any level
     */
    public static function schemaHasRef(array $schema): bool
    {
        if (isset($schema['$ref'])) {
            return true;
        }

        foreach ($schema as $value) {
            if (is_array($value) && self::schemaHasRef($value)) {
                return true;
            }
        }

        return false;
    }
}