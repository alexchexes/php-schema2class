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
     * @var int|1|2|'1'|'2'
     */
    private $foo;

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
     * @var 'red'|'green'|mixed
     */
    private $nullable;

    /**
     * @param int|1|2|'1'|'2' $foo
     * @param mixed $bar
     * @param mixed|null $baz
     * @param int|null $contradiction
     * @param 'red'|'green'|mixed $nullable
     */
    public function __construct($foo, $bar, $baz, ?int $contradiction, $nullable)
    {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->contradiction = $contradiction;
        $this->nullable = $nullable;
    }

    /**
     * @return int|1|2|'1'|'2'
     */
    public function getFoo()
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
     * @return 'red'|'green'|mixed
     */
    public function getNullable()
    {
        return $this->nullable;
    }

    /**
     * @param int|1|2|'1'|'2' $foo
     * @return self
     */
    public function withFoo($foo) : self
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
     * @param 'red'|'green'|mixed $nullable
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
    public static function buildFromInput($input, bool $validate = true) : MyClass
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to buildFromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $foo = $input->{'foo'};
        $bar = $input->{'bar'};
        $baz = ($input->{'baz'} !== null) ? ($input->{'baz'}) : null;
        $contradiction = ($input->{'contradiction'} !== null) ? ((int)($input->{'contradiction'})) : null;
        $nullable = $input->{'nullable'};

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
        if ((is_int($this->foo)) || (is_string($this->foo) && in_array($this->foo, array (
          0 => 1,
          1 => 2,
          2 => '1',
          3 => '2',
        ), true))) {
            $output['foo'] = $this->foo;
        }
        $output['bar'] = $this->bar;
        $output['baz'] = $this->baz;
        $output['contradiction'] = $this->contradiction;
        if ((is_string($this->nullable) && in_array($this->nullable, array (
          0 => 'red',
          1 => 'green',
        ), true)) || (true)) {
            $output['nullable'] = $this->nullable;
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
    public static function validateInput($input, bool $return = false) : bool
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
        $this->foo = (is_string($this->foo) && in_array($this->foo, array (
          0 => 1,
          1 => 2,
          2 => '1',
          3 => '2',
        ), true)) ? ($this->foo) : ((is_int($this->foo)) ? ($this->foo) : ($this->foo));
        $this->nullable = (true) ? ($this->nullable) : ((is_string($this->nullable) && in_array($this->nullable, array (
          0 => 'red',
          1 => 'green',
        ), true)) ? ($this->nullable) : ($this->nullable));
    }
}
