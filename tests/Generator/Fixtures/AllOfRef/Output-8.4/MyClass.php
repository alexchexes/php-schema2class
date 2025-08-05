<?php

declare(strict_types=1);

namespace Ns\AllOfRef_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'required' => [
            'city',
            'street',
            'country',
        ],
        'properties' => [
            'city' => [
                'type' => 'string',
                'maxLength' => 32,
            ],
            'street' => [
                'type' => 'string',
            ],
            'country' => [
                'type' => 'string',
            ],
        ],
    ];

    private string $city;

    private string $street;

    private string $country;

    public function __construct(string $city, string $street, string $country)
    {
        $this->city = $city;
        $this->street = $street;
        $this->country = $country;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function withCity(string $city, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($city, self::$_schema['properties']['city']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->city = $city;

        return $clone;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function withStreet(string $street): self
    {
        $clone = clone $this;
        $clone->street = $street;

        return $clone;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function withCountry(string $country): self
    {
        $clone = clone $this;
        $clone->country = $country;

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $city = $input->{'city'};
        $street = $input->{'street'};
        $country = $input->{'country'};

        $obj = new self($city, $street, $country);

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
        $output['city'] = $this->city;
        $output['street'] = $this->street;
        $output['country'] = $this->country;

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
        $output->{'city'} = $this->city;
        $output->{'street'} = $this->street;
        $output->{'country'} = $this->country;

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
