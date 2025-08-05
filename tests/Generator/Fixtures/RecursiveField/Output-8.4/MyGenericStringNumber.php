<?php

declare(strict_types=1);

namespace Ns\RecursiveField_8_4;

class MyGenericStringNumber
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
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

    /**
     * @var MyGenericStringNumberField
     */
    private MyGenericStringNumberField $field;

    /**
     * @param MyGenericStringNumberField $field
     */
    public function __construct(MyGenericStringNumberField $field)
    {
        $this->field = $field;
    }

    /**
     * @return MyGenericStringNumberField
     */
    public function getField(): MyGenericStringNumberField
    {
        return $this->field;
    }

    /**
     * @param MyGenericStringNumberField $field
     */
    public function withField(MyGenericStringNumberField $field): self
    {
        $clone = clone $this;
        $clone->field = $field;

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MyGenericStringNumber Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): MyGenericStringNumber
    {
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
    public function toArray(): array
    {
        $output = [];
        $output['field'] = ($this->field)->toArray();

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
        $output->{'field'} = ($this->field)->toStdClass();

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

    public function __clone()
    {
        $this->field = clone $this->field;
    }
}
