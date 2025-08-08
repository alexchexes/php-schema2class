<?php

declare(strict_types=1);

namespace Ns\MutableSettersChainable_7_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'required' => [
            'bar',
        ],
        'properties' => [
            'foo' => [
                'type' => 'string',
            ],
            'bar' => [
                '$ref' => '#/definitions/Baz',
            ],
            'opt' => [
                'type' => [
                    'string',
                    'null',
                ],
            ],
        ],
        'definitions' => [
            'Baz' => [
                'type' => 'object',
                'properties' => [
                    'name' => [
                        'type' => 'string',
                    ],
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
     * Map of name/value pairs for properties not specified in the schema.
     */
    private object $_additionalProperties;

    private ?string $foo = null;

    private Baz $bar;

    private ?string $opt = null;

    public function __construct(Baz $bar, ?string $foo = null, ?string $opt = null)
    {
        $this->bar = $bar;
        $this->foo = $foo;
        $this->opt = $opt;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param array|object $additionalProperties Map of property name/value pairs to add.
     */
    public function setAdditionalProperties($additionalProperties): self
    {
        $this->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        return $this;
    }

    public function getFoo(): ?string
    {
        return $this->foo ?? null;
    }

    public function setFoo(string $foo): self
    {
        $this->foo = $foo;

        return $this;
    }

    public function unsetFoo(): self
    {
        unset($this->foo);

        return $this;
    }

    public function getBar(): Baz
    {
        return $this->bar;
    }

    public function setBar(Baz $bar): self
    {
        $this->bar = $bar;

        return $this;
    }

    public function getOpt(): ?string
    {
        return $this->opt ?? null;
    }

    public function setOpt(?string $opt): self
    {
        $this->opt = $opt;
        $this->_providedOptionals['opt'] = true;

        return $this;
    }

    public function unsetOpt(): self
    {
        unset($this->opt);
        unset($this->_providedOptionals['opt']);

        return $this;
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

        $__providedOptionals = [];
        $bar = Baz::fromInput($input->{'bar'}, $validate);
        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $opt = null;
        if (property_exists($input, 'opt')) {
            $opt = ($input->{'opt'} !== null ? $input->{'opt'} : null);
            $__providedOptionals['opt'] = true;
        }

        $obj = new self($bar, $foo, $opt);
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
        if (isset($this->foo)) {
            $output['foo'] = $this->foo;
        }
        $output['bar'] = $this->bar->toArray();
        if (isset($this->opt) || array_key_exists('opt', $this->_providedOptionals)) {
            $output['opt'] = ($this->opt !== null) ? ($this->opt) : null;
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
        $output->{'bar'} = $this->bar->toStdClass();
        if (isset($this->opt) || array_key_exists('opt', $this->_providedOptionals)) {
            $output->{'opt'} = ($this->opt !== null) ? ($this->opt) : null;
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
