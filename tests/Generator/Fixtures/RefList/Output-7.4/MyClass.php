<?php

declare(strict_types=1);

namespace Ns\RefList_7_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
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
     * @param \Helmich\Schema2Class\Example\CustomerAddress[]|null $foo
     */
    public function __construct(?array $foo = null)
    {
        $this->foo = $foo;
    }

    /**
     * @return \Helmich\Schema2Class\Example\CustomerAddress[]|null
     */
    public function getFoo(): ?array
    {
        return $this->foo;
    }

    /**
     * @param \Helmich\Schema2Class\Example\CustomerAddress[] $foo
     */
    public function withFoo(array $foo, bool $validate = true): self
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

    public function withoutFoo(): self
    {
        $clone = clone $this;
        unset($clone->foo);

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

        $foo = isset($input->{'foo'}) ? array_map(
            fn($i) => \Helmich\Schema2Class\Example\CustomerAddress::fromInput($i, $validate),
            $input->{'foo'}
        ) : null;

        $obj = new self($foo);
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
        if (isset($this->foo)) {
            $output['foo'] = array_map(fn(\Helmich\Schema2Class\Example\CustomerAddress $i): array => $i->toArray(), $this->foo);
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
        if (isset($this->foo)) {
            $output->{'foo'} = array_map(fn(\Helmich\Schema2Class\Example\CustomerAddress $i): object => $i->toStdClass(), $this->foo);
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
}
