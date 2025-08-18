<?php

declare(strict_types=1);

namespace Ns\ReferencedUnion_8_4;

class MyObject
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
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

    private A|B $foo;

    public function __construct(A|B $foo)
    {
        $this->foo = $foo;
    }

    public function getFoo(): A|B
    {
        return $this->foo;
    }

    public function withFoo(A|B $foo): self
    {
        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return MyObject Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): MyObject
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
     * Converts this object to array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = [];
        $output['foo'] = match (true) {
            $this->foo instanceof A || $this->foo instanceof B => $this->foo->value,
            default => $this->foo,
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
            $this->foo instanceof A || $this->foo instanceof B => $this->foo->value,
            default => $this->foo,
        };

        return $output;
    }

    /**
     * Validates the current instance against its schema
     *
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result if `$return` is `true`
     * @throws \InvalidArgumentException
     */
    public function validate(bool $return = false): bool
    {
        return self::validateInput($this->toStdClass(), $return);
    }

    /**
     * Validates an input array
     *
     * @param array|object $input Input data
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result if `$return` is `true`
     * @throws \InvalidArgumentException
     */
    public static function validateInput(array|object $input, bool $return = false): bool
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(
                fn (array $e): string => ($e["property"] ? $e["property"] . ": " : "") . $e["message"],
                $validator->getErrors(),
            );
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
