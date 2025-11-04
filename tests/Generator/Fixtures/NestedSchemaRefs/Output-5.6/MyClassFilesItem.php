<?php

namespace Ns\NestedSchemaRefs_5_6;

class MyClassFilesItem
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
        'properties' => [
            'input' => [
                'type' => 'string',
            ],
            'options' => [
                '$ref' => '#/definitions/OptionsObject',
            ],
        ],
        'definitions' => [
            'OptionsObject' => [
                'properties' => [
                    'output' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     *
     * @var array
     */
    private static $_namesMap = [
        'input' => 'input',
        'options' => 'options',
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
    private $input = null;

    /**
     * @var OptionsObject|null
     */
    private $options = null;

    /**
     * @param string|null $_input
     * @param OptionsObject|null $options
     */
    public function __construct($_input = null, OptionsObject $options = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->input = $_input;
        $this->options = $options;
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
    public function getInput()
    {
        return isset($this->input) ? $this->input : null;
    }

    /**
     * @param string $_input
     * @param bool $validate
     * @return self
     */
    public function withInput($_input, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_input, self::$_schema['properties']['input']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->input = $_input;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutInput()
    {
        $clone = clone $this;
        unset($clone->input);

        return $clone;
    }

    /**
     * @return OptionsObject|null
     */
    public function getOptions()
    {
        return isset($this->options) ? $this->options : null;
    }

    /**
     * @return self
     */
    public function withOptions(OptionsObject $options)
    {
        $clone = clone $this;
        $clone->options = $options;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptions()
    {
        $clone = clone $this;
        unset($clone->options);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return MyClassFilesItem Created instance
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

        $_input = isset($input->{'input'}) ? $input->{'input'} : null;
        $options = isset($input->{'options'})
            ? OptionsObject::fromInput($input->{'options'}, $validate)
            : null;

        $obj = new self($_input, $options);

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

        if (isset($this->input)) {
            $output['input'] = $this->input;
        }
        if (isset($this->options)) {
            $output['options'] = $this->options->toArray();
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

        if (isset($this->input)) {
            $output->{'input'} = $this->input;
        }
        if (isset($this->options)) {
            $output->{'options'} = $this->options->toStdClass();
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

    public function __clone()
    {
        if (isset($this->options)) {
            $this->options = clone $this->options;
        }
    }
}
