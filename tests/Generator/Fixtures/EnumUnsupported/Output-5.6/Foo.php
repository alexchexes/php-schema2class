<?php

namespace Ns\EnumUnsupported_5_6;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
        'type' => 'object',
        'properties' => [
            'floatEnum' => [
                'type' => 'number',
                'enum' => [
                    0,
                    1.5,
                    2.5,
                    3.5,
                ],
            ],
            'floatEnumRef' => [
                '$ref' => '#/definitions/EnumFloat',
            ],
            'boolEnum' => [
                'type' => 'boolean',
                'enum' => [
                    false,
                ],
            ],
            'boolEnumRef' => [
                '$ref' => '#/definitions/EnumBool',
            ],
            'requiredBoolEnumRef' => [
                '$ref' => '#/definitions/EnumBool',
            ],
        ],
        'required' => [
            'requiredBoolEnumRef',
        ],
        'definitions' => [
            'EnumFloat' => [
                'type' => 'number',
                'enum' => [
                    0,
                    1.5,
                    2.5,
                    3.5,
                ],
            ],
            'EnumBool' => [
                'type' => 'boolean',
                'enum' => [
                    false,
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
        'floatEnum' => 'floatEnum',
        'floatEnumRef' => 'floatEnumRef',
        'boolEnum' => 'boolEnum',
        'boolEnumRef' => 'boolEnumRef',
        'requiredBoolEnumRef' => 'requiredBoolEnumRef',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     *
     * @var \stdClass
     */
    private $_additionalProperties;

    /**
     * @var 0|1.5|2.5|3.5|null
     */
    private $floatEnum = null;

    /**
     * @var 0|1.5|2.5|3.5|null
     */
    private $floatEnumRef = null;

    /**
     * @var false|null
     */
    private $boolEnum = null;

    /**
     * @var false|null
     */
    private $boolEnumRef = null;

    /**
     * @var false
     */
    private $requiredBoolEnumRef;

    /**
     * @param false $requiredBoolEnumRef
     * @param 0|1.5|2.5|3.5|null $floatEnum
     * @param 0|1.5|2.5|3.5|null $floatEnumRef
     * @param false|null $boolEnum
     * @param false|null $boolEnumRef
     */
    public function __construct(
        $requiredBoolEnumRef,
        $floatEnum = null,
        $floatEnumRef = null,
        $boolEnum = null,
        $boolEnumRef = null
    ) {
        $this->_additionalProperties = new \stdClass();

        $this->requiredBoolEnumRef = $requiredBoolEnumRef;
        $this->floatEnum = $floatEnum;
        $this->floatEnumRef = $floatEnumRef;
        $this->boolEnum = $boolEnum;
        $this->boolEnumRef = $boolEnumRef;
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
     * @return 0|1.5|2.5|3.5|null
     */
    public function getFloatEnum()
    {
        return isset($this->floatEnum) ? $this->floatEnum : null;
    }

    /**
     * @param 0|1.5|2.5|3.5 $floatEnum
     * @param bool $validate
     * @return self
     */
    public function withFloatEnum($floatEnum, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($floatEnum, self::$_schema['properties']['floatEnum']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->floatEnum = $floatEnum;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutFloatEnum()
    {
        $clone = clone $this;
        unset($clone->floatEnum);

        return $clone;
    }

    /**
     * @return 0|1.5|2.5|3.5|null
     */
    public function getFloatEnumRef()
    {
        return isset($this->floatEnumRef) ? $this->floatEnumRef : null;
    }

    /**
     * @param 0|1.5|2.5|3.5 $floatEnumRef
     * @param bool $validate
     * @return self
     */
    public function withFloatEnumRef($floatEnumRef, $validate = true)
    {
        $clone = clone $this;
        $clone->floatEnumRef = $floatEnumRef;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    /**
     * @return self
     */
    public function withoutFloatEnumRef()
    {
        $clone = clone $this;
        unset($clone->floatEnumRef);

        return $clone;
    }

    /**
     * @return false|null
     */
    public function getBoolEnum()
    {
        return isset($this->boolEnum) ? $this->boolEnum : null;
    }

    /**
     * @param false $boolEnum
     * @param bool $validate
     * @return self
     */
    public function withBoolEnum($boolEnum, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($boolEnum, self::$_schema['properties']['boolEnum']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->boolEnum = $boolEnum;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBoolEnum()
    {
        $clone = clone $this;
        unset($clone->boolEnum);

        return $clone;
    }

    /**
     * @return false|null
     */
    public function getBoolEnumRef()
    {
        return isset($this->boolEnumRef) ? $this->boolEnumRef : null;
    }

    /**
     * @param false $boolEnumRef
     * @param bool $validate
     * @return self
     */
    public function withBoolEnumRef($boolEnumRef, $validate = true)
    {
        $clone = clone $this;
        $clone->boolEnumRef = $boolEnumRef;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBoolEnumRef()
    {
        $clone = clone $this;
        unset($clone->boolEnumRef);

        return $clone;
    }

    /**
     * @return false
     */
    public function getRequiredBoolEnumRef()
    {
        return $this->requiredBoolEnumRef;
    }

    /**
     * @param false $requiredBoolEnumRef
     * @param bool $validate
     * @return self
     */
    public function withRequiredBoolEnumRef($requiredBoolEnumRef, $validate = true)
    {
        $clone = clone $this;
        $clone->requiredBoolEnumRef = $requiredBoolEnumRef;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Foo Created instance
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

        $requiredBoolEnumRef = $input->{'requiredBoolEnumRef'};
        $floatEnum = isset($input->{'floatEnum'})
            ? (str_contains((string)$input->{'floatEnum'}, '.')
                ? (float)$input->{'floatEnum'}
                : (int)$input->{'floatEnum'}
            )
            : null;
        $floatEnumRef = isset($input->{'floatEnumRef'}) ? $input->{'floatEnumRef'} : null;
        $boolEnum = isset($input->{'boolEnum'}) ? $input->{'boolEnum'} : null;
        $boolEnumRef = isset($input->{'boolEnumRef'}) ? $input->{'boolEnumRef'} : null;

        $obj = new self(
            $requiredBoolEnumRef,
            $floatEnum,
            $floatEnumRef,
            $boolEnum,
            $boolEnumRef
        );

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

        if (isset($this->floatEnum)) {
            $output['floatEnum'] = $this->floatEnum;
        }
        if (isset($this->floatEnumRef)) {
            $output['floatEnumRef'] = $this->floatEnumRef;
        }
        if (isset($this->boolEnum)) {
            $output['boolEnum'] = $this->boolEnum;
        }
        if (isset($this->boolEnumRef)) {
            $output['boolEnumRef'] = $this->boolEnumRef;
        }
        $output['requiredBoolEnumRef'] = $this->requiredBoolEnumRef;

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

        if (isset($this->floatEnum)) {
            $output->{'floatEnum'} = $this->floatEnum;
        }
        if (isset($this->floatEnumRef)) {
            $output->{'floatEnumRef'} = $this->floatEnumRef;
        }
        if (isset($this->boolEnum)) {
            $output->{'boolEnum'} = $this->boolEnum;
        }
        if (isset($this->boolEnumRef)) {
            $output->{'boolEnumRef'} = $this->boolEnumRef;
        }
        $output->{'requiredBoolEnumRef'} = $this->requiredBoolEnumRef;

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
