<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Type\DateProperty;
use Helmich\Schema2Class\Generator\Property\Type\IntersectProperty;
use Helmich\Schema2Class\Generator\Property\Type\NestedObjectProperty;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Generator\Property\Type\ReferenceProperty;
use Helmich\Schema2Class\Generator\Property\Type\StringEnumProperty;

/**
 * Helpers for quickly obtaining information about property metadata.
 */
class PropertyQuery
{
    /**
     * Checks whether the given property is marked as deprecated in its schema.
     */
    public static function isDeprecated(PropertyInterface $property): bool
    {
        $schema = $property->schema();
        return isset($schema["deprecated"]) && $schema["deprecated"];
    }

    /**
     * Determines if a setter for the given property should perform schema
     * validation. Validation is required when the schema contains additional
     * constraints that cannot be enforced by PHP's type system, or when the
     * target PHP version does not support type hints for the property's type.
     */
    public static function needsValidation(
        PropertyInterface $property,
        ?string $propTypeHint,
        GeneratorRequest $request,
    ): bool {
        // Properties backed by generated classes or by native PHP enums are
        // already fully validated by their type-hints. Validating these again
        // would either be redundant or even fail because the JSON validator
        // expects plain arrays/stdClass instances.
        if (
            $property instanceof ReferenceProperty
            || $property instanceof NestedObjectProperty
            || $property instanceof IntersectProperty
            || $property instanceof DateProperty
            || ($property instanceof StringEnumProperty
                && $request->isAtLeastPHP('8.1')
                && !$request->getNoEnums())
        ) {
            return false;
        }

        // If the schema declares validation keywords (enums, minLength, …)
        // we must validate regardless of available type hints.
        if (self::schemaHasValidation($property->schema())) {
            return true;
        }

        // No validation keywords present – rely on PHP type hints when
        // available. When targeting an old PHP version without type hints,
        // fall back to runtime validation.
        return $propTypeHint === null;
    }

    /**
     * Detects presence of validation-relevant keywords in a schema.
     */
    private static function schemaHasValidation(array $schema): bool
    {
        $keywords = [
            // Generic keywords
            'enum', 'const', 'pattern', 'format',
            // String specific
            'minLength', 'maxLength',
            // Number specific
            'minimum', 'exclusiveMinimum', 'maximum', 'exclusiveMaximum', 'multipleOf',
            // Array specific
            'items', 'additionalItems', 'minItems', 'maxItems', 'uniqueItems', 'contains', 'minContains', 'maxContains',
            // Object specific
            'properties', 'required', 'additionalProperties', 'propertyNames', 'minProperties', 'maxProperties', 'dependentRequired', 'dependencies', 'dependentSchemas', 'patternProperties',
            // Composition / conditional
            'allOf', 'anyOf', 'oneOf', 'not', 'if', 'then', 'else', 'unevaluatedItems', 'unevaluatedProperties', 'prefixItems',
        ];

        foreach ($keywords as $keyword) {
            if (!array_key_exists($keyword, $schema)) {
                continue;
            }

            switch ($keyword) {
                case 'allOf':
                case 'anyOf':
                case 'oneOf':
                    // Only treat as validation when any sub schema has
                    // additional constraints; otherwise PHP's type system can
                    // handle simple unions on its own.
                    foreach ($schema[$keyword] as $sub) {
                        if (self::schemaHasValidation($sub)) {
                            return true;
                        }
                    }
                    return false;

                case 'items':
                case 'additionalItems':
                    // The presence of an items schema requires validation of
                    // array elements, even if that schema is otherwise
                    // unconstrained.
                    return true;

                default:
                    return true;
            }
        }

        return false;
    }
}