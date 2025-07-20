<?php

namespace Ns\TypeArrayUnion_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
        'required' => [
            'foo',
        ],
        'properties' => [
            'foo' => [
                'type' => [
                    'string',
                    'object',
                ],
                'required' => [
                    'bar',
                ],
                'properties' => [
                    'bar' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var string|MyClassFooAlternative2
     */
    private $foo;

    /**
     * @param string|MyClassFooAlternative2 $foo
     */
    public function __construct($foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return string|MyClassFooAlternative2
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param string|MyClassFooAlternative2 $foo
     * @return self
     */
    public function withFoo($foo)
    {
        $clone = clone $this;
        $clone->foo = $foo;

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
    public static function buildFromInput($input, bool $validate = true)
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

        if ((MyClassFooAlternative2::validateInput($input->{'foo'}, true))) {
            $foo = MyClassFooAlternative2::buildFromInput($input->{'foo'}, $validate);
        } else {
            $foo = $input->{'foo'};
        }

        $obj = new self($foo);

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
        } else if (($this->foo instanceof MyClassFooAlternative2)) {
            $output['foo'] = ($this->foo)->toArray();
        }

        return $output;
    }

    /**
     * Converts this object back to a stdClass that can be JSON-serialized
     *
     * @return stdClass Converted object
     */
    public function toObject()
    {
        $output = new \stdClass();
        if ((is_string($this->foo))) {
        $output->{'foo'} = $this->foo;
        } else if (($this->foo instanceof MyClassFooAlternative2)) {
        $output->{'foo'} = ($this->foo)->toObject();
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
        $validator->validate($input, self::$schema);

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
        $this->foo = ($this->foo instanceof MyClassFooAlternative2) ? (clone $this->foo) : ((is_string($this->foo)) ? ($this->foo) : ($this->foo));
    }
}
