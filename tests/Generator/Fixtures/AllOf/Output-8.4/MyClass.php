<?php

declare(strict_types=1);

namespace Ns\AllOf_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'required' => [
            'street_address',
            'state',
        ],
        'properties' => [
            'street_address' => [
                'type' => 'string',
                'description' => 'Descr for \'street_address\'',
            ],
            'house_number' => [
                'type' => 'string',
                'description' => 'Descr for \'house_number\'',
            ],
            'type' => [
                'enum' => [
                    'residential',
                    'business',
                ],
                'description' => 'Descr for \'type\'',
            ],
            'city' => [
                'type' => 'string',
                'description' => 'Descr for \'city\'',
            ],
            'state' => [
                'type' => 'string',
                'description' => 'Descr for \'state\'',
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'street_address' => 'streetAddress',
        'house_number' => 'houseNumber',
        'type' => 'type',
        'city' => 'city',
        'state' => 'state',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    private string $streetAddress;

    private ?string $houseNumber = null;

    private ?MyClassType $type = null;

    private ?string $city = null;

    private string $state;

    public function __construct(
        string $streetAddress,
        string $state,
        ?string $houseNumber = null,
        ?MyClassType $type = null,
        ?string $city = null
    ) {
        $this->_additionalProperties = new \stdClass();

        $this->streetAddress = $streetAddress;
        $this->state = $state;
        $this->houseNumber = $houseNumber;
        $this->type = $type;
        $this->city = $city;
    }

    /**
     * Object (`stdClass`) or array with name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return an associative array instead of `stdClass` object.
     */
    public function getAdditionalProperties(bool $asArray = true): \stdClass|array
    {
        return $asArray
            ? json_decode(json_encode($this->_additionalProperties), true)
            : $this->_additionalProperties;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param \stdClass|array $additionalProperties Map of property name/value pairs to add.
     */
    public function withAdditionalProperties(\stdClass|array $additionalProperties): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        return $clone;
    }

    /**
     * Removes all extra properties not specified in the schema.
     */
    public function withoutAdditionalProperties(): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = new \stdClass();
        return $clone;
    }

    /**
     * Descr for 'street_address'
     */
    public function getStreetAddress(): string
    {
        return $this->streetAddress;
    }

    /**
     * Descr for 'street_address'
     */
    public function withStreetAddress(string $streetAddress): self
    {
        $clone = clone $this;
        $clone->streetAddress = $streetAddress;

        return $clone;
    }

    /**
     * Descr for 'house_number'
     */
    public function getHouseNumber(): ?string
    {
        return $this->houseNumber ?? null;
    }

    /**
     * Descr for 'house_number'
     */
    public function withHouseNumber(string $houseNumber): self
    {
        $clone = clone $this;
        $clone->houseNumber = $houseNumber;

        return $clone;
    }

    public function withoutHouseNumber(): self
    {
        $clone = clone $this;
        unset($clone->houseNumber);

        return $clone;
    }

    /**
     * Descr for 'type'
     */
    public function getType(): ?MyClassType
    {
        return $this->type ?? null;
    }

    /**
     * Descr for 'type'
     */
    public function withType(MyClassType $type): self
    {
        $clone = clone $this;
        $clone->type = $type;

        return $clone;
    }

    public function withoutType(): self
    {
        $clone = clone $this;
        unset($clone->type);

        return $clone;
    }

    /**
     * Descr for 'city'
     */
    public function getCity(): ?string
    {
        return $this->city ?? null;
    }

    /**
     * Descr for 'city'
     */
    public function withCity(string $city): self
    {
        $clone = clone $this;
        $clone->city = $city;

        return $clone;
    }

    public function withoutCity(): self
    {
        $clone = clone $this;
        unset($clone->city);

        return $clone;
    }

    /**
     * Descr for 'state'
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Descr for 'state'
     */
    public function withState(string $state): self
    {
        $clone = clone $this;
        $clone->state = $state;

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $streetAddress = $input->{'street_address'};
        $state = $input->{'state'};
        $houseNumber = isset($input->{'house_number'}) ? $input->{'house_number'} : null;
        $type = isset($input->{'type'}) ? MyClassType::from($input->{'type'}) : null;
        $city = isset($input->{'city'}) ? $input->{'city'} : null;

        $obj = new self($streetAddress, $state, $houseNumber, $type, $city);

        $_additionalProperties = array_diff_key(get_object_vars($input), self::$_namesMap);
        if (!empty($_additionalProperties)) {
            $obj->_additionalProperties = (object) $_additionalProperties;
        }

        return $obj;
    }

    /**
     * Converts this object to array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

        $output['street_address'] = $this->streetAddress;
        if (isset($this->houseNumber)) {
            $output['house_number'] = $this->houseNumber;
        }
        if (isset($this->type)) {
            $output['type'] = $this->type->value;
        }
        if (isset($this->city)) {
            $output['city'] = $this->city;
        }
        $output['state'] = $this->state;

        return $output;
    }

    /**
     * Converts this object to a stdClass that can be JSON-serialized
     *
     * @return \stdClass Converted object
     */
    public function toStdClass(): \stdClass
    {
        $output = $this->_additionalProperties;

        $output->{'street_address'} = $this->streetAddress;
        if (isset($this->houseNumber)) {
            $output->{'house_number'} = $this->houseNumber;
        }
        if (isset($this->type)) {
            $output->{'type'} = $this->type->value;
        }
        if (isset($this->city)) {
            $output->{'city'} = $this->city;
        }
        $output->{'state'} = $this->state;

        return $output;
    }

    /**
     * Validates the current instance against its schema
     *
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result if `$return` is `true`
     * @throws \InvalidArgumentException
     */
    public function validate(bool $return = false): bool
    {
        return self::validateInput($this->toStdClass(), $return);
    }

    /**
     * Validates an input array
     *
     * @param array|object $input Input data
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result if `$return` is `true`
     * @throws \InvalidArgumentException
     */
    public static function validateInput(array|object $input, bool $return = false): bool
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(
                fn (array $e): string => ($e["property"] ? $e["property"] . ": " : "") . $e["message"],
                $validator->getErrors(),
            );
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }

    public function __clone()
    {
        $this->_additionalProperties = json_decode(json_encode($this->_additionalProperties));
    }
}
