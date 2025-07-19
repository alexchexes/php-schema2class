<?php

declare(strict_types=1);

namespace Ns\MaterializeDefaultsNested_8_4;

class MyClassBar
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
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
    private string $nestedFoo;

    /**
     * @param string $nestedFoo
     */
    public function __construct(string $nestedFoo)
    {
        $this->nestedFoo = $nestedFoo;
    }

    /**
     * @return string
     */
    public function getNestedFoo() : string
    {
        return $this->nestedFoo;
    }

    /**
     * @param string $nestedFoo
     * @return self
     * @param bool $validate
     */
    public function withNestedFoo(string $nestedFoo, bool $validate = true) : self
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
    public static function buildFromInput(array|object $input, bool $validate = true) : MyClassBar
    {
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
    public function toArray() : array
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
    public static function validateInput(array|object $input, bool $return = false) : bool
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
