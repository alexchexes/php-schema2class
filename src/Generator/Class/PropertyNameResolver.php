<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollectionFilterFactory;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Generator\Class\Method\FromInputMethodFactory;
use Helmich\Schema2Class\Util\ReservedNames;
use Helmich\Schema2Class\Util\StringUtils;

/**
 * Resolves name collisions for properties, accessor methods and temporary
 * variables in a deterministic way.
 */
class PropertyNameResolver
{
    public function __construct(private GeneratorRequest $request)
    {
    }

    /**
     * Adjusts property, method and variable names in the given collection.
     */
    public function resolve(PropertyCollection $properties): void
    {
        $this->resolvePropertyNames($properties);
        $this->resolveVarNames($properties);
        $this->resolveMethodNames($properties);
    }

    private function resolvePropertyNames(PropertyCollection $properties): void
    {
        $reserved = array_flip(ReservedNames::getBannedPropertyNames());

        $props = iterator_to_array($properties);
        usort($props, fn(PropertyInterface $a, PropertyInterface $b) =>
            [$a->name(), $a->key()] <=> [$b->name(), $b->key()]
        );

        foreach ($props as $property) {
            $base = $property->name();
            $new  = $base;

            if (isset($reserved[$new])) {
                if ($base[0] !== '_') {
                    $new = '_' . $base;
                }
            }

            $i = 1;
            $baseUnique = $new;
            while (isset($reserved[$new])) {
                $new = $baseUnique . '_' . $i;
                $i++;
            }

            if ($new !== $base) {
                $property->setName($new);
            }

            $reserved[$new] = true;
        }
    }

    private function resolveVarNames(PropertyCollection $properties): void
    {
        foreach ($properties as $property) {
            $property->setVarName($property->name());
        }

        $reserved = array_flip(array_merge(
            ReservedNames::getBannedVarNames(),
            FromInputMethodFactory::allVarNames(),
        ));

        $props = iterator_to_array($properties);
        usort($props, fn(PropertyInterface $a, PropertyInterface $b) =>
            [$a->varName(), $a->key()] <=> [$b->varName(), $b->key()]
        );

        foreach ($props as $property) {
            $base = $property->varName();
            $new  = $base;

            if (isset($reserved[$new])) {
                if ($base[0] !== '_') {
                    $new = '_' . $base;
                }
            }

            $i = 1;
            $baseUnique = $new;
            while (isset($reserved[$new])) {
                $new = $baseUnique . '_' . $i;
                $i++;
            }

            if ($new !== $base) {
                $property->setVarName($new);
            }

            $reserved[$new] = true;
        }
    }

    private function resolveMethodNames(PropertyCollection $properties): void
    {
        $preserve = $this->request->getOptions()->getPreservePropertyNames();
        foreach ($properties as $property) {
            $name = $property->name();
            $methodName = $preserve
                ? StringUtils::pascalCasePreserveOuterUnderscores($name)
                : StringUtils::safePascalCase($name);
            $property->setMethodName($methodName);
        }

        $filtered = $properties->filter(
            PropertyCollectionFilterFactory::excludeDeprecatedCaseVariants($properties)
        );

        $reserved = array_flip(array_map('strtolower', ReservedNames::getBannedMethodNames()));

        $props = iterator_to_array($filtered);
        usort($props, fn(PropertyInterface $a, PropertyInterface $b) =>
            [strtolower($a->methodName()), $a->key()] <=> [strtolower($b->methodName()), $b->key()]
        );

        foreach ($props as $property) {
            $base = $property->methodName();
            $new  = $base;

            $candidates = $this->buildCandidateMethodNames($property, $new);
            $i = 1;
            while ($this->hasUsedCandidate($candidates, $reserved)) {
                $new = $base . '_' . $i;
                $i++;
                $candidates = $this->buildCandidateMethodNames($property, $new);
            }

            if ($new !== $base) {
                $property->setMethodName($new);
            }

            foreach ($candidates as $candidate) {
                $reserved[strtolower($candidate)] = true;
            }
        }
    }

    /**
     * @param PropertyInterface $property
     * @param string $base
     * @return string[]
     */
    private function buildCandidateMethodNames(PropertyInterface $property, string $base): array
    {
        $names = [];

        if (!$this->request->getNoGetters()) {
            $names[] = 'get' . $base;
        }

        if (!$this->request->getNoSetters()) {
            $mutableConfig = $this->request->getMutableSetters();
            $mutating = $mutableConfig !== null;

            $names[] = ($mutating ? 'set' : 'with') . $base;

            if ($mutating) {
                if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
                    $names[] = 'unset' . $base;
                }
            } else {
                if ($property instanceof OptionalPropertyDecorator) {
                    $names[] = 'without' . $base;
                }
            }
        }

        return $names;
    }

    /**
     * @param string[] $candidates
     * @param array<string,bool> $used
     */
    private function hasUsedCandidate(array $candidates, array $used): bool
    {
        foreach ($candidates as $c) {
            if (isset($used[strtolower($c)])) {
                return true;
            }
        }
        return false;
    }
}

