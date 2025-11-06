<?php

namespace Ns\DefinitionsFilter_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
        '$schema' => 'http://json-schema.org/draft-07/schema#',
        'title' => 'Definitions filter fixture',
        'type' => 'object',
        'properties' => [
            'rootValue' => [
                'type' => 'string',
            ],
            'inlineObject' => [
                'type' => 'object',
                'properties' => [
                    'inlineValue' => [
                        'type' => 'integer',
                    ],
                ],
                'required' => [
                    'inlineValue',
                ],
            ],
        ],
        'definitions' => [
            'Primary' => [
                'type' => 'object',
                'properties' => [
                    'dep' => [
                        '$ref' => '#/definitions/Dependency',
                    ],
                    'helper' => [
                        '$ref' => '#/$defs/SupportChain',
                    ],
                    'inlineObject' => [
                        'type' => 'object',
                        'properties' => [
                            'label' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                ],
                'required' => [
                    'dep',
                    'helper',
                ],
            ],
            'Dependency' => [
                'type' => 'object',
                'properties' => [
                    'leaf' => [
                        '$ref' => '#/definitions/Leaf',
                    ],
                ],
                'required' => [
                    'leaf',
                ],
            ],
            'Leaf' => [
                'type' => 'object',
                'properties' => [
                    'value' => [
                        'type' => 'string',
                    ],
                ],
                'required' => [
                    'value',
                ],
            ],
            'Ignored' => [
                'type' => 'object',
                'properties' => [
                    'skip' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
        '$defs' => [
            'Support' => [
                'type' => 'object',
                'properties' => [
                    'detail' => [
                        'type' => 'string',
                    ],
                ],
                'required' => [
                    'detail',
                ],
            ],
            'SupportChain' => [
                'type' => 'object',
                'properties' => [
                    'support' => [
                        '$ref' => '#/$defs/Support',
                    ],
                    'extra' => [
                        '$ref' => '#/definitions/Dependency',
                    ],
                ],
                'required' => [
                    'support',
                ],
            ],
            'Unwanted' => [
                'type' => 'object',
                'properties' => [
                    'drop' => [
                        'type' => 'integer',
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
        'rootValue' => 'rootValue',
        'inlineObject' => 'inlineObject',
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
    private $rootValue = null;

    /**
     * @var MyClassInlineObject|null
     */
    private $inlineObject = null;

    /**
     * @param string|null $rootValue
     * @param MyClassInlineObject|null $inlineObject
     */
    public function __construct($rootValue = null, MyClassInlineObject $inlineObject = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->rootValue = $rootValue;
        $this->inlineObject = $inlineObject;
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
    public function getRootValue()
    {
        return isset($this->rootValue) ? $this->rootValue : null;
    }

    /**
     * @param string $rootValue
     * @param bool $validate
     * @return self
     */
    public function withRootValue($rootValue, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($rootValue, self::$_schema['properties']['rootValue']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->rootValue = $rootValue;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutRootValue()
    {
        $clone = clone $this;
        unset($clone->rootValue);

        return $clone;
    }

    /**
     * @return MyClassInlineObject|null
     */
    public function getInlineObject()
    {
        return isset($this->inlineObject) ? $this->inlineObject : null;
    }

    /**
     * @return self
     */
    public function withInlineObject(MyClassInlineObject $inlineObject)
    {
        $clone = clone $this;
        $clone->inlineObject = $inlineObject;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutInlineObject()
    {
        $clone = clone $this;
        unset($clone->inlineObject);

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

        $rootValue = isset($input->{'rootValue'}) ? $input->{'rootValue'} : null;
        $inlineObject = isset($input->{'inlineObject'})
            ? MyClassInlineObject::fromInput($input->{'inlineObject'}, $validate)
            : null;

        $obj = new self($rootValue, $inlineObject);

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

        if (isset($this->rootValue)) {
            $output['rootValue'] = $this->rootValue;
        }
        if (isset($this->inlineObject)) {
            $output['inlineObject'] = $this->inlineObject->toArray();
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

        if (isset($this->rootValue)) {
            $output->{'rootValue'} = $this->rootValue;
        }
        if (isset($this->inlineObject)) {
            $output->{'inlineObject'} = $this->inlineObject->toStdClass();
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
        $this->_additionalProperties = json_decode(json_encode($this->_additionalProperties));

        if (isset($this->inlineObject)) {
            $this->inlineObject = clone $this->inlineObject;
        }
    }
}
