<?php

namespace Ns\EnumUnsupported_5_6;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
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
     * @var int|float|null
     */
    private $floatEnum = null;

    /**
     * @var 0|1|2|3|null
     */
    private $floatEnumRef = null;

    /**
     * @var bool|null
     */
    private $boolEnum = null;

    /**
     * @var 0|null
     */
    private $boolEnumRef = null;

    /**
     * @var
     */
    private $requiredBoolEnumRef;

    /**
     * @param 0 $requiredBoolEnumRef
     */
    public function __construct(string $requiredBoolEnumRef)
    {
        $this->requiredBoolEnumRef = $requiredBoolEnumRef;
    }

    /**
     * @return int|float|null
     */
    public function getFloatEnum()
    {
        return $this->floatEnum;
    }

    /**
     * @return 0|1|2|3|null
     */
    public function getFloatEnumRef()
    {
        return $this->floatEnumRef;
    }

    /**
     * @return bool|null
     */
    public function getBoolEnum()
    {
        return $this->boolEnum;
    }

    /**
     * @return 0|null
     */
    public function getBoolEnumRef()
    {
        return $this->boolEnumRef;
    }

    /**
     * @return
     */
    public function getRequiredBoolEnumRef()
    {
        return $this->requiredBoolEnumRef;
    }

    /**
     * @param int|float $floatEnum
     * @return self
     * @param bool $validate
     */
    public function withFloatEnum($floatEnum, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($floatEnum, self::$schema['properties']['floatEnum']);
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
     * @param 0|1|2|3 $floatEnumRef
     * @return self
     * @param bool $validate
     */
    public function withFloatEnumRef(int $floatEnumRef, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($floatEnumRef, self::$schema['properties']['floatEnumRef']);
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
     * @param bool $boolEnum
     * @return self
     * @param bool $validate
     */
    public function withBoolEnum($boolEnum, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($boolEnum, self::$schema['properties']['boolEnum']);
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
     * @param 0 $boolEnumRef
     * @return self
     * @param bool $validate
     */
    public function withBoolEnumRef(string $boolEnumRef, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($boolEnumRef, self::$schema['properties']['boolEnumRef']);
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
     * @param 0 $requiredBoolEnumRef
     * @return self
     * @param bool $validate
     */
    public function withRequiredBoolEnumRef(string $requiredBoolEnumRef, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($requiredBoolEnumRef, self::$schema['properties']['requiredBoolEnumRef']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->requiredBoolEnumRef = $requiredBoolEnumRef;

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return Foo Created instance
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

        $floatEnum = isset($input->{'floatEnum'}) ? $input->{'floatEnum'} : null;
        $floatEnumRef = isset($input->{'floatEnumRef'}) ? $input->{'floatEnumRef'} : null;
        $boolEnum = isset($input->{'boolEnum'}) ? $input->{'boolEnum'} : null;
        $boolEnumRef = isset($input->{'boolEnumRef'}) ? $input->{'boolEnumRef'} : null;
        $requiredBoolEnumRef = $input->{'requiredBoolEnumRef'};

        $obj = new self($requiredBoolEnumRef);
        $obj->floatEnum = $floatEnum;
        $obj->floatEnumRef = $floatEnumRef;
        $obj->boolEnum = $boolEnum;
        $obj->boolEnumRef = $boolEnumRef;
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
