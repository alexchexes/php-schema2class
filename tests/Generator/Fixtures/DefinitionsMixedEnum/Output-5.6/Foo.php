<?php

namespace Ns\DefinitionsMixedEnum_5_6;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
        'type' => 'object',
        'additionalProperties' => false,
        'properties' => [
            'val' => [
                '$ref' => '#/definitions/Mixed',
            ],
        ],
        'required' => [
            'val',
        ],
        'definitions' => [
            'Mixed' => [
                'enum' => [
                    5,
                    6,
                    '5',
                    '6',
                ],
                'type' => [
                    'integer',
                    'string',
                ],
            ],
        ],
    ];

    /**
     * @var 5|6|'5'|'6'
     */
    private $val;

    /**
     * @param 5|6|'5'|'6' $val
     */
    public function __construct($val)
    {
        $this->val = $val;
    }

    /**
     * @return 5|6|'5'|'6'
     */
    public function getVal()
    {
        return $this->val;
    }

    /**
     * @param 5|6|'5'|'6' $val
     * @return self
     * @param bool $validate
     */
    public function withVal($val, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($val, self::$schema['properties']['val']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->val = $val;

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return Foo Created instance
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

        $val = $input->{'val'};

        $obj = new self($val);

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
        $output['val'] = $this->val;

        return $output;
    }

    /**
     * Converts this object back to a stdClass that can be JSON-serialized
     *
     * @return stdClass Converted object
     */
    public function toObject()
    {
        $output = new \stdClass();
        $output->{'val'} = $this->val;

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
