<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use Helmich\Schema2Class\Generator\GeneratorRequest;

class DefaultsGenUtils
{
    public static function collectDefaults(array $schema, GeneratorRequest $req): array
    {
        $defaults = [];
        if (!isset($schema['properties']) || !is_array($schema['properties'])) {
            return $defaults;
        }

        $raw = $req->getRawSchema();
        $rawProps = null;
        if ($raw instanceof \stdClass && isset($raw->properties) && $raw->properties instanceof \stdClass) {
            $rawProps = $raw->properties;
        }

        foreach ($schema['properties'] as $key => $def) {
            $found = false;
            $rawKey = (string)$key;
            $rawDef = $rawProps && property_exists($rawProps, $rawKey) ? $rawProps->{$rawKey} : null;
            $d = self::extractDefault($def, $req, $found, $rawDef);
            if ($found) {
                $defaults[$key] = $d;
            }
        }

        return $defaults;
    }

    public static function extractDefault(array $def, GeneratorRequest $req, bool &$found = false, object|null $rawDef = null): array
    {
        if (array_key_exists('default', $def)) {
            $found = true;
            $val = $def['default'];
            $type = null;
            if (is_array($val)) {
                if ($rawDef !== null && property_exists($rawDef, 'default')) {
                    $rawDefault = $rawDef->default;
                    if ($rawDefault instanceof \stdClass) {
                        $type = 'object';
                    } elseif (is_array($rawDefault)) {
                        $type = 'array';
                    }
                } else {
                    $type = SchemaUtils::extractTypeForDefault($def);
                }
            }
            $default = ['default' => $val];
            if ($type) {
                $default['type'] = $type;
            }
            return $default;
        }

        if (isset($def['$ref'])) {
            $schema = $req->lookupSchema($def['$ref']);
            $d = self::extractDefault($schema, $req, $found);
            if ($found) {
                return $d;
            }
        }

        foreach (['anyOf', 'oneOf', 'allOf'] as $k) {
            if (isset($def[$k]) && is_array($def[$k])) {
                foreach ($def[$k] as $i => $sub) {
                    $rawSub = null;
                    if ($rawDef !== null && isset($rawDef->{$k}) && is_array($rawDef->{$k})) {
                        $rawSub = $rawDef->{$k}[$i] ?? null;
                    }
                    if (isset($sub['$ref'])) {
                        $sub = $req->lookupSchema($sub['$ref']);
                    }
                    if (is_array($sub)) {
                        $d = self::extractDefault($sub, $req, $found, $rawSub);
                        if ($found) {
                            return $d;
                        }
                    }
                }
            }
        }

        $found = false;
        return ['default' => null, 'type' => null];
    }
}
