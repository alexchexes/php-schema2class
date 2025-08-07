<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\PropertyBuilder;

/**
 * Collects property from schema and build PropertyCollection instance.
 */
class SchemaPropertyCollector
{
    public static function collectPropertiesFromSchema(array $schema, GeneratorRequest $req): PropertyCollection
    {
        $properties = new PropertyCollection();

        if (isset($schema['properties'])) {
            foreach ($schema['properties'] as $key => $definition) {
                $key = (string) $key;
                $isRequired = isset($schema['required']) && in_array($key, $schema['required']);

                $property = PropertyBuilder::buildPropertyFromSchema($req, $key, $definition, $isRequired);
                $properties->add($property);
            }
        }

        return $properties;
    }
}
