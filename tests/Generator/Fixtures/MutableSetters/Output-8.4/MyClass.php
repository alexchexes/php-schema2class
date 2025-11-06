<?php

declare(strict_types=1);

namespace Ns\MutableSetters_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
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
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'foo' => 'foo',
        'bar' => 'bar',
        'opt' => 'opt',
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
    private \stdClass $_additionalProperties;

    private ?string $foo = null;

    private Baz $bar;

    private ?string $opt = null;

    public function __construct(Baz $bar, ?string $foo = null, ?string $opt = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->bar = $bar;
        $this->foo = $foo;
        if ($opt !== null) {
            $this->opt = $opt;
            $this->_providedOptionals['opt'] = true;
        };
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
     */
    public function setAdditionalProperties(\stdClass|array $additionalProperties): void
    {
        $this->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;
    }

    /**
     * Removes all extra properties not specified in the schema.
     */
    public function unsetAdditionalProperties(): void
    {
        $this->_additionalProperties = new \stdClass();
    }

    public function getFoo(): ?string
    {
        return $this->foo ?? null;
    }

    public function setFoo(string $foo): void
    {
        $this->foo = $foo;
    }

    public function unsetFoo(): void
    {
        unset($this->foo);
    }

    public function getBar(): Baz
    {
        return $this->bar;
    }

    public function setBar(Baz $bar): void
    {
        $this->bar = $bar;
    }

    public function getOpt(): ?string
    {
        return $this->opt ?? null;
    }

    public function setOpt(?string $opt): void
    {
        $this->opt = $opt;
        $this->_providedOptionals['opt'] = true;
    }

    public function unsetOpt(): void
    {
        unset($this->opt);
        unset($this->_providedOptionals['opt']);
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

        $_providedOptionals = [];
        $bar = Baz::fromInput($input->{'bar'}, false);
        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $opt = null;
        if (property_exists($input, 'opt')) {
            $opt = $input->{'opt'};
            $_providedOptionals['opt'] = true;
        }

        $obj = new self($bar, $foo, $opt);
        $obj->_providedOptionals = $_providedOptionals;

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
        $output['bar'] = $this->bar->toArray();
        if (isset($this->opt) || array_key_exists('opt', $this->_providedOptionals)) {
            $output['opt'] = $this->opt;
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
        $output->{'bar'} = $this->bar->toStdClass();
        if (isset($this->opt) || array_key_exists('opt', $this->_providedOptionals)) {
            $output->{'opt'} = $this->opt;
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

        $this->bar = clone $this->bar;
    }

    /**
     * Checks if an optional nullable property was explicitly set.
     *
     * @param string $propertyName Property name to check (exactly as it appears in the schema).
     * @throws \InvalidArgumentException If property with that name doesn't exist.
     */
    public function isOptionalProvided(string $propertyName): bool
    {
        if (!array_key_exists($propertyName, self::$_namesMap)) {
            throw new \InvalidArgumentException("Unknown property: {$propertyName}");
        }
        return
            array_key_exists($propertyName, $this->_providedOptionals)
            || isset($this->{ self::$_namesMap[$propertyName] });
    }
}
