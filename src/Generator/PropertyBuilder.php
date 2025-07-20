<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\Property\BooleanProperty;
use Helmich\Schema2Class\Generator\Property\NumberProperty;
use Helmich\Schema2Class\Generator\Property\ObjectArrayProperty;
use Helmich\Schema2Class\Generator\Property\TypedArrayProperty;
use Helmich\Schema2Class\Generator\Property\PrimitiveArrayProperty;
use Helmich\Schema2Class\Generator\Property\InferredEnumProperty;
use Helmich\Schema2Class\Generator\Property\DateProperty;
use Helmich\Schema2Class\Generator\Property\IntegerProperty;
use Helmich\Schema2Class\Generator\Property\IntersectProperty;
use Helmich\Schema2Class\Generator\Property\MixedProperty;
use Helmich\Schema2Class\Generator\Property\NestedObjectProperty;
use Helmich\Schema2Class\Generator\Property\NullablePropertyDecorator;
use Helmich\Schema2Class\Generator\Property\NullProperty;
use Helmich\Schema2Class\Generator\Property\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\PrimitiveUnionEnumProperty;
use Helmich\Schema2Class\Generator\Property\PropertyInterface;
use Helmich\Schema2Class\Generator\Property\ReferenceArrayProperty;
use Helmich\Schema2Class\Generator\Property\ReferenceProperty;
use Helmich\Schema2Class\Generator\Property\RawObjectProperty;
use Helmich\Schema2Class\Generator\Property\StringEnumProperty;
use Helmich\Schema2Class\Generator\Property\StringProperty;
use Helmich\Schema2Class\Generator\Property\UnionProperty;

class PropertyBuilder
{
    /** @var class-string[] */
    private static array $propertyTypes = [
        IntersectProperty::class,
        UnionProperty::class,
        DateProperty::class,
        StringEnumProperty::class,
        PrimitiveUnionEnumProperty::class,
        InferredEnumProperty::class,
        NullProperty::class,
        StringProperty::class,
        IntegerProperty::class,
        NumberProperty::class,
        NestedObjectProperty::class,
        ObjectArrayProperty::class,
        ReferenceArrayProperty::class,
        TypedArrayProperty::class,
        PrimitiveArrayProperty::class,
        BooleanProperty::class,
        ReferenceProperty::class,
        RawObjectProperty::class,
        MixedProperty::class,
    ];

    /**
     * @param GeneratorRequest $req
     * @param string           $name
     * @param array            $definition
     * @param bool             $isRequired
     * @return PropertyInterface
     * @throws GeneratorException
     */
    public static function buildPropertyFromSchema(GeneratorRequest $req, string $name, array $definition, bool $isRequired): PropertyInterface
    {
        self::testInvariants($definition);

        $definition = self::sanitizeEnum($definition);

        // collapse single-element type arrays (e.g. ["string"] -> "string")
        $defType = $definition['type'] ?? null;
        if (is_array($defType) && count($defType) === 1) {
            $definition['type'] = $defType[0];
        }

        // Dereference references to schemas that will not result in a separate class
        if (isset($definition['$ref'])) {
            $refSchema = $req->lookupSchema($definition['$ref']);
            if (!empty($refSchema)) {
                $shouldInline =
                    (isset($refSchema['oneOf']) || isset($refSchema['anyOf'])) ||
                    (!NestedObjectProperty::canHandleSchema($refSchema)
                        && !IntersectProperty::canHandleSchema($refSchema)
                        && !array_key_exists('enum', $refSchema));

                if ($shouldInline) {
                    foreach ([
                        'title',
                        'description',
                        'summary',
                        'default',
                        'deprecated',
                        'readOnly',
                        'writeOnly',
                        'examples',
                        'example',
                    ] as $k) {
                        if (array_key_exists($k, $definition)) {
                            $refSchema[$k] = $definition[$k];
                        }
                    }

                    return self::buildPropertyFromSchema($req, $name, $refSchema, $isRequired);
                }
            }
        }

        // Handle ["null","primitive"] style optional primitives
        if (isset($definition['type'])
            && is_array($definition['type'])
            && count($definition['type']) === 2
            && (in_array('null', $definition['type'], true) || in_array(null, $definition['type'], true))
        ) {
            [$a, $b] = $definition['type'];
            $prim = $a === 'null' ? $b : $a;
            switch ($prim) {
                case 'string':
                    if (isset($definition['enum'])) {
                        $prop = new StringEnumProperty($name, $definition, $req);
                    } else {
                        $prop = new StringProperty($name, $definition, $req);
                    }
                    break;
                case 'integer':
                    $prop = new IntegerProperty($name, $definition, $req);
                    break;
                case 'number':
                    $prop = new NumberProperty($name, $definition, $req);
                    break;
                case 'boolean':
                    $prop = new BooleanProperty($name, $definition, $req);
                    break;
                default:
                    $prop = null;
            }
            if ($prop !== null) {
                if ($isRequired) {
                    return new NullablePropertyDecorator($name, $prop);   // required + nullable
                }

                $decorator = new OptionalPropertyDecorator($name, $prop);  // optional
                if (self::definitionAllowsNull($definition) || $prop->allowsNull()) {
                    $decorator->markOptionalNullable();
                }

                return $decorator;
            }
        }

        if (PrimitiveUnionEnumProperty::canHandleSchema($definition)) {
            $property = new PrimitiveUnionEnumProperty($name, $definition, $req);
            if (!$isRequired) {
                $decorator = new OptionalPropertyDecorator($name, $property);
                if (self::definitionAllowsNull($definition) || $property->allowsNull()) {
                    $decorator->markOptionalNullable();
                }
                $property = $decorator;
            } elseif ($property->allowsNull()) {
                $property = new NullablePropertyDecorator($name, $property);
            }

            return $property;
        }

        if (InferredEnumProperty::canHandleSchema($definition)) {
            $property = new InferredEnumProperty($name, $definition, $req);
            if (!$isRequired) {
                $decorator = new OptionalPropertyDecorator($name, $property);
                if (self::definitionAllowsNull($definition) || $property->allowsNull()) {
                    $decorator->markOptionalNullable();
                }
                $property = $decorator;
            } elseif ($property->allowsNull()) {
                $property = new NullablePropertyDecorator($name, $property);
            }

            return $property;
        }

        // Expand multi-type definitions like ["string", "object"] into an anyOf union
        if (isset($definition['type']) && is_array($definition['type']) && count($definition['type']) > 1) {
            $types      = $definition['type'];
            $subSchemas = [];
            foreach ($types as $t) {
                $sub       = $definition;
                $sub['type'] = $t;
                // prune object specific fields for non-object arms
                if ($t !== 'object') {
                    unset($sub['properties'], $sub['required'], $sub['additionalProperties']);
                }
                $subSchemas[] = $sub;
            }

            $unionDef = $definition;
            unset($unionDef['type']);
            $unionDef['anyOf'] = $subSchemas;

            return self::buildPropertyFromSchema($req, $name, $unionDef, $isRequired);
        }

        // Strip out null arms from anyOf/oneOf and wrap the rest as an Optional<…>
        $unionKey = isset($definition['anyOf']) ? 'anyOf'
                : (isset($definition['oneOf']) ? 'oneOf' : null);

        if ($unionKey) {
            $subs   = $definition[$unionKey];
            $nullIx = null;
            foreach ($subs as $i => $sub) {
                if (isset($sub['type']) && $sub['type'] === 'null') {
                    $nullIx = $i;
                    break;
                }
            }

            // found a null–arm and at least one non-null arm
            if ($nullIx !== null && count($subs) > 1) {
                // everything except the null
                $otherArms = $subs;
                array_splice($otherArms, $nullIx, 1);

                //--------------------------------------------------------
                // CASE A – exactly one remaining arm
                //         → build that schema directly
                //--------------------------------------------------------

                if (count($otherArms) === 1) {
                    $singleSchema = $otherArms[0];
                    
                    /** copy meta fields that live on the top-level property */
                    foreach (['description', 'title', 'default', 'deprecated'] as $k) {
                        if (isset($definition[$k]) && !isset($singleSchema[$k])) {
                            $singleSchema[$k] = $definition[$k];
                        }
                    }

                    // build the inner property in the usual way
                    $inner = self::buildPropertyFromSchema(
                        $req,
                        $name,
                        $singleSchema,
                        $isRequired     // pass-through
                    );

                    if ($isRequired) {
                        return new NullablePropertyDecorator($name, $inner);
                    }

                    $decorator = new OptionalPropertyDecorator($name, $inner);
                    if (self::definitionAllowsNull($definition) || $inner->allowsNull()) {
                        $decorator->markOptionalNullable();
                    }

                    return $decorator;
                }

                //--------------------------------------------------------
                // CASE B – still multiple arms
                //          → keep a real UnionProperty
                //--------------------------------------------------------
                
                $cleanDef             = $definition;
                $cleanDef[$unionKey]  = $otherArms;            // without the null arm
                $unionProp            = new UnionProperty($name, $cleanDef, $req);

                if ($isRequired) {
                    return new NullablePropertyDecorator($name, $unionProp);
                }

                $decorator = new OptionalPropertyDecorator($name, $unionProp);
                if (self::definitionAllowsNull($definition) || $unionProp->allowsNull()) {
                    $decorator->markOptionalNullable();
                }

                return $decorator;
            }
        }

        foreach (self::$propertyTypes as $propertyType) {
            if ($propertyType::canHandleSchema($definition)) {
                /** @var PropertyInterface $property */
                $property = new $propertyType($name, $definition, $req);

                if (!$isRequired) {
                    $decorator = new OptionalPropertyDecorator($name, $property); // optional
                    if (self::definitionAllowsNull($definition) || $property->allowsNull()) {
                        $decorator->markOptionalNullable();
                    }
                    $property = $decorator;
                } elseif ($property->allowsNull()) {
                    $property = new NullablePropertyDecorator($name, $property); // required + nullable
                }

                return $property;
            }
        }

        throw new GeneratorException("cannot map type " . json_encode($definition));
    }

    private static function testInvariants(array $definition): void
    {
        $hasAdditionalProperties =
            isset($definition["additionalProperties"])
            && is_array($definition["additionalProperties"])
            && count($definition["additionalProperties"]) > 0;

        $hasProperties =
            isset($definition["properties"])
            && is_array($definition["properties"])
            && count($definition["properties"]) > 0;

        if ($hasProperties && $hasAdditionalProperties) {
            throw new GeneratorException("using 'properties' and 'additionalProperties' in the same schema is currently not supported.");
        }
    }

    /**
     * Remove enum values that do not match the declared type and drop
     * type entries that are not represented in the remaining enum.
     */
    private static function sanitizeEnum(array $definition): array
    {
        if (!isset($definition['enum']) || !isset($definition['type'])) {
            return $definition;
        }

        $originalIsArray = is_array($definition['type']);
        $types = $originalIsArray ? $definition['type'] : [$definition['type']];
        $types = array_map(static function ($t) {
            return $t === 'int' ? 'integer' : ($t === null ? 'null' : $t);
        }, $types);

        $allowed = [];
        foreach ($definition['enum'] as $v) {
            $t = match (true) {
                $v === null      => 'null',
                is_string($v)    => 'string',
                is_int($v)       => 'integer',
                is_float($v)     => 'number',
                is_bool($v)      => 'boolean',
                default          => null,
            };
            if ($t === null) {
                continue;
            }
            if (in_array($t, $types, true) || ($t === 'integer' && in_array('number', $types, true))) {
                $allowed[] = $v;
            }
        }

        $definition['enum'] = $allowed;

        $foundTypes = [];
        foreach ($allowed as $v) {
            $t = match (true) {
                $v === null      => 'null',
                is_string($v)    => 'string',
                is_int($v)       => 'integer',
                is_float($v)     => 'number',
                is_bool($v)      => 'boolean',
                default          => null,
            };
            if ($t !== null) {
                $foundTypes[$t] = true;
            }
        }

        $newTypes = array_values(array_filter($types, static function ($t) use ($foundTypes) {
            if ($t === 'null') {
                return true; // keep null if declared
            }
            if ($t === 'number') {
                return isset($foundTypes['number']) || isset($foundTypes['integer']);
            }
            return isset($foundTypes[$t]);
        }));

        if (count($newTypes) === 1 && !$originalIsArray) {
            $definition['type'] = $newTypes[0];
        } else {
            $definition['type'] = $newTypes;
        }

        if (is_array($definition['type']) && count($definition['type']) === 1 && !$originalIsArray) {
            $definition['type'] = $definition['type'][0];
        }

        if (is_array($definition['type']) && count($definition['type']) > 1) {
            $definition['type'] = array_values($definition['type']);
        }

        return $definition;
    }

    private static function definitionAllowsNull(array $definition): bool
    {
        if (isset($definition['enum']) && in_array(null, $definition['enum'], true)) {
            return true;
        }

        $type = $definition['type'] ?? null;
        if ($type === 'null') {
            return true;
        }
        if (is_array($type) && in_array('null', $type, true)) {
            return true;
        }

        foreach (['anyOf', 'oneOf'] as $k) {
            if (isset($definition[$k]) && is_array($definition[$k])) {
                foreach ($definition[$k] as $sub) {
                    if (($sub['type'] ?? null) === 'null') {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
