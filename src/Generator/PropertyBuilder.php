<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\Property\BooleanProperty;
use Helmich\Schema2Class\Generator\Property\DefaultPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\NumberProperty;
use Helmich\Schema2Class\Generator\Property\ObjectArrayProperty;
use Helmich\Schema2Class\Generator\Property\PrimitiveArrayProperty;
use Helmich\Schema2Class\Generator\Property\DateProperty;
use Helmich\Schema2Class\Generator\Property\IntegerProperty;
use Helmich\Schema2Class\Generator\Property\IntersectProperty;
use Helmich\Schema2Class\Generator\Property\MixedProperty;
use Helmich\Schema2Class\Generator\Property\NestedObjectProperty;
use Helmich\Schema2Class\Generator\Property\NullProperty;
use Helmich\Schema2Class\Generator\Property\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\PrimitiveUnionEnumProperty;
use Helmich\Schema2Class\Generator\Property\PropertyInterface;
use Helmich\Schema2Class\Generator\Property\ReferenceArrayProperty;
use Helmich\Schema2Class\Generator\Property\ReferenceProperty;
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
        NullProperty::class,
        StringProperty::class,
        IntegerProperty::class,
        NumberProperty::class,
        NestedObjectProperty::class,
        ObjectArrayProperty::class,
        ReferenceArrayProperty::class,
        PrimitiveArrayProperty::class,
        BooleanProperty::class,
        ReferenceProperty::class,
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

        // ─── Handle ["null","primitive"] style optional primitives ──────────────
        if (isset($definition['type'])
        && is_array($definition['type'])
        && count($definition['type']) === 2
        && in_array('null', $definition['type'], true)
        ) {
            [$a, $b] = $definition['type'];
            $prim = $a === 'null' ? $b : $a;
            switch ($prim) {
                case 'string':
                    $prop = new StringProperty($name, $definition, $req);
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
                return new OptionalPropertyDecorator($name, $prop);
            }
        }
        // ───────────────────────────────────────────────────────────────────────



        // ─── Strip out null arms from anyOf/oneOf and wrap the rest as an Optional<…> ──────
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

                /**
                 * ▌CASE A – exactly **one** remaining arm
                 * ▌        → build *that* schema directly
                 */
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

                    // and make it nullable
                    return new OptionalPropertyDecorator($name, $inner);
                }

                /**
                 * ▌CASE B – still multiple arms
                 * ▌        → keep a real UnionProperty
                 */
                $cleanDef             = $definition;
                $cleanDef[$unionKey]  = $otherArms;            // without the null arm
                $unionProp            = new UnionProperty($name, $cleanDef, $req);

                return new OptionalPropertyDecorator($name, $unionProp);
            }
        }
        // ────────────────────────────────────────────────────────────────────────────────────

        foreach (self::$propertyTypes as $propertyType) {
            if ($propertyType::canHandleSchema($definition)) {
                /** @var PropertyInterface $property */
                $property = new $propertyType($name, $definition, $req);

                if (isset($definition["default"]) && $req->getOptions()->getTreatValuesWithDefaultAsOptional()) {
                    $property = new DefaultPropertyDecorator($name, $property);
                } elseif (!$isRequired) {
                    $property = new OptionalPropertyDecorator($name, $property);
                }

                return $property;
            }
        }

        throw new GeneratorException("cannot map type " . json_encode($definition));
    }

    private static function testInvariants(array $definition): void
    {
        $hasAdditionalProperties = isset($definition["additionalProperties"]) && is_array($definition["additionalProperties"]) && count($definition["additionalProperties"]) > 0;
        $hasProperties = isset($definition["properties"]) && is_array($definition["properties"]) && count($definition["properties"]) > 0;

        if ($hasProperties && $hasAdditionalProperties) {
            throw new GeneratorException("using 'properties' and 'additionalProperties' in the same schema is currently not supported.");
        }
    }
}
