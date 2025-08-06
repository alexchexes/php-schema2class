<?php

declare(strict_types=1);

namespace Ns\EnumWithNull_8_4;

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
                'type' => [
                    'null',
                    'string',
                ],
                'enum' => [
                    null,
                    'red',
                    'green',
                ],
            ],
        ],
    ];

    /**
     * Map of optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private array $_providedOptionals = [];

    /**
     * @var MyClassFoo|null
     */
    private ?MyClassFoo $foo = null;

    /**
     * @param MyClassFoo|null $foo
     */
    public function __construct(?MyClassFoo $foo = null)
    {
        $this->foo = $foo;
    }

    /**
     * @return MyClassFoo|null
     */
    public function getFoo(): ?MyClassFoo
    {
        return $this->foo;
    }

    /**
     * @param MyClassFoo|null $foo
     */
    public function withFoo(?MyClassFoo $foo): self
    {
        $clone = clone $this;
        $clone->foo = $foo;
        $clone->_providedOptionals['foo'] = true;

        return $clone;
    }

    public function withoutFoo(): self
    {
        $clone = clone $this;
        unset($clone->foo);
        unset($clone->_providedOptionals['foo']);

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

        $__providedOptionals = [];
        $foo = null;
        if (property_exists($input, 'foo')) {
            $foo = ($input->{'foo'} !== null ? MyClassFoo::from($input->{'foo'}) : null);
            $__providedOptionals['foo'] = true;
        }

        $obj = new self($foo);
        $obj->_providedOptionals = $__providedOptionals;
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
        if (isset($this->foo) || array_key_exists('foo', $this->_providedOptionals)) {
            $output['foo'] = ($this->foo !== null) ? (($this->foo)->value) : null;
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
        if (isset($this->foo) || array_key_exists('foo', $this->_providedOptionals)) {
            $output->{'foo'} = ($this->foo !== null) ? (($this->foo)->value) : null;
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

    /**
     * Checks if an optional nullable property was explicitly set
     *
     * @param string $propertyName Property name to check (exactly as it appears in the schema)
     * @return bool
     */
    public function isOptionalProvided(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->_providedOptionals);
    }
}
