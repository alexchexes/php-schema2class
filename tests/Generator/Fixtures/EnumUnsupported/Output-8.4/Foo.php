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
    private static array $schema = [
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
     */
    public function __construct(bool $requiredBoolEnumRef)
    {
        $this->requiredBoolEnumRef = $requiredBoolEnumRef;
    }

    /**
     * @return int|float|null
     */
    public function getFloatEnum(): int|float|null
    {
        return $this->floatEnum;
    }

    /**
     * @return 0|1.5|2.5|3.5|null
     */
    public function getFloatEnumRef(): int|float|null
    {
        return $this->floatEnumRef;
    }

    /**
     * @return false|null
     */
    public function getBoolEnum(): ?bool
    {
        return $this->boolEnum ?? null;
    }

    /**
     * @return false|null
     */
    public function getBoolEnumRef(): ?bool
    {
        return $this->boolEnumRef ?? null;
    }

    /**
     * @return false
     */
    public function getRequiredBoolEnumRef(): bool
    {
        return $this->requiredBoolEnumRef;
    }

    /**
     * @param int|float $floatEnum
     * @return self
     * @param bool $validate
     */
    public function withFloatEnum(int|float $floatEnum, bool $validate = true): self
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
    public function withoutFloatEnum(): self
    {
        $clone = clone $this;
        unset($clone->floatEnum);

        return $clone;
    }

    /**
     * @param 0|1.5|2.5|3.5 $floatEnumRef
     * @return self
     * @param bool $validate
     */
    public function withFloatEnumRef(int|float $floatEnumRef, bool $validate = true): self
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
    public function withoutFloatEnumRef(): self
    {
        $clone = clone $this;
        unset($clone->floatEnumRef);

        return $clone;
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
    public function withoutBoolEnum(): self
    {
        $clone = clone $this;
        unset($clone->boolEnum);

        return $clone;
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
    public function withoutBoolEnumRef(): self
    {
        $clone = clone $this;
        unset($clone->boolEnumRef);

        return $clone;
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
    public static function buildFromInput(array|object $input, bool $validate = true): Foo
    {
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
        $validator->validate($input, self::$schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function(array $e): string {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
