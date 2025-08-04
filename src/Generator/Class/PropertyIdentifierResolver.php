<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\Class\Method\FromInputMethodFactory;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollectionFilterFactory;
use Helmich\Schema2Class\Util\ReservedNames;
use Helmich\Schema2Class\Util\StringUtils;

/**
 * Resolves class property names, accessor method names and temporary variable names
 * for all collected schema properties.
 *
 * The resolver makes sure that all identifiers are unique and valid within their
 * respective namespaces (class property, method and local variable scope).
 */
class PropertyIdentifierResolver
{
    public function __construct(private GeneratorRequest $request)
    {
    }

    public function resolve(PropertyCollection $properties): void
    {
        $this->resolvePropertyNames($properties);
        $this->resolveMethodNames($properties);
        $this->resolveVarNames($properties);
    }

    private function resolvePropertyNames(PropertyCollection $properties): void
    {
        $reserved = array_merge(['this'], PropertyNames::all());

        $props = iterator_to_array($properties, false);
        usort($props, function ($a, $b) {
            $cmp = strcmp($a->name(), $b->name());
            if ($cmp !== 0) {
                return $cmp;
            }
            return strcmp($a->key(), $b->key());
        });

        $used = [];
        foreach ($reserved as $r) {
            $used[$r] = true;
        }

        foreach ($props as $prop) {
            $base = $prop->name();
            $candidate = $base;

            if (isset($used[$candidate])) {
                if ($base[0] !== '_') {
                    $candidate = '_' . $base;
                }
                $i = 1;
                $baseUnique = $candidate;
                while (isset($used[$candidate])) {
                    $candidate = $baseUnique . '_' . $i;
                    $i++;
                }
            }

            if ($candidate !== $base) {
                $prop->setName($candidate);
            }

            $prop->setVarName($prop->name());

            $used[$prop->name()] = true;
        }
    }

    private function resolveMethodNames(PropertyCollection $properties): void
    {
        $filtered = $properties->filter(
            PropertyCollectionFilterFactory::excludeDeprecatedCaseVariants($properties)
        );

        $props = iterator_to_array($filtered, false);
        usort($props, function ($a, $b) {
            $cmp = strcmp(strtolower($a->methodName()), strtolower($b->methodName()));
            if ($cmp !== 0) {
                return $cmp;
            }
            return strcmp($a->key(), $b->key());
        });

        $used = [];
        $prefixes = ['get', 'set', 'with', 'without', 'unset'];

        foreach ($props as $prop) {
            // recompute method name in case property name changed
            $methodName = $this->request->getOptions()->getPreservePropertyNames()
                ? StringUtils::pascalCasePreserveOuterUnderscores($prop->name())
                : StringUtils::safePascalCase($prop->name());
            $prop->setMethodName($methodName);

            $base = $prop->methodName();
            $candidate = $base;

            if ($this->methodNamesCollide($candidate, $used, $prefixes)) {
                if ($base[0] !== '_') {
                    $candidate = '_' . $base;
                }
                $i = 1;
                $baseUnique = $candidate;
                while ($this->methodNamesCollide($candidate, $used, $prefixes)) {
                    $candidate = $baseUnique . '_' . $i;
                    $i++;
                }
            }

            if ($candidate !== $base) {
                $prop->setMethodName($candidate);
            }

            foreach ($prefixes as $prefix) {
                $used[] = strtolower($prefix . $prop->methodName());
            }
        }
    }

    private function methodNamesCollide(string $candidate, array $used, array $prefixes): bool
    {
        foreach ($prefixes as $prefix) {
            if (in_array(strtolower($prefix . $candidate), $used, true)) {
                return true;
            }
        }
        return false;
    }

    private function resolveVarNames(PropertyCollection $properties): void
    {
        $reserved = array_merge(
            ReservedNames::getBannedVarNames(),
            FromInputMethodFactory::allVarNames()
        );

        $props = iterator_to_array($properties, false);
        usort($props, function ($a, $b) {
            $cmp = strcmp($a->varName(), $b->varName());
            if ($cmp !== 0) {
                return $cmp;
            }
            return strcmp($a->key(), $b->key());
        });

        $used = [];
        foreach ($reserved as $r) {
            $used[$r] = true;
        }

        foreach ($props as $prop) {
            $base = $prop->varName();
            $candidate = $base;

            if (isset($used[$candidate])) {
                if ($base[0] !== '_') {
                    $candidate = '_' . $base;
                }
                $i = 1;
                $baseUnique = $candidate;
                while (isset($used[$candidate])) {
                    $candidate = $baseUnique . '_' . $i;
                    $i++;
                }
            }

            if ($candidate !== $base) {
                $prop->setVarName($candidate);
            }

            $used[$prop->varName()] = true;
        }
    }
}

