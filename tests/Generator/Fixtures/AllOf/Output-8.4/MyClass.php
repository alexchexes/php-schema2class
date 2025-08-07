<?php

declare(strict_types=1);

namespace Ns\AllOf_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
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

    private string $streetAddress;

    private ?string $houseNumber = null;

    private ?MyClassType $type = null;

    private ?string $city = null;

    private string $state;

    public function __construct(string $streetAddress, string $state, ?string $houseNumber = null, ?MyClassType $type = null, ?string $city = null)
    {
        $this->streetAddress = $streetAddress;
        $this->state = $state;
        $this->houseNumber = $houseNumber;
        $this->type = $type;
        $this->city = $city;
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
        return $this->houseNumber;
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
        return $this->type;
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
        return $this->city;
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
        $output['street_address'] = $this->streetAddress;
        if (isset($this->houseNumber)) {
            $output['house_number'] = $this->houseNumber;
        }
        if (isset($this->type)) {
            $output['type'] = ($this->type)->value;
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
        $output = new \stdClass();
        $output->{'street_address'} = $this->streetAddress;
        if (isset($this->houseNumber)) {
            $output->{'house_number'} = $this->houseNumber;
        }
        if (isset($this->type)) {
            $output->{'type'} = ($this->type)->value;
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
            $errors = array_map(function(array $e): string {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
