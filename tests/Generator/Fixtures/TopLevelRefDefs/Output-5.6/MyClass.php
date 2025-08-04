<?php

namespace Ns\TopLevelRefDefs_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
        'properties' => [
            'foo' => [
                '$ref' => '#/definitions/Bar',
            ],
            'encoded' => [
                '$ref' => '#/definitions/Encoded<Test>',
            ],
        ],
        'definitions' => [
            'Foo' => [
                'properties' => [
                    'foo' => [
                        '$ref' => '#/definitions/Bar',
                    ],
                    'encoded' => [
                        '$ref' => '#/definitions/Encoded<Test>',
                    ],
                ],
            ],
            'Bar' => [
                'type' => 'object',
            ],
            'Encoded<Test>' => [
                'type' => 'object',
            ],
        ],
    ];

    /**
     * @var array|object|null
     */
    private $foo = null;

    /**
     * @var array|object|null
     */
    private $encoded = null;

    /**
     * @return array|object|null
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param array|object $foo
     * @return self
     */
    public function withFoo($foo)
    {
        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutFoo()
    {
        $clone = clone $this;
        unset($clone->foo);

        return $clone;
    }

    /**
     * @return array|object|null
     */
    public function getEncoded()
    {
        return $this->encoded;
    }

    /**
     * @param array|object $encoded
     * @return self
     */
    public function withEncoded($encoded)
    {
        $clone = clone $this;
        $clone->encoded = $encoded;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutEncoded()
    {
        $clone = clone $this;
        unset($clone->encoded);

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
    public static function fromInput($input, bool $validate = true)
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

        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $encoded = isset($input->{'encoded'}) ? $input->{'encoded'} : null;

        $obj = new self();
        $obj->foo = $foo;
        $obj->encoded = $encoded;
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
        if (isset($this->foo)) {
            $output['foo'] = json_decode(json_encode($this->foo), true);
        }
        if (isset($this->encoded)) {
            $output['encoded'] = json_decode(json_encode($this->encoded), true);
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
        if (isset($this->foo)) {
            $output->{'foo'} = json_decode(json_encode($this->foo));
        }
        if (isset($this->encoded)) {
            $output->{'encoded'} = json_decode(json_encode($this->encoded));
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
