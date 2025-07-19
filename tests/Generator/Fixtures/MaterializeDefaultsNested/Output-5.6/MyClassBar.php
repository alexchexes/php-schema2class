<?php

namespace Ns\MaterializeDefaultsNested_5_6;

class MyClassBar
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
        'type' => 'object',
        'properties' => [
            'nestedFoo' => [
                'type' => 'string',
            ],
        ],
        'required' => [
            'nestedFoo',
        ],
        'default' => [
            'nestedFoo' => 'some value inside default value for \'bar\' object',
        ],
    ];

    /**
     * @var string
     */
    private $nestedFoo;

    /**
     * @param string $nestedFoo
     */
    public function __construct($nestedFoo)
    {
        $this->nestedFoo = $nestedFoo;
    }

    /**
     * @return string
     */
    public function getNestedFoo()
    {
        return $this->nestedFoo;
    }

    /**
     * @param string $nestedFoo
     * @return self
     * @param bool $validate
     */
    public function withNestedFoo($nestedFoo, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($nestedFoo, self::$schema['properties']['nestedFoo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->nestedFoo = $nestedFoo;

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MyClassBar Created instance
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

        $nestedFoo = $input->{'nestedFoo'};

        $obj = new self($nestedFoo);

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
        $output['nestedFoo'] = $this->nestedFoo;

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
