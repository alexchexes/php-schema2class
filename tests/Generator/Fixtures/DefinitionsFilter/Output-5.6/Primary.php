<?php

namespace Ns\DefinitionsFilter_5_6;

class Primary
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
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
        'definitions' => [
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
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     *
     * @var array
     */
    private static $_namesMap = [
        'dep' => 'dep',
        'helper' => 'helper',
        'inlineObject' => 'inlineObject',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     *
     * @var \stdClass
     */
    private $_additionalProperties;

    private $dep;

    private $helper;

    /**
     * @var PrimaryInlineObject|null
     */
    private $inlineObject = null;

    /**
     * @param PrimaryInlineObject|null $inlineObject
     */
    public function __construct(Dependency $dep, SupportChain $helper, PrimaryInlineObject $inlineObject = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->dep = $dep;
        $this->helper = $helper;
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

    public function getDep()
    {
        return $this->dep;
    }

    /**
     * @return self
     */
    public function withDep(Dependency $dep)
    {
        $clone = clone $this;
        $clone->dep = $dep;

        return $clone;
    }

    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @return self
     */
    public function withHelper(SupportChain $helper)
    {
        $clone = clone $this;
        $clone->helper = $helper;

        return $clone;
    }

    /**
     * @return PrimaryInlineObject|null
     */
    public function getInlineObject()
    {
        return isset($this->inlineObject) ? $this->inlineObject : null;
    }

    /**
     * @return self
     */
    public function withInlineObject(PrimaryInlineObject $inlineObject)
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
     * @return Primary Created instance
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

        $dep = Dependency::fromInput($input->{'dep'}, $validate);
        $helper = SupportChain::fromInput($input->{'helper'}, $validate);
        $inlineObject = isset($input->{'inlineObject'})
            ? PrimaryInlineObject::fromInput($input->{'inlineObject'}, $validate)
            : null;

        $obj = new self($dep, $helper, $inlineObject);

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

        $output['dep'] = $this->dep->toArray();
        $output['helper'] = $this->helper->toArray();
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

        $output->{'dep'} = $this->dep->toStdClass();
        $output->{'helper'} = $this->helper->toStdClass();
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

        $this->dep = clone $this->dep;
        $this->helper = clone $this->helper;
        if (isset($this->inlineObject)) {
            $this->inlineObject = clone $this->inlineObject;
        }
    }
}
