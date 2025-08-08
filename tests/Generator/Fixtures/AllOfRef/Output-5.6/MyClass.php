<?php

namespace Ns\AllOfRef_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
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

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $country;

    /**
     * @param string $city
     * @param string $street
     * @param string $country
     */
    public function __construct($city, $street, $country)
    {
        $this->city = $city;
        $this->street = $street;
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @param bool $validate
     * @return self
     */
    public function withCity($city, $validate = true)
    {
        $clone = clone $this;
        $clone->city = $city;
        if ($validate) {
            $clone->validate();
        }

        return $clone;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @param bool $validate
     * @return self
     */
    public function withStreet($street, $validate = true)
    {
        $clone = clone $this;
        $clone->street = $street;
        if ($validate) {
            $clone->validate();
        }

        return $clone;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @param bool $validate
     * @return self
     */
    public function withCountry($country, $validate = true)
    {
        $clone = clone $this;
        $clone->country = $country;
        if ($validate) {
            $clone->validate();
        }

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
    public static function fromInput($input, $validate = true)
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
    public function toArray()
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
    public function toStdClass()
    {
        $output = new \stdClass();
        $output->{'city'} = $this->city;
        $output->{'street'} = $this->street;
        $output->{'country'} = $this->country;

        return $output;
    }

    /**
     * Validates the current instance against its schema
     *
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result if `$return` is `true`
     * @throws \InvalidArgumentException
     */
    public function validate($return = false)
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
    public static function validateInput($input, $return = false)
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function($e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
