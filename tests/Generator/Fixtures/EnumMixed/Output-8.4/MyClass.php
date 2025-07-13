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
        ],
        'required' => [
            'foo',
            'bar',
            'baz',
            'contradiction',
            'nullable',
        ],
    ];

    /**
     * @var int|MyClassFooAlternative2
     */
    private int|MyClassFooAlternative2 $foo;

    /**
     * @var mixed
     */
    private $bar;

    /**
     * @var mixed|null
     */
    private $baz;

    /**
     * @var int|null
     */
    private ?int $contradiction;

    /**
     * @var MyClassNullableAlternative1|mixed
     */
    private $nullable;

    /**
     * @param int|MyClassFooAlternative2 $foo
     * @param mixed $bar
     * @param mixed|null $baz
     * @param int|null $contradiction
     * @param MyClassNullableAlternative1|mixed $nullable
     */
    public function __construct(MyClassFooAlternative2|int $foo, $bar, $baz, ?int $contradiction, $nullable)
    {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->contradiction = $contradiction;
        $this->nullable = $nullable;
    }

    /**
     * @return int|MyClassFooAlternative2
     */
    public function getFoo() : MyClassFooAlternative2|int
    {
        return $this->foo;
    }

    /**
     * @return mixed
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @return mixed|null
     */
    public function getBaz()
    {
        return $this->baz;
    }

    /**
     * @return int|null
     */
    public function getContradiction() : ?int
    {
        return $this->contradiction ?? null;
    }

    /**
     * @return MyClassNullableAlternative1|mixed
     */
    public function getNullable()
    {
        return $this->nullable;
    }

    /**
     * @param int|MyClassFooAlternative2 $foo
     * @return self
     */
    public function withFoo(MyClassFooAlternative2|int $foo) : self
    {
        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    /**
     * @param mixed $bar
     * @return self
     * @param bool $validate
     */
    public function withBar($bar, bool $validate = true) : self
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
     * @param mixed $baz
     * @return self
     * @param bool $validate
     */
    public function withBaz($baz, bool $validate = true) : self
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
    public function withContradiction(?int $contradiction, bool $validate = true) : self
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
     * @param MyClassNullableAlternative1|mixed $nullable
     * @return self
     */
    public function withNullable($nullable) : self
    {
        $clone = clone $this;
        $clone->nullable = $nullable;

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
    public static function buildFromInput(array|object $input, bool $validate = true) : MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $foo = match (true) {
            is_int($input->{'foo'}) => $input->{'foo'},
            MyClassFooAlternative2::tryFrom($input->{'foo'}) !== null => MyClassFooAlternative2::from($input->{'foo'}),
            default => throw new \InvalidArgumentException("could not build property 'foo' from JSON"),
        };
        $bar = $input->{'bar'};
        $baz = ($input->{'baz'} !== null) ? ($input->{'baz'}) : null;
        $contradiction = ($input->{'contradiction'} !== null) ? ((int)($input->{'contradiction'})) : null;
        $nullable = match (true) {
            MyClassNullableAlternative1::tryFrom($input->{'nullable'}) !== null => MyClassNullableAlternative1::from($input->{'nullable'}),
            true => $input->{'nullable'},
            default => throw new \InvalidArgumentException("could not build property 'nullable' from JSON"),
        };

        $obj = new self($foo, $bar, $baz, $contradiction, $nullable);

        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray() : array
    {
        $output = [];
        $output['foo'] = match (true) {
            is_int($this->foo) => $this->foo,
            $this->foo instanceof MyClassFooAlternative2 => ($this->foo)->value,
        };
        $output['bar'] = $this->bar;
        $output['baz'] = $this->baz;
        $output['contradiction'] = $this->contradiction;
        $output['nullable'] = match (true) {
            $this->nullable instanceof MyClassNullableAlternative1 => ($this->nullable)->value,
            true => $this->nullable,
        };

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
    public static function validateInput(array|object $input, bool $return = false) : bool
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

    public function __clone()
    {
        $this->foo = match (true) {
            is_int($this->foo),
            $this->foo instanceof MyClassFooAlternative2 => $this->foo,
        };
        $this->nullable = match (true) {
            $this->nullable instanceof MyClassNullableAlternative1,
            true => $this->nullable,
        };
    }
}
