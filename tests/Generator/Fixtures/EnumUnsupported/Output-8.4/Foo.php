<?php

declare(strict_types=1);

namespace Ns\EnumUnsupported_8_4;

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
    private int|float|null $floatEnum = null;

    /**
     * @var 0|1.5|2.5|3.5|null
     */
    private int|float|null $floatEnumRef = null;

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
    public function __construct(bool $requiredBoolEnumRef, int|float|null $floatEnum = null, int|float|null $floatEnumRef = null, ?bool $boolEnum = null, ?bool $boolEnumRef = null)
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
    public function getFloatEnum(): int|float|null
    {
        return $this->floatEnum;
    }

    /**
     * @param 0|1.5|2.5|3.5 $floatEnum
     */
    public function withFloatEnum(int|float $floatEnum, bool $validate = true): self
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
    public function getFloatEnumRef(): int|float|null
    {
        return $this->floatEnumRef;
    }

    /**
     * @param 0|1.5|2.5|3.5 $floatEnumRef
     */
    public function withFloatEnumRef(int|float $floatEnumRef, bool $validate = true): self
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
        return $this->boolEnum;
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
        return $this->boolEnumRef;
    }

    /**
     * @param false $boolEnumRef
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
    public static function fromInput(array|object $input, bool $validate = true): Foo
    {
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
    public static function validateInput(array|object $input, bool $return = false): bool
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
