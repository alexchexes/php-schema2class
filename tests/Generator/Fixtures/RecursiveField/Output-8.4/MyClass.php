<?php

declare(strict_types=1);

namespace Ns\RecursiveField_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
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

    private MyGenericStringNumber $value;

    public function __construct(MyGenericStringNumber $value)
    {
        $this->value = $value;
    }

    public function getValue(): MyGenericStringNumber
    {
        return $this->value;
    }

    public function withValue(MyGenericStringNumber $value): self
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
    public static function fromInput(array|object $input, bool $validate = true): MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $value = MyGenericStringNumber::fromInput($input->{'value'}, $validate);

        $obj = new self($value);

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
        $output['value'] = $this->value->toArray();

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
