<?php

namespace Ns\Definitions_5_6;

class Address
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
        'type' => 'object',
        'properties' => [
            'name' => [
                '$ref' => '#/definitions/address/$defs/name',
            ],
            'city' => [
                'type' => 'string',
            ],
        ],
        'required' => [
            'city',
        ],
        '$defs' => [
            'name' => [
                'type' => 'object',
                'properties' => [
                    'first' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     *
     * @var object
     */
    private $_additionalProperties;

    /**
     * @var Address\Defs\Name|null
     */
    private $name = null;

    /**
     * @var string
     */
    private $city;

    /**
     * @param string $city
     * @param Address\Defs\Name|null $name
     */
    public function __construct($city, Address\Defs\Name $name = null)
    {
        $this->city = $city;
        $this->name = $name;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param array|object $additionalProperties Map of property name/value pairs to add.
     * @return self
     */
    public function withAdditionalProperties($additionalProperties)
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        return $clone;
    }

    /**
     * @return Address\Defs\Name|null
     */
    public function getName()
    {
        return isset($this->name) ? $this->name : null;
    }

    /**
     * @return self
     */
    public function withName(Address\Defs\Name $name)
    {
        $clone = clone $this;
        $clone->name = $name;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutName()
    {
        $clone = clone $this;
        unset($clone->name);

        return $clone;
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

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Address Created instance
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
        $name = isset($input->{'name'}) ? Address\Defs\Name::fromInput($input->{'name'}, $validate) : null;

        $obj = new self($city, $name);
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
        if (isset($this->name)) {
            $output['name'] = $this->name->toArray();
        }
        $output['city'] = $this->city;

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
        if (isset($this->name)) {
            $output->{'name'} = $this->name->toStdClass();
        }
        $output->{'city'} = $this->city;

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
