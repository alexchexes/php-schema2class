<?php

declare(strict_types=1);

namespace Ns\AdditionalPropertiesRoot_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'number' => [
                'type' => 'number',
            ],
            'street_name' => [
                'type' => 'string',
            ],
            'street_type' => [
                'enum' => [
                    'Street',
                    'Avenue',
                    'Boulevard',
                ],
            ],
        ],
        'additionalProperties' => [
            'type' => 'string',
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'number' => 'number',
        'street_name' => 'streetName',
        'street_type' => 'streetType',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    private int|float|null $number = null;

    private ?string $streetName = null;

    private ?MyClassStreetType $streetType = null;

    public function __construct(int|float|null $number = null, ?string $streetName = null, ?MyClassStreetType $streetType = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->number = $number;
        $this->streetName = $streetName;
        $this->streetType = $streetType;
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
     * @param bool $validate Whether to revalidate the resulting object.
     */
    public function withAdditionalProperties(\stdClass|array $additionalProperties, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    /**
     * Removes all extra properties not specified in the schema.
     *
     * @param bool $validate Whether to revalidate the resulting object.
     */
    public function withoutAdditionalProperties(bool $validate = true): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = new \stdClass();
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function getNumber(): int|float|null
    {
        return $this->number ?? null;
    }

    public function withNumber(int|float $number): self
    {
        $clone = clone $this;
        $clone->number = $number;

        return $clone;
    }

    public function withoutNumber(): self
    {
        $clone = clone $this;
        unset($clone->number);

        return $clone;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName ?? null;
    }

    public function withStreetName(string $streetName): self
    {
        $clone = clone $this;
        $clone->streetName = $streetName;

        return $clone;
    }

    public function withoutStreetName(): self
    {
        $clone = clone $this;
        unset($clone->streetName);

        return $clone;
    }

    public function getStreetType(): ?MyClassStreetType
    {
        return $this->streetType ?? null;
    }

    public function withStreetType(MyClassStreetType $streetType): self
    {
        $clone = clone $this;
        $clone->streetType = $streetType;

        return $clone;
    }

    public function withoutStreetType(): self
    {
        $clone = clone $this;
        unset($clone->streetType);

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

        $number = isset($input->{'number'}) ? $input->{'number'} : null;
        $streetName = isset($input->{'street_name'}) ? $input->{'street_name'} : null;
        $streetType = isset($input->{'street_type'}) ? MyClassStreetType::from($input->{'street_type'}) : null;

        $obj = new self($number, $streetName, $streetType);

        $_additionalProperties = array_diff_key(get_object_vars($input), self::$_namesMap);
        if (!empty($_additionalProperties)) {
            $obj->_additionalProperties = (object) $_additionalProperties;
        }

        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

        if (isset($this->number)) {
            $output['number'] = $this->number;
        }
        if (isset($this->streetName)) {
            $output['street_name'] = $this->streetName;
        }
        if (isset($this->streetType)) {
            $output['street_type'] = ($this->streetType)->value;
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
        $output = $this->_additionalProperties;

        if (isset($this->number)) {
            $output->{'number'} = $this->number;
        }
        if (isset($this->streetName)) {
            $output->{'street_name'} = $this->streetName;
        }
        if (isset($this->streetType)) {
            $output->{'street_type'} = ($this->streetType)->value;
        }

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
