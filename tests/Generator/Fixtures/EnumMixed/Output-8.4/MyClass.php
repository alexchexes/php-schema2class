<?php

declare(strict_types=1);

namespace Ns\EnumMixed_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'properties' => [
            'foo' => [
                'type' => [
                    'integer',
                    'string',
                ],
                'enum' => [
                    1,
                    2,
                    '1',
                    '2',
                ],
            ],
            'bar' => [
                'enum' => [
                    3,
                    4,
                    '3',
                    '4',
                ],
            ],
            'baz' => [
                'enum' => [
                    'red',
                    'amber',
                    'green',
                    '42',
                    42,
                    42.5,
                    false,
                    null,
                ],
            ],
            'inferString' => [
                'enum' => [
                    '3',
                    '4',
                ],
            ],
            'inferInt' => [
                'enum' => [
                    3,
                    4,
                ],
            ],
            'contradiction' => [
                'type' => 'integer',
                'enum' => [
                    1,
                    'one',
                    false,
                    null,
                ],
            ],
            'contradiction2' => [
                'type' => [
                    'string',
                    'integer',
                    'number',
                ],
                'enum' => [
                    1,
                    2,
                    'one',
                    false,
                    null,
                ],
            ],
            'nullable' => [
                'type' => [
                    'string',
                    null,
                ],
                'enum' => [
                    'red',
                    'green',
                ],
            ],
            'optionalNullable' => [
                'type' => [
                    'string',
                    null,
                ],
                'enum' => [
                    'red',
                    'green',
                ],
            ],
        ],
        'required' => [
            'foo',
            'bar',
            'baz',
            'contradiction',
            'contradiction2',
            'nullable',
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'foo' => 'foo',
        'bar' => 'bar',
        'baz' => 'baz',
        'inferString' => 'inferString',
        'inferInt' => 'inferInt',
        'contradiction' => 'contradiction',
        'contradiction2' => 'contradiction2',
        'nullable' => 'nullable',
        'optionalNullable' => 'optionalNullable',
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

    /**
     * @var 1|2|'1'|'2'
     */
    private int|string $foo;

    /**
     * @var 3|4|'3'|'4'
     */
    private int|string $bar;

    /**
     * @var 'red'|'amber'|'green'|'42'|42|42.5|false|null
     */
    private bool|int|float|string|null $baz;

    private ?MyClassInferString $inferString = null;

    private ?MyClassInferInt $inferInt = null;

    private MyClassContradiction $contradiction;

    /**
     * @var 1|2|'one'
     */
    private int|string $contradiction2;

    private ?MyClassNullable $nullable;

    private ?MyClassOptionalNullable $optionalNullable = null;

    /**
     * @param 1|2|'1'|'2' $foo
     * @param 3|4|'3'|'4' $bar
     * @param 'red'|'amber'|'green'|'42'|42|42.5|false|null $baz
     * @param 1|2|'one' $contradiction2
     */
    public function __construct(int|string $foo, int|string $bar, bool|int|float|string|null $baz, MyClassContradiction $contradiction, int|string $contradiction2, ?MyClassNullable $nullable, ?MyClassInferString $inferString = null, ?MyClassInferInt $inferInt = null, ?MyClassOptionalNullable $optionalNullable = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->contradiction = $contradiction;
        $this->contradiction2 = $contradiction2;
        $this->nullable = $nullable;
        $this->inferString = $inferString;
        $this->inferInt = $inferInt;
        if ($optionalNullable !== null) {
            $this->optionalNullable = $optionalNullable;
            $this->_providedOptionals['optionalNullable'] = true;
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
    public function withAdditionalProperties(\stdClass|array $additionalProperties): self
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
     * @return 1|2|'1'|'2'
     */
    public function getFoo(): int|string
    {
        return $this->foo;
    }

    /**
     * @param 1|2|'1'|'2' $foo
     */
    public function withFoo(int|string $foo, bool $validate = true): self
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

    /**
     * @return 3|4|'3'|'4'
     */
    public function getBar(): int|string
    {
        return $this->bar;
    }

    /**
     * @param 3|4|'3'|'4' $bar
     */
    public function withBar(int|string $bar, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($bar, self::$_schema['properties']['bar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    /**
     * @return 'red'|'amber'|'green'|'42'|42|42.5|false|null
     */
    public function getBaz(): bool|int|float|string|null
    {
        return $this->baz;
    }

    /**
     * @param 'red'|'amber'|'green'|'42'|42|42.5|false|null $baz
     */
    public function withBaz(bool|int|float|string|null $baz, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($baz, self::$_schema['properties']['baz']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->baz = $baz;

        return $clone;
    }

    public function getInferString(): ?MyClassInferString
    {
        return $this->inferString ?? null;
    }

    public function withInferString(MyClassInferString $inferString): self
    {
        $clone = clone $this;
        $clone->inferString = $inferString;

        return $clone;
    }

    public function withoutInferString(): self
    {
        $clone = clone $this;
        unset($clone->inferString);

        return $clone;
    }

    public function getInferInt(): ?MyClassInferInt
    {
        return $this->inferInt ?? null;
    }

    public function withInferInt(MyClassInferInt $inferInt): self
    {
        $clone = clone $this;
        $clone->inferInt = $inferInt;

        return $clone;
    }

    public function withoutInferInt(): self
    {
        $clone = clone $this;
        unset($clone->inferInt);

        return $clone;
    }

    public function getContradiction(): MyClassContradiction
    {
        return $this->contradiction;
    }

    public function withContradiction(MyClassContradiction $contradiction): self
    {
        $clone = clone $this;
        $clone->contradiction = $contradiction;

        return $clone;
    }

    /**
     * @return 1|2|'one'
     */
    public function getContradiction2(): int|string
    {
        return $this->contradiction2;
    }

    /**
     * @param 1|2|'one' $contradiction2
     */
    public function withContradiction2(int|string $contradiction2, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($contradiction2, self::$_schema['properties']['contradiction2']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->contradiction2 = $contradiction2;

        return $clone;
    }

    public function getNullable(): ?MyClassNullable
    {
        return $this->nullable;
    }

    public function withNullable(?MyClassNullable $nullable): self
    {
        $clone = clone $this;
        $clone->nullable = $nullable;

        return $clone;
    }

    public function getOptionalNullable(): ?MyClassOptionalNullable
    {
        return $this->optionalNullable ?? null;
    }

    public function withOptionalNullable(?MyClassOptionalNullable $optionalNullable): self
    {
        $clone = clone $this;
        $clone->optionalNullable = $optionalNullable;
        $clone->_providedOptionals['optionalNullable'] = true;

        return $clone;
    }

    public function withoutOptionalNullable(): self
    {
        $clone = clone $this;
        unset($clone->optionalNullable);
        unset($clone->_providedOptionals['optionalNullable']);

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

        $_providedOptionals = [];
        $foo = $input->{'foo'};
        $bar = $input->{'bar'};
        $baz = ($input->{'baz'} !== null ? $input->{'baz'} : null);
        $contradiction = MyClassContradiction::from($input->{'contradiction'});
        $contradiction2 = $input->{'contradiction2'};
        $nullable = ($input->{'nullable'} !== null ? MyClassNullable::from($input->{'nullable'}) : null);
        $inferString = isset($input->{'inferString'})
            ? MyClassInferString::from($input->{'inferString'})
            : null;
        $inferInt = isset($input->{'inferInt'}) ? MyClassInferInt::from($input->{'inferInt'}) : null;
        $optionalNullable = null;
        if (property_exists($input, 'optionalNullable')) {
            $optionalNullable = ($input->{'optionalNullable'} !== null
                ? MyClassOptionalNullable::from($input->{'optionalNullable'})
                : null
            );
            $_providedOptionals['optionalNullable'] = true;
        }

        $obj = new self(
            $foo,
            $bar,
            $baz,
            $contradiction,
            $contradiction2,
            $nullable,
            $inferString,
            $inferInt,
            $optionalNullable
        );
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

        $output['foo'] = $this->foo;
        $output['bar'] = $this->bar;
        $output['baz'] = $this->baz;
        if (isset($this->inferString)) {
            $output['inferString'] = $this->inferString->value;
        }
        if (isset($this->inferInt)) {
            $output['inferInt'] = $this->inferInt->value;
        }
        $output['contradiction'] = $this->contradiction->value;
        $output['contradiction2'] = $this->contradiction2;
        $output['nullable'] = $this->nullable->value;
        if (isset($this->optionalNullable) || array_key_exists('optionalNullable', $this->_providedOptionals)) {
            $output['optionalNullable'] = ($this->optionalNullable !== null ? $this->optionalNullable->value : null);
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

        $output->{'foo'} = $this->foo;
        $output->{'bar'} = $this->bar;
        $output->{'baz'} = $this->baz;
        if (isset($this->inferString)) {
            $output->{'inferString'} = $this->inferString->value;
        }
        if (isset($this->inferInt)) {
            $output->{'inferInt'} = $this->inferInt->value;
        }
        $output->{'contradiction'} = $this->contradiction->value;
        $output->{'contradiction2'} = $this->contradiction2;
        $output->{'nullable'} = $this->nullable->value;
        if (isset($this->optionalNullable) || array_key_exists('optionalNullable', $this->_providedOptionals)) {
            $output->{'optionalNullable'} = ($this->optionalNullable !== null ? $this->optionalNullable->value : null);
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
