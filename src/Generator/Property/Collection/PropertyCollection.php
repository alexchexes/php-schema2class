<?php
declare(strict_types=1);
namespace Helmich\Schema2Class\Generator\Property\Collection;

use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;

/**
 * Iterable list of {@see PropertyInterface} instances (which represent schema properties)
 * with helper methods for code generation.
 * 
 * @template-implements \Iterator<PropertyInterface>
 */
class PropertyCollection implements \Iterator
{
    /** @var PropertyInterface[] */
    private array $properties = [];

    private int $current = 0;

    public static function fromArray(array $properties): PropertyCollection
    {
        $collection = new PropertyCollection();
        $collection->properties = array_values($properties);
        return $collection;
    }

    /** @return PropertyInterface[] */
    public function toArray(): array
    {
        return iterator_to_array($this);
    }

    public function add(PropertyInterface $propertyGenerator): void
    {
        $this->properties[] = $propertyGenerator;
    }

    public function generateInputToTypeConversionCode(): string
    {
        $conv = array_map(fn ($p) => $p->convertInputToType(), $this->properties);
        return join("\n", $conv);
    }

    public function generateTypeToArrayConversionCode(): string
    {
        $conv = array_map(fn ($p) => $p->convertTypeToArray(), $this->properties);
        return join("\n", $conv);
    }

    public function generateTypeToStdClassConversionCode(): string
    {
        $conv = array_map(fn ($p) => $p->convertTypeToStdClass(), $this->properties);
        return join("\n", $conv);
    }

    public function hasPropertyWithKey(string $key): bool
    {
        foreach ($this->properties as $p) {
            if ($p->key() === $key) {
                return true;
            }
        }

        return false;
    }

    public function hasPropertyWithName(string $propName): bool
    {
        foreach ($this->properties as $p) {
            if ($p->propName() === $propName) {
                return true;
            }
        }

        return false;
    }

    public function filter(PropertyCollectionFilter $filter): PropertyCollection
    {
        $matching = [];

        foreach ($this->properties as $property) {
            if ($filter->apply($property)) {
                $matching[] = $property;
            }
        }

        return PropertyCollection::fromArray($matching);
    }

    public function isOptional(PropertyInterface $prop): bool
    {
        return $prop instanceof OptionalPropertyDecorator;
    }

    public function current(): PropertyInterface
    {
        return $this->properties[$this->current];
    }

    public function next(): void
    {
        $this->current ++;
    }

    public function key(): int
    {
        return $this->current;
    }

    public function valid(): bool
    {
        return $this->current < count($this->properties);
    }

    public function rewind(): void
    {
        $this->current = 0;
    }


}
