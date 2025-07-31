<?php

declare(strict_types=1);

namespace Ns\EnumMixed_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
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
    private int|string $foo;

    /**
     * @var 3|4|'3'|'4'
     */
    private int|string $bar;

    /**
     * @var 'red'|'amber'|'green'|'42'|42|42.5|false|null
     */
    private string|int|float|bool|null $baz;

    /**
     * @var int
     */
    private int $contradiction;

    /**
     * @var 1|2|'one'
     */
    private string|int $contradiction2;

    /**
     * @var MyClassNullable|null
     */
    private ?MyClassNullable $nullable;

    /**
     * @var MyClassOptionalNullable|null
     */
    private ?MyClassOptionalNullable $optionalNullable = null;

    /**
     * @param 1|2|'1'|'2' $foo
     * @param 3|4|'3'|'4' $bar
     * @param 'red'|'amber'|'green'|'42'|42|42.5|false|null $baz
     * @param int $contradiction
     * @param 1|2|'one' $contradiction2
     * @param MyClassNullable|null $nullable
     */
    public function __construct(int|string $foo, int|string $bar, bool|int|float|string|null $baz, int $contradiction, int|string $contradiction2, ?MyClassNullable $nullable)
    {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->contradiction = $contradiction;
        $this->contradiction2 = $contradiction2;
        $this->nullable = $nullable;
    }

    /**
     * @return 1|2|'1'|'2'
     */
    public function getFoo(): int|string
    {
        return $this->foo;
    }

    /**
     * @return 3|4|'3'|'4'
     */
    public function getBar(): int|string
    {
        return $this->bar;
    }

    /**
     * @return 'red'|'amber'|'green'|'42'|42|42.5|false|null
     */
    public function getBaz(): bool|int|float|string|null
    {
        return $this->baz;
    }

    /**
     * @return int
     */
    public function getContradiction(): int
    {
        return $this->contradiction;
    }

    /**
     * @return 1|2|'one'
     */
    public function getContradiction2(): int|string
    {
        return $this->contradiction2;
    }

    /**
     * @return MyClassNullable|null
     */
    public function getNullable(): ?MyClassNullable
    {
        return $this->nullable ?? null;
    }

    /**
     * @return MyClassOptionalNullable|null
     */
    public function getOptionalNullable(): ?MyClassOptionalNullable
    {
        return $this->optionalNullable ?? null;
    }

    /**
     * @param 1|2|'1'|'2' $foo
     * @return self
     * @param bool $validate
     */
    public function withFoo(int|string $foo, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo, self::$schema['properties']['foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    /**
     * @param 3|4|'3'|'4' $bar
     * @return self
     * @param bool $validate
     */
    public function withBar(int|string $bar, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($bar, self::$schema['properties']['bar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    /**
     * @param 'red'|'amber'|'green'|'42'|42|42.5|false $baz
     * @return self
     * @param bool $validate
     */
    public function withBaz(bool|int|float|string|null $baz, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($baz, self::$schema['properties']['baz']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->baz = $baz;

        return $clone;
    }

    /**
     * @param int $contradiction
     * @return self
     * @param bool $validate
     */
    public function withContradiction(int $contradiction, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($contradiction, self::$schema['properties']['contradiction']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->contradiction = $contradiction;

        return $clone;
    }

    /**
     * @param 1|2|'one' $contradiction2
     * @return self
     * @param bool $validate
     */
    public function withContradiction2(int|string $contradiction2, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($contradiction2, self::$schema['properties']['contradiction2']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->contradiction2 = $contradiction2;

        return $clone;
    }

    /**
     * @param MyClassNullable $nullable
     * @return self
     */
    public function withNullable(?MyClassNullable $nullable): self
    {
        $clone = clone $this;
        $clone->nullable = $nullable;

        return $clone;
    }

    /**
     * @param MyClassOptionalNullable $optionalNullable
     * @return self
     */
    public function withOptionalNullable(MyClassOptionalNullable $optionalNullable): self
    {
        $clone = clone $this;
        $clone->optionalNullable = $optionalNullable;
        $clone->_providedOptionals['optionalNullable'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptionalNullable(): self
    {
        $clone = clone $this;
        unset($clone->optionalNullable);
        unset($clone->_providedOptionals['optionalNullable']);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true): MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $__providedOptionals = [];
        $foo = $input->{'foo'};
        $bar = $input->{'bar'};
        $baz = ($input->{'baz'} !== null) ? ($input->{'baz'}) : null;
        $contradiction = (int)($input->{'contradiction'});
        $contradiction2 = $input->{'contradiction2'};
        $nullable = ($input->{'nullable'} !== null) ? (MyClassNullable::from($input->{'nullable'})) : null;
        $optionalNullable = property_exists($input, 'optionalNullable') ? (($input->{'optionalNullable'} !== null) ? (MyClassOptionalNullable::from($input->{'optionalNullable'})) : null) : null;
        if (property_exists($input, 'optionalNullable')) {
            $__providedOptionals['optionalNullable'] = true;
        }

        $obj = new self($foo, $bar, $baz, $contradiction, $contradiction2, $nullable);
        $obj->optionalNullable = $optionalNullable;
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
        $output['contradiction'] = $this->contradiction;
        $output['contradiction2'] = $this->contradiction2;
        $output['nullable'] = ($this->nullable)->value;
        if (isset($this->optionalNullable) || array_key_exists('optionalNullable', $this->_providedOptionals)) {
            $output['optionalNullable'] = ($this->optionalNullable !== null) ? (($this->optionalNullable)->value) : null;
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
        $output->{'contradiction'} = $this->contradiction;
        $output->{'contradiction2'} = $this->contradiction2;
        $output->{'nullable'} = ($this->nullable)->value;
        if (isset($this->optionalNullable) || array_key_exists('optionalNullable', $this->_providedOptionals)) {
            $output->{'optionalNullable'} = ($this->optionalNullable !== null) ? (($this->optionalNullable)->value) : null;
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
        $validator->validate($input, self::$schema);

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
