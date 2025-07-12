<?php

declare(strict_types=1);

namespace Ns\ObjectWithoutProps_8_4;

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
                'type' => 'object',
            ],
            'bar' => [
                'type' => 'object',
            ],
        ],
        'required' => [
            'bar',
        ],
    ];

    /**
     * @var array|object|null
     */
    private array|object|null $foo = null;

    /**
     * @var array|object
     */
    private array|object $bar;

    /**
     * @param array|object $bar
     */
    public function __construct(array|object $bar)
    {
        $this->bar = $bar;
    }

    /**
     * @return array|object|null
     */
    public function getFoo() : array|object|null
    {
        return $this->foo;
    }

    /**
     * @return array|object
     */
    public function getBar() : array|object
    {
        return $this->bar;
    }

    /**
     * @param array|object $foo
     * @return self
     * @param bool $validate
     */
    public function withFoo(array|object $foo, bool $validate = true) : self
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
     * @return self
     */
    public function withoutFoo() : self
    {
        $clone = clone $this;
        unset($clone->foo);

        return $clone;
    }

    /**
     * @param array|object $bar
     * @return self
     * @param bool $validate
     */
    public function withBar(array|object $bar, bool $validate = true) : self
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

        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $bar = $input->{'bar'};

        $obj = new self($bar);
        $obj->foo = $foo;
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
            $output['foo'] = json_decode(json_encode($this->foo), true);
        }
        $output['bar'] = json_decode(json_encode($this->bar), true);

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
}
