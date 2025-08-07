<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Collection;

use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\PropertyQuery;

/** 
 * Convenience factory for commonly used {@see PropertyCollectionFilter} implementations.
 */
readonly class PropertyCollectionFilterFactory
{
    public static function excludeDeprecatedCaseVariants(PropertyCollection $properties): PropertyCollectionFilter
    {
        return new class($properties) implements PropertyCollectionFilter {
            private array $propertyNamesCaseInsensitive = [];

            public function __construct(PropertyCollection $properties)
            {
                foreach ($properties as $property) {
                    $caseInsensitiveName = strtolower($property->key());
                    if (!isset($this->propertyNamesCaseInsensitive[$caseInsensitiveName])) {
                        $this->propertyNamesCaseInsensitive[$caseInsensitiveName] = [];
                    }

                    $this->propertyNamesCaseInsensitive[$caseInsensitiveName][] = $property->key();
                }
            }

            public function apply(PropertyInterface $property): bool
            {
                $matchingProperties = $this->propertyNamesCaseInsensitive[strtolower($property->key())];

                $matchingPropertiesWithDifferentCase = array_filter(
                    $matchingProperties,
                    fn(string $name) => $name !== $property->key()
                );

                if (PropertyQuery::isDeprecated($property) && count($matchingPropertiesWithDifferentCase) > 0) {
                    return false;
                }

                return true;
            }
        };
    }

    public static function onlyOptional(): PropertyCollectionFilter
    {
        return new class implements PropertyCollectionFilter {
            public function apply(PropertyInterface $property): bool
            {
                return $property instanceof OptionalPropertyDecorator;
            }
        };
    }

    public static function onlyRequired(): PropertyCollectionFilter
    {
        return new class(self::onlyOptional()) implements PropertyCollectionFilter {
            public function __construct(private PropertyCollectionFilter $optional)
            {
            }

            public function apply(PropertyInterface $property): bool
            {
                return !$this->optional->apply($property);
            }
        };
    }
}