<?php

declare(strict_types=1);

namespace Ns\ReferencedUnion_7_4;

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

    /**
     * @var 'foo'|'bar'|'baz'|'quz'
     */
    private string $foo;

    /**
     * @param 'foo'|'bar'|'baz'|'quz' $foo
     */
    public function __construct(string $foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return 'foo'|'bar'|'baz'|'quz'
     */
    public function getFoo(): string
    {
        return $this->foo;
    }

    /**
     * @param 'foo'|'bar'|'baz'|'quz' $foo
     */
    public function withFoo(string $foo, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->foo = $foo;
        if ($validate) {
            $clone->validate();
        }
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
    public static function fromInput($input, bool $validate = true): MyObject
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to fromInput must be array or object, got ' . gettype($input)
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
        if (in_array($this->foo, ['foo', 'bar'], true) || in_array($this->foo, ['baz', 'quz'], true)) {
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
        if (in_array($this->foo, ['foo', 'bar'], true) || in_array($this->foo, ['baz', 'quz'], true)) {
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
    public static function validateInput($input, bool $return = false): bool
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

    public function __clone()
    {
        $this->foo = (in_array($this->foo, ['baz', 'quz'], true)
            ? $this->foo
            : (in_array($this->foo, ['foo', 'bar'], true) ? $this->foo : $this->foo)
        );
    }
}
