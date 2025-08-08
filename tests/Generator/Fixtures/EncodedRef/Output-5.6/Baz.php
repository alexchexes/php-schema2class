<?php

namespace Ns\EncodedRef_5_6;

class Baz
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
        'type' => 'object',
        'properties' => [
            'a' => [
                '$ref' => '#/definitions/Foo<Test>',
            ],
            'b' => [
                '$ref' => '#/definitions/Foo<Test>',
            ],
            'c' => [
                '$ref' => '#/definitions/Bar<Test>',
            ],
        ],
        'definitions' => [
            'Foo<Test>' => [
                'type' => 'object',
                'properties' => [
                    'foo' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'Bar<Test>' => [
                'type' => 'object',
                'properties' => [
                    'bar' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     *
     * @var object
     */
    private $_additionalProperties;

    /**
     * @var FooTest|null
     */
    private $a = null;

    /**
     * @var FooTest|null
     */
    private $b = null;

    /**
     * @var BarTest|null
     */
    private $c = null;

    /**
     * @param FooTest|null $a
     * @param FooTest|null $b
     * @param BarTest|null $c
     */
    public function __construct(FooTest $a = null, FooTest $b = null, BarTest $c = null)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }

    /**
     * Object or array containing name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return array instead of `stdClass` object.
     * @return array|object
     */
    public function getAdditionalProperties($asArray = true)
    {
        return $asArray
            ? json_decode(json_encode($this->_additionalProperties), true)
            : $this->_additionalProperties;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param array|object $additionalProperties Map of property name/value pairs to add.
     * @return self
     */
    public function withAdditionalProperties($additionalProperties)
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        return $clone;
    }

    /**
     * Removes all extra properties not specified in the schema.
     *
     * @return self
     */
    public function withoutAdditionalProperties()
    {
        $clone = clone $this;
        $clone->_additionalProperties = new \stdClass;
        return $clone;
    }

    /**
     * @return FooTest|null
     */
    public function getA()
    {
        return isset($this->a) ? $this->a : null;
    }

    /**
     * @return self
     */
    public function withA(FooTest $a)
    {
        $clone = clone $this;
        $clone->a = $a;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutA()
    {
        $clone = clone $this;
        unset($clone->a);

        return $clone;
    }

    /**
     * @return FooTest|null
     */
    public function getB()
    {
        return isset($this->b) ? $this->b : null;
    }

    /**
     * @return self
     */
    public function withB(FooTest $b)
    {
        $clone = clone $this;
        $clone->b = $b;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutB()
    {
        $clone = clone $this;
        unset($clone->b);

        return $clone;
    }

    /**
     * @return BarTest|null
     */
    public function getC()
    {
        return isset($this->c) ? $this->c : null;
    }

    /**
     * @return self
     */
    public function withC(BarTest $c)
    {
        $clone = clone $this;
        $clone->c = $c;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutC()
    {
        $clone = clone $this;
        unset($clone->c);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Baz Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput($input, $validate = true)
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

        $a = isset($input->{'a'}) ? FooTest::fromInput($input->{'a'}, $validate) : null;
        $b = isset($input->{'b'}) ? FooTest::fromInput($input->{'b'}, $validate) : null;
        $c = isset($input->{'c'}) ? BarTest::fromInput($input->{'c'}, $validate) : null;

        $obj = new self($a, $b, $c);
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray()
    {
        $output = [];
        if (isset($this->a)) {
            $output['a'] = $this->a->toArray();
        }
        if (isset($this->b)) {
            $output['b'] = $this->b->toArray();
        }
        if (isset($this->c)) {
            $output['c'] = $this->c->toArray();
        }

        return $output;
    }

    /**
     * Converts this object to a stdClass that can be JSON-serialized
     *
     * @return \stdClass Converted object
     */
    public function toStdClass()
    {
        $output = new \stdClass();
        if (isset($this->a)) {
            $output->{'a'} = $this->a->toStdClass();
        }
        if (isset($this->b)) {
            $output->{'b'} = $this->b->toStdClass();
        }
        if (isset($this->c)) {
            $output->{'c'} = $this->c->toStdClass();
        }

        return $output;
    }

    /**
     * Validates the current instance against its schema
     *
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result if `$return` is `true`
     * @throws \InvalidArgumentException
     */
    public function validate($return = false)
    {
        return self::validateInput($this->toStdClass(), $return);
    }

    /**
     * Validates an input array
     *
     * @param array|object $input Input data
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result if `$return` is `true`
     * @throws \InvalidArgumentException
     */
    public static function validateInput($input, $return = false)
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function($e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
