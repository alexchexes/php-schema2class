<?php

declare(strict_types=1);

namespace Ns\UnionArrayObject_7_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'properties' => [
            'arrayOfObjectsUnion' => [
                'oneOf' => [
                    [
                        'type' => 'array',
                        'items' => [
                            'properties' => [
                                'name' => [
                                    'type' => 'string',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type' => 'array',
                        'items' => [
                            'properties' => [
                                'accountNumber' => [
                                    'type' => 'string',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'refArrayOfObjectsUnion' => [
                'oneOf' => [
                    [
                        '$ref' => '#/definitions/ArrayOfObjects1',
                    ],
                    [
                        '$ref' => '#/definitions/ArrayOfObjects2',
                    ],
                ],
            ],
            'refAndNotRefArrayOfObjectsUnion' => [
                'oneOf' => [
                    [
                        '$ref' => '#/definitions/ArrayOfObjects1',
                    ],
                    [
                        'type' => 'array',
                        'items' => [
                            'properties' => [
                                'name' => [
                                    'type' => 'string',
                                ],
                            ],
                        ],
                    ],
                    [
                        '$ref' => '#/definitions/ArrayOfObjects2',
                    ],
                    [
                        'type' => 'array',
                        'items' => [
                            'properties' => [
                                'accountNumber' => [
                                    'type' => 'string',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'arrayOfObjAndStringUnion' => [
                'oneOf' => [
                    [
                        'type' => 'array',
                        'items' => [
                            'properties' => [
                                'name' => [
                                    'type' => 'string',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type' => 'string',
                    ],
                ],
            ],
            'unionOfOneArrayOfObjects' => [
                'oneOf' => [
                    [
                        'type' => 'array',
                        'items' => [
                            'properties' => [
                                'name' => [
                                    'type' => 'string',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'definitions' => [
            'ArrayOfObjects1' => [
                'type' => 'array',
                'items' => [
                    'properties' => [
                        'name' => [
                            'type' => 'string',
                        ],
                    ],
                ],
            ],
            'ArrayOfObjects2' => [
                'type' => 'array',
                'items' => [
                    'properties' => [
                        'accountNumber' => [
                            'type' => 'string',
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
        'arrayOfObjectsUnion' => 'arrayOfObjectsUnion',
        'refArrayOfObjectsUnion' => 'refArrayOfObjectsUnion',
        'refAndNotRefArrayOfObjectsUnion' => 'refAndNotRefArrayOfObjectsUnion',
        'arrayOfObjAndStringUnion' => 'arrayOfObjAndStringUnion',
        'unionOfOneArrayOfObjects' => 'unionOfOneArrayOfObjects',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    /**
     * @var MyClassArrayOfObjectsUnionAlternative1Item[]|MyClassArrayOfObjectsUnionAlternative2Item[]|null
     */
    private ?array $arrayOfObjectsUnion = null;

    /**
     * @var MyClassRefArrayOfObjectsUnionAlternative1Item[]|MyClassRefArrayOfObjectsUnionAlternative2Item[]|null
     */
    private ?array $refArrayOfObjectsUnion = null;

    /**
     * @var MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item[]|MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item[]|MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item[]|MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item[]|null
     */
    private ?array $refAndNotRefArrayOfObjectsUnion = null;

    /**
     * @var MyClassArrayOfObjAndStringUnionAlternative1Item[]|string|null
     */
    private $arrayOfObjAndStringUnion = null;

    /**
     * @var MyClassUnionOfOneArrayOfObjectsItem[]|null
     */
    private ?array $unionOfOneArrayOfObjects = null;

    /**
     * @param MyClassArrayOfObjectsUnionAlternative1Item[]|MyClassArrayOfObjectsUnionAlternative2Item[]|null $arrayOfObjectsUnion
     * @param MyClassRefArrayOfObjectsUnionAlternative1Item[]|MyClassRefArrayOfObjectsUnionAlternative2Item[]|null $refArrayOfObjectsUnion
     * @param MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item[]|MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item[]|MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item[]|MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item[]|null $refAndNotRefArrayOfObjectsUnion
     * @param MyClassArrayOfObjAndStringUnionAlternative1Item[]|string|null $arrayOfObjAndStringUnion
     * @param MyClassUnionOfOneArrayOfObjectsItem[]|null $unionOfOneArrayOfObjects
     */
    public function __construct(
        ?array $arrayOfObjectsUnion = null,
        ?array $refArrayOfObjectsUnion = null,
        ?array $refAndNotRefArrayOfObjectsUnion = null,
        $arrayOfObjAndStringUnion = null,
        ?array $unionOfOneArrayOfObjects = null
    ) {
        $this->_additionalProperties = new \stdClass();

        $this->arrayOfObjectsUnion = $arrayOfObjectsUnion;
        $this->refArrayOfObjectsUnion = $refArrayOfObjectsUnion;
        $this->refAndNotRefArrayOfObjectsUnion = $refAndNotRefArrayOfObjectsUnion;
        $this->arrayOfObjAndStringUnion = $arrayOfObjAndStringUnion;
        $this->unionOfOneArrayOfObjects = $unionOfOneArrayOfObjects;
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
     * @return MyClassArrayOfObjectsUnionAlternative1Item[]|MyClassArrayOfObjectsUnionAlternative2Item[]|null
     */
    public function getArrayOfObjectsUnion(): ?array
    {
        return $this->arrayOfObjectsUnion ?? null;
    }

    /**
     * @param MyClassArrayOfObjectsUnionAlternative1Item[]|MyClassArrayOfObjectsUnionAlternative2Item[] $arrayOfObjectsUnion
     */
    public function withArrayOfObjectsUnion(array $arrayOfObjectsUnion, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($arrayOfObjectsUnion, self::$_schema['properties']['arrayOfObjectsUnion']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->arrayOfObjectsUnion = $arrayOfObjectsUnion;

        return $clone;
    }

    public function withoutArrayOfObjectsUnion(): self
    {
        $clone = clone $this;
        unset($clone->arrayOfObjectsUnion);

        return $clone;
    }

    /**
     * @return MyClassRefArrayOfObjectsUnionAlternative1Item[]|MyClassRefArrayOfObjectsUnionAlternative2Item[]|null
     */
    public function getRefArrayOfObjectsUnion(): ?array
    {
        return $this->refArrayOfObjectsUnion ?? null;
    }

    /**
     * @param MyClassRefArrayOfObjectsUnionAlternative1Item[]|MyClassRefArrayOfObjectsUnionAlternative2Item[] $refArrayOfObjectsUnion
     */
    public function withRefArrayOfObjectsUnion(array $refArrayOfObjectsUnion, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->refArrayOfObjectsUnion = $refArrayOfObjectsUnion;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutRefArrayOfObjectsUnion(): self
    {
        $clone = clone $this;
        unset($clone->refArrayOfObjectsUnion);

        return $clone;
    }

    /**
     * @return MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item[]|MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item[]|MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item[]|MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item[]|null
     */
    public function getRefAndNotRefArrayOfObjectsUnion(): ?array
    {
        return $this->refAndNotRefArrayOfObjectsUnion ?? null;
    }

    /**
     * @param MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item[]|MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item[]|MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item[]|MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item[] $refAndNotRefArrayOfObjectsUnion
     */
    public function withRefAndNotRefArrayOfObjectsUnion(array $refAndNotRefArrayOfObjectsUnion, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->refAndNotRefArrayOfObjectsUnion = $refAndNotRefArrayOfObjectsUnion;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutRefAndNotRefArrayOfObjectsUnion(): self
    {
        $clone = clone $this;
        unset($clone->refAndNotRefArrayOfObjectsUnion);

        return $clone;
    }

    /**
     * @return MyClassArrayOfObjAndStringUnionAlternative1Item[]|string|null
     */
    public function getArrayOfObjAndStringUnion()
    {
        return $this->arrayOfObjAndStringUnion ?? null;
    }

    /**
     * @param MyClassArrayOfObjAndStringUnionAlternative1Item[]|string $arrayOfObjAndStringUnion
     */
    public function withArrayOfObjAndStringUnion($arrayOfObjAndStringUnion, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($arrayOfObjAndStringUnion, self::$_schema['properties']['arrayOfObjAndStringUnion']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->arrayOfObjAndStringUnion = $arrayOfObjAndStringUnion;

        return $clone;
    }

    public function withoutArrayOfObjAndStringUnion(): self
    {
        $clone = clone $this;
        unset($clone->arrayOfObjAndStringUnion);

        return $clone;
    }

    /**
     * @return MyClassUnionOfOneArrayOfObjectsItem[]|null
     */
    public function getUnionOfOneArrayOfObjects(): ?array
    {
        return $this->unionOfOneArrayOfObjects ?? null;
    }

    /**
     * @param MyClassUnionOfOneArrayOfObjectsItem[] $unionOfOneArrayOfObjects
     */
    public function withUnionOfOneArrayOfObjects(array $unionOfOneArrayOfObjects, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($unionOfOneArrayOfObjects, self::$_schema['properties']['unionOfOneArrayOfObjects']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->unionOfOneArrayOfObjects = $unionOfOneArrayOfObjects;

        return $clone;
    }

    public function withoutUnionOfOneArrayOfObjects(): self
    {
        $clone = clone $this;
        unset($clone->unionOfOneArrayOfObjects);

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

        $arrayOfObjectsUnion = isset($input->{'arrayOfObjectsUnion'})
            ? (((is_array($input->{'arrayOfObjectsUnion'})
                && count($input->{'arrayOfObjectsUnion'}) === count(array_filter(
                    $input->{'arrayOfObjectsUnion'},
                    fn (MyClassArrayOfObjectsUnionAlternative1Item $item): bool => MyClassArrayOfObjectsUnionAlternative1Item::validateInput($item, true),
                )))
            )
                ? array_map(
                    fn ($i): MyClassArrayOfObjectsUnionAlternative1Item => MyClassArrayOfObjectsUnionAlternative1Item::fromInput($i, $validate),
                    $input->{'arrayOfObjectsUnion'},
                )
                : (((is_array($input->{'arrayOfObjectsUnion'})
                    && count($input->{'arrayOfObjectsUnion'}) === count(array_filter(
                        $input->{'arrayOfObjectsUnion'},
                        fn (MyClassArrayOfObjectsUnionAlternative2Item $item): bool => MyClassArrayOfObjectsUnionAlternative2Item::validateInput($item, true),
                    )))
                )
                    ? array_map(
                        fn ($i): MyClassArrayOfObjectsUnionAlternative2Item => MyClassArrayOfObjectsUnionAlternative2Item::fromInput($i, $validate),
                        $input->{'arrayOfObjectsUnion'},
                    )
                    : $input->{'arrayOfObjectsUnion'}
                )
            )
            : null;
        $refArrayOfObjectsUnion = isset($input->{'refArrayOfObjectsUnion'})
            ? (((is_array($input->{'refArrayOfObjectsUnion'})
                && count($input->{'refArrayOfObjectsUnion'}) === count(array_filter(
                    $input->{'refArrayOfObjectsUnion'},
                    fn (MyClassRefArrayOfObjectsUnionAlternative1Item $item): bool => MyClassRefArrayOfObjectsUnionAlternative1Item::validateInput($item, true),
                )))
            )
                ? array_map(
                    fn ($i): MyClassRefArrayOfObjectsUnionAlternative1Item => MyClassRefArrayOfObjectsUnionAlternative1Item::fromInput($i, $validate),
                    $input->{'refArrayOfObjectsUnion'},
                )
                : (((is_array($input->{'refArrayOfObjectsUnion'})
                    && count($input->{'refArrayOfObjectsUnion'}) === count(array_filter(
                        $input->{'refArrayOfObjectsUnion'},
                        fn (MyClassRefArrayOfObjectsUnionAlternative2Item $item): bool => MyClassRefArrayOfObjectsUnionAlternative2Item::validateInput($item, true),
                    )))
                )
                    ? array_map(
                        fn ($i): MyClassRefArrayOfObjectsUnionAlternative2Item => MyClassRefArrayOfObjectsUnionAlternative2Item::fromInput($i, $validate),
                        $input->{'refArrayOfObjectsUnion'},
                    )
                    : $input->{'refArrayOfObjectsUnion'}
                )
            )
            : null;
        $refAndNotRefArrayOfObjectsUnion = isset($input->{'refAndNotRefArrayOfObjectsUnion'})
            ? (((is_array($input->{'refAndNotRefArrayOfObjectsUnion'})
                && count($input->{'refAndNotRefArrayOfObjectsUnion'}) === count(array_filter(
                    $input->{'refAndNotRefArrayOfObjectsUnion'},
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item $item): bool => MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item::validateInput($item, true),
                )))
            )
                ? array_map(
                    fn ($i): MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item => MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item::fromInput($i, $validate),
                    $input->{'refAndNotRefArrayOfObjectsUnion'},
                )
                : (((is_array($input->{'refAndNotRefArrayOfObjectsUnion'})
                    && count($input->{'refAndNotRefArrayOfObjectsUnion'}) === count(array_filter(
                        $input->{'refAndNotRefArrayOfObjectsUnion'},
                        fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item $item): bool => MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item::validateInput($item, true),
                    )))
                )
                    ? array_map(
                        fn ($i): MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item => MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item::fromInput($i, $validate),
                        $input->{'refAndNotRefArrayOfObjectsUnion'},
                    )
                    : (((is_array($input->{'refAndNotRefArrayOfObjectsUnion'})
                        && count($input->{'refAndNotRefArrayOfObjectsUnion'}) === count(array_filter(
                            $input->{'refAndNotRefArrayOfObjectsUnion'},
                            fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item $item): bool => MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item::validateInput($item, true),
                        )))
                    )
                        ? array_map(
                            fn ($i): MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item => MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item::fromInput($i, $validate),
                            $input->{'refAndNotRefArrayOfObjectsUnion'},
                        )
                        : (((is_array($input->{'refAndNotRefArrayOfObjectsUnion'})
                            && count($input->{'refAndNotRefArrayOfObjectsUnion'}) === count(array_filter(
                                $input->{'refAndNotRefArrayOfObjectsUnion'},
                                fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item $item): bool => MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item::validateInput($item, true),
                            )))
                        )
                            ? array_map(
                                fn ($i): MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item => MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item::fromInput($i, $validate),
                                $input->{'refAndNotRefArrayOfObjectsUnion'},
                            )
                            : $input->{'refAndNotRefArrayOfObjectsUnion'}
                        )
                    )
                )
            )
            : null;
        $arrayOfObjAndStringUnion = isset($input->{'arrayOfObjAndStringUnion'})
            ? (((is_array($input->{'arrayOfObjAndStringUnion'})
                && count($input->{'arrayOfObjAndStringUnion'}) === count(array_filter(
                    $input->{'arrayOfObjAndStringUnion'},
                    fn (MyClassArrayOfObjAndStringUnionAlternative1Item $item): bool => MyClassArrayOfObjAndStringUnionAlternative1Item::validateInput($item, true),
                )))
            )
                ? array_map(
                    fn ($i): MyClassArrayOfObjAndStringUnionAlternative1Item => MyClassArrayOfObjAndStringUnionAlternative1Item::fromInput($i, $validate),
                    $input->{'arrayOfObjAndStringUnion'},
                )
                : $input->{'arrayOfObjAndStringUnion'}
            )
            : null;
        $unionOfOneArrayOfObjects = isset($input->{'unionOfOneArrayOfObjects'})
            ? array_map(
                fn ($i): MyClassUnionOfOneArrayOfObjectsItem => MyClassUnionOfOneArrayOfObjectsItem::fromInput($i, $validate),
                $input->{'unionOfOneArrayOfObjects'},
            )
            : null;

        $obj = new self(
            $arrayOfObjectsUnion,
            $refArrayOfObjectsUnion,
            $refAndNotRefArrayOfObjectsUnion,
            $arrayOfObjAndStringUnion,
            $unionOfOneArrayOfObjects
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

        if (isset($this->arrayOfObjectsUnion)) {
            if ((is_array($this->arrayOfObjectsUnion)
                && count($this->arrayOfObjectsUnion) === count(array_filter(
                    $this->arrayOfObjectsUnion,
                    fn (MyClassArrayOfObjectsUnionAlternative1Item $item): bool => $item instanceof MyClassArrayOfObjectsUnionAlternative1Item,
                )))
            ) {
                $output['arrayOfObjectsUnion'] = array_map(
                    fn (MyClassArrayOfObjectsUnionAlternative1Item $i) => $i->toArray(),
                    $this->arrayOfObjectsUnion,
                );
            } elseif ((is_array($this->arrayOfObjectsUnion)
                && count($this->arrayOfObjectsUnion) === count(array_filter(
                    $this->arrayOfObjectsUnion,
                    fn (MyClassArrayOfObjectsUnionAlternative2Item $item): bool => $item instanceof MyClassArrayOfObjectsUnionAlternative2Item,
                )))
            ) {
                $output['arrayOfObjectsUnion'] = array_map(
                    fn (MyClassArrayOfObjectsUnionAlternative2Item $i) => $i->toArray(),
                    $this->arrayOfObjectsUnion,
                );
            } else {
                $output['arrayOfObjectsUnion'] = $this->arrayOfObjectsUnion;
            }
        }
        if (isset($this->refArrayOfObjectsUnion)) {
            if ((is_array($this->refArrayOfObjectsUnion)
                && count($this->refArrayOfObjectsUnion) === count(array_filter(
                    $this->refArrayOfObjectsUnion,
                    fn (MyClassRefArrayOfObjectsUnionAlternative1Item $item): bool => $item instanceof MyClassRefArrayOfObjectsUnionAlternative1Item,
                )))
            ) {
                $output['refArrayOfObjectsUnion'] = array_map(
                    fn (MyClassRefArrayOfObjectsUnionAlternative1Item $i) => $i->toArray(),
                    $this->refArrayOfObjectsUnion,
                );
            } elseif ((is_array($this->refArrayOfObjectsUnion)
                && count($this->refArrayOfObjectsUnion) === count(array_filter(
                    $this->refArrayOfObjectsUnion,
                    fn (MyClassRefArrayOfObjectsUnionAlternative2Item $item): bool => $item instanceof MyClassRefArrayOfObjectsUnionAlternative2Item,
                )))
            ) {
                $output['refArrayOfObjectsUnion'] = array_map(
                    fn (MyClassRefArrayOfObjectsUnionAlternative2Item $i) => $i->toArray(),
                    $this->refArrayOfObjectsUnion,
                );
            } else {
                $output['refArrayOfObjectsUnion'] = $this->refArrayOfObjectsUnion;
            }
        }
        if (isset($this->refAndNotRefArrayOfObjectsUnion)) {
            if ((is_array($this->refAndNotRefArrayOfObjectsUnion)
                && count($this->refAndNotRefArrayOfObjectsUnion) === count(array_filter(
                    $this->refAndNotRefArrayOfObjectsUnion,
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item $item): bool => $item instanceof MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item,
                )))
            ) {
                $output['refAndNotRefArrayOfObjectsUnion'] = array_map(
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item $i) => $i->toArray(),
                    $this->refAndNotRefArrayOfObjectsUnion,
                );
            } elseif ((is_array($this->refAndNotRefArrayOfObjectsUnion)
                && count($this->refAndNotRefArrayOfObjectsUnion) === count(array_filter(
                    $this->refAndNotRefArrayOfObjectsUnion,
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item $item): bool => $item instanceof MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item,
                )))
            ) {
                $output['refAndNotRefArrayOfObjectsUnion'] = array_map(
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item $i) => $i->toArray(),
                    $this->refAndNotRefArrayOfObjectsUnion,
                );
            } elseif ((is_array($this->refAndNotRefArrayOfObjectsUnion)
                && count($this->refAndNotRefArrayOfObjectsUnion) === count(array_filter(
                    $this->refAndNotRefArrayOfObjectsUnion,
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item $item): bool => $item instanceof MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item,
                )))
            ) {
                $output['refAndNotRefArrayOfObjectsUnion'] = array_map(
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item $i) => $i->toArray(),
                    $this->refAndNotRefArrayOfObjectsUnion,
                );
            } elseif ((is_array($this->refAndNotRefArrayOfObjectsUnion)
                && count($this->refAndNotRefArrayOfObjectsUnion) === count(array_filter(
                    $this->refAndNotRefArrayOfObjectsUnion,
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item $item): bool => $item instanceof MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item,
                )))
            ) {
                $output['refAndNotRefArrayOfObjectsUnion'] = array_map(
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item $i) => $i->toArray(),
                    $this->refAndNotRefArrayOfObjectsUnion,
                );
            } else {
                $output['refAndNotRefArrayOfObjectsUnion'] = $this->refAndNotRefArrayOfObjectsUnion;
            }
        }
        if (isset($this->arrayOfObjAndStringUnion)) {
            if ((is_array($this->arrayOfObjAndStringUnion)
                && count($this->arrayOfObjAndStringUnion) === count(array_filter(
                    $this->arrayOfObjAndStringUnion,
                    fn (MyClassArrayOfObjAndStringUnionAlternative1Item $item): bool => $item instanceof MyClassArrayOfObjAndStringUnionAlternative1Item,
                )))
            ) {
                $output['arrayOfObjAndStringUnion'] = array_map(
                    fn (MyClassArrayOfObjAndStringUnionAlternative1Item $i) => $i->toArray(),
                    $this->arrayOfObjAndStringUnion,
                );
            } else {
                $output['arrayOfObjAndStringUnion'] = $this->arrayOfObjAndStringUnion;
            }
        }
        if (isset($this->unionOfOneArrayOfObjects)) {
            $output['unionOfOneArrayOfObjects'] = array_map(
                fn (MyClassUnionOfOneArrayOfObjectsItem $i) => $i->toArray(),
                $this->unionOfOneArrayOfObjects,
            );
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

        if (isset($this->arrayOfObjectsUnion)) {
            if ((is_array($this->arrayOfObjectsUnion)
                && count($this->arrayOfObjectsUnion) === count(array_filter(
                    $this->arrayOfObjectsUnion,
                    fn (MyClassArrayOfObjectsUnionAlternative1Item $item): bool => $item instanceof MyClassArrayOfObjectsUnionAlternative1Item,
                )))
            ) {
                $output->{'arrayOfObjectsUnion'} = array_map(
                    fn (MyClassArrayOfObjectsUnionAlternative1Item $i) => $i->toStdClass(),
                    $this->arrayOfObjectsUnion,
                );
            } elseif ((is_array($this->arrayOfObjectsUnion)
                && count($this->arrayOfObjectsUnion) === count(array_filter(
                    $this->arrayOfObjectsUnion,
                    fn (MyClassArrayOfObjectsUnionAlternative2Item $item): bool => $item instanceof MyClassArrayOfObjectsUnionAlternative2Item,
                )))
            ) {
                $output->{'arrayOfObjectsUnion'} = array_map(
                    fn (MyClassArrayOfObjectsUnionAlternative2Item $i) => $i->toStdClass(),
                    $this->arrayOfObjectsUnion,
                );
            } else {
                $output->{'arrayOfObjectsUnion'} = $this->arrayOfObjectsUnion;
            }
        }
        if (isset($this->refArrayOfObjectsUnion)) {
            if ((is_array($this->refArrayOfObjectsUnion)
                && count($this->refArrayOfObjectsUnion) === count(array_filter(
                    $this->refArrayOfObjectsUnion,
                    fn (MyClassRefArrayOfObjectsUnionAlternative1Item $item): bool => $item instanceof MyClassRefArrayOfObjectsUnionAlternative1Item,
                )))
            ) {
                $output->{'refArrayOfObjectsUnion'} = array_map(
                    fn (MyClassRefArrayOfObjectsUnionAlternative1Item $i) => $i->toStdClass(),
                    $this->refArrayOfObjectsUnion,
                );
            } elseif ((is_array($this->refArrayOfObjectsUnion)
                && count($this->refArrayOfObjectsUnion) === count(array_filter(
                    $this->refArrayOfObjectsUnion,
                    fn (MyClassRefArrayOfObjectsUnionAlternative2Item $item): bool => $item instanceof MyClassRefArrayOfObjectsUnionAlternative2Item,
                )))
            ) {
                $output->{'refArrayOfObjectsUnion'} = array_map(
                    fn (MyClassRefArrayOfObjectsUnionAlternative2Item $i) => $i->toStdClass(),
                    $this->refArrayOfObjectsUnion,
                );
            } else {
                $output->{'refArrayOfObjectsUnion'} = $this->refArrayOfObjectsUnion;
            }
        }
        if (isset($this->refAndNotRefArrayOfObjectsUnion)) {
            if ((is_array($this->refAndNotRefArrayOfObjectsUnion)
                && count($this->refAndNotRefArrayOfObjectsUnion) === count(array_filter(
                    $this->refAndNotRefArrayOfObjectsUnion,
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item $item): bool => $item instanceof MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item,
                )))
            ) {
                $output->{'refAndNotRefArrayOfObjectsUnion'} = array_map(
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item $i) => $i->toStdClass(),
                    $this->refAndNotRefArrayOfObjectsUnion,
                );
            } elseif ((is_array($this->refAndNotRefArrayOfObjectsUnion)
                && count($this->refAndNotRefArrayOfObjectsUnion) === count(array_filter(
                    $this->refAndNotRefArrayOfObjectsUnion,
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item $item): bool => $item instanceof MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item,
                )))
            ) {
                $output->{'refAndNotRefArrayOfObjectsUnion'} = array_map(
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item $i) => $i->toStdClass(),
                    $this->refAndNotRefArrayOfObjectsUnion,
                );
            } elseif ((is_array($this->refAndNotRefArrayOfObjectsUnion)
                && count($this->refAndNotRefArrayOfObjectsUnion) === count(array_filter(
                    $this->refAndNotRefArrayOfObjectsUnion,
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item $item): bool => $item instanceof MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item,
                )))
            ) {
                $output->{'refAndNotRefArrayOfObjectsUnion'} = array_map(
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item $i) => $i->toStdClass(),
                    $this->refAndNotRefArrayOfObjectsUnion,
                );
            } elseif ((is_array($this->refAndNotRefArrayOfObjectsUnion)
                && count($this->refAndNotRefArrayOfObjectsUnion) === count(array_filter(
                    $this->refAndNotRefArrayOfObjectsUnion,
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item $item): bool => $item instanceof MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item,
                )))
            ) {
                $output->{'refAndNotRefArrayOfObjectsUnion'} = array_map(
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item $i) => $i->toStdClass(),
                    $this->refAndNotRefArrayOfObjectsUnion,
                );
            } else {
                $output->{'refAndNotRefArrayOfObjectsUnion'} = $this->refAndNotRefArrayOfObjectsUnion;
            }
        }
        if (isset($this->arrayOfObjAndStringUnion)) {
            if ((is_array($this->arrayOfObjAndStringUnion)
                && count($this->arrayOfObjAndStringUnion) === count(array_filter(
                    $this->arrayOfObjAndStringUnion,
                    fn (MyClassArrayOfObjAndStringUnionAlternative1Item $item): bool => $item instanceof MyClassArrayOfObjAndStringUnionAlternative1Item,
                )))
            ) {
                $output->{'arrayOfObjAndStringUnion'} = array_map(
                    fn (MyClassArrayOfObjAndStringUnionAlternative1Item $i) => $i->toStdClass(),
                    $this->arrayOfObjAndStringUnion,
                );
            } else {
                $output->{'arrayOfObjAndStringUnion'} = $this->arrayOfObjAndStringUnion;
            }
        }
        if (isset($this->unionOfOneArrayOfObjects)) {
            $output->{'unionOfOneArrayOfObjects'} = array_map(
                fn (MyClassUnionOfOneArrayOfObjectsItem $i) => $i->toStdClass(),
                $this->unionOfOneArrayOfObjects,
            );
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
        $this->_additionalProperties = json_decode(json_encode($this->_additionalProperties));

        if (isset($this->arrayOfObjectsUnion)) {
            $this->arrayOfObjectsUnion = (((is_array($this->arrayOfObjectsUnion)
                && count($this->arrayOfObjectsUnion) === count(array_filter(
                    $this->arrayOfObjectsUnion,
                    fn (MyClassArrayOfObjectsUnionAlternative1Item $item): bool => $item instanceof MyClassArrayOfObjectsUnionAlternative1Item,
                )))
            )
                ? array_map(
                    fn (MyClassArrayOfObjectsUnionAlternative1Item $i) => clone $i,
                    $this->arrayOfObjectsUnion,
                )
                : (((is_array($this->arrayOfObjectsUnion)
                    && count($this->arrayOfObjectsUnion) === count(array_filter(
                        $this->arrayOfObjectsUnion,
                        fn (MyClassArrayOfObjectsUnionAlternative2Item $item): bool => $item instanceof MyClassArrayOfObjectsUnionAlternative2Item,
                    )))
                )
                    ? array_map(
                        fn (MyClassArrayOfObjectsUnionAlternative2Item $i) => clone $i,
                        $this->arrayOfObjectsUnion,
                    )
                    : $this->arrayOfObjectsUnion
                )
            );
        }
        if (isset($this->refArrayOfObjectsUnion)) {
            $this->refArrayOfObjectsUnion = (((is_array($this->refArrayOfObjectsUnion)
                && count($this->refArrayOfObjectsUnion) === count(array_filter(
                    $this->refArrayOfObjectsUnion,
                    fn (MyClassRefArrayOfObjectsUnionAlternative1Item $item): bool => $item instanceof MyClassRefArrayOfObjectsUnionAlternative1Item,
                )))
            )
                ? array_map(
                    fn (MyClassRefArrayOfObjectsUnionAlternative1Item $i) => clone $i,
                    $this->refArrayOfObjectsUnion,
                )
                : (((is_array($this->refArrayOfObjectsUnion)
                    && count($this->refArrayOfObjectsUnion) === count(array_filter(
                        $this->refArrayOfObjectsUnion,
                        fn (MyClassRefArrayOfObjectsUnionAlternative2Item $item): bool => $item instanceof MyClassRefArrayOfObjectsUnionAlternative2Item,
                    )))
                )
                    ? array_map(
                        fn (MyClassRefArrayOfObjectsUnionAlternative2Item $i) => clone $i,
                        $this->refArrayOfObjectsUnion,
                    )
                    : $this->refArrayOfObjectsUnion
                )
            );
        }
        if (isset($this->refAndNotRefArrayOfObjectsUnion)) {
            $this->refAndNotRefArrayOfObjectsUnion = (((is_array($this->refAndNotRefArrayOfObjectsUnion)
                && count($this->refAndNotRefArrayOfObjectsUnion) === count(array_filter(
                    $this->refAndNotRefArrayOfObjectsUnion,
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item $item): bool => $item instanceof MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item,
                )))
            )
                ? array_map(
                    fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative1Item $i) => clone $i,
                    $this->refAndNotRefArrayOfObjectsUnion,
                )
                : (((is_array($this->refAndNotRefArrayOfObjectsUnion)
                    && count($this->refAndNotRefArrayOfObjectsUnion) === count(array_filter(
                        $this->refAndNotRefArrayOfObjectsUnion,
                        fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item $item): bool => $item instanceof MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item,
                    )))
                )
                    ? array_map(
                        fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative2Item $i) => clone $i,
                        $this->refAndNotRefArrayOfObjectsUnion,
                    )
                    : (((is_array($this->refAndNotRefArrayOfObjectsUnion)
                        && count($this->refAndNotRefArrayOfObjectsUnion) === count(array_filter(
                            $this->refAndNotRefArrayOfObjectsUnion,
                            fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item $item): bool => $item instanceof MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item,
                        )))
                    )
                        ? array_map(
                            fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative3Item $i) => clone $i,
                            $this->refAndNotRefArrayOfObjectsUnion,
                        )
                        : (((is_array($this->refAndNotRefArrayOfObjectsUnion)
                            && count($this->refAndNotRefArrayOfObjectsUnion) === count(array_filter(
                                $this->refAndNotRefArrayOfObjectsUnion,
                                fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item $item): bool => $item instanceof MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item,
                            )))
                        )
                            ? array_map(
                                fn (MyClassRefAndNotRefArrayOfObjectsUnionAlternative4Item $i) => clone $i,
                                $this->refAndNotRefArrayOfObjectsUnion,
                            )
                            : $this->refAndNotRefArrayOfObjectsUnion
                        )
                    )
                )
            );
        }
        if (isset($this->arrayOfObjAndStringUnion)) {
            $this->arrayOfObjAndStringUnion = (((is_array($this->arrayOfObjAndStringUnion)
                && count($this->arrayOfObjAndStringUnion) === count(array_filter(
                    $this->arrayOfObjAndStringUnion,
                    fn (MyClassArrayOfObjAndStringUnionAlternative1Item $item): bool => $item instanceof MyClassArrayOfObjAndStringUnionAlternative1Item,
                )))
            )
                ? array_map(
                    fn (MyClassArrayOfObjAndStringUnionAlternative1Item $i) => clone $i,
                    $this->arrayOfObjAndStringUnion,
                )
                : $this->arrayOfObjAndStringUnion
            );
        }
        if (isset($this->unionOfOneArrayOfObjects)) {
            $this->unionOfOneArrayOfObjects = array_map(fn (MyClassUnionOfOneArrayOfObjectsItem $i) => clone $i, $this->unionOfOneArrayOfObjects);
        }
    }
}
