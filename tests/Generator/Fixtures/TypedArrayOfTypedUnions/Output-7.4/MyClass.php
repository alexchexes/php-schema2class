<?php

declare(strict_types=1);

namespace Ns\TypedArrayOfTypedUnions_7_4;

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
                        [
                            'oneOf' => [
                                [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'string',
                                    ],
                                ],
                                [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'number',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'arrayOfRefUnions' => [
                'type' => 'array',
                'items' => [
                    'oneOf' => [
                        [
                            '$ref' => '#/definitions/ArrayOfOneOfs',
                        ],
                        [
                            '$ref' => '#/definitions/OneOfArrays',
                        ],
                    ],
                ],
            ],
            'arrayOfRefAndNotRefUnions' => [
                'type' => 'array',
                'items' => [
                    'oneOf' => [
                        [
                            '$ref' => '#/definitions/ArrayOfOneOfs',
                        ],
                        [
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
                        [
                            '$ref' => '#/definitions/OneOfArrays',
                        ],
                        [
                            'oneOf' => [
                                [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'string',
                                    ],
                                ],
                                [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'number',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'arrayOfUnionOfStringAndArray' => [
                'type' => 'array',
                'items' => [
                    'oneOf' => [
                        [
                            'type' => 'array',
                            'items' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'string',
                                ],
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
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
        'definitions' => [
            'ArrayOfOneOfs' => [
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
            'OneOfArrays' => [
                'oneOf' => [
                    [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                        ],
                    ],
                    [
                        'type' => 'array',
                        'items' => [
                            'type' => 'number',
                        ],
                    ],
                ],
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
        'arrayOfUnionOfStringAndArray' => 'arrayOfUnionOfStringAndArray',
        'arrayOfUnionWithOneType' => 'arrayOfUnionWithOneType',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    /**
     * @var ((string|int|float)[]|string[]|float[])[]|null
     */
    private ?array $arrayOfUnions = null;

    /**
     * @var ((string|int|float)[]|string[]|float[])[]|null
     */
    private ?array $arrayOfRefUnions = null;

    /**
     * @var ((string|int|float)[]|string[]|float[])[]|null
     */
    private ?array $arrayOfRefAndNotRefUnions = null;

    /**
     * @var (string[][]|string)[]|null
     */
    private ?array $arrayOfUnionOfStringAndArray = null;

    /**
     * @var string[][]|null
     */
    private ?array $arrayOfUnionWithOneType = null;

    /**
     * @param ((string|int|float)[]|string[]|float[])[]|null $arrayOfUnions
     * @param ((string|int|float)[]|string[]|float[])[]|null $arrayOfRefUnions
     * @param ((string|int|float)[]|string[]|float[])[]|null $arrayOfRefAndNotRefUnions
     * @param (string[][]|string)[]|null $arrayOfUnionOfStringAndArray
     * @param string[][]|null $arrayOfUnionWithOneType
     */
    public function __construct(?array $arrayOfUnions = null, ?array $arrayOfRefUnions = null, ?array $arrayOfRefAndNotRefUnions = null, ?array $arrayOfUnionOfStringAndArray = null, ?array $arrayOfUnionWithOneType = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->arrayOfUnions = $arrayOfUnions;
        $this->arrayOfRefUnions = $arrayOfRefUnions;
        $this->arrayOfRefAndNotRefUnions = $arrayOfRefAndNotRefUnions;
        $this->arrayOfUnionOfStringAndArray = $arrayOfUnionOfStringAndArray;
        $this->arrayOfUnionWithOneType = $arrayOfUnionWithOneType;
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
     * @return ((string|int|float)[]|string[]|float[])[]|null
     */
    public function getArrayOfUnions(): ?array
    {
        return $this->arrayOfUnions ?? null;
    }

    /**
     * @param ((string|int|float)[]|string[]|float[])[] $arrayOfUnions
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
     * @return ((string|int|float)[]|string[]|float[])[]|null
     */
    public function getArrayOfRefUnions(): ?array
    {
        return $this->arrayOfRefUnions ?? null;
    }

    /**
     * @param ((string|int|float)[]|string[]|float[])[] $arrayOfRefUnions
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
     * @return ((string|int|float)[]|string[]|float[])[]|null
     */
    public function getArrayOfRefAndNotRefUnions(): ?array
    {
        return $this->arrayOfRefAndNotRefUnions ?? null;
    }

    /**
     * @param ((string|int|float)[]|string[]|float[])[] $arrayOfRefAndNotRefUnions
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
     * @return (string[][]|string)[]|null
     */
    public function getArrayOfUnionOfStringAndArray(): ?array
    {
        return $this->arrayOfUnionOfStringAndArray ?? null;
    }

    /**
     * @param (string[][]|string)[] $arrayOfUnionOfStringAndArray
     */
    public function withArrayOfUnionOfStringAndArray(array $arrayOfUnionOfStringAndArray, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($arrayOfUnionOfStringAndArray, self::$_schema['properties']['arrayOfUnionOfStringAndArray']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->arrayOfUnionOfStringAndArray = $arrayOfUnionOfStringAndArray;

        return $clone;
    }

    public function withoutArrayOfUnionOfStringAndArray(): self
    {
        $clone = clone $this;
        unset($clone->arrayOfUnionOfStringAndArray);

        return $clone;
    }

    /**
     * @return string[][]|null
     */
    public function getArrayOfUnionWithOneType(): ?array
    {
        return $this->arrayOfUnionWithOneType ?? null;
    }

    /**
     * @param string[][] $arrayOfUnionWithOneType
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
    public static function fromInput($input, bool $validate = true): MyClass
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

        $arrayOfUnions = isset($input->{'arrayOfUnions'})
            ? array_map(fn ($i) => (((is_array($i)
                && count($i) === count(array_filter($i, fn ($i) => (is_string($i) || (is_int($i) || is_float($i))))))
            )
                ? array_map(fn ($i) => ((is_string($i))
                    ? $i
                    : (((is_int($i) || is_float($i)))
                        ? (str_contains((string)$i, '.') ? (float)$i : (int)$i)
                        : null
                    )
                ), $i)
                : (((is_array($i))) ? ((is_array($i)) ? $i : null) : null)
            ), $input->{'arrayOfUnions'})
            : null;
        $arrayOfRefUnions = isset($input->{'arrayOfRefUnions'})
            ? array_map(fn ($i) => (((is_array($i)
                && count($i) === count(array_filter($i, fn ($i) => (is_string($i) || (is_int($i) || is_float($i))))))
            )
                ? array_map(fn ($i) => ((is_string($i))
                    ? $i
                    : (((is_int($i) || is_float($i)))
                        ? (str_contains((string)$i, '.') ? (float)$i : (int)$i)
                        : null
                    )
                ), $i)
                : (((is_array($i))) ? ((is_array($i)) ? $i : null) : null)
            ), $input->{'arrayOfRefUnions'})
            : null;
        $arrayOfRefAndNotRefUnions = isset($input->{'arrayOfRefAndNotRefUnions'})
            ? array_map(fn ($i) => (((is_array($i)
                && count($i) === count(array_filter($i, fn ($i) => (is_string($i) || (is_int($i) || is_float($i))))))
            )
                ? array_map(fn ($i) => ((is_string($i))
                    ? $i
                    : (((is_int($i) || is_float($i)))
                        ? (str_contains((string)$i, '.') ? (float)$i : (int)$i)
                        : null
                    )
                ), $i)
                : (((is_array($i))) ? ((is_array($i)) ? $i : null) : null)
            ), $input->{'arrayOfRefAndNotRefUnions'})
            : null;
        $arrayOfUnionOfStringAndArray = isset($input->{'arrayOfUnionOfStringAndArray'})
            ? array_map(fn ($i) => (((is_array($i)
                && count($i) === count(array_filter($i, fn ($i) => is_array($i))))
            )
                ? array_map(fn ($i) => $i, $i)
                : ((is_string($i)) ? $i : null)
            ), $input->{'arrayOfUnionOfStringAndArray'})
            : null;
        $arrayOfUnionWithOneType = isset($input->{'arrayOfUnionWithOneType'})
            ? array_map(fn ($i) => $i, $input->{'arrayOfUnionWithOneType'})
            : null;

        $obj = new self(
            $arrayOfUnions,
            $arrayOfRefUnions,
            $arrayOfRefAndNotRefUnions,
            $arrayOfUnionOfStringAndArray,
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
            $output['arrayOfUnions'] = array_map(fn ($i) => (((is_array($i)
                && count($i) === count(array_filter($i, fn ($i) => (is_string($i) || (is_int($i) || is_float($i))))))
            )
                ? array_map(fn ($i) => ((is_string($i) || (is_int($i) || is_float($i))) ? $i : null), $i)
                : (((is_array($i))) ? ((is_array($i)) ? $i : null) : null)
            ), $this->arrayOfUnions);
        }
        if (isset($this->arrayOfRefUnions)) {
            $output['arrayOfRefUnions'] = array_map(fn ($i) => (((is_array($i)
                && count($i) === count(array_filter($i, fn ($i) => (is_string($i) || (is_int($i) || is_float($i))))))
            )
                ? array_map(fn ($i) => ((is_string($i) || (is_int($i) || is_float($i))) ? $i : null), $i)
                : (((is_array($i))) ? ((is_array($i)) ? $i : null) : null)
            ), $this->arrayOfRefUnions);
        }
        if (isset($this->arrayOfRefAndNotRefUnions)) {
            $output['arrayOfRefAndNotRefUnions'] = array_map(fn ($i) => (((is_array($i)
                && count($i) === count(array_filter($i, fn ($i) => (is_string($i) || (is_int($i) || is_float($i))))))
            )
                ? array_map(fn ($i) => ((is_string($i) || (is_int($i) || is_float($i))) ? $i : null), $i)
                : (((is_array($i))) ? ((is_array($i)) ? $i : null) : null)
            ), $this->arrayOfRefAndNotRefUnions);
        }
        if (isset($this->arrayOfUnionOfStringAndArray)) {
            $output['arrayOfUnionOfStringAndArray'] = array_map(fn ($i) => (((is_array($i)
                && count($i) === count(array_filter($i, fn ($i) => is_array($i))))
            )
                ? array_map(fn ($i) => $i, $i)
                : ((is_string($i)) ? $i : null)
            ), $this->arrayOfUnionOfStringAndArray);
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
            $output->{'arrayOfUnions'} = array_map(fn ($i) => (((is_array($i)
                && count($i) === count(array_filter($i, fn ($i) => (is_string($i) || (is_int($i) || is_float($i))))))
            )
                ? array_map(fn ($i) => ((is_string($i) || (is_int($i) || is_float($i))) ? $i : null), $i)
                : (((is_array($i))) ? ((is_array($i)) ? $i : null) : null)
            ), $this->arrayOfUnions);
        }
        if (isset($this->arrayOfRefUnions)) {
            $output->{'arrayOfRefUnions'} = array_map(fn ($i) => (((is_array($i)
                && count($i) === count(array_filter($i, fn ($i) => (is_string($i) || (is_int($i) || is_float($i))))))
            )
                ? array_map(fn ($i) => ((is_string($i) || (is_int($i) || is_float($i))) ? $i : null), $i)
                : (((is_array($i))) ? ((is_array($i)) ? $i : null) : null)
            ), $this->arrayOfRefUnions);
        }
        if (isset($this->arrayOfRefAndNotRefUnions)) {
            $output->{'arrayOfRefAndNotRefUnions'} = array_map(fn ($i) => (((is_array($i)
                && count($i) === count(array_filter($i, fn ($i) => (is_string($i) || (is_int($i) || is_float($i))))))
            )
                ? array_map(fn ($i) => ((is_string($i) || (is_int($i) || is_float($i))) ? $i : null), $i)
                : (((is_array($i))) ? ((is_array($i)) ? $i : null) : null)
            ), $this->arrayOfRefAndNotRefUnions);
        }
        if (isset($this->arrayOfUnionOfStringAndArray)) {
            $output->{'arrayOfUnionOfStringAndArray'} = array_map(fn ($i) => (((is_array($i)
                && count($i) === count(array_filter($i, fn ($i) => is_array($i))))
            )
                ? array_map(fn ($i) => $i, $i)
                : ((is_string($i)) ? $i : null)
            ), $this->arrayOfUnionOfStringAndArray);
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

    public function __clone()
    {
        if (isset($this->arrayOfUnions)) {
            $this->arrayOfUnions = array_map(fn ($i) => (((is_array($i)
                && count($i) === count(array_filter($i, fn ($i) => (is_string($i) || (is_int($i) || is_float($i))))))
            )
                ? array_map(fn ($i) => $i, $i)
                : $i
            ), $this->arrayOfUnions);
        }
        if (isset($this->arrayOfRefUnions)) {
            $this->arrayOfRefUnions = array_map(fn ($i) => (((is_array($i)
                && count($i) === count(array_filter($i, fn ($i) => (is_string($i) || (is_int($i) || is_float($i))))))
            )
                ? array_map(fn ($i) => $i, $i)
                : $i
            ), $this->arrayOfRefUnions);
        }
        if (isset($this->arrayOfRefAndNotRefUnions)) {
            $this->arrayOfRefAndNotRefUnions = array_map(fn ($i) => (((is_array($i)
                && count($i) === count(array_filter($i, fn ($i) => (is_string($i) || (is_int($i) || is_float($i))))))
            )
                ? array_map(fn ($i) => $i, $i)
                : $i
            ), $this->arrayOfRefAndNotRefUnions);
        }
        if (isset($this->arrayOfUnionOfStringAndArray)) {
            $this->arrayOfUnionOfStringAndArray = array_map(fn ($i) => (((is_array($i)
                && count($i) === count(array_filter($i, fn ($i) => is_array($i))))
            )
                ? array_map(fn ($i) => $i, $i)
                : $i
            ), $this->arrayOfUnionOfStringAndArray);
        }
        if (isset($this->arrayOfUnionWithOneType)) {
            $this->arrayOfUnionWithOneType = array_map(fn ($i) => $i, $this->arrayOfUnionWithOneType);
        }
    }
}
