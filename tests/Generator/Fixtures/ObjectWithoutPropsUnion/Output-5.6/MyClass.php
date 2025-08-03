<?php

namespace Ns\ObjectWithoutPropsUnion_5_6;

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
                'type' => [
                    'string',
                    'object',
                ],
            ],
            'bar' => [
                'type' => [
                    'string',
                    'object',
                ],
            ],
        ],
        'required' => [
            'foo',
        ],
    ];

    /**
     * @var string|array|object
     */
    private $foo;

    /**
     * @var string|array|object|null
     */
    private $bar = null;

    /**
     * @param string|array|object $foo
     */
    public function __construct($foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return string|array|object
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param string|array|object $foo
     * @return self
     */
    public function withFoo($foo)
    {
        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    /**
     * @return string|array|object|null
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param string|array|object $bar
     * @return self
     */
    public function withBar($bar)
    {
        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBar()
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

        $foo = $input->{'foo'};
        $bar = isset($input->{'bar'}) ? ((is_array($input->{'bar'}) || is_object($input->{'bar'})) ? $input->{'bar'} : (((is_string($input->{'bar'})) ? $input->{'bar'} : (null)))) : null;

        $obj = new self($foo);
        $obj->bar = $bar;
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
        if ((is_string($this->foo))) {
            $output['foo'] = $this->foo;
        } else if ((is_array($this->foo) || is_object($this->foo))) {
            $output['foo'] = json_decode(json_encode($this->foo), true);
        }
        if (isset($this->bar)) {
            if ((is_string($this->bar))) {
                $output['bar'] = $this->bar;
            } else if ((is_array($this->bar) || is_object($this->bar))) {
                $output['bar'] = json_decode(json_encode($this->bar), true);
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
        if ((is_string($this->foo))) {
        $output->{'foo'} = $this->foo;
        } else if ((is_array($this->foo) || is_object($this->foo))) {
        $output->{'foo'} = json_decode(json_encode($this->foo));
        }
        if (isset($this->bar)) {
            if ((is_string($this->bar))) {
            $output->{'bar'} = $this->bar;
            } else if ((is_array($this->bar) || is_object($this->bar))) {
            $output->{'bar'} = json_decode(json_encode($this->bar));
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
        $this->foo = (is_array($this->foo) || is_object($this->foo)) ? ($this->foo) : ((is_string($this->foo)) ? ($this->foo) : ($this->foo));
        if (isset($this->bar)) {
            $this->bar = (is_array($this->bar) || is_object($this->bar)) ? ($this->bar) : ((is_string($this->bar)) ? ($this->bar) : ($this->bar));
        }
    }
}
