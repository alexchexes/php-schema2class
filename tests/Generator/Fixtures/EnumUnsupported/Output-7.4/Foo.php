<?php

declare(strict_types=1);

namespace Ns\EnumUnsupported_7_4;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
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
     */
    private static array $_namesMap = [
        'floatEnum' => 'floatEnum',
        'floatEnumRef' => 'floatEnumRef',
        'boolEnum' => 'boolEnum',
        'boolEnumRef' => 'boolEnumRef',
        'requiredBoolEnumRef' => 'requiredBoolEnumRef',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

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
    private ?bool $boolEnum = null;

    /**
     * @var false|null
     */
    private ?bool $boolEnumRef = null;

    /**
     * @var false
     */
    private bool $requiredBoolEnumRef;

    /**
     * @param false $requiredBoolEnumRef
     * @param 0|1.5|2.5|3.5|null $floatEnum
     * @param 0|1.5|2.5|3.5|null $floatEnumRef
     * @param false|null $boolEnum
     * @param false|null $boolEnumRef
     */
    public function __construct(bool $requiredBoolEnumRef, $floatEnum = null, $floatEnumRef = null, ?bool $boolEnum = null, ?bool $boolEnumRef = null)
    {
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
    public function getAdditionalProperties(bool $asArray = true)
    {
        return $asArray
            ? json_decode(json_encode($this->_additionalProperties), true)
            : $this->_additionalProperties;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param \stdClass|array $additionalProperties Map of property name/value pairs to add.
     */
    public function withAdditionalProperties($additionalProperties): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        return $clone;
    }

    /**
     * Removes all extra properties not specified in the schema.
     */
    public function withoutAdditionalProperties(): self
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
        return $this->floatEnum ?? null;
    }

    /**
     * @param 0|1.5|2.5|3.5 $floatEnum
     */
    public function withFloatEnum($floatEnum, bool $validate = true): self
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

    public function withoutFloatEnum(): self
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
        return $this->floatEnumRef ?? null;
    }

    /**
     * @param 0|1.5|2.5|3.5 $floatEnumRef
     */
    public function withFloatEnumRef($floatEnumRef, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->floatEnumRef = $floatEnumRef;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutFloatEnumRef(): self
    {
        $clone = clone $this;
        unset($clone->floatEnumRef);

        return $clone;
    }

    /**
     * @return false|null
     */
    public function getBoolEnum(): ?bool
    {
        return $this->boolEnum ?? null;
    }

    /**
     * @param false $boolEnum
     */
    public function withBoolEnum(bool $boolEnum, bool $validate = true): self
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

    public function withoutBoolEnum(): self
    {
        $clone = clone $this;
        unset($clone->boolEnum);

        return $clone;
    }

    /**
     * @return false|null
     */
    public function getBoolEnumRef(): ?bool
    {
        return $this->boolEnumRef ?? null;
    }

    /**
     * @param false $boolEnumRef
     */
    public function withBoolEnumRef(bool $boolEnumRef, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->boolEnumRef = $boolEnumRef;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutBoolEnumRef(): self
    {
        $clone = clone $this;
        unset($clone->boolEnumRef);

        return $clone;
    }

    /**
     * @return false
     */
    public function getRequiredBoolEnumRef(): bool
    {
        return $this->requiredBoolEnumRef;
    }

    /**
     * @param false $requiredBoolEnumRef
     */
    public function withRequiredBoolEnumRef(bool $requiredBoolEnumRef, bool $validate = true): self
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
    public static function fromInput($input, bool $validate = true): Foo
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
    public function toArray(): array
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
    public function toStdClass(): \stdClass
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
    public function validate(bool $return = false): bool
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
    public static function validateInput($input, bool $return = false): bool
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(
                fn (array $e): string => ($e["property"] ? $e["property"] . ": " : "") . $e["message"],
                $validator->getErrors(),
            );
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
