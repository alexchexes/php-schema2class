<?php

declare(strict_types=1);

namespace Ns\ReferencedUnion_7_4;

class MyObject
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'type' => 'object',
        'additionalProperties' => false,
        'properties' => [
            'foo' => [
                '$ref' => '#/definitions/C',
            ],
        ],
        'required' => [
            'foo',
        ],
        'definitions' => [
            'A' => [
                'enum' => [
                    'foo',
                    'bar',
                ],
                'type' => 'string',
            ],
            'B' => [
                'enum' => [
                    'baz',
                    'quz',
                ],
                'type' => 'string',
            ],
            'C' => [
                'anyOf' => [
                    [
                        '$ref' => '#/definitions/A',
                    ],
                    [
                        '$ref' => '#/definitions/B',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var 'foo'|'bar'|'baz'|'quz'
     */
    private $foo;

    /**
     * @param 'foo'|'bar'|'baz'|'quz' $foo
     */
    public function __construct($foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return 'foo'|'bar'|'baz'|'quz'
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param 'foo'|'bar'|'baz'|'quz' $foo
     * @return self
     */
    public function withFoo($foo) : self
    {
        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MyObject Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput($input, bool $validate = true): MyObject
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

        $foo = $input->{'foo'};

        $obj = new self($foo);

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
        if ((in_array($this->foo, array (
          0 => 'foo',
          1 => 'bar',
        ), true)) || (in_array($this->foo, array (
          0 => 'baz',
          1 => 'quz',
        ), true))) {
            $output['foo'] = $this->foo;
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

    public function __clone()
    {
        $this->foo = (in_array($this->foo, array (
          0 => 'baz',
          1 => 'quz',
        ), true)) ? ($this->foo) : ((in_array($this->foo, array (
          0 => 'foo',
          1 => 'bar',
        ), true)) ? ($this->foo) : ($this->foo));
    }
}
