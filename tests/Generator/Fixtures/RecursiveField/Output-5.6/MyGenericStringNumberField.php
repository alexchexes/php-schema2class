<?php

namespace Ns\RecursiveField_5_6;

class MyGenericStringNumberField
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
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
    private $field = null;

    /**
     * @return MyGenericStringNumber|null
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param MyGenericStringNumber $field
     * @return self
     */
    public function withField(MyGenericStringNumber $field)
    {
        $clone = clone $this;
        $clone->field = $field;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutField()
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
    public function toArray()
    {
        $output = [];
        if (isset($this->field)) {
            $output['field'] = $this->field->toArray();
        }

        return $output;
    }

    /**
     * Converts this object back to an stdClass that can be JSON-serialized
     *
     * @return \stdClass Converted object
     */
    public function toStdClass()
    {
        $array = $this->toArray();
        return json_decode(json_encode($array));
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
