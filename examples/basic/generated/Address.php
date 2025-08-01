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
    private static array $schema = ['type' => 'object', 'properties' => ['street' => ['type' => 'string'], 'house' => ['type' => 'integer']]];

    /**
     * @var string|null
     */
    private ?string $street = null;

    /**
     * @var int|null
     */
    private ?int $house = null;

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street ?? null;
    }

    /**
     * @return int|null
     */
    public function getHouse(): ?int
    {
        return $this->house ?? null;
    }

    /**
     * @param string $street
     * @return self
     * @param bool $validate
     */
    public function withStreet(string $street, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($street, self::$schema['properties']['street']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->street = $street;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutStreet(): self
    {
        $clone = clone $this;
        unset($clone->street);

        return $clone;
    }

    /**
     * @param int $house
     * @return self
     * @param bool $validate
     */
    public function withHouse(int $house, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($house, self::$schema['properties']['house']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->house = $house;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutHouse(): self
    {
        $clone = clone $this;
        unset($clone->house);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
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

        $obj = new self();
        $obj->street = $street;
        $obj->house = $house;
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
        $validator->validate($input, self::$schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function(array $e): string {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}

