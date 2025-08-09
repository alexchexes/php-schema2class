<?php

declare(strict_types=1);

namespace Ns\Descriptions_7_4;

/**
 * Simple object with two preperties and a description
 */
class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'type' => 'object',
        'description' => 'Simple object with two preperties and a description',
        'properties' => [
            'foo' => [
                'type' => 'string',
                'description' => 'Description for the `foo` property',
            ],
            'bar' => [
                '$ref' => '#/definitions/Baz',
            ],
        ],
        'definitions' => [
            'Baz' => [
                'type' => 'object',
                'properties' => [
                    'name' => [
                        'type' => 'string',
                        'description' => 'Description of the `name` property in `Baz` definition',
                    ],
                ],
                'description' => 'Description  of the `Baz` definition',
            ],
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

    private ?string $foo = null;

    private ?Baz $bar = null;

    public function __construct(?string $foo = null, ?Baz $bar = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->foo = $foo;
        $this->bar = $bar;
    }

    /**
     * Object (`stdClass`) or array with name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return an associative array instead of `stdClass` object.
     * @return array|\stdClass
     */
    public function getAdditionalProperties(bool $asArray = true)
    {
        return $asArray
            ? json_decode(json_encode($this->_additionalProperties), true)
            : $this->_additionalProperties;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param \stdClass|array $additionalProperties Map of property name/value pairs to add.
     */
    public function withAdditionalProperties($additionalProperties): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        return $clone;
    }

    /**
     * Removes all extra properties not specified in the schema.
     */
    public function withoutAdditionalProperties(): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = new \stdClass();
        return $clone;
    }

    /**
     * Description for the `foo` property
     */
    public function getFoo(): ?string
    {
        return $this->foo ?? null;
    }

    /**
     * Description for the `foo` property
     */
    public function withFoo(string $foo): self
    {
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

    public function getBar(): ?Baz
    {
        return $this->bar ?? null;
    }

    public function withBar(Baz $bar): self
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
    public static function fromInput($input, bool $validate = true): MyClass
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

        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $bar = isset($input->{'bar'}) ? Baz::fromInput($input->{'bar'}, $validate) : null;

        $obj = new self($foo, $bar);

        $_additionalProperties = array_diff_key(get_object_vars($input), self::$_namesMap);
        if (!empty($_additionalProperties)) {
            $obj->_additionalProperties = (object) $_additionalProperties;
        }

        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
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
}
