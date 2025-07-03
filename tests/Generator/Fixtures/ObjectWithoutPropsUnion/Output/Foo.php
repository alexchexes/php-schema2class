<?php

declare(strict_types=1);

namespace Ns\ObjectWithoutPropsUnion;

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
                'type' => [
                    'string',
                    'object',
                ],
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
     * @var string|array|object
     */
    private string|array|object $foo;

    /**
     * @var string|null
     */
    private ?string $bar = null;

    /**
     * @param string|array|object $foo
     */
    public function __construct(string|array|object $foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return string|array|object
     */
    public function getFoo() : string|array|object
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
     * @param string|array|object $foo
     * @return self
     */
    public function withFoo(string|array|object $foo) : self
    {
        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    /**
     * @param string $bar
     * @return self
     */
    public function withBar(string $bar) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($bar, self::$schema['properties']['bar']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
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

        $foo = match (true) {
            is_string($input->{'foo'}),
            is_array($input->{'foo'}) || is_object($input->{'foo'}) => $input->{'foo'},
            default => throw new \InvalidArgumentException("could not build property 'foo' from JSON"),
        };
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
        $output['foo'] = match (true) {
            is_string($this->foo),
            is_array($this->foo) || is_object($this->foo) => $this->foo,
        };
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
        $this->foo = match (true) {
            is_string($this->foo),
            is_array($this->foo) || is_object($this->foo) => $this->foo,
        };
    }
}
