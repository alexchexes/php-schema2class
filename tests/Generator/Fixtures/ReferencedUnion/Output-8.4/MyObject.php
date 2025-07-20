<?php

declare(strict_types=1);

namespace Ns\ReferencedUnion_8_4;

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
     * @var A|B
     */
    private A|B $foo;

    /**
     * @param A|B $foo
     */
    public function __construct(A|B $foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return A|B
     */
    public function getFoo(): A|B
    {
        return $this->foo;
    }

    /**
     * @param A|B $foo
     * @return self
     */
    public function withFoo(A|B $foo): self
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
    public static function buildFromInput(array|object $input, bool $validate = true): MyObject
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $foo = match (true) {
            A::tryFrom($input->{'foo'}) !== null => A::from($input->{'foo'}),
            B::tryFrom($input->{'foo'}) !== null => B::from($input->{'foo'}),
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
            ($this->foo) instanceof A,
            ($this->foo) instanceof B => $this->foo->value,
        };

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
        $output->{'foo'} = match (true) {
            ($this->foo) instanceof A,
            ($this->foo) instanceof B => $this->foo->value,
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
            ($this->foo) instanceof A,
            ($this->foo) instanceof B => $this->foo,
        };
    }
}
