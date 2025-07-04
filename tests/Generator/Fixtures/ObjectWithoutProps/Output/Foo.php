<?php

declare(strict_types=1);

namespace Ns\ObjectWithoutProps;

class Foo
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
                'type' => 'string',
            ],
        ],
        'required' => [
            'foo',
        ],
    ];

    /**
     * @var array|object
     */
    private array|object $foo;

    /**
     * @var string|null
     */
    private ?string $bar = null;

    /**
     * @param array|object $foo
     */
    public function __construct(array|object $foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return array|object
     */
    public function getFoo() : array|object
    {
        return $this->foo;
    }

    /**
     * @return string|null
     */
    public function getBar() : ?string
    {
        return $this->bar ?? null;
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
     * @param string $bar
     * @return self
     * @param bool $validate
     */
    public function withBar(string $bar, bool $validate = true) : self
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
     * @return Foo Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : Foo
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $foo = $input->{'foo'};
        $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;

        $obj = new self($foo);
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
        $output['foo'] = $this->foo;
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
}
