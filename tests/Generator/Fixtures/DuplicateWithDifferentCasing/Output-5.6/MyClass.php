<?php

namespace Ns\DuplicateWithDifferentCasing_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
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
     * Map of name/value pairs for properties not specified in the schema.
     *
     * @var \stdClass
     */
    private $_additionalProperties;

    /**
     * @var string|null
     * @deprecated
     */
    private $foobar = null;

    /**
     * @var string
     */
    private $fooBar;

    /**
     * @var string|null
     * @deprecated
     */
    private $bar = null;

    /**
     * @param string $fooBar
     * @param string|null $foobar
     * @param string|null $bar
     */
    public function __construct($fooBar, $foobar = null, $bar = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->fooBar = $fooBar;
        $this->foobar = $foobar;
        $this->bar = $bar;
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
     * @return string
     */
    public function getFooBar()
    {
        return $this->fooBar;
    }

    /**
     * @param string $fooBar
     * @param bool $validate
     * @return self
     */
    public function withFooBar($fooBar, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($fooBar, self::$_schema['properties']['fooBar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->fooBar = $fooBar;

        return $clone;
    }

    /**
     * @return string|null
     * @deprecated
     */
    public function getBar()
    {
        return isset($this->bar) ? $this->bar : null;
    }

    /**
     * @param string $bar
     * @param bool $validate
     * @return self
     * @deprecated
     */
    public function withBar($bar, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($bar, self::$_schema['properties']['bar']);
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

        $fooBar = $input->{'fooBar'};
        $foobar = isset($input->{'foobar'}) ? $input->{'foobar'} : null;
        $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;

        $obj = new self($fooBar, $foobar, $bar);

        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray()
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

        if (isset($this->foobar)) {
            $output['foobar'] = $this->foobar;
        }
        $output['fooBar'] = $this->fooBar;
        if (isset($this->bar)) {
            $output['bar'] = $this->bar;
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

        if (isset($this->foobar)) {
            $output->{'foobar'} = $this->foobar;
        }
        $output->{'fooBar'} = $this->fooBar;
        if (isset($this->bar)) {
            $output->{'bar'} = $this->bar;
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
            $errors = array_map(function($e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
