<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\PropertyBuilder;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Class\Method\FromInputMethodFactory;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Util\ReservedNames;
use Helmich\Schema2Class\Util\SchemaUtils;
use Helmich\Schema2Class\Util\StringUtils;

/**
 * Collects property information from a JSON schema and applies name sanitisation
 * and default value extraction.
 */
class SchemaPropertyCollector
{
    public function collectPropertiesFromSchema(array $schema, GeneratorRequest $req): PropertyCollection
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

        $this->ensureUniquePropertyNames(
            $properties,
            $req->getOptions()->getPreservePropertyNames(),
        );

        return $properties;
    }

    public function collectDefaults(array $schema, GeneratorRequest $req): array
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

    public function hasOptionalNullable(PropertyCollection $properties): bool
    {
        foreach ($properties as $p) {
            if ($p instanceof OptionalPropertyDecorator && $p->isOptionalNullable()) {
                return true;
            }
        }
        return false;
    }


    private static function extractDefault(array $def, GeneratorRequest $req, bool &$found = false, object|null $rawDef = null): array
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
            if ($type !== null && $type !== '') {
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
    
    private function ensureUniquePropertyNames(PropertyCollection $schemaProperties, bool $preservePropertyNames): void
    {
        $reservedPropertyNames = ReservedNames::getBannedVarNames();
        $reservedMethodNames = ReservedNames::getBannedMethodNames();

        $used = [];
        foreach (array_merge($reservedPropertyNames, $reservedMethodNames) as $n) {
            $used[] = StringUtils::safeCamelCase($n);
            $used[] = StringUtils::sanitizeIdentifier($n);
        }
        $used = array_values(array_unique($used));

        $usedMethods = [];
        foreach ($reservedMethodNames as $n) {
            $usedMethods[] = strtolower(StringUtils::safePascalCase(StringUtils::safeCamelCase($n)));
            $usedMethods[] = strtolower(StringUtils::safePascalCase(StringUtils::sanitizeIdentifier($n)));
        }
        $usedMethods = array_values(array_unique($usedMethods));

        foreach ($schemaProperties as $schemaProp) {
            $base    = $schemaProp->name();
            $newName = $base;
            $newPascal  = strtolower(StringUtils::safePascalCase($newName));

            // Of collides with "used",
            // OR if "preserve" option disabled and pascal-variant collides with "usedMethods" – change
            $needsChange = fn(string $_newName, string $_newPascal) => in_array($_newName, $used, true)
                || (!$preservePropertyNames && in_array($_newPascal, $usedMethods, true));

            if ($needsChange($newName, $newPascal)) {
                if ($base[0] !== '_') {
                    $newName = '_' . $base;
                    $newPascal = strtolower(StringUtils::safePascalCase($newName));
                }

                $i = 1;
                $baseUnique = $newName;

                while ($needsChange($newName, $newPascal)) {
                    $newName = $baseUnique . '_' . $i;
                    $newPascal = strtolower(StringUtils::safePascalCase($newName));
                    $i++;
                }
            }

            // rename the property
            if ($newName !== $base) {
                $schemaProp->setName($newName);
            }
            $schemaProp->setVarName($schemaProp->name());

            $used[]       = $newName;
            $usedMethods[] = $newPascal;
        }

        $usedVarNames = [
            FromInputMethodFactory::INPUT_ARG_NAME,
            FromInputMethodFactory::VALIDATE_ARG_NAME,
            FromInputMethodFactory::DEFAULTS_ARG_NAME,
            FromInputMethodFactory::OBJ_VAR_NAME,
            '_' . PropertyNames::OPTIONALS,
        ];

        foreach ($schemaProperties as $schemaProp) {
            $baseVar = $schemaProp->varName();
            $newVar = $baseVar;
            if (in_array($newVar, $usedVarNames, true)) {
                if ($baseVar[0] !== '_') {
                    $newVar = '_' . $baseVar;
                }
                $i = 1;
                $baseUnique = $newVar;
                while (in_array($newVar, $usedVarNames, true)) {
                    $newVar = $baseUnique . '_' . $i;
                    $i++;
                }
            }
            if ($newVar !== $baseVar) {
                $schemaProp->setVarName($newVar);
            }
            $usedVarNames[] = $newVar;
        }
    }
}
