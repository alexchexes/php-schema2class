<?php

namespace Ns\OptionalNullableDefault_5_6;

/**
 * optional, nullable, with default, object, and default is empty object
 */
class MyClassGooks
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
        'type' => 'object',
        'description' => 'optional, nullable, with default, object, and default is empty object',
        'properties' => [
            'a' => [
                'type' => 'string',
            ],
            'b' => [
                'type' => 'number',
            ],
        ],
        'default' => [
            
        ],
    ];

    /**
     * @var string|null
     */
    private $a = null;

    /**
     * @var int|float|null
     */
    private $b = null;

    /**
     * @return string|null
     */
    public function getA()
    {
        return $this->a;
    }

    /**
     * @return int|float|null
     */
    public function getB()
    {
        return $this->b;
    }

    /**
     * @param string $a
     * @return self
     * @param bool $validate
     */
    public function withA($a, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($a, self::$schema['properties']['a']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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
     * @param int|float $b
     * @return self
     * @param bool $validate
     */
    public function withB($b, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($b, self::$schema['properties']['b']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MyClassGooks Created instance
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

        $a = isset($input->{'a'}) ? $input->{'a'} : null;
        $b = isset($input->{'b'}) ? $input->{'b'} : null;

        $obj = new self();
        $obj->a = $a;
        $obj->b = $b;
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
            $output['a'] = $this->a;
        }
        if (isset($this->b)) {
            $output['b'] = $this->b;
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
            $output->{'a'} = $this->a;
        }
        if (isset($this->b)) {
            $output->{'b'} = $this->b;
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
}
