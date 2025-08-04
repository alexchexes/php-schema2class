<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollectionFilterFactory;
use Helmich\Schema2Class\Generator\Class\Method\FromInputMethodFactory;
use Helmich\Schema2Class\Generator\Class\Method\SerializeMethodFactory;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Util\ReservedNames;
use Helmich\Schema2Class\Util\StringUtils;

class IdentifierResolver
{
    public function __construct(private GeneratorRequest $request)
    {
    }

    public function resolve(PropertyCollection $properties): void
    {
        $this->resolvePropertyNames($properties);
        $this->resolveVarNames($properties);
        $this->resolveMethodNames($properties);
    }

    private function resolvePropertyNames(PropertyCollection $properties): void
    {
        $reserved = array_merge(['this'], PropertyNames::all());
        $used = [];
        foreach ($reserved as $r) {
            $used[$r] = true;
        }

        $items = iterator_to_array($properties);
        usort($items, fn($a, $b) => [$a->name(), $a->key()] <=> [$b->name(), $b->key()]);
        foreach ($items as $prop) {
            $base = $prop->name();
            $candidate = $base;
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
            if ($candidate !== $base) {
                $prop->setName($candidate);
            }
            $prop->setVarName($prop->name());
            $used[$prop->name()] = true;
        }
    }

    private function resolveVarNames(PropertyCollection $properties): void
    {
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

        $items = iterator_to_array($properties);
        usort($items, fn($a, $b) => [$a->varName(), $a->key()] <=> [$b->varName(), $b->key()]);
        foreach ($items as $prop) {
            $base = $prop->varName();
            $candidate = $base;
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
            if ($candidate !== $base) {
                $prop->setVarName($candidate);
            }
            $used[$prop->varName()] = true;
        }
    }

    private function resolveMethodNames(PropertyCollection $properties): void
    {
        foreach ($properties as $prop) {
            $propName = $prop->name();
            $methodName = $this->request->getOptions()->getPreservePropertyNames()
                ? StringUtils::pascalCasePreserveOuterUnderscores($propName)
                : StringUtils::safePascalCase($propName);
            $prop->setMethodName($methodName);
        }

        $filtered = $properties->filter(
            PropertyCollectionFilterFactory::excludeDeprecatedCaseVariants($properties)
        );

        $prefixes = $this->determinePrefixes();
        $reserved = [];
        foreach (ReservedNames::getBannedMethodNames() as $n) {
            $reserved[strtolower($n)] = true;
        }

        $used = $reserved;
        $items = iterator_to_array($filtered);
        usort($items, fn($a, $b) => [strtolower($a->methodName()), $a->methodName(), $a->key()] <=> [strtolower($b->methodName()), $b->methodName(), $b->key()]);

        $conflicts = function(string $suffix) use (&$used, $prefixes): bool {
            foreach ($prefixes as $p) {
                if (isset($used[strtolower($p . $suffix)])) {
                    return true;
                }
            }
            return false;
        };

        $markUsed = function(string $suffix) use (&$used, $prefixes): void {
            foreach ($prefixes as $p) {
                $used[strtolower($p . $suffix)] = true;
            }
        };

        foreach ($items as $prop) {
            $base = $prop->methodName();
            $candidate = $base;
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
            $markUsed($prop->methodName());
        }
    }

    private function determinePrefixes(): array
    {
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
        // always include with/without to account for unsetter when noSetters=false but property optional? Actually above covers.
        return $prefixes;
    }
}
