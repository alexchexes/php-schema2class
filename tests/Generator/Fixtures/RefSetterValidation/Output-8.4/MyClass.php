<?php

declare(strict_types=1);

namespace Ns\RefSetterValidation_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'foo' => [
                '$ref' => '#/definitions/Foo',
            ],
        ],
        'definitions' => [
            'Cat' => [
                'type' => 'object',
                'properties' => [
                    'foo' => [
                        '$ref' => '#/definitions/Bar',
                    ],
                    'bar' => [
                        'anyOf' => [
                            [
                                'type' => 'string',
                            ],
                            [
                                '$ref' => '#/definitions/Bar',
                            ],
                        ],
                    ],
                    'baz' => [
                        'type' => 'object',
                        'properties' => [
                            'nestedFoo' => [
                                '$ref' => '#/definitions/Bar',
                            ],
                        ],
                    ],
                ],
            ],
            'Foo' => [
                'type' => 'string',
                'enum' => [
                    'a',
                    'b',
                ],
            ],
            'Bar' => [
                'type' => 'number',
                'enum' => [
                    1,
                    2,
                ],
            ],
        ],
    ];

    /**
     * @var 'a'|'b'|null
     */
    private ?string $foo = null;

    /**
     * @param 'a'|'b'|null $foo
     */
    public function __construct(?string $foo = null)
    {
        $this->foo = $foo;
    }

    /**
     * @return 'a'|'b'|null
     */
    public function getFoo(): ?string
    {
        return $this->foo;
    }

    /**
     * @param 'a'|'b' $foo
     */
    public function withFoo(string $foo, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo, self::$_schema['properties']['foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    public function withoutFoo(): self
    {
        $clone = clone $this;
        unset($clone->foo);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;

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
        if (isset($this->foo)) {
            $output['foo'] = $this->foo;
        }

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
        if (isset($this->foo)) {
            $output->{'foo'} = $this->foo;
        }

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
            $errors = array_map(function(array $e): string {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
