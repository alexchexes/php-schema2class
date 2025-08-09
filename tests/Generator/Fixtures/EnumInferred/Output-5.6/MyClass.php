<?php

namespace Ns\EnumInferred_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
        'required' => [
            'inferString',
            'inferInt',
            'inferMixed',
        ],
        'properties' => [
            'inferString' => [
                'enum' => [
                    '3',
                    '4',
                    '',
                ],
            ],
            'inferInt' => [
                'enum' => [
                    3,
                    4,
                    5,
                ],
            ],
            'inferMixed' => [
                'enum' => [
                    '42',
                    42,
                    42.5,
                    false,
                    null,
                ],
            ],
            'inferStringOpt' => [
                'enum' => [
                    '3',
                    '4',
                    '',
                ],
            ],
            'inferIntOpt' => [
                'enum' => [
                    3,
                    4,
                    5,
                ],
            ],
            'inferMixedOpt' => [
                'enum' => [
                    '42',
                    42,
                    42.5,
                    false,
                    null,
                ],
            ],
        ],
    ];

    /**
     * Map of optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private $_providedOptionals = [];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     *
     * @var \stdClass
     */
    private $_additionalProperties;

    /**
     * @var '3'|'4'|''
     */
    private $inferString;

    /**
     * @var 3|4|5
     */
    private $inferInt;

    /**
     * @var '42'|42|42.5|false|null
     */
    private $inferMixed;

    /**
     * @var '3'|'4'|''|null
     */
    private $inferStringOpt = null;

    /**
     * @var 3|4|5|null
     */
    private $inferIntOpt = null;

    /**
     * @var '42'|42|42.5|false|null
     */
    private $inferMixedOpt = null;

    /**
     * @param '3'|'4'|'' $inferString
     * @param 3|4|5 $inferInt
     * @param '42'|42|42.5|false|null $inferMixed
     * @param '3'|'4'|''|null $inferStringOpt
     * @param 3|4|5|null $inferIntOpt
     * @param '42'|42|42.5|false|null $inferMixedOpt
     */
    public function __construct($inferString, $inferInt, $inferMixed, $inferStringOpt = null, $inferIntOpt = null, $inferMixedOpt = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->inferString = $inferString;
        $this->inferInt = $inferInt;
        $this->inferMixed = $inferMixed;
        $this->inferStringOpt = $inferStringOpt;
        $this->inferIntOpt = $inferIntOpt;
        if ($inferMixedOpt !== null) {
            $this->inferMixedOpt = $inferMixedOpt;
            $this->_providedOptionals['inferMixedOpt'] = true;
        };
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
     * @return '3'|'4'|''
     */
    public function getInferString()
    {
        return $this->inferString;
    }

    /**
     * @param '3'|'4'|'' $inferString
     * @param bool $validate
     * @return self
     */
    public function withInferString($inferString, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inferString, self::$_schema['properties']['inferString']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->inferString = $inferString;

        return $clone;
    }

    /**
     * @return 3|4|5
     */
    public function getInferInt()
    {
        return $this->inferInt;
    }

    /**
     * @param 3|4|5 $inferInt
     * @param bool $validate
     * @return self
     */
    public function withInferInt($inferInt, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inferInt, self::$_schema['properties']['inferInt']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->inferInt = $inferInt;

        return $clone;
    }

    /**
     * @return '42'|42|42.5|false|null
     */
    public function getInferMixed()
    {
        return $this->inferMixed;
    }

    /**
     * @param '42'|42|42.5|false|null $inferMixed
     * @param bool $validate
     * @return self
     */
    public function withInferMixed($inferMixed, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inferMixed, self::$_schema['properties']['inferMixed']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->inferMixed = $inferMixed;

        return $clone;
    }

    /**
     * @return '3'|'4'|''|null
     */
    public function getInferStringOpt()
    {
        return isset($this->inferStringOpt) ? $this->inferStringOpt : null;
    }

    /**
     * @param '3'|'4'|'' $inferStringOpt
     * @param bool $validate
     * @return self
     */
    public function withInferStringOpt($inferStringOpt, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inferStringOpt, self::$_schema['properties']['inferStringOpt']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->inferStringOpt = $inferStringOpt;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutInferStringOpt()
    {
        $clone = clone $this;
        unset($clone->inferStringOpt);

        return $clone;
    }

    /**
     * @return 3|4|5|null
     */
    public function getInferIntOpt()
    {
        return isset($this->inferIntOpt) ? $this->inferIntOpt : null;
    }

    /**
     * @param 3|4|5 $inferIntOpt
     * @param bool $validate
     * @return self
     */
    public function withInferIntOpt($inferIntOpt, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inferIntOpt, self::$_schema['properties']['inferIntOpt']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->inferIntOpt = $inferIntOpt;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutInferIntOpt()
    {
        $clone = clone $this;
        unset($clone->inferIntOpt);

        return $clone;
    }

    /**
     * @return '42'|42|42.5|false|null
     */
    public function getInferMixedOpt()
    {
        return isset($this->inferMixedOpt) ? $this->inferMixedOpt : null;
    }

    /**
     * @param '42'|42|42.5|false|null $inferMixedOpt
     * @param bool $validate
     * @return self
     */
    public function withInferMixedOpt($inferMixedOpt, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inferMixedOpt, self::$_schema['properties']['inferMixedOpt']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->inferMixedOpt = $inferMixedOpt;
        $clone->_providedOptionals['inferMixedOpt'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutInferMixedOpt()
    {
        $clone = clone $this;
        unset($clone->inferMixedOpt);
        unset($clone->_providedOptionals['inferMixedOpt']);

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

        $__providedOptionals = [];
        $inferString = $input->{'inferString'};
        $inferInt = (int)$input->{'inferInt'};
        $inferMixed = ($input->{'inferMixed'} !== null ? $input->{'inferMixed'} : null);
        $inferStringOpt = isset($input->{'inferStringOpt'}) ? $input->{'inferStringOpt'} : null;
        $inferIntOpt = isset($input->{'inferIntOpt'}) ? $input->{'inferIntOpt'} : null;
        $inferMixedOpt = null;
        if (property_exists($input, 'inferMixedOpt')) {
            $inferMixedOpt = ($input->{'inferMixedOpt'} !== null ? $input->{'inferMixedOpt'} : null);
            $__providedOptionals['inferMixedOpt'] = true;
        }

        $obj = new self(
            $inferString,
            $inferInt,
            $inferMixed,
            $inferStringOpt,
            $inferIntOpt,
            $inferMixedOpt
        );
        $obj->_providedOptionals = $__providedOptionals;

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

        $output['inferString'] = $this->inferString;
        $output['inferInt'] = $this->inferInt;
        $output['inferMixed'] = $this->inferMixed;
        if (isset($this->inferStringOpt)) {
            $output['inferStringOpt'] = $this->inferStringOpt;
        }
        if (isset($this->inferIntOpt)) {
            $output['inferIntOpt'] = $this->inferIntOpt;
        }
        if (isset($this->inferMixedOpt) || array_key_exists('inferMixedOpt', $this->_providedOptionals)) {
            $output['inferMixedOpt'] = ($this->inferMixedOpt !== null) ? ($this->inferMixedOpt) : null;
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

        $output->{'inferString'} = $this->inferString;
        $output->{'inferInt'} = $this->inferInt;
        $output->{'inferMixed'} = $this->inferMixed;
        if (isset($this->inferStringOpt)) {
            $output->{'inferStringOpt'} = $this->inferStringOpt;
        }
        if (isset($this->inferIntOpt)) {
            $output->{'inferIntOpt'} = $this->inferIntOpt;
        }
        if (isset($this->inferMixedOpt) || array_key_exists('inferMixedOpt', $this->_providedOptionals)) {
            $output->{'inferMixedOpt'} = ($this->inferMixedOpt !== null) ? ($this->inferMixedOpt) : null;
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

    /**
     * Checks if an optional nullable property was explicitly set
     *
     * @param string $propertyName Property name to check (exactly as it appears in the schema)
     * @return bool
     */
    public function isOptionalProvided(string $propertyName)
    {
        return array_key_exists($propertyName, $this->_providedOptionals);
    }
}
