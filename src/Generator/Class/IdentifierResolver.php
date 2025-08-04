<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\Class\Method\FromInputMethodFactory;
use Helmich\Schema2Class\Generator\Class\Method\SerializeMethodFactory;
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
        $reserved = array_merge(['this'], PropertyNames::all());
        $used = [];
        foreach ($reserved as $r) {
            $used[$r] = true;
        }
        
        // Sort for deterministic results when resolving conflicts
        $props = $properties->toArray();
        usort($props, fn($a, $b) => [$a->propName(), $a->key()] <=> [$b->propName(), $b->key()]);

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
            }
            $used[$prop->propName()] = true;
        }
    }

    private function resolveVarNames(PropertyCollection $properties): void
    {
        // Collect all variable names that must not be used
        $reserved = array_merge(
            ReservedNames::getBannedVarNames(),
            FromInputMethodFactory::allVarNames(),
            [
                'clone',
                SerializeMethodFactory::DEFAULTS_ARG_NAME,
                SerializeMethodFactory::OUTPUT_VAR_NAME,
            ],
        );
        $used = [];
        foreach ($reserved as $r) {
            $used[$r] = true;
        }

        // Sort by var name for deterministic results
        $props = $properties->toArray();
        usort($props, fn($a, $b) => [$a->varName(), $a->key()] <=> [$b->varName(), $b->key()]);

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

    private function resolveMethodNames(PropertyCollection $properties): void
    {
        // Update method name suffixes according to property names which might be renamed above
        foreach ($properties as $prop) {
            $propName = $prop->propName();
            $methodName = $this->request->getOptions()->getPreservePropertyNames()
                ? StringUtils::pascalCasePreserveOuterUnderscores($propName)
                : StringUtils::safePascalCase($propName);
            $prop->setMethodName($methodName);
        }

        // Determine what prefixes (get/with/set) are used based on configuration
        $prefixes = $this->determinePrefixes();
        // Reserve all banned method names (case-insensitive)
        $reserved = [];
        foreach (ReservedNames::getBannedMethodNames() as $n) {
            $reserved[strtolower($n)] = true;
        }

        $used = $reserved;
        // Sort by methodName
        $props = $properties->toArray();
        usort(
            $props,
            fn($a, $b) =>
                [strtolower($a->methodName()), $a->methodName(), $a->key()]
                <=> [strtolower($b->methodName()), $b->methodName(), $b->key()]
        );

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
