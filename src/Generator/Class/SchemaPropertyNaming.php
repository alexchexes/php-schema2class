<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollectionFilterFactory;
use Helmich\Schema2Class\Generator\Class\Method\FromInputMethodFactory;
use Helmich\Schema2Class\Util\ReservedNames;
use Helmich\Schema2Class\Util\StringUtils;

/**
 * Resolves unique identifiers for schema properties.
 *
 * Ensures that class property names, accessor method suffixes and
 * temporary variable names do not collide with each other, with PHP
 * reserved identifiers or with internal helper variables.
 */
class SchemaPropertyNaming
{
    public function __construct(private GeneratorRequest $request)
    {
    }

    public function resolve(PropertyCollection $schemaProperties): void
    {
        $this->ensureUniquePropertyNames($schemaProperties);

        foreach ($schemaProperties as $schemaProp) {
            $schemaProp->setVarName($schemaProp->name());
            $methodName = $this->request->getOptions()->getPreservePropertyNames()
                ? StringUtils::pascalCasePreserveOuterUnderscores($schemaProp->name())
                : StringUtils::safePascalCase($schemaProp->name());
            $schemaProp->setMethodName($methodName);
        }

        $this->ensureUniqueMethodNames($schemaProperties);
        $this->ensureUniqueVarNames($schemaProperties);
    }

    private function ensureUniquePropertyNames(PropertyCollection $schemaProperties): void
    {
        $reserved = array_merge(['this'], PropertyNames::all());
        $used = $reserved;

        $props = iterator_to_array($schemaProperties);
        usort($props, function($a, $b) {
            return strcmp($a->name(), $b->name()) ?: strcmp($a->key(), $b->key());
        });

        foreach ($props as $prop) {
            $base = $prop->name();
            $new = $base;
            $needsChange = function(string $candidate) use (&$used): bool {
                return in_array($candidate, $used, true);
            };

            if ($needsChange($new)) {
                if ($base[0] !== '_') {
                    $new = '_' . $base;
                }
                $i = 1;
                $baseUnique = $new;
                while ($needsChange($new)) {
                    $new = $baseUnique . '_' . $i;
                    $i++;
                }
            }

            if ($new !== $base) {
                $prop->setName($new);
            }

            $used[] = $new;
        }
    }

    private function ensureUniqueMethodNames(PropertyCollection $schemaProperties): void
    {
        $filtered = $schemaProperties->filter(
            PropertyCollectionFilterFactory::excludeDeprecatedCaseVariants($schemaProperties)
        );

        $props = iterator_to_array($filtered);
        usort($props, function($a, $b) {
            $cmp = strcmp(strtolower($a->methodName()), strtolower($b->methodName()));
            if ($cmp !== 0) {
                return $cmp;
            }
            return strcmp($a->key(), $b->key());
        });

        $prefixes = ['get', 'set', 'unset', 'with', 'without'];
        $used = [];

        foreach ($props as $prop) {
            $base = $prop->methodName();
            $candidate = $base;

            $needsChange = function(string $cand) use (&$used, $prefixes): bool {
                foreach ($prefixes as $p) {
                    if (in_array(strtolower($p . $cand), $used, true)) {
                        return true;
                    }
                }
                return false;
            };

            $i = 1;
            while ($needsChange($candidate)) {
                $candidate = $base . '_' . $i;
                $i++;
            }

            if ($candidate !== $base) {
                $prop->setMethodName($candidate);
            }

            foreach ($prefixes as $p) {
                $used[] = strtolower($p . $candidate);
            }
        }
    }

    private function ensureUniqueVarNames(PropertyCollection $schemaProperties): void
    {
        $reserved = ReservedNames::getBannedVarNames();
        $used = FromInputMethodFactory::allVarNames();

        $props = iterator_to_array($schemaProperties);
        usort($props, function($a, $b) {
            return strcmp($a->varName(), $b->varName()) ?: strcmp($a->key(), $b->key());
        });

        foreach ($props as $prop) {
            $base = $prop->varName();
            $candidate = $base;

            $needsChange = function(string $cand) use (&$used, $reserved): bool {
                return in_array($cand, $used, true) || in_array($cand, $reserved, true);
            };

            if ($needsChange($candidate)) {
                if ($base[0] !== '_') {
                    $candidate = '_' . $base;
                }
                $i = 1;
                $baseUnique = $candidate;
                while ($needsChange($candidate)) {
                    $candidate = $baseUnique . '_' . $i;
                    $i++;
                }
            }

            if ($candidate !== $base) {
                $prop->setVarName($candidate);
            }

            $used[] = $candidate;
        }
    }
}
