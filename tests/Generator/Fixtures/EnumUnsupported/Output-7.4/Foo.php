<?php

declare(strict_types=1);

namespace Ns\EnumUnsupported_7_4;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
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
     */
    public function __construct(bool $requiredBoolEnumRef)
    {
        $this->requiredBoolEnumRef = $requiredBoolEnumRef;
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
     * @return self
     * @param bool $validate
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

    /**
     * @return self
     */
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
        return $this->floatEnumRef;
    }

    /**
     * @param 0|1.5|2.5|3.5 $floatEnumRef
     * @return self
     * @param bool $validate
     */
    public function withFloatEnumRef($floatEnumRef, bool $validate = true): self
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
     * @return self
     * @param bool $validate
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

    /**
     * @return self
     */
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
     * @return self
     * @param bool $validate
     */
    public function withBoolEnumRef(bool $boolEnumRef, bool $validate = true): self
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
     * @return self
     * @param bool $validate
     */
    public function withRequiredBoolEnumRef(bool $requiredBoolEnumRef, bool $validate = true): self
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
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
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
    public function toArray(): array
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
    public function toStdClass(): \stdClass
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
     * Validates an input array
     *
     * @param array|object $input Input data
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result
     * @throws \InvalidArgumentException
     */
    public static function validateInput($input, bool $return = false): bool
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function(array $e): string {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
