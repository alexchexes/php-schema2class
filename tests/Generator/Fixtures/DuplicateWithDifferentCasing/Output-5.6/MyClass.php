<?php

namespace Ns\DuplicateWithDifferentCasing_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
        'required' => [
            'fooBar',
        ],
        'properties' => [
            'foobar' => [
                'type' => 'string',
                'deprecated' => true,
            ],
            'fooBar' => [
                'type' => 'string',
            ],
            'bar' => [
                'type' => 'string',
                'deprecated' => true,
            ],
        ],
    ];

    /**
     * @var string|null
     * @deprecated
     */
    private $foobar = null;

    /**
     * @var string
     */
    private $_fooBar_1;

    /**
     * @var string|null
     * @deprecated
     */
    private $bar = null;

    /**
     * @param string $_fooBar_1
     */
    public function __construct($_fooBar_1)
    {
        $this->_fooBar_1 = $_fooBar_1;
    }

    /**
     * @return string
     */
    public function getFooBar1()
    {
        return $this->_fooBar_1;
    }

    /**
     * @return string|null
     * @deprecated
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param string $_fooBar_1
     * @return self
     * @param bool $validate
     */
    public function withFooBar1($_fooBar_1, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_fooBar_1, self::$schema['properties']['fooBar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_fooBar_1 = $_fooBar_1;

        return $clone;
    }

    /**
     * @param string $bar
     * @return self
     * @deprecated
     * @param bool $validate
     */
    public function withBar($bar, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($bar, self::$schema['properties']['bar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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

        $foobar = isset($input->{'foobar'}) ? $input->{'foobar'} : null;
        $_fooBar_1 = $input->{'fooBar'};
        $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;

        $obj = new self($_fooBar_1);
        $obj->foobar = $foobar;
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
        if (isset($this->foobar)) {
            $output['foobar'] = $this->foobar;
        }
        $output['fooBar'] = $this->_fooBar_1;
        if (isset($this->bar)) {
            $output['bar'] = $this->bar;
        }

        return $output;
    }

    /**
     * Converts this object back to an stdClass that can be JSON-serialized
     *
     * @return \stdClass Converted object
     */
    public function toStdClass()
    {
        $array = $this->toArray();
        return json_decode(json_encode($array));
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
