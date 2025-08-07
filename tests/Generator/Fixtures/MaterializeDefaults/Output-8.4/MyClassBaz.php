<?php

declare(strict_types=1);

namespace Ns\MaterializeDefaults_8_4;

class MyClassBaz
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
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
        'definitions' => [
            'ObjDef' => [
                'type' => 'object',
                'description' => 'Definition of an object with default value that is empty object',
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                ],
                'default' => [
                    
                ],
            ],
            'ArrayDef' => [
                'type' => 'array',
                'description' => 'Definition of an array with default value that is empty array',
                'items' => [
                    'type' => 'string',
                ],
                'default' => [
                    
                ],
            ],
            'NumericKeysObj' => [
                'type' => 'object',
                'properties' => [
                    [
                        'type' => 'string',
                    ],
                    [
                        'type' => 'string',
                    ],
                    [
                        'type' => 'string',
                    ],
                ],
                'default' => [
                    'a default string for \'0\'',
                    'a default string for \'1\'',
                    'a default string for \'2\'',
                ],
            ],
        ],
    ];

    private string $nestedFoo;

    public function __construct(string $nestedFoo)
    {
        $this->nestedFoo = $nestedFoo;
    }

    public function getNestedFoo(): string
    {
        return $this->nestedFoo;
    }

    public function withNestedFoo(string $nestedFoo): self
    {
        $clone = clone $this;
        $clone->nestedFoo = $nestedFoo;

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return MyClassBaz Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): MyClassBaz
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
    public function toArray(): array
    {
        $output = [];
        $output['nestedFoo'] = $this->nestedFoo;

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
        $output->{'nestedFoo'} = $this->nestedFoo;

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
}
