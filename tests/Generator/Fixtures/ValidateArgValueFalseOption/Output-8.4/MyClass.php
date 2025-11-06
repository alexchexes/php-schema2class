<?php

declare(strict_types=1);

namespace Ns\ValidateArgValueFalseOption_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'foo' => [
                'enum' => [
                    'a',
                    1,
                ],
            ],
            'bar' => [
                '$ref' => '#/definitions/Baz',
            ],
        ],
        'additionalProperties' => [
            'type' => 'string',
        ],
        'maxProperties' => 10,
        'definitions' => [
            'Baz' => [
                'type' => 'object',
                'properties' => [
                    'name' => [
                        'enum' => [
                            'x',
                            2,
                        ],
                    ],
                ],
                'additionalProperties' => [
                    'type' => 'string',
                ],
            ],
            'maxProperties' => 10,
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'foo' => 'foo',
        'bar' => 'bar',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    /**
     * @var 'a'|1|null
     */
    private int|string|null $foo = null;

    private ?Baz $bar = null;

    /**
     * @param 'a'|1|null $foo
     */
    public function __construct(int|string|null $foo = null, ?Baz $bar = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->foo = $foo;
        $this->bar = $bar;
    }

    /**
     * Object (`stdClass`) or array with name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return an associative array instead of `stdClass` object.
     */
    public function getAdditionalProperties(bool $asArray = true): \stdClass|array
    {
        return $asArray
            ? json_decode(json_encode($this->_additionalProperties), true)
            : $this->_additionalProperties;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param \stdClass|array $additionalProperties Map of property name/value pairs to add.
     * @param bool $validate Whether to revalidate the resulting object.
     */
    public function withAdditionalProperties(\stdClass|array $additionalProperties, bool $validate = false): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    /**
     * Removes all extra properties not specified in the schema.
     *
     * @param bool $validate Whether to revalidate the resulting object.
     */
    public function withoutAdditionalProperties(bool $validate = false): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = new \stdClass();
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    /**
     * @return 'a'|1|null
     */
    public function getFoo(): int|string|null
    {
        return $this->foo ?? null;
    }

    /**
     * @param 'a'|1 $foo
     */
    public function withFoo(int|string $foo, bool $validate = false): self
    {
        $clone = clone $this;
        $clone->foo = $foo;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutFoo(bool $validate = false): self
    {
        $clone = clone $this;
        unset($clone->foo);
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function getBar(): ?Baz
    {
        return $this->bar ?? null;
    }

    public function withBar(Baz $bar, bool $validate = false): self
    {
        $clone = clone $this;
        $clone->bar = $bar;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutBar(bool $validate = false): self
    {
        $clone = clone $this;
        unset($clone->bar);
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
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = false): MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $bar = isset($input->{'bar'}) ? Baz::fromInput($input->{'bar'}, false) : null;

        $obj = new self($foo, $bar);

        $_additionalProperties = array_diff_key(get_object_vars($input), self::$_namesMap);
        if (!empty($_additionalProperties)) {
            $obj->_additionalProperties = (object) $_additionalProperties;
        }

        return $obj;
    }

    /**
     * Converts this object to array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

        if (isset($this->foo)) {
            $output['foo'] = $this->foo;
        }
        if (isset($this->bar)) {
            $output['bar'] = $this->bar->toArray();
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
        $output = $this->_additionalProperties;

        if (isset($this->foo)) {
            $output->{'foo'} = $this->foo;
        }
        if (isset($this->bar)) {
            $output->{'bar'} = $this->bar->toStdClass();
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
            $errors = array_map(
                fn (array $e): string => ($e["property"] ? $e["property"] . ": " : "") . $e["message"],
                $validator->getErrors(),
            );
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }

    public function __clone()
    {
        $this->_additionalProperties = json_decode(json_encode($this->_additionalProperties));

        if (isset($this->bar)) {
            $this->bar = clone $this->bar;
        }
    }
}
