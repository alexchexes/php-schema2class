<?php

declare(strict_types=1);

namespace Ns\TypedArrayOfUnions_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'properties' => [
            'arrayOfUnions' => [
                'type' => 'array',
                'items' => [
                    'oneOf' => [
                        [
                            'type' => 'string',
                        ],
                        [
                            'type' => 'number',
                        ],
                    ],
                ],
            ],
            'arrayOfRefUnions' => [
                'type' => 'array',
                'items' => [
                    'oneOf' => [
                        [
                            '$ref' => '#/definitions/SomeStr',
                        ],
                        [
                            '$ref' => '#/definitions/SomeNumber',
                        ],
                    ],
                ],
            ],
            'arrayOfRefAndNotRefUnions' => [
                'type' => 'array',
                'items' => [
                    'oneOf' => [
                        [
                            '$ref' => '#/definitions/SomeStr',
                        ],
                        [
                            'type' => 'string',
                        ],
                        [
                            '$ref' => '#/definitions/SomeNumber',
                        ],
                        [
                            'type' => 'number',
                        ],
                    ],
                ],
            ],
            'arrayOfUnionOfTypedArrayAndString' => [
                'type' => 'array',
                'items' => [
                    'oneOf' => [
                        [
                            'type' => 'array',
                            'items' => [
                                'type' => 'string',
                            ],
                        ],
                        [
                            'type' => 'string',
                        ],
                    ],
                ],
            ],
            'arrayOfUnionWithOneType' => [
                'type' => 'array',
                'items' => [
                    'oneOf' => [
                        [
                            'type' => 'string',
                        ],
                    ],
                ],
            ],
        ],
        'definitions' => [
            'SomeStr' => [
                'type' => 'string',
            ],
            'SomeNumber' => [
                'type' => 'number',
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'arrayOfUnions' => 'arrayOfUnions',
        'arrayOfRefUnions' => 'arrayOfRefUnions',
        'arrayOfRefAndNotRefUnions' => 'arrayOfRefAndNotRefUnions',
        'arrayOfUnionOfTypedArrayAndString' => 'arrayOfUnionOfTypedArrayAndString',
        'arrayOfUnionWithOneType' => 'arrayOfUnionWithOneType',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    /**
     * @var (string|int|float)[]|null
     */
    private ?array $arrayOfUnions = null;

    /**
     * @var (string|int|float)[]|null
     */
    private ?array $arrayOfRefUnions = null;

    /**
     * @var (string|int|float)[]|null
     */
    private ?array $arrayOfRefAndNotRefUnions = null;

    /**
     * @var (string[]|string)[]|null
     */
    private ?array $arrayOfUnionOfTypedArrayAndString = null;

    /**
     * @var string[]|null
     */
    private ?array $arrayOfUnionWithOneType = null;

    /**
     * @param (string|int|float)[]|null $arrayOfUnions
     * @param (string|int|float)[]|null $arrayOfRefUnions
     * @param (string|int|float)[]|null $arrayOfRefAndNotRefUnions
     * @param (string[]|string)[]|null $arrayOfUnionOfTypedArrayAndString
     * @param string[]|null $arrayOfUnionWithOneType
     */
    public function __construct(?array $arrayOfUnions = null, ?array $arrayOfRefUnions = null, ?array $arrayOfRefAndNotRefUnions = null, ?array $arrayOfUnionOfTypedArrayAndString = null, ?array $arrayOfUnionWithOneType = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->arrayOfUnions = $arrayOfUnions;
        $this->arrayOfRefUnions = $arrayOfRefUnions;
        $this->arrayOfRefAndNotRefUnions = $arrayOfRefAndNotRefUnions;
        $this->arrayOfUnionOfTypedArrayAndString = $arrayOfUnionOfTypedArrayAndString;
        $this->arrayOfUnionWithOneType = $arrayOfUnionWithOneType;
    }

    /**
     * Object (`stdClass`) or array with name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return an associative array instead of `stdClass` object.
     */
    public function getAdditionalProperties(bool $asArray = true): \stdClass|array
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
    public function withAdditionalProperties(\stdClass|array $additionalProperties): self
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
     * @return (string|int|float)[]|null
     */
    public function getArrayOfUnions(): ?array
    {
        return $this->arrayOfUnions ?? null;
    }

    /**
     * @param (string|int|float)[] $arrayOfUnions
     */
    public function withArrayOfUnions(array $arrayOfUnions, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($arrayOfUnions, self::$_schema['properties']['arrayOfUnions']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->arrayOfUnions = $arrayOfUnions;

        return $clone;
    }

    public function withoutArrayOfUnions(): self
    {
        $clone = clone $this;
        unset($clone->arrayOfUnions);

        return $clone;
    }

    /**
     * @return (string|int|float)[]|null
     */
    public function getArrayOfRefUnions(): ?array
    {
        return $this->arrayOfRefUnions ?? null;
    }

    /**
     * @param (string|int|float)[] $arrayOfRefUnions
     */
    public function withArrayOfRefUnions(array $arrayOfRefUnions, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->arrayOfRefUnions = $arrayOfRefUnions;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutArrayOfRefUnions(): self
    {
        $clone = clone $this;
        unset($clone->arrayOfRefUnions);

        return $clone;
    }

    /**
     * @return (string|int|float)[]|null
     */
    public function getArrayOfRefAndNotRefUnions(): ?array
    {
        return $this->arrayOfRefAndNotRefUnions ?? null;
    }

    /**
     * @param (string|int|float)[] $arrayOfRefAndNotRefUnions
     */
    public function withArrayOfRefAndNotRefUnions(array $arrayOfRefAndNotRefUnions, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->arrayOfRefAndNotRefUnions = $arrayOfRefAndNotRefUnions;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutArrayOfRefAndNotRefUnions(): self
    {
        $clone = clone $this;
        unset($clone->arrayOfRefAndNotRefUnions);

        return $clone;
    }

    /**
     * @return (string[]|string)[]|null
     */
    public function getArrayOfUnionOfTypedArrayAndString(): ?array
    {
        return $this->arrayOfUnionOfTypedArrayAndString ?? null;
    }

    /**
     * @param (string[]|string)[] $arrayOfUnionOfTypedArrayAndString
     */
    public function withArrayOfUnionOfTypedArrayAndString(array $arrayOfUnionOfTypedArrayAndString, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($arrayOfUnionOfTypedArrayAndString, self::$_schema['properties']['arrayOfUnionOfTypedArrayAndString']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->arrayOfUnionOfTypedArrayAndString = $arrayOfUnionOfTypedArrayAndString;

        return $clone;
    }

    public function withoutArrayOfUnionOfTypedArrayAndString(): self
    {
        $clone = clone $this;
        unset($clone->arrayOfUnionOfTypedArrayAndString);

        return $clone;
    }

    /**
     * @return string[]|null
     */
    public function getArrayOfUnionWithOneType(): ?array
    {
        return $this->arrayOfUnionWithOneType ?? null;
    }

    /**
     * @param string[] $arrayOfUnionWithOneType
     */
    public function withArrayOfUnionWithOneType(array $arrayOfUnionWithOneType, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($arrayOfUnionWithOneType, self::$_schema['properties']['arrayOfUnionWithOneType']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->arrayOfUnionWithOneType = $arrayOfUnionWithOneType;

        return $clone;
    }

    public function withoutArrayOfUnionWithOneType(): self
    {
        $clone = clone $this;
        unset($clone->arrayOfUnionWithOneType);

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
    public static function fromInput(array|object $input, bool $validate = true): MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $arrayOfUnions = isset($input->{'arrayOfUnions'})
            ? array_map(fn ($i) => match (true) {
                is_string($i) => $i,
                (is_int($i) || is_float($i)) => (str_contains((string)$i, '.') ? (float)$i : (int)$i),
                default => null,
            }, $input->{'arrayOfUnions'})
            : null;
        $arrayOfRefUnions = isset($input->{'arrayOfRefUnions'})
            ? array_map(fn ($i) => match (true) {
                is_string($i) => $i,
                (is_int($i) || is_float($i)) => (str_contains((string)$i, '.') ? (float)$i : (int)$i),
                default => null,
            }, $input->{'arrayOfRefUnions'})
            : null;
        $arrayOfRefAndNotRefUnions = isset($input->{'arrayOfRefAndNotRefUnions'})
            ? array_map(fn ($i) => match (true) {
                is_string($i) => $i,
                (is_int($i) || is_float($i)) => (str_contains((string)$i, '.') ? (float)$i : (int)$i),
                default => null,
            }, $input->{'arrayOfRefAndNotRefUnions'})
            : null;
        $arrayOfUnionOfTypedArrayAndString = isset($input->{'arrayOfUnionOfTypedArrayAndString'})
            ? array_map(fn ($i) => match (true) {
                is_array($i),
                is_string($i) => $i,
                default => null,
            }, $input->{'arrayOfUnionOfTypedArrayAndString'})
            : null;
        $arrayOfUnionWithOneType = isset($input->{'arrayOfUnionWithOneType'})
            ? array_map(fn ($i) => $i, $input->{'arrayOfUnionWithOneType'})
            : null;

        $obj = new self(
            $arrayOfUnions,
            $arrayOfRefUnions,
            $arrayOfRefAndNotRefUnions,
            $arrayOfUnionOfTypedArrayAndString,
            $arrayOfUnionWithOneType
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

        if (isset($this->arrayOfUnions)) {
            $output['arrayOfUnions'] = array_map(fn ($i) => match (true) {
                default => null,
                is_string($i),
                (is_int($i) || is_float($i)) => $i,
            }, $this->arrayOfUnions);
        }
        if (isset($this->arrayOfRefUnions)) {
            $output['arrayOfRefUnions'] = array_map(fn ($i) => match (true) {
                default => null,
                is_string($i),
                (is_int($i) || is_float($i)) => $i,
            }, $this->arrayOfRefUnions);
        }
        if (isset($this->arrayOfRefAndNotRefUnions)) {
            $output['arrayOfRefAndNotRefUnions'] = array_map(fn ($i) => match (true) {
                default => null,
                is_string($i),
                (is_int($i) || is_float($i)) => $i,
            }, $this->arrayOfRefAndNotRefUnions);
        }
        if (isset($this->arrayOfUnionOfTypedArrayAndString)) {
            $output['arrayOfUnionOfTypedArrayAndString'] = array_map(fn ($i) => match (true) {
                default => null,
                is_array($i),
                is_string($i) => $i,
            }, $this->arrayOfUnionOfTypedArrayAndString);
        }
        if (isset($this->arrayOfUnionWithOneType)) {
            $output['arrayOfUnionWithOneType'] = array_map(fn ($i) => $i, $this->arrayOfUnionWithOneType);
        }

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

        if (isset($this->arrayOfUnions)) {
            $output->{'arrayOfUnions'} = array_map(fn ($i) => match (true) {
                default => null,
                is_string($i),
                (is_int($i) || is_float($i)) => $i,
            }, $this->arrayOfUnions);
        }
        if (isset($this->arrayOfRefUnions)) {
            $output->{'arrayOfRefUnions'} = array_map(fn ($i) => match (true) {
                default => null,
                is_string($i),
                (is_int($i) || is_float($i)) => $i,
            }, $this->arrayOfRefUnions);
        }
        if (isset($this->arrayOfRefAndNotRefUnions)) {
            $output->{'arrayOfRefAndNotRefUnions'} = array_map(fn ($i) => match (true) {
                default => null,
                is_string($i),
                (is_int($i) || is_float($i)) => $i,
            }, $this->arrayOfRefAndNotRefUnions);
        }
        if (isset($this->arrayOfUnionOfTypedArrayAndString)) {
            $output->{'arrayOfUnionOfTypedArrayAndString'} = array_map(fn ($i) => match (true) {
                default => null,
                is_array($i),
                is_string($i) => $i,
            }, $this->arrayOfUnionOfTypedArrayAndString);
        }
        if (isset($this->arrayOfUnionWithOneType)) {
            $output->{'arrayOfUnionWithOneType'} = array_map(fn ($i) => $i, $this->arrayOfUnionWithOneType);
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
    public static function validateInput(array|object $input, bool $return = false): bool
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

    public function __clone()
    {
        if (isset($this->arrayOfUnions)) {
            $this->arrayOfUnions = array_map(fn ($i) => match (true) {
                is_string($i),
                (is_int($i) || is_float($i)) => $i,
            }, $this->arrayOfUnions);
        }
        if (isset($this->arrayOfRefUnions)) {
            $this->arrayOfRefUnions = array_map(fn ($i) => match (true) {
                is_string($i),
                (is_int($i) || is_float($i)) => $i,
            }, $this->arrayOfRefUnions);
        }
        if (isset($this->arrayOfRefAndNotRefUnions)) {
            $this->arrayOfRefAndNotRefUnions = array_map(fn ($i) => match (true) {
                is_string($i),
                (is_int($i) || is_float($i)) => $i,
            }, $this->arrayOfRefAndNotRefUnions);
        }
        if (isset($this->arrayOfUnionOfTypedArrayAndString)) {
            $this->arrayOfUnionOfTypedArrayAndString = array_map(fn ($i) => match (true) {
                is_array($i),
                is_string($i) => $i,
            }, $this->arrayOfUnionOfTypedArrayAndString);
        }
        if (isset($this->arrayOfUnionWithOneType)) {
            $this->arrayOfUnionWithOneType = array_map(fn ($i) => $i, $this->arrayOfUnionWithOneType);
        }
    }
}
