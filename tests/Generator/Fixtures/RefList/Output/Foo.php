<?php

declare(strict_types=1);

namespace Ns\RefList;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'required' => [
            'foo_bar',
        ],
        'properties' => [
            'foo' => [
                'type' => 'array',
                'items' => [
                    '$ref' => '#/properties/address',
                ],
            ],
        ],
    ];

    /**
     * @var \Helmich\Schema2Class\Example\CustomerAddress[]|null
     */
    private ?array $foo = null;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return \Helmich\Schema2Class\Example\CustomerAddress[]|null
     */
    public function getFoo() : ?array
    {
        return $this->foo ?? null;
    }

    /**
     * @param \Helmich\Schema2Class\Example\CustomerAddress[] $foo
     * @return self
     */
    public function withFoo(array $foo) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($foo, self::$schema['properties']['foo']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
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
     * Builds a new instance from an input array
     *
     * @param mixed $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return Foo Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(mixed $input, bool $validate = true) : Foo
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

        $foo = isset($input->{'foo'}) ? array_map(fn(array|object $i): \Helmich\Schema2Class\Example\CustomerAddress => \Helmich\Schema2Class\Example\CustomerAddress::buildFromInput($i, $validate), $input->{'foo'}) : null;

        $obj = new self();
        $obj->foo = $foo;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toJson() : array
    {
        $output = [];
        if (isset($this->foo)) {
            $output['foo'] = array_map(fn(\Helmich\Schema2Class\Example\CustomerAddress $i): array => $i->toJson(), $this->foo);
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
    }
}
