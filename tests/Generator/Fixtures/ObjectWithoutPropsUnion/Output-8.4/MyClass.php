<?php

declare(strict_types=1);

namespace Ns\ObjectWithoutPropsUnion_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'properties' => [
            'foo' => [
                'type' => [
                    'string',
                    'object',
                ],
            ],
            'bar' => [
                'type' => [
                    'string',
                    'object',
                ],
            ],
        ],
        'required' => [
            'foo',
        ],
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private object $_additionalProperties;

    private string|array|object $foo;

    private string|array|object|null $bar = null;

    public function __construct(string|array|object $foo, string|array|object|null $bar = null)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param array|object $additionalProperties Map of property name/value pairs to add.
     */
    public function withAdditionalProperties(array|object $additionalProperties): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        return $clone;
    }

    public function getFoo(): string|array|object
    {
        return $this->foo;
    }

    public function withFoo(string|array|object $foo): self
    {
        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    public function getBar(): string|array|object|null
    {
        return $this->bar ?? null;
    }

    public function withBar(string|array|object $bar): self
    {
        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    public function withoutBar(): self
    {
        $clone = clone $this;
        unset($clone->bar);

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

        $foo = match (true) {
            is_string($input->{'foo'}),
            is_array($input->{'foo'}) || is_object($input->{'foo'}) => $input->{'foo'},
            default => throw new \InvalidArgumentException("could not build property 'foo' from JSON"),
        };
        $bar = isset($input->{'bar'}) ? match (true) {
            is_string($input->{'bar'}),
            is_array($input->{'bar'}) || is_object($input->{'bar'}) => $input->{'bar'},
            default => null,
        } : null;

        $obj = new self($foo, $bar);
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
            is_array($this->foo) || is_object($this->foo) => json_decode(json_encode($this->foo), true),
        };
        if (isset($this->bar)) {
            $output['bar'] = match (true) {
                is_string($this->bar) => $this->bar,
                is_array($this->bar) || is_object($this->bar) => json_decode(json_encode($this->bar), true),
            };
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
        $output->{'foo'} = match (true) {
            is_string($this->foo) => $this->foo,
            is_array($this->foo) || is_object($this->foo) => json_decode(json_encode($this->foo)),
        };
        if (isset($this->bar)) {
            $output->{'bar'} = match (true) {
                is_string($this->bar) => $this->bar,
                is_array($this->bar) || is_object($this->bar) => json_decode(json_encode($this->bar)),
            };
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

    public function __clone()
    {
        $this->foo = match (true) {
            is_string($this->foo),
            is_array($this->foo) || is_object($this->foo) => $this->foo,
        };
        if (isset($this->bar)) {
            $this->bar = match (true) {
                is_string($this->bar),
                is_array($this->bar) || is_object($this->bar) => $this->bar,
            };
        }
    }
}
