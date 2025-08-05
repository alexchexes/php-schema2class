<?php

namespace Ns\OneOfWithLiteralBool_5_6;

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
    private $foo = null;

    /**
     * @return true|'red'|null
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param true|'red' $foo
     * @return self
     * @param bool $validate
     */
    public function withFoo($foo, bool $validate = true)
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
    public function withoutFoo()
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

        $foo = isset($input->{'foo'}) ? ((in_array($input->{'foo'}, array (
          0 => 'red',
        ), true)) ? $input->{'foo'} : (((is_bool($input->{'foo'})) ? (bool)$input->{'foo'} : (null)))) : null;

        $obj = new self();
        $obj->foo = $foo;
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
            if ((is_bool($this->foo)) || (is_string($this->foo) && in_array($this->foo, array (
              0 => 'red',
            ), true))) {
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
    public function toStdClass()
    {
        $output = new \stdClass();
        if (isset($this->foo)) {
            if ((is_bool($this->foo)) || (is_string($this->foo) && in_array($this->foo, array (
              0 => 'red',
            ), true))) {
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

    public function __clone()
    {
        if (isset($this->foo)) {
            $this->foo = (is_string($this->foo) && in_array($this->foo, array (
              0 => 'red',
            ), true)) ? ($this->foo) : ((is_bool($this->foo)) ? ($this->foo) : ($this->foo));
        }
    }
}
