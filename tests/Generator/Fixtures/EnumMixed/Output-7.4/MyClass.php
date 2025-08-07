<?php

declare(strict_types=1);

namespace Ns\EnumMixed_7_4;

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
     * Map of optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private array $_providedOptionals = [];

    /**
     * @var 1|2|'1'|'2'
     */
    private $foo;

    /**
     * @var 3|4|'3'|'4'
     */
    private $bar;

    /**
     * @var 'red'|'amber'|'green'|'42'|42|42.5|false|null
     */
    private $baz;

    /**
     * @var '3'|'4'|null
     */
    private ?string $inferString = null;

    /**
     * @var 3|4|null
     */
    private ?int $inferInt = null;

    /**
     * @var 1
     */
    private int $contradiction;

    /**
     * @var 1|2|'one'
     */
    private $contradiction2;

    /**
     * @var 'red'|'green'|null
     */
    private ?string $nullable;

    /**
     * @var 'red'|'green'|null
     */
    private ?string $optionalNullable = null;

    /**
     * @param 1|2|'1'|'2' $foo
     * @param 3|4|'3'|'4' $bar
     * @param 'red'|'amber'|'green'|'42'|42|42.5|false|null $baz
     * @param 1 $contradiction
     * @param 1|2|'one' $contradiction2
     * @param 'red'|'green'|null $nullable
     * @param '3'|'4'|null $inferString
     * @param 3|4|null $inferInt
     * @param 'red'|'green'|null $optionalNullable
     */
    public function __construct($foo, $bar, $baz, int $contradiction, $contradiction2, ?string $nullable, ?string $inferString = null, ?int $inferInt = null, ?string $optionalNullable = null)
    {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->contradiction = $contradiction;
        $this->contradiction2 = $contradiction2;
        $this->nullable = $nullable;
        $this->inferString = $inferString;
        $this->inferInt = $inferInt;
        $this->optionalNullable = $optionalNullable;
    }

    /**
     * @return 1|2|'1'|'2'
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param 1|2|'1'|'2' $foo
     */
    public function withFoo($foo, bool $validate = true): self
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
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param 3|4|'3'|'4' $bar
     */
    public function withBar($bar, bool $validate = true): self
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
    public function getBaz()
    {
        return $this->baz;
    }

    /**
     * @param 'red'|'amber'|'green'|'42'|42|42.5|false|null $baz
     */
    public function withBaz($baz, bool $validate = true): self
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

    /**
     * @return '3'|'4'|null
     */
    public function getInferString(): ?string
    {
        return $this->inferString;
    }

    /**
     * @param '3'|'4' $inferString
     */
    public function withInferString(string $inferString, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inferString, self::$_schema['properties']['inferString']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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

    /**
     * @return 3|4|null
     */
    public function getInferInt(): ?int
    {
        return $this->inferInt;
    }

    /**
     * @param 3|4 $inferInt
     */
    public function withInferInt(int $inferInt, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inferInt, self::$_schema['properties']['inferInt']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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

    /**
     * @return 1
     */
    public function getContradiction(): int
    {
        return $this->contradiction;
    }

    /**
     * @param 1 $contradiction
     */
    public function withContradiction(int $contradiction, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($contradiction, self::$_schema['properties']['contradiction']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->contradiction = $contradiction;

        return $clone;
    }

    /**
     * @return 1|2|'one'
     */
    public function getContradiction2()
    {
        return $this->contradiction2;
    }

    /**
     * @param 1|2|'one' $contradiction2
     */
    public function withContradiction2($contradiction2, bool $validate = true): self
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

    /**
     * @return 'red'|'green'|null
     */
    public function getNullable(): ?string
    {
        return $this->nullable;
    }

    /**
     * @param 'red'|'green'|null $nullable
     */
    public function withNullable(?string $nullable, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($nullable, self::$_schema['properties']['nullable']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->nullable = $nullable;

        return $clone;
    }

    /**
     * @return 'red'|'green'|null
     */
    public function getOptionalNullable(): ?string
    {
        return $this->optionalNullable;
    }

    /**
     * @param 'red'|'green'|null $optionalNullable
     */
    public function withOptionalNullable(?string $optionalNullable, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optionalNullable, self::$_schema['properties']['optionalNullable']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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
        $foo = $input->{'foo'};
        $bar = $input->{'bar'};
        $baz = ($input->{'baz'} !== null ? $input->{'baz'} : null);
        $contradiction = (int)$input->{'contradiction'};
        $contradiction2 = $input->{'contradiction2'};
        $nullable = ($input->{'nullable'} !== null ? $input->{'nullable'} : null);
        $inferString = isset($input->{'inferString'}) ? $input->{'inferString'} : null;
        $inferInt = isset($input->{'inferInt'}) ? $input->{'inferInt'} : null;
        $optionalNullable = null;
        if (property_exists($input, 'optionalNullable')) {
            $optionalNullable = ($input->{'optionalNullable'} !== null ? $input->{'optionalNullable'} : null);
            $__providedOptionals['optionalNullable'] = true;
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
        $output['foo'] = $this->foo;
        $output['bar'] = $this->bar;
        $output['baz'] = $this->baz;
        if (isset($this->inferString)) {
            $output['inferString'] = $this->inferString;
        }
        if (isset($this->inferInt)) {
            $output['inferInt'] = $this->inferInt;
        }
        $output['contradiction'] = $this->contradiction;
        $output['contradiction2'] = $this->contradiction2;
        $output['nullable'] = $this->nullable;
        if (isset($this->optionalNullable) || array_key_exists('optionalNullable', $this->_providedOptionals)) {
            $output['optionalNullable'] = ($this->optionalNullable !== null) ? ($this->optionalNullable) : null;
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
        $output->{'foo'} = $this->foo;
        $output->{'bar'} = $this->bar;
        $output->{'baz'} = $this->baz;
        if (isset($this->inferString)) {
            $output->{'inferString'} = $this->inferString;
        }
        if (isset($this->inferInt)) {
            $output->{'inferInt'} = $this->inferInt;
        }
        $output->{'contradiction'} = $this->contradiction;
        $output->{'contradiction2'} = $this->contradiction2;
        $output->{'nullable'} = $this->nullable;
        if (isset($this->optionalNullable) || array_key_exists('optionalNullable', $this->_providedOptionals)) {
            $output->{'optionalNullable'} = ($this->optionalNullable !== null) ? ($this->optionalNullable) : null;
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
