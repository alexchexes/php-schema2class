<?php

namespace Ns\RecursiveField_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
        'additionalProperties' => false,
        'properties' => [
            'value' => [
                '$ref' => '#/definitions/MyGeneric<string,number>',
            ],
        ],
        'required' => [
            'value',
        ],
        'type' => 'object',
        'definitions' => [
            'MyGeneric<string,number>' => [
                'additionalProperties' => false,
                'properties' => [
                    'field' => [
                        'additionalProperties' => false,
                        'properties' => [
                            'field' => [
                                '$ref' => '#/definitions/MyGeneric<string,number>',
                            ],
                        ],
                        'type' => 'object',
                    ],
                ],
                'required' => [
                    'field',
                ],
                'type' => 'object',
            ],
            'MyObject' => [
                'additionalProperties' => false,
                'properties' => [
                    'value' => [
                        '$ref' => '#/definitions/MyGeneric<string,number>',
                    ],
                ],
                'required' => [
                    'value',
                ],
                'type' => 'object',
            ],
            'MyRecursiveObject' => [
                'additionalProperties' => false,
                'properties' => [
                    'MyRecursiveObject' => [
                        '$ref' => '#/definitions/MyRecursiveObject',
                    ],
                ],
                'type' => 'object',
            ],
        ],
    ];

    private $value;

    public function __construct(MyGenericStringNumber $value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return self
     */
    public function withValue(MyGenericStringNumber $value)
    {
        $clone = clone $this;
        $clone->value = $value;

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

        $value = MyGenericStringNumber::fromInput($input->{'value'}, false);

        $obj = new self($value);

        return $obj;
    }

    /**
     * Converts this object to array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray()
    {
        $output = [];
        $output['value'] = $this->value->toArray();

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
        $output->{'value'} = $this->value->toStdClass();

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
            $errors = array_map(function(array $e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }

    public function __clone()
    {
        $this->value = clone $this->value;
    }
}
