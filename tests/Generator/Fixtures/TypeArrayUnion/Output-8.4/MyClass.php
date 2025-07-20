<?php

declare(strict_types=1);

namespace Ns\TypeArrayUnion_8_4;

class MyClass
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
     * @var string|MyClassFooAlternative2
     */
    private string|MyClassFooAlternative2 $foo;

    /**
     * @param string|MyClassFooAlternative2 $foo
     */
    public function __construct(MyClassFooAlternative2|string $foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return string|MyClassFooAlternative2
     */
    public function getFoo(): MyClassFooAlternative2|string
    {
        return $this->foo;
    }

    /**
     * @param string|MyClassFooAlternative2 $foo
     * @return self
     */
    public function withFoo(MyClassFooAlternative2|string $foo): self
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
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true): MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $foo = match (true) {
            is_string($input->{'foo'}) => $input->{'foo'},
            MyClassFooAlternative2::validateInput($input->{'foo'}, true) => MyClassFooAlternative2::buildFromInput($input->{'foo'}, $validate),
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
    public function toArray(): array
    {
        $output = [];
        $output['foo'] = match (true) {
            is_string($this->foo) => $this->foo,
            $this->foo instanceof MyClassFooAlternative2 => ($this->foo)->toArray(),
        };

        return $output;
    }

    /**
     * Converts this object back to a stdClass that can be JSON-encoded
     *
     * @return \stdClass Converted object
     */
    public function toObject(): \stdClass
    {
        $output = [];
        $output['foo'] = match (true) {
            is_string($this->foo) => $this->foo,
            $this->foo instanceof MyClassFooAlternative2 => ($this->foo)->toArray(),
        };

        return \JsonSchema\Validator::arrayToObjectRecursive($output);
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
            $this->foo instanceof MyClassFooAlternative2 => clone $this->foo,
        };
    }
}
