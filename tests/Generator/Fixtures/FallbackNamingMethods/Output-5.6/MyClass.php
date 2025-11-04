<?php

namespace Ns\FallbackNamingMethods_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
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
     * Mapping of schema property names to this class's property names.
     *
     * @var array
     */
    private static $_namesMap = [
        'bound' => 'bound',
        'outbound' => 'outbound',
        '_outbound' => '_outbound',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     *
     * @var \stdClass
     */
    private $_additionalProperties;

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
     * @param string|null $bound
     * @param string|null $outbound
     * @param string|null $_outbound
     */
    public function __construct($bound = null, $outbound = null, $_outbound = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->bound = $bound;
        $this->outbound = $outbound;
        $this->_outbound = $_outbound;
    }

    /**
     * Object (`stdClass`) or array with name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return an associative array instead of `stdClass` object.
     * @return array|\stdClass
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
     * @param \stdClass|array $additionalProperties Map of property name/value pairs to add.
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
        $clone->_additionalProperties = new \stdClass();
        return $clone;
    }

    /**
     * @return string|null
     */
    public function getBound()
    {
        return isset($this->bound) ? $this->bound : null;
    }

    /**
     * @param string $bound
     * @param bool $validate
     * @return self
     */
    public function withBound($bound, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($bound, self::$_schema['properties']['bound']);
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
     * @return string|null
     */
    public function get_Outbound()
    {
        return isset($this->outbound) ? $this->outbound : null;
    }

    /**
     * @param string $outbound
     * @param bool $validate
     * @return self
     */
    public function with_Outbound($outbound, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($outbound, self::$_schema['properties']['outbound']);
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
    public function without_Outbound()
    {
        $clone = clone $this;
        unset($clone->outbound);

        return $clone;
    }

    /**
     * @return string|null
     */
    public function get_Outbound_1()
    {
        return isset($this->_outbound) ? $this->_outbound : null;
    }

    /**
     * @param string $_outbound
     * @param bool $validate
     * @return self
     */
    public function with_Outbound_1($_outbound, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_outbound, self::$_schema['properties']['_outbound']);
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
    public function without_Outbound_1()
    {
        $clone = clone $this;
        unset($clone->_outbound);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return MyClass Created instance
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

        $bound = isset($input->{'bound'}) ? $input->{'bound'} : null;
        $outbound = isset($input->{'outbound'}) ? $input->{'outbound'} : null;
        $_outbound = isset($input->{'_outbound'}) ? $input->{'_outbound'} : null;

        $obj = new self($bound, $outbound, $_outbound);

        $_additionalProperties = array_diff_key(get_object_vars($input), self::$_namesMap);
        if (!empty($_additionalProperties)) {
            $obj->_additionalProperties = (object) $_additionalProperties;
        }

        return $obj;
    }

    /**
     * Converts this object to array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray()
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

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
     * Converts this object to a stdClass that can be JSON-serialized
     *
     * @return \stdClass Converted object
     */
    public function toStdClass()
    {
        $output = $this->_additionalProperties;

        if (isset($this->bound)) {
            $output->{'bound'} = $this->bound;
        }
        if (isset($this->outbound)) {
            $output->{'outbound'} = $this->outbound;
        }
        if (isset($this->_outbound)) {
            $output->{'_outbound'} = $this->_outbound;
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
            $errors = array_map(function(array $e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
