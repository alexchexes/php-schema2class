<?php

declare(strict_types=1);

namespace Ns\TopLevelRef_7_4;

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
    private $foo = null;

    /**
     * @param string|int|float|null $foo
     */
    public function __construct($foo = null)
    {
        $this->foo = $foo;
    }

    /**
     * @return string|int|float|null
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param string|int|float $foo
     */
    public function withFoo($foo, bool $validate = true): self
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

        $foo = isset($input->{'foo'}) ? ((is_int($input->{'foo'}) || is_float($input->{'foo'})) ? (str_contains((string)$input->{'foo'}, '.') ? (float)$input->{'foo'} : (int)$input->{'foo'}) : (((is_string($input->{'foo'})) ? $input->{'foo'} : (null)))) : null;

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
            if ((is_string($this->foo)) || (is_int($this->foo) || is_float($this->foo))) {
                $output['foo'] = $this->foo;
            }
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
            if ((is_string($this->foo)) || (is_int($this->foo) || is_float($this->foo))) {
            $output->{'foo'} = $this->foo;
            }
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

    public function __clone()
    {
        if (isset($this->foo)) {
            $this->foo = (is_int($this->foo) || is_float($this->foo) ? $this->foo : (is_string($this->foo) ? $this->foo : $this->foo));
        }
    }
}
