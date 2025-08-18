<?php

declare(strict_types=1);

namespace Ns\UnionArrayTyped_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'properties' => [
            'unionOfTypedArrays' => [
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
            'refUnionOfTypedArrays' => [
                'oneOf' => [
                    [
                        '$ref' => '#/definitions/TypedArrayStr',
                    ],
                    [
                        '$ref' => '#/definitions/TypedArrayNumber',
                    ],
                ],
            ],
            'refAndNotRefUnionOfTypedArrays' => [
                'oneOf' => [
                    [
                        '$ref' => '#/definitions/TypedArrayStr',
                    ],
                    [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                        ],
                    ],
                    [
                        '$ref' => '#/definitions/TypedArrayNumber',
                    ],
                    [
                        'type' => 'array',
                        'items' => [
                            'type' => 'number',
                        ],
                    ],
                ],
            ],
            'unionOfTypedArrayAndString' => [
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
            'unionOfOneTypedArray' => [
                'oneOf' => [
                    [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                        ],
                    ],
                ],
            ],
        ],
        'definitions' => [
            'TypedArrayStr' => [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                ],
            ],
            'TypedArrayNumber' => [
                'type' => 'array',
                'items' => [
                    'type' => 'number',
                ],
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'unionOfTypedArrays' => 'unionOfTypedArrays',
        'refUnionOfTypedArrays' => 'refUnionOfTypedArrays',
        'refAndNotRefUnionOfTypedArrays' => 'refAndNotRefUnionOfTypedArrays',
        'unionOfTypedArrayAndString' => 'unionOfTypedArrayAndString',
        'unionOfOneTypedArray' => 'unionOfOneTypedArray',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    /**
     * @var string[]|float[]|null
     */
    private ?array $unionOfTypedArrays = null;

    /**
     * @var string[]|float[]|null
     */
    private ?array $refUnionOfTypedArrays = null;

    /**
     * @var string[]|float[]|null
     */
    private ?array $refAndNotRefUnionOfTypedArrays = null;

    /**
     * @var string[]|string|null
     */
    private string|array|null $unionOfTypedArrayAndString = null;

    /**
     * @var string[]|null
     */
    private ?array $unionOfOneTypedArray = null;

    /**
     * @param string[]|float[]|null $unionOfTypedArrays
     * @param string[]|float[]|null $refUnionOfTypedArrays
     * @param string[]|float[]|null $refAndNotRefUnionOfTypedArrays
     * @param string[]|string|null $unionOfTypedArrayAndString
     * @param string[]|null $unionOfOneTypedArray
     */
    public function __construct(?array $unionOfTypedArrays = null, ?array $refUnionOfTypedArrays = null, ?array $refAndNotRefUnionOfTypedArrays = null, string|array|null $unionOfTypedArrayAndString = null, ?array $unionOfOneTypedArray = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->unionOfTypedArrays = $unionOfTypedArrays;
        $this->refUnionOfTypedArrays = $refUnionOfTypedArrays;
        $this->refAndNotRefUnionOfTypedArrays = $refAndNotRefUnionOfTypedArrays;
        $this->unionOfTypedArrayAndString = $unionOfTypedArrayAndString;
        $this->unionOfOneTypedArray = $unionOfOneTypedArray;
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
     * @return string[]|float[]|null
     */
    public function getUnionOfTypedArrays(): ?array
    {
        return $this->unionOfTypedArrays ?? null;
    }

    /**
     * @param string[]|float[] $unionOfTypedArrays
     */
    public function withUnionOfTypedArrays(array $unionOfTypedArrays, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($unionOfTypedArrays, self::$_schema['properties']['unionOfTypedArrays']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->unionOfTypedArrays = $unionOfTypedArrays;

        return $clone;
    }

    public function withoutUnionOfTypedArrays(): self
    {
        $clone = clone $this;
        unset($clone->unionOfTypedArrays);

        return $clone;
    }

    /**
     * @return string[]|float[]|null
     */
    public function getRefUnionOfTypedArrays(): ?array
    {
        return $this->refUnionOfTypedArrays ?? null;
    }

    /**
     * @param string[]|float[] $refUnionOfTypedArrays
     */
    public function withRefUnionOfTypedArrays(array $refUnionOfTypedArrays, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->refUnionOfTypedArrays = $refUnionOfTypedArrays;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutRefUnionOfTypedArrays(): self
    {
        $clone = clone $this;
        unset($clone->refUnionOfTypedArrays);

        return $clone;
    }

    /**
     * @return string[]|float[]|null
     */
    public function getRefAndNotRefUnionOfTypedArrays(): ?array
    {
        return $this->refAndNotRefUnionOfTypedArrays ?? null;
    }

    /**
     * @param string[]|float[] $refAndNotRefUnionOfTypedArrays
     */
    public function withRefAndNotRefUnionOfTypedArrays(array $refAndNotRefUnionOfTypedArrays, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->refAndNotRefUnionOfTypedArrays = $refAndNotRefUnionOfTypedArrays;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutRefAndNotRefUnionOfTypedArrays(): self
    {
        $clone = clone $this;
        unset($clone->refAndNotRefUnionOfTypedArrays);

        return $clone;
    }

    /**
     * @return string[]|string|null
     */
    public function getUnionOfTypedArrayAndString(): string|array|null
    {
        return $this->unionOfTypedArrayAndString ?? null;
    }

    /**
     * @param string[]|string $unionOfTypedArrayAndString
     */
    public function withUnionOfTypedArrayAndString(string|array $unionOfTypedArrayAndString, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($unionOfTypedArrayAndString, self::$_schema['properties']['unionOfTypedArrayAndString']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->unionOfTypedArrayAndString = $unionOfTypedArrayAndString;

        return $clone;
    }

    public function withoutUnionOfTypedArrayAndString(): self
    {
        $clone = clone $this;
        unset($clone->unionOfTypedArrayAndString);

        return $clone;
    }

    /**
     * @return string[]|null
     */
    public function getUnionOfOneTypedArray(): ?array
    {
        return $this->unionOfOneTypedArray ?? null;
    }

    /**
     * @param string[] $unionOfOneTypedArray
     */
    public function withUnionOfOneTypedArray(array $unionOfOneTypedArray, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($unionOfOneTypedArray, self::$_schema['properties']['unionOfOneTypedArray']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->unionOfOneTypedArray = $unionOfOneTypedArray;

        return $clone;
    }

    public function withoutUnionOfOneTypedArray(): self
    {
        $clone = clone $this;
        unset($clone->unionOfOneTypedArray);

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

        $unionOfTypedArrays = isset($input->{'unionOfTypedArrays'})
            ? match (true) {
                is_array($input->{'unionOfTypedArrays'}) => $input->{'unionOfTypedArrays'},
                default => ($input->{'unionOfTypedArrays'}),
            }
            : null;
        $refUnionOfTypedArrays = isset($input->{'refUnionOfTypedArrays'})
            ? match (true) {
                is_array($input->{'refUnionOfTypedArrays'}) => $input->{'refUnionOfTypedArrays'},
                default => ($input->{'refUnionOfTypedArrays'}),
            }
            : null;
        $refAndNotRefUnionOfTypedArrays = isset($input->{'refAndNotRefUnionOfTypedArrays'})
            ? match (true) {
                is_array($input->{'refAndNotRefUnionOfTypedArrays'}) => $input->{'refAndNotRefUnionOfTypedArrays'},
                default => ($input->{'refAndNotRefUnionOfTypedArrays'}),
            }
            : null;
        $unionOfTypedArrayAndString = isset($input->{'unionOfTypedArrayAndString'})
            ? match (true) {
                is_array($input->{'unionOfTypedArrayAndString'})
                    || is_string($input->{'unionOfTypedArrayAndString'}) =>
                    $input->{'unionOfTypedArrayAndString'},
                default => ($input->{'unionOfTypedArrayAndString'}),
            }
            : null;
        $unionOfOneTypedArray = isset($input->{'unionOfOneTypedArray'}) ? $input->{'unionOfOneTypedArray'} : null;

        $obj = new self(
            $unionOfTypedArrays,
            $refUnionOfTypedArrays,
            $refAndNotRefUnionOfTypedArrays,
            $unionOfTypedArrayAndString,
            $unionOfOneTypedArray
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

        if (isset($this->unionOfTypedArrays)) {
            $output['unionOfTypedArrays'] = match (true) {
                default => $this->unionOfTypedArrays,
            };
        }
        if (isset($this->refUnionOfTypedArrays)) {
            $output['refUnionOfTypedArrays'] = match (true) {
                default => $this->refUnionOfTypedArrays,
            };
        }
        if (isset($this->refAndNotRefUnionOfTypedArrays)) {
            $output['refAndNotRefUnionOfTypedArrays'] = match (true) {
                default => $this->refAndNotRefUnionOfTypedArrays,
            };
        }
        if (isset($this->unionOfTypedArrayAndString)) {
            $output['unionOfTypedArrayAndString'] = match (true) {
                default => $this->unionOfTypedArrayAndString,
            };
        }
        if (isset($this->unionOfOneTypedArray)) {
            $output['unionOfOneTypedArray'] = $this->unionOfOneTypedArray;
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

        if (isset($this->unionOfTypedArrays)) {
            $output->{'unionOfTypedArrays'} = match (true) {
                default => $this->unionOfTypedArrays,
            };
        }
        if (isset($this->refUnionOfTypedArrays)) {
            $output->{'refUnionOfTypedArrays'} = match (true) {
                default => $this->refUnionOfTypedArrays,
            };
        }
        if (isset($this->refAndNotRefUnionOfTypedArrays)) {
            $output->{'refAndNotRefUnionOfTypedArrays'} = match (true) {
                default => $this->refAndNotRefUnionOfTypedArrays,
            };
        }
        if (isset($this->unionOfTypedArrayAndString)) {
            $output->{'unionOfTypedArrayAndString'} = match (true) {
                default => $this->unionOfTypedArrayAndString,
            };
        }
        if (isset($this->unionOfOneTypedArray)) {
            $output->{'unionOfOneTypedArray'} = $this->unionOfOneTypedArray;
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
}
