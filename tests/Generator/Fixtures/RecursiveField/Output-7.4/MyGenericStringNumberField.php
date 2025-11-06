<?php

declare(strict_types=1);

namespace Ns\RecursiveField_7_4;

class MyGenericStringNumberField
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'additionalProperties' => false,
        'properties' => [
            'field' => [
                '$ref' => '#/definitions/MyGeneric<string,number>',
            ],
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

    private ?MyGenericStringNumber $field = null;

    public function __construct(?MyGenericStringNumber $field = null)
    {
        $this->field = $field;
    }

    public function getField(): ?MyGenericStringNumber
    {
        return $this->field ?? null;
    }

    public function withField(MyGenericStringNumber $field): self
    {
        $clone = clone $this;
        $clone->field = $field;

        return $clone;
    }

    public function withoutField(): self
    {
        $clone = clone $this;
        unset($clone->field);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return MyGenericStringNumberField Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput($input, bool $validate = true): MyGenericStringNumberField
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

        $field = isset($input->{'field'})
            ? MyGenericStringNumber::fromInput($input->{'field'}, false)
            : null;

        $obj = new self($field);

        return $obj;
    }

    /**
     * Converts this object to array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = [];
        if (isset($this->field)) {
            $output['field'] = $this->field->toArray();
        }

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
        if (isset($this->field)) {
            $output->{'field'} = $this->field->toStdClass();
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
    public static function validateInput($input, bool $return = false): bool
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(
                fn (array $e): string => ($e["property"] ? $e["property"] . ": " : "") . $e["message"],
                $validator->getErrors(),
            );
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }

    public function __clone()
    {
        if (isset($this->field)) {
            $this->field = clone $this->field;
        }
    }
}
