<?php

declare(strict_types=1);

namespace Ns\OneOfWithLiteralBool_8_4;

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
                'oneOf' => [
                    [
                        'type' => 'boolean',
                        'enum' => [
                            true,
                        ],
                    ],
                    [
                        'type' => 'string',
                        'enum' => [
                            'red',
                        ],
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var true|'red'|null
     */
    private bool|string|null $foo = null;

    /**
     * @return true|'red'|null
     */
    public function getFoo(): bool|string|null
    {
        return $this->foo;
    }

    /**
     * @param true|'red' $foo
     * @return self
     * @param bool $validate
     */
    public function withFoo(bool|string $foo, bool $validate = true): self
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
     * @return self
     */
    public function withoutFoo(): self
    {
        $clone = clone $this;
        unset($clone->foo);

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
    public static function fromInput(array|object $input, bool $validate = true): MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $foo = isset($input->{'foo'}) ? match (true) {
            is_bool($input->{'foo'}) => (bool)$input->{'foo'},
            in_array($input->{'foo'}, array (
          0 => 'red',
        ), true) => $input->{'foo'},
            default => null,
        } : null;

        $obj = new self();
        $obj->foo = $foo;
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
            $output['foo'] = match (true) {
                is_bool($this->foo),
                is_string($this->foo) && in_array($this->foo, array (
              0 => 'red',
            ), true) => $this->foo,
            };
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
            $output->{'foo'} = match (true) {
                is_bool($this->foo),
                is_string($this->foo) && in_array($this->foo, array (
              0 => 'red',
            ), true) => $this->foo,
            };
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
        $validator->validate($input, self::$_schema);

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
                is_bool($this->foo),
                is_string($this->foo) && in_array($this->foo, array (
              0 => 'red',
            ), true) => $this->foo,
            };
        }
    }
}
