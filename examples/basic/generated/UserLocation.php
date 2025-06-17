<?php

declare(strict_types=1);

namespace Example\Basic;

class UserLocation
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'properties' => [
            'country' => [
                'type' => 'string',
            ],
            'city' => [
                'type' => 'string',
            ],
        ],
    ];

    /**
     * @var string|null
     */
    private ?string $country = null;

    /**
     * @var string|null
     */
    private ?string $city = null;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return string|null
     */
    public function getCountry() : ?string
    {
        return $this->country ?? null;
    }

    /**
     * @return string|null
     */
    public function getCity() : ?string
    {
        return $this->city ?? null;
    }

    /**
     * @param string $country
     * @return self
     */
    public function withCountry(string $country) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($country, self::$schema['properties']['country']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->country = $country;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutCountry() : self
    {
        $clone = clone $this;
        unset($clone->country);

        return $clone;
    }

    /**
     * @param string $city
     * @return self
     */
    public function withCity(string $city) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($city, self::$schema['properties']['city']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->city = $city;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutCity() : self
    {
        $clone = clone $this;
        unset($clone->city);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return UserLocation Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : UserLocation
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to buildFromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $country = isset($input->{'country'}) ? $input->{'country'} : null;
        $city = isset($input->{'city'}) ? $input->{'city'} : null;

        $obj = new self();
        $obj->country = $country;
        $obj->city = $city;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toJson() : array
    {
        $output = [];
        if (isset($this->country)) {
            $output['country'] = $this->country;
        }
        if (isset($this->city)) {
            $output['city'] = $this->city;
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
    public static function validateInput(array|object $input, bool $return = false) : bool
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

    public function __clone()
    {
    }
}

