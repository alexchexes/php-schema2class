<?php

namespace Ns\EscapeDefinitionName_5_6;

class BarTest
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
        'type' => 'object',
        'properties' => [
            'exampleProp' => [
                'anyOf' => [
                    [
                        '$ref' => '#/definitions/Foo<Test>',
                    ],
                    [
                        '$ref' => '#/definitions/МойКласс',
                    ],
                    [
                        '$ref' => '#/definitions/FooTest',
                    ],
                ],
            ],
        ],
        'definitions' => [
            'Foo<Test>' => [
                'type' => 'object',
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'FooTest' => [
                'type' => 'object',
                'properties' => [
                    'b' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'МойКласс' => [
                'type' => 'object',
                'properties' => [
                    'c' => [
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
        'exampleProp' => 'exampleProp',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     *
     * @var \stdClass
     */
    private $_additionalProperties;

    /**
     * @var FooTest|MoiKlass|FooTest_1|null
     */
    private $exampleProp = null;

    /**
     * @param FooTest|MoiKlass|FooTest_1|null $exampleProp
     */
    public function __construct($exampleProp = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->exampleProp = $exampleProp;
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
     * @return FooTest|MoiKlass|FooTest_1|null
     */
    public function getExampleProp()
    {
        return isset($this->exampleProp) ? $this->exampleProp : null;
    }

    /**
     * @param FooTest|MoiKlass|FooTest_1 $exampleProp
     * @param bool $validate
     * @return self
     */
    public function withExampleProp($exampleProp, $validate = true)
    {
        $clone = clone $this;
        $clone->exampleProp = $exampleProp;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    /**
     * @return self
     */
    public function withoutExampleProp()
    {
        $clone = clone $this;
        unset($clone->exampleProp);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return BarTest Created instance
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

        $exampleProp = isset($input->{'exampleProp'})
            ? ((FooTest::validateInput($input->{'exampleProp'}, true))
                ? FooTest::fromInput($input->{'exampleProp'}, $validate)
                : ((MoiKlass::validateInput($input->{'exampleProp'}, true))
                    ? MoiKlass::fromInput($input->{'exampleProp'}, $validate)
                    : ((FooTest_1::validateInput($input->{'exampleProp'}, true))
                        ? FooTest_1::fromInput($input->{'exampleProp'}, $validate)
                        : null
                    )
                )
            )
            : null;

        $obj = new self($exampleProp);

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

        if (isset($this->exampleProp)) {
            if ($this->exampleProp instanceof FooTest
                || $this->exampleProp instanceof MoiKlass
                || $this->exampleProp instanceof FooTest_1
            ) {
                $output['exampleProp'] = $this->exampleProp->toArray();
            }
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

        if (isset($this->exampleProp)) {
            if ($this->exampleProp instanceof FooTest
                || $this->exampleProp instanceof MoiKlass
                || $this->exampleProp instanceof FooTest_1
            ) {
                $output->{'exampleProp'} = $this->exampleProp->toStdClass();
            }
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
