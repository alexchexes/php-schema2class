<?php

declare(strict_types=1);

namespace Ns\TopLevelRef_8_4;

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
                    'string',
                    'number',
                ],
            ],
        ],
        'type' => 'object',
    ];

    /**
     * @var string|int|float|null
     */
    private string|int|float|null $foo = null;

    /**
     * @return string|int|float|null
     */
    public function getFoo() : int|float|string|null
    {
        return $this->foo;
    }

    /**
     * @param string|int|float $foo
     * @return self
     */
    public function withFoo(int|float|string $foo) : self
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

        $foo = property_exists($input, 'foo') ? match (true) {
            is_string($input->{'foo'}) => $input->{'foo'},
            is_int($input->{'foo'}) || is_float($input->{'foo'}) => str_contains((string)($input->{'foo'}), '.') ? (float)($input->{'foo'}) : (int)($input->{'foo'}),
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
    public function toArray() : array
    {
        $output = [];
        if (isset($this->foo)) {
            $output['foo'] = match (true) {
                is_string($this->foo),
                is_int($this->foo) || is_float($this->foo) => $this->foo,
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
                is_string($this->foo),
                is_int($this->foo) || is_float($this->foo) => $this->foo,
            };
        }
    }
}
