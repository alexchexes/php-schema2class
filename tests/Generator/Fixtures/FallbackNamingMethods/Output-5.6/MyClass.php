<?php

namespace Ns\FallbackNamingMethods_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
        'properties' => [
            'bound' => [
                'type' => 'string',
            ],
            'outbound' => [
                'type' => 'string',
            ],
            '_outbound' => [
                'type' => 'string',
            ],
        ],
    ];

    /**
     * @var string|null
     */
    private $bound = null;

    /**
     * @var string|null
     */
    private $outbound = null;

    /**
     * @var string|null
     */
    private $_outbound = null;

    /**
     * @return string|null
     */
    public function getBound()
    {
        return $this->bound;
    }

    /**
     * @return string|null
     */
    public function getOutbound()
    {
        return $this->outbound;
    }

    /**
     * @return string|null
     */
    public function get_Outbound()
    {
        return $this->_outbound;
    }

    /**
     * @param string $bound
     * @return self
     * @param bool $validate
     */
    public function withBound($bound, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($bound, self::$schema['properties']['bound']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->bound = $bound;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBound()
    {
        $clone = clone $this;
        unset($clone->bound);

        return $clone;
    }

    /**
     * @param string $outbound
     * @return self
     * @param bool $validate
     */
    public function with_Outbound($outbound, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($outbound, self::$schema['properties']['outbound']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->outbound = $outbound;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOutbound()
    {
        $clone = clone $this;
        unset($clone->outbound);

        return $clone;
    }

    /**
     * @param string $_outbound
     * @return self
     * @param bool $validate
     */
    public function with__Outbound($_outbound, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_outbound, self::$schema['properties']['_outbound']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_outbound = $_outbound;

        return $clone;
    }

    /**
     * @return self
     */
    public function without_Outbound()
    {
        $clone = clone $this;
        unset($clone->_outbound);

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

        $bound = isset($input->{'bound'}) ? $input->{'bound'} : null;
        $outbound = isset($input->{'outbound'}) ? $input->{'outbound'} : null;
        $_outbound = isset($input->{'_outbound'}) ? $input->{'_outbound'} : null;

        $obj = new self();
        $obj->bound = $bound;
        $obj->outbound = $outbound;
        $obj->_outbound = $_outbound;
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
        if (isset($this->bound)) {
            $output['bound'] = $this->bound;
        }
        if (isset($this->outbound)) {
            $output['outbound'] = $this->outbound;
        }
        if (isset($this->_outbound)) {
            $output['_outbound'] = $this->_outbound;
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
