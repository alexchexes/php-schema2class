<?php

namespace Ns\RecursiveField_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
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
        ],
    ];

    /**
     * @var MyGenericStringNumber
     */
    private $value;

    /**
     * @param MyGenericStringNumber $value
     */
    public function __construct(MyGenericStringNumber $value)
    {
        $this->value = $value;
    }

    /**
     * @return MyGenericStringNumber
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param MyGenericStringNumber $value
     * @return self
     */
    public function withValue(MyGenericStringNumber $value)
    {
        $clone = clone $this;
        $clone->value = $value;

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
    public static function buildFromInput($input, bool $validate = true)
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

        $value = MyGenericStringNumber::buildFromInput($input->{'value'}, $validate);

        $obj = new self($value);

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
        $output['value'] = $this->value->toArray();

        return $output;
    }

    /**
     * Converts this object back to a stdClass that can be JSON-encoded
     *
     * @return \stdClass Converted object
     */
    public function toObject()
    {
        $output = [];
        $output['value'] = $this->value->toArray();

        return \JsonSchema\Validator::arrayToObjectRecursive($output);
    }

    /**
     * Validates an input array
     *
     * @param array|object $input Input data
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result
     * @throws \InvalidArgumentException
     */
    public static function validateInput($input, $return = false)
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function($e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
