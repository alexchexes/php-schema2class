<?php

declare(strict_types=1);

namespace Ns\RecursiveField_7_4;

class MyGenericStringNumberField
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
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

    /**
     * @var MyGenericStringNumber|null
     */
    private ?MyGenericStringNumber $field = null;

    /**
     * @return MyGenericStringNumber|null
     */
    public function getField(): ?MyGenericStringNumber
    {
        return $this->field ?? null;
    }

    /**
     * @param MyGenericStringNumber $field
     * @return self
     */
    public function withField(MyGenericStringNumber $field): self
    {
        $clone = clone $this;
        $clone->field = $field;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutField(): self
    {
        $clone = clone $this;
        unset($clone->field);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MyGenericStringNumberField Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput($input, bool $validate = true): MyGenericStringNumberField
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

        $field = isset($input->{'field'}) ? MyGenericStringNumber::buildFromInput($input->{'field'}, $validate) : null;

        $obj = new self();
        $obj->field = $field;
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
        if (isset($this->field)) {
            $output['field'] = $this->field->toArray();
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
    public static function validateInput($input, bool $return = false): bool
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
}
