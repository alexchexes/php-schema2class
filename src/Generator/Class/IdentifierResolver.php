<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\Class\Method\FromInputMethodFactory;
use Helmich\Schema2Class\Generator\Class\Method\SerializeMethodFactory;
use Helmich\Schema2Class\Generator\Class\Method\SetterFactory;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Util\ReservedNames;
use Helmich\Schema2Class\Util\StringUtils;

/**
 * Resolves identifiers (class property/method and variables names) for set 
 * of schema properties that will be used in the generated class.
 *
 * Ensures that class property names, accessor method suffixes and
 * temporary variable names do not collide with each other, with PHP
 * reserved identifiers or with internal helper variables.
 */
class IdentifierResolver
{
    public function __construct(private GeneratorRequest $request)
    {
    }

    /**
     * Adjusts property, method and variable names in the given collection.
     */
    public function resolve(PropertyCollection $properties): void
    {
        // Resolve property names first so var and method names can be derived from them
        $this->resolvePropertyNames($properties);
        $this->resolveVarNames($properties);
        $this->resolveMethodNames($properties);
    }

    private function resolvePropertyNames(PropertyCollection $properties): void
    {
        // Seed the set of already taken names with PHP internals and our own helpers
        $reserved = [
            ...PropertyNames::all(),
            'this'
        ];

        $used = [];
        foreach ($reserved as $r) {
            $used[$r] = true;
        }
        
        // Sort to get deterministic results
        $props = self::sortByKey($properties);

        foreach ($props as $prop) {
            // Try to use the original property name
            $base = $prop->propName();
            $candidate = $base;

            // If the name is taken, prefix with `_` and check,
            // if still not unique, append a counter until free
            if (isset($used[$candidate])) {
                if ($candidate[0] !== '_') {
                    $candidate = '_' . $candidate;
                }
                $i = 1;
                $baseUnique = $candidate;
                while (isset($used[$candidate])) {
                    $candidate = $baseUnique . '_' . $i;
                    $i++;
                }
            }

            // Set the new name and use it as a base for variable names
            if ($candidate !== $base) {
                $prop->setName($candidate);
                $prop->setVarName($prop->propName());

                $methodName = $this->request->getOptions()->getPreservePropertyNames()
                    ? StringUtils::pascalCasePreserveOuterUnderscores($prop->propName())
                    : StringUtils::safePascalCase($prop->propName());
                $prop->setMethodName($methodName);
            }
            $used[$prop->propName()] = true;
        }
    }

    private function resolveVarNames(PropertyCollection $properties): void
    {
        // Collect all variable names that must not be used
        $reserved = array_merge(
            ReservedNames::phpPredefined(),
            FromInputMethodFactory::allVarNames(),
            SerializeMethodFactory::allVarNames(),
            [SetterFactory::CLONE_VAR_NAME],
        );

        $used = [];
        foreach ($reserved as $r) {
            $used[$r] = true;
        }

        // Sort to get deterministic results
        $props = self::sortByKey($properties);

        foreach ($props as $prop) {
            // Start with the existing varName
            $base = $prop->varName();
            $candidate = $base;
            
            // Resolve collisions similarly to property names
            if (isset($used[$candidate])) {
                if ($candidate[0] !== '_') {
                    $candidate = '_' . $candidate;
                }
                $i = 1;
                $baseUnique = $candidate;
                while (isset($used[$candidate])) {
                    $candidate = $baseUnique . '_' . $i;
                    $i++;
                }
            }

            // Set to property's `varName`
            if ($candidate !== $base) {
                $prop->setVarName($candidate);
            }
            $used[$prop->varName()] = true;
        }
    }

    /**
     * Compares two property keys with `<=>` after moving leading underscore to the end.
     * Used for sorting to make underscored properties go after non-underscored
     */
    static public function sortByKey(PropertyCollection $properties)
    {
        $move_ = fn(string $str) => preg_replace('/^(_*)(.+)/', '$2$1', $str);
        $props = $properties->toArray();
        usort(
            $props,
            fn($propA, $propB) => $move_($propA->key()) <=> $move_($propB->key())
        );
        return $props;
    }

    private function resolveMethodNames(PropertyCollection $properties): void
    {
        // we don't check for collisions with php magick methods or our build-in methods like toArray(),
        // because we always prefix schema property names with one of our prefixes, thus collision is impossible

        // Determine what prefixes (get/with/set) are used based on configuration
        $prefixes = $this->determinePrefixes();

        // Sort to get deterministic results
        $props = self::sortByKey($properties);

        /** @var array<string,bool> $used */
        $used = [];

        // Helper to check if any prefix + suffix combination is already taken
        $conflicts = function(string $suffix) use (&$used, $prefixes): bool {
            foreach ($prefixes as $p) {
                if (isset($used[strtolower($p . $suffix)])) {
                    return true;
                }
            }
            return false;
        };

        // Helper to mark all prefixed versions of a suffix as taken
        $markUsed = function(string $suffix) use (&$used, $prefixes): void {
            foreach ($prefixes as $p) {
                $used[strtolower($p . $suffix)] = true;
            }
        };

        foreach ($props as $prop) {
            $base = $prop->methodName();
            $candidate = $base;
            // Resolve conflicts as with properties/variables
            if ($conflicts($candidate)) {
                if ($candidate[0] !== '_') {
                    $candidate = '_' . $candidate;
                }
                $i = 1;
                $baseUnique = $candidate;
                while ($conflicts($candidate)) {
                    $candidate = $baseUnique . '_' . $i;
                    $i++;
                }
            }
            if ($candidate !== $base) {
                $prop->setMethodName($candidate);
            }
            // Mark method name as used for all prefixes
            $markUsed($prop->methodName());
        }
    }

    private function determinePrefixes(): array
    {
        // Start with no prefixes and add those allowed by configuration
        $prefixes = [];
        if (!$this->request->getNoGetters()) {
            $prefixes[] = 'get';
        }
        if (!$this->request->getNoSetters()) {
            $mutable = $this->request->getMutableSetters();
            if ($mutable !== null) {
                $prefixes[] = 'set';
                $prefixes[] = 'unset';
            } else {
                $prefixes[] = 'with';
                $prefixes[] = 'without';
            }
        }
        return $prefixes;
    }
}
