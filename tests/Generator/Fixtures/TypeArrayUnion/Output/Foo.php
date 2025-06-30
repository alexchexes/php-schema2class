<?php

declare(strict_types=1);

namespace Ns\TypeArrayUnion;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'required' => [
            'foo',
        ],
        'properties' => [
            'foo' => [
                'type' => [
                    'string',
                    'object',
                ],
                'required' => [
                    'bar',
                ],
                'properties' => [
                    'bar' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var string|FooFooAlternative2
     */
    private string|FooFooAlternative2 $foo;

    /**
     * @param string|FooFooAlternative2 $foo
     */
    public function __construct(FooFooAlternative2|string $foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return string|FooFooAlternative2
     */
    public function getFoo() : FooFooAlternative2|string
    {
        return $this->foo;
    }

    /**
     * @param string|FooFooAlternative2 $foo
     * @return self
     */
    public function withFoo(FooFooAlternative2|string $foo) : self
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
     * @return Foo Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : Foo
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $foo = match (true) {
            is_string($input->{'foo'}) => $input->{'foo'},
            FooFooAlternative2::validateInput($input->{'foo'}, true) => FooFooAlternative2::buildFromInput($input->{'foo'}, validate: $validate),
            default => throw new \InvalidArgumentException("could not build property 'foo' from JSON"),
        };

        $obj = new self($foo);

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
        $output['foo'] = match (true) {
            is_string($this->foo) => $this->foo,
            $this->foo instanceof FooFooAlternative2 => ($this->foo)->toArray(),
        };

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

    public function __clone()
    {
        $this->foo = match (true) {
            is_string($this->foo) => $this->foo,
            $this->foo instanceof FooFooAlternative2 => clone $this->foo,
        };
    }
}
