<?php

namespace Ns\DuplicateSanitizedNames_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
        'required' => [
            'foo-bar',
            'foo bar',
        ],
        'properties' => [
            'foo-bar' => [
                'type' => 'string',
            ],
            'foo bar' => [
                'type' => 'string',
            ],
        ],
    ];

    /**
     * @var string
     */
    private $foo_bar;

    /**
     * @var string
     */
    private $_foo_bar;

    /**
     * @param string $foo_bar
     * @param string $_foo_bar
     */
    public function __construct($foo_bar, $_foo_bar)
    {
        $this->foo_bar = $foo_bar;
        $this->_foo_bar = $_foo_bar;
    }

    /**
     * @return string
     */
    public function getFooBar()
    {
        return $this->foo_bar;
    }

    /**
     * @return string
     */
    public function get_FooBar()
    {
        return $this->_foo_bar;
    }

    /**
     * @param string $foo_bar
     * @return self
     * @param bool $validate
     */
    public function withFooBar($foo_bar, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo_bar, self::$schema['properties']['foo-bar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->foo_bar = $foo_bar;

        return $clone;
    }

    /**
     * @param string $_foo_bar
     * @return self
     * @param bool $validate
     */
    public function with_FooBar($_foo_bar, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_foo_bar, self::$schema['properties']['foo bar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_foo_bar = $_foo_bar;

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

        $foo_bar = $input->{'foo-bar'};
        $_foo_bar = $input->{'foo bar'};

        $obj = new self($foo_bar, $_foo_bar);

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
        $output['foo-bar'] = $this->foo_bar;
        $output['foo bar'] = $this->_foo_bar;

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
        $output->{'foo-bar'} = $this->foo_bar;
        $output->{'foo bar'} = $this->_foo_bar;

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
        $validator->validate($input, self::$schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function($e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
