<?php

declare(strict_types=1);

namespace Example\Basic;

class Address
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = ['type' => 'object', 'properties' => ['street' => ['type' => 'string'], 'house' => ['type' => 'integer']]];

    private ?string $street = null;

    private ?int $house = null;

    public function __construct(?string $street = null, ?int $house = null)
    {
        $this->street = $street;
        $this->house = $house;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function withStreet(string $street): self
    {
        $clone = clone $this;
        $clone->street = $street;

        return $clone;
    }

    public function withoutStreet(): self
    {
        $clone = clone $this;
        unset($clone->street);

        return $clone;
    }

    public function getHouse(): ?int
    {
        return $this->house;
    }

    public function withHouse(int $house): self
    {
        $clone = clone $this;
        $clone->house = $house;

        return $clone;
    }

    public function withoutHouse(): self
    {
        $clone = clone $this;
        unset($clone->house);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Address Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput($input, bool $validate = true): Address
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to fromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }


        $street = isset($input->{'street'}) ? $input->{'street'} : null;
        $house = isset($input->{'house'}) ? $input->{'house'} : null;

        $obj = new self($street, $house);
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = [];
        if (isset($this->street)) {
            $output['street'] = $this->street;
        }
        if (isset($this->house)) {
            $output['house'] = $this->house;
        }

        return $output;
    }

    /**
     * Converts this object to a stdClass that can be JSON-serialized
     *
     * @return \stdClass Converted object
     */
    public function toStdClass(): \stdClass
    {
        $output = new \stdClass();
        if (isset($this->street)) {
            $output->{'street'} = $this->street;
        }
        if (isset($this->house)) {
            $output->{'house'} = $this->house;
        }

        return $output;
    }

    /**
     * Validates an input array
     *
     * @param array|object $input Input data
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result
     * @throws \InvalidArgumentException
     */
    public static function validateInput($input, bool $return = false): bool
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function(array $e): string {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}

