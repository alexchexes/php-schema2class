<?php

declare(strict_types=1);

namespace Ns\MixedEnum_8_4;

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
        ],
    ];

    /**
     * @var int|MyClassFooAlternative2|null
     */
    private int|MyClassFooAlternative2|null $foo = null;

    /**
     * @var mixed|null
     */
    private $bar = null;

    /**
     * @return int|MyClassFooAlternative2|null
     */
    public function getFoo() : MyClassFooAlternative2|int|null
    {
        return $this->foo;
    }

    /**
     * @return mixed|null
     */
    public function getBar()
    {
        return $this->bar;
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
     * @return self
     */
    public function withoutFoo() : self
    {
        $clone = clone $this;
        unset($clone->foo);

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
     * @return self
     */
    public function withoutBar() : self
    {
        $clone = clone $this;
        unset($clone->bar);

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

        $foo = isset($input->{'foo'}) ? match (true) {
            is_int($input->{'foo'}) => (int)($input->{'foo'}),
            MyClassFooAlternative2::tryFrom($input->{'foo'}) !== null => MyClassFooAlternative2::from($input->{'foo'}),
            default => null,
        } : null;
        $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;

        $obj = new self();
        $obj->foo = $foo;
        $obj->bar = $bar;
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
        if (isset($this->foo)) {
            $output['foo'] = match (true) {
                is_int($this->foo) => $this->foo,
                $this->foo instanceof MyClassFooAlternative2 => ($this->foo)->value,
            };
        }
        if (isset($this->bar)) {
            $output['bar'] = $this->bar;
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
        if (isset($this->foo)) {
            $this->foo = match (true) {
                is_int($this->foo),
                $this->foo instanceof MyClassFooAlternative2 => $this->foo,
            };
        }
    }
}
