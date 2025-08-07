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
    public function __construct($requiredBoolEnumRef, $floatEnum = null, $floatEnumRef = null, $boolEnum = null, $boolEnumRef = null)
    {
        $this->requiredBoolEnumRef = $requiredBoolEnumRef;
        $this->floatEnum = $floatEnum;
        $this->floatEnumRef = $floatEnumRef;
        $this->boolEnum = $boolEnum;
        $this->boolEnumRef = $boolEnumRef;
    }

    /**
     * @return 0|1.5|2.5|3.5|null
     */
    public function getFloatEnum()
    {
        return $this->floatEnum;
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
        return $this->floatEnumRef;
    }

    /**
     * @param 0|1.5|2.5|3.5 $floatEnumRef
     * @param bool $validate
     * @return self
     */
    public function withFloatEnumRef($floatEnumRef, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($floatEnumRef, self::$_schema['properties']['floatEnumRef']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->floatEnumRef = $floatEnumRef;

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
        return $this->boolEnum;
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
        return $this->boolEnumRef;
    }

    /**
     * @param false $boolEnumRef
     * @param bool $validate
     * @return self
     */
    public function withBoolEnumRef($boolEnumRef, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($boolEnumRef, self::$_schema['properties']['boolEnumRef']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->boolEnumRef = $boolEnumRef;

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
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($requiredBoolEnumRef, self::$_schema['properties']['requiredBoolEnumRef']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->requiredBoolEnumRef = $requiredBoolEnumRef;

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
        $floatEnum = isset($input->{'floatEnum'}) ? $input->{'floatEnum'} : null;
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
        $output = new \stdClass();
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
            $errors = array_map(function($e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
