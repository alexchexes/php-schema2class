<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property;

use Helmich\Schema2Class\Generator\GeneratorException;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Type\Primitive\BooleanProperty;
use Helmich\Schema2Class\Generator\Property\Type\Primitive\DateProperty;
use Helmich\Schema2Class\Generator\Property\Type\Primitive\IntegerProperty;
use Helmich\Schema2Class\Generator\Property\Type\Enum\IntegerEnumProperty;
use Helmich\Schema2Class\Generator\Property\Type\Composite\IntersectProperty;
use Helmich\Schema2Class\Generator\Property\Type\MixedProperty;
use Helmich\Schema2Class\Generator\Property\Type\Object\NestedObjectProperty;
use Helmich\Schema2Class\Generator\Property\Type\Primitive\NullProperty;
use Helmich\Schema2Class\Generator\Property\Type\Primitive\NumberProperty;
use Helmich\Schema2Class\Generator\Property\Type\Array\ObjectArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\Array\PrimitiveArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\Enum\PrimitiveUnionEnumProperty;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Generator\Property\Type\Object\RawObjectProperty;
use Helmich\Schema2Class\Generator\Property\Type\Array\ReferenceArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\ReferenceProperty;
use Helmich\Schema2Class\Generator\Property\Type\Enum\StringEnumProperty;
use Helmich\Schema2Class\Generator\Property\Type\Primitive\StringProperty;
use Helmich\Schema2Class\Generator\Property\Type\Array\TypedArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\Composite\UnionProperty;
use Helmich\Schema2Class\Generator\Property\Decorator\NullablePropertyDecorator;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;

/**
 * Factory that creates the correct {@see PropertyInterface} implementation
 * for a given schema fragment.
 * 
 * It inspects the schema fragment and chooses the most specific property class able to handle it.
 * 
 * Optional and nullable semantics are handled here as well.
 * 
 * Used by {@see Helmich\Schema2Class\Generator\SchemaPropertyCollector}
 * when turning a schema object `"properties"` section into a {@see Property\PropertyCollection}.
 */
class PropertyBuilder
{
    /** @var class-string[] */
    private static array $propertyTypes = [
        IntersectProperty::class,
        UnionProperty::class,
        DateProperty::class,
        StringEnumProperty::class,
        IntegerEnumProperty::class,
        PrimitiveUnionEnumProperty::class,
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
    public static function buildPropertyFromSchema(
        GeneratorRequest $req,
        string $name,
        array $definition,
        bool $isRequired
    ): PropertyInterface
    {
        $definition = self::collapseSingleUnion($definition);
        $definition = self::sanitizeEnum($definition);
        $definition = self::collapseSingleTypeArray($definition);
        
        self::assertNoPropertiesWithAdditional($definition);
        
        if ($property = self::tryInlineReference($req, $name, $definition, $isRequired)) {
            return $property;
        }

        if ($property = self::tryPrimitiveWithNull($req, $name, $definition, $isRequired)) {
            return $property;
        }

        if (PrimitiveUnionEnumProperty::canHandleSchema($definition)) {
            $property = new PrimitiveUnionEnumProperty($name, $definition, $req);
            return self::wrapProperty($req, $property, $definition, $name, $isRequired);
        }

        if ($property = self::tryExpandMultiType($req, $name, $definition, $isRequired)) {
            return $property;
        }

        if ($property = self::tryNullableUnion($req, $name, $definition, $isRequired)) {
            return $property;
        }

        foreach (self::$propertyTypes as $propertyType) {
            if ($propertyType::canHandleSchema($definition)) {
                /** @var PropertyInterface $property */
                $property = new $propertyType($name, $definition, $req);
                return self::wrapProperty($req, $property, $definition, $name, $isRequired);
            }
        }

        throw new GeneratorException("cannot map type " . (string) json_encode($definition));
    }

    /** Collapse oneOf/anyOf unions with a single element */
    private static function collapseSingleUnion(array $definition): array
    {
        $unionKey = isset($definition['anyOf']) ? 'anyOf' : (isset($definition['oneOf']) ? 'oneOf' : null);
        if (!$unionKey) {
            return $definition;
        }

        $subs = $definition[$unionKey];
        if (!is_array($subs) || count($subs) !== 1) {
            return $definition;
        }

        $single = $subs[0];
        foreach (['description', 'title', 'default', 'deprecated'] as $k) {
            if (isset($definition[$k]) && !isset($single[$k])) {
                $single[$k] = $definition[$k];
            }
        }

        return self::collapseSingleUnion($single);
    }

    /** Collapse single-element type arrays (e.g. ["string"] -> "string") */
    private static function collapseSingleTypeArray(array $definition): array
    {
        $defType = $definition['type'] ?? null;
        if (is_array($defType) && count($defType) === 1) {
            $definition['type'] = $defType[0];
        }

        return $definition;
    }

    /**
     * Inline referenced schema definitions when they would not create a new class
     */
    private static function tryInlineReference(
        GeneratorRequest $req,
        string $name,
        array $definition,
        bool $isRequired
    ): ?PropertyInterface {
        if (!isset($definition['$ref'])) {
            return null;
        }

        $refSchema = $req->lookupSchema($definition['$ref']);
        if (empty($refSchema)) {
            return null;
        }

        $shouldInline =
            (isset($refSchema['oneOf']) || isset($refSchema['anyOf'])) ||
            (!NestedObjectProperty::canHandleSchema($refSchema)
                && !IntersectProperty::canHandleSchema($refSchema)
                && !array_key_exists('enum', $refSchema));

        if (!$shouldInline) {
            return null;
        }

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

    /** Handle ["null", "<primitive>"] optional primitives */
    private static function tryPrimitiveWithNull(
        GeneratorRequest $req,
        string $name,
        array $definition,
        bool $isRequired
    ): ?PropertyInterface {
        if (!isset($definition['type']) || !is_array($definition['type']) || count($definition['type']) !== 2) {
            return null;
        }
        if (!(in_array('null', $definition['type'], true) || in_array(null, $definition['type'], true))) {
            return null;
        }

        [$a, $b] = $definition['type'];
        $prim = $a === 'null' ? $b : $a;
        $prop = match ($prim) {
            'string'  => isset($definition['enum'])
                ? new StringEnumProperty($name, $definition, $req)
                : new StringProperty($name, $definition, $req),
            'integer' => new IntegerProperty($name, $definition, $req),
            'number'  => new NumberProperty($name, $definition, $req),
            'boolean' => new BooleanProperty($name, $definition, $req),
            default   => null,
        };

        if ($prop === null) {
            return null;
        }

        if ($isRequired) {
            return new NullablePropertyDecorator($name, $prop, $req);
        }

        $decorator = new OptionalPropertyDecorator($name, $prop, $req);
        if (self::definitionAllowsNull($definition) || $prop->allowsNull()) {
            $decorator->markOptionalNullable();
        }

        return $decorator;
    }

    /**
     * Expand multi-type definitions like ["string", "object"] into an "anyOf" union
     */
    private static function tryExpandMultiType(
        GeneratorRequest $req,
        string $name,
        array $definition,
        bool $isRequired
    ): ?PropertyInterface {
        if (!isset($definition['type']) || !is_array($definition['type']) || count($definition['type']) <= 1) {
            return null;
        }

        $types      = $definition['type'];
        $subSchemas = [];
        foreach ($types as $t) {
            $sub       = $definition;
            $sub['type'] = $t;
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

    /** Strip out null arms from anyOf/oneOf definitions */
    private static function tryNullableUnion(
        GeneratorRequest $req,
        string $name,
        array $definition,
        bool $isRequired
    ): ?PropertyInterface {
        $unionKey = isset($definition['anyOf']) ? 'anyOf' : (isset($definition['oneOf']) ? 'oneOf' : null);
        if (!$unionKey) {
            return null;
        }

        $subs      = $definition[$unionKey];
        $otherArms = [];
        $hasNull   = false;
        foreach ($subs as $sub) {
            if (!isset($sub['type'])) {
                $otherArms[] = $sub;
                continue;
            }

            $type = $sub['type'];
            if ($type === 'null') {
                $hasNull = true;
                continue;
            }

            if (is_array($type) && ($nullPos = array_search('null', $type, true)) !== false) {
                $hasNull = true;
                unset($type[$nullPos]);
                $type = array_values($type);
                if (count($type) === 0) {
                    continue;
                }
                $sub['type'] = count($type) === 1 ? $type[0] : $type;
            }

            $otherArms[] = $sub;
        }

        if (!$hasNull || count($subs) <= 1) {
            return null;
        }

        if (count($otherArms) === 1) {
            $singleSchema = $otherArms[0];
            foreach (['description', 'title', 'default', 'deprecated'] as $k) {
                if (isset($definition[$k]) && !isset($singleSchema[$k])) {
                    $singleSchema[$k] = $definition[$k];
                }
            }
            $inner = self::buildPropertyFromSchema($req, $name, $singleSchema, $isRequired);
            return $isRequired
                ? new NullablePropertyDecorator($name, $inner, $req)
                : self::wrapProperty($req, $inner, $definition, $name, false);
        }

        $cleanDef            = $definition;
        $cleanDef[$unionKey] = $otherArms;
        $unionProp           = new UnionProperty($name, $cleanDef, $req);

        return $isRequired
            ? new NullablePropertyDecorator($name, $unionProp, $req)
            : self::wrapProperty($req, $unionProp, $definition, $name, false);
    }

    /** Apply optional/nullable decorators around a property */
    private static function wrapProperty(
        GeneratorRequest $req,
        PropertyInterface $property,
        array $definition,
        string $name,
        bool $isRequired
    ): PropertyInterface {
        if (!$isRequired) {
            $decorator = new OptionalPropertyDecorator($name, $property, $req);
            if (self::definitionAllowsNull($definition) || $property->allowsNull()) {
                $decorator->markOptionalNullable();
            }
            return $decorator;
        }

        return $property->allowsNull() ? new NullablePropertyDecorator($name, $property, $req) : $property;
    }

    private static function assertNoPropertiesWithAdditional(array $definition): void
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
        if (!isset($definition['enum'])) {
            return $definition;
        }

        // Normalize numeric values: 5.0 → 5
        $definition['enum'] = array_map(
            static function ($v) {
                if (is_float($v) && fmod($v, 1.0) === 0.0) {
                    return (int)$v;
                }
                return $v;
            },
            $definition['enum']
        );

        // If no explicit type is given, try to infer it from enum values
        if (!isset($definition['type']) && !isset($definition['anyOf']) && !isset($definition['oneOf'])) {
            $types = [];
            foreach ($definition['enum'] as $v) {
                $t = match (true) {
                    $v === null     => 'null',
                    is_string($v)   => 'string',
                    is_int($v)      => 'integer',
                    is_float($v)    => 'number',
                    is_bool($v)     => 'boolean',
                    default         => null,
                };
                if ($t === null) {
                    // contains unsupported value (array/object) – give up
                    return $definition;
                }
                $types[$t] = true;
            }

            $definition['type'] = array_keys($types);
            if (count($definition['type']) === 1) {
                $definition['type'] = $definition['type'][0];
            }

            return $definition;
        }

        $originalIsArray = is_array($definition['type']);
        $types = $originalIsArray ? $definition['type'] : [$definition['type']];
        $types = array_map(
            static fn (?string $t) => $t === 'int' ? 'integer' : ($t === null ? 'null' : $t),
            $types
        );

        $allowed = [];
        foreach ($definition['enum'] as $v) {
            /** @var  'null'|'string'|'integer'|'number'|'boolean'|null */
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
