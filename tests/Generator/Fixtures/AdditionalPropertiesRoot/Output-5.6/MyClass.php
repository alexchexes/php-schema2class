<?php

namespace Ns\AdditionalPropertiesRoot_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
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
            'type' => [
                'string',
                'null',
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     *
     * @var array
     */
    private static $_namesMap = [
        'number' => 'number',
        'street_name' => 'streetName',
        'street_type' => 'streetType',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     *
     * @var \stdClass
     */
    private $_additionalProperties;

    /**
     * @var int|float|null
     */
    private $number = null;

    /**
     * @var string|null
     */
    private $streetName = null;

    /**
     * @var 'Street'|'Avenue'|'Boulevard'|null
     */
    private $streetType = null;

    /**
     * @param int|float|null $number
     * @param string|null $streetName
     * @param 'Street'|'Avenue'|'Boulevard'|null $streetType
     */
    public function __construct($number = null, $streetName = null, $streetType = null)
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
     * @return array|\stdClass
     */
    public function getAdditionalProperties($asArray = true)
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
     * @return self
     */
    public function withAdditionalProperties($additionalProperties, $validate = true)
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
     * @return self
     */
    public function withoutAdditionalProperties($validate = true)
    {
        $clone = clone $this;
        $clone->_additionalProperties = new \stdClass();
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    /**
     * @return int|float|null
     */
    public function getNumber()
    {
        return isset($this->number) ? $this->number : null;
    }

    /**
     * @param int|float $number
     * @param bool $validate
     * @return self
     */
    public function withNumber($number, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($number, self::$_schema['properties']['number']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->number = $number;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutNumber()
    {
        $clone = clone $this;
        unset($clone->number);

        return $clone;
    }

    /**
     * @return string|null
     */
    public function getStreetName()
    {
        return isset($this->streetName) ? $this->streetName : null;
    }

    /**
     * @param string $streetName
     * @param bool $validate
     * @return self
     */
    public function withStreetName($streetName, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($streetName, self::$_schema['properties']['street_name']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->streetName = $streetName;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutStreetName()
    {
        $clone = clone $this;
        unset($clone->streetName);

        return $clone;
    }

    /**
     * @return 'Street'|'Avenue'|'Boulevard'|null
     */
    public function getStreetType()
    {
        return isset($this->streetType) ? $this->streetType : null;
    }

    /**
     * @param 'Street'|'Avenue'|'Boulevard' $streetType
     * @param bool $validate
     * @return self
     */
    public function withStreetType($streetType, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($streetType, self::$_schema['properties']['street_type']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->streetType = $streetType;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutStreetType()
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

        $number = isset($input->{'number'}) ? $input->{'number'} : null;
        $streetName = isset($input->{'street_name'}) ? $input->{'street_name'} : null;
        $streetType = isset($input->{'street_type'}) ? $input->{'street_type'} : null;

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
    public function toArray()
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

        if (isset($this->number)) {
            $output['number'] = $this->number;
        }
        if (isset($this->streetName)) {
            $output['street_name'] = $this->streetName;
        }
        if (isset($this->streetType)) {
            $output['street_type'] = $this->streetType;
        }

        return $output;
    }

    /**
     * Converts this object to a stdClass that can be JSON-serialized
     *
     * @return \stdClass Converted object
     */
    public function toStdClass()
    {
        $output = $this->_additionalProperties;

        if (isset($this->number)) {
            $output->{'number'} = $this->number;
        }
        if (isset($this->streetName)) {
            $output->{'street_name'} = $this->streetName;
        }
        if (isset($this->streetType)) {
            $output->{'street_type'} = $this->streetType;
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
