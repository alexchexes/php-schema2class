<?php

namespace Ns\RecursiveField_5_6;

class MyGenericStringNumber
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
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
        ],
    ];

    private $field;

    public function __construct(MyGenericStringNumberField $field)
    {
        $this->field = $field;
    }

    public function getField()
    {
        return $this->field;
    }

    /**
     * @return self
     */
    public function withField(MyGenericStringNumberField $field)
    {
        $clone = clone $this;
        $clone->field = $field;

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return MyGenericStringNumber Created instance
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

        $field = MyGenericStringNumberField::fromInput($input->{'field'}, $validate);

        $obj = new self($field);
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
        $output['field'] = $this->field->toArray();

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
        $output->{'field'} = $this->field->toStdClass();

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

    public function __clone()
    {
        $this->field = clone $this->field;
    }
}
