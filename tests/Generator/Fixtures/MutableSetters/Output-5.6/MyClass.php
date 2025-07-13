<?php

namespace Ns\MutableSetters_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
        'required' => [
            'bar',
        ],
        'properties' => [
            'foo' => [
                'type' => 'string',
            ],
            'bar' => [
                '$ref' => '#/definitions/Baz',
            ],
            'opt' => [
                'type' => [
                    'string',
                    'null',
                ],
            ],
        ],
        'definitions' => [
            'Baz' => [
                'type' => 'object',
                'properties' => [
                    'name' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var string|null
     */
    private $foo = null;

    /**
     * @var Baz
     */
    private $bar;

    /**
     * @var string|null
     */
    private $opt = null;

    /**
     * @param Baz $bar
     */
    public function __construct(Baz $bar)
    {
        $this->bar = $bar;
    }

    /**
     * @return string|null
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @return Baz
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @return string|null
     */
    public function getOpt()
    {
        return $this->opt;
    }

    /**
     * @param string $foo
     * @param bool $validate
     */
    public function setFoo($foo, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo, self::$schema['properties']['foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $this->foo = $foo;
    }

    /**
     * @param Baz $bar
     */
    public function setBar(Baz $bar)
    {
        $this->bar = $bar;
    }

    /**
     * @param string $opt
     * @param bool $validate
     */
    public function setOpt($opt, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($opt, self::$schema['properties']['opt']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $this->opt = $opt;
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

        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $bar = Baz::buildFromInput($input->{'bar'}, $validate);
        $opt = isset($input->{'opt'}) ? $input->{'opt'} : null;

        $obj = new self($bar);
        $obj->foo = $foo;
        $obj->opt = $opt;
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
            $output['foo'] = $this->foo;
        }
        $output['bar'] = $this->bar->toArray();
        $output['opt'] = $this->opt;

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
