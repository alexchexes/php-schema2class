<?php

declare(strict_types=1);

namespace Ns\UnionObject_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'properties' => [
            'objectsUnion' => [
                'oneOf' => [
                    [
                        'properties' => [
                            'type' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                    [
                        'properties' => [
                            'accountNumber' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                ],
            ],
            'refObjectsUnion' => [
                'oneOf' => [
                    [
                        '$ref' => '#/definitions/SomeObj1',
                    ],
                    [
                        '$ref' => '#/definitions/SomeObj2',
                    ],
                ],
            ],
            'refAndNotRefObjectsUnion' => [
                'oneOf' => [
                    [
                        '$ref' => '#/definitions/SomeObj1',
                    ],
                    [
                        'properties' => [
                            'type' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                    [
                        '$ref' => '#/definitions/SomeObj2',
                    ],
                    [
                        'properties' => [
                            'accountNumber' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                ],
            ],
            'objAndStringUnion' => [
                'oneOf' => [
                    [
                        'properties' => [
                            'type' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                    [
                        'type' => 'string',
                    ],
                ],
            ],
            'unionOfOneObj' => [
                'oneOf' => [
                    [
                        'properties' => [
                            'type' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                ],
            ],
            'unionOfOneNull' => [
                'oneOf' => [
                    [
                        'type' => 'null',
                    ],
                ],
            ],
        ],
        'definitions' => [
            'SomeObj1' => [
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'SomeObj2' => [
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'objectsUnion' => 'objectsUnion',
        'refObjectsUnion' => 'refObjectsUnion',
        'refAndNotRefObjectsUnion' => 'refAndNotRefObjectsUnion',
        'objAndStringUnion' => 'objAndStringUnion',
        'unionOfOneObj' => 'unionOfOneObj',
        'unionOfOneNull' => 'unionOfOneNull',
    ];

    /**
     * Map of optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private array $_providedOptionals = [];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    private MyClassObjectsUnionAlternative1|MyClassObjectsUnionAlternative2|null $objectsUnion = null;

    private SomeObj1|SomeObj2|null $refObjectsUnion = null;

    private MyClassRefAndNotRefObjectsUnionAlternative2|MyClassRefAndNotRefObjectsUnionAlternative4|SomeObj1|SomeObj2|null $refAndNotRefObjectsUnion = null;

    private MyClassObjAndStringUnionAlternative1|string|null $objAndStringUnion = null;

    private ?MyClassUnionOfOneObj $unionOfOneObj = null;

    /**
     * @var null
     */
    private $unionOfOneNull = null;

    /**
     * @param null $unionOfOneNull
     */
    public function __construct(MyClassObjectsUnionAlternative1|MyClassObjectsUnionAlternative2|null $objectsUnion = null, SomeObj1|SomeObj2|null $refObjectsUnion = null, MyClassRefAndNotRefObjectsUnionAlternative2|MyClassRefAndNotRefObjectsUnionAlternative4|SomeObj1|SomeObj2|null $refAndNotRefObjectsUnion = null, MyClassObjAndStringUnionAlternative1|string|null $objAndStringUnion = null, ?MyClassUnionOfOneObj $unionOfOneObj = null, $unionOfOneNull = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->objectsUnion = $objectsUnion;
        $this->refObjectsUnion = $refObjectsUnion;
        $this->refAndNotRefObjectsUnion = $refAndNotRefObjectsUnion;
        $this->objAndStringUnion = $objAndStringUnion;
        $this->unionOfOneObj = $unionOfOneObj;
        if ($unionOfOneNull !== null) {
            $this->unionOfOneNull = $unionOfOneNull;
            $this->_providedOptionals['unionOfOneNull'] = true;
        };
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

    public function getObjectsUnion(): MyClassObjectsUnionAlternative1|MyClassObjectsUnionAlternative2|null
    {
        return $this->objectsUnion ?? null;
    }

    public function withObjectsUnion(MyClassObjectsUnionAlternative1|MyClassObjectsUnionAlternative2 $objectsUnion): self
    {
        $clone = clone $this;
        $clone->objectsUnion = $objectsUnion;

        return $clone;
    }

    public function withoutObjectsUnion(): self
    {
        $clone = clone $this;
        unset($clone->objectsUnion);

        return $clone;
    }

    public function getRefObjectsUnion(): SomeObj1|SomeObj2|null
    {
        return $this->refObjectsUnion ?? null;
    }

    public function withRefObjectsUnion(SomeObj1|SomeObj2 $refObjectsUnion): self
    {
        $clone = clone $this;
        $clone->refObjectsUnion = $refObjectsUnion;

        return $clone;
    }

    public function withoutRefObjectsUnion(): self
    {
        $clone = clone $this;
        unset($clone->refObjectsUnion);

        return $clone;
    }

    public function getRefAndNotRefObjectsUnion(): MyClassRefAndNotRefObjectsUnionAlternative2|MyClassRefAndNotRefObjectsUnionAlternative4|SomeObj1|SomeObj2|null
    {
        return $this->refAndNotRefObjectsUnion ?? null;
    }

    public function withRefAndNotRefObjectsUnion(MyClassRefAndNotRefObjectsUnionAlternative2|MyClassRefAndNotRefObjectsUnionAlternative4|SomeObj1|SomeObj2 $refAndNotRefObjectsUnion): self
    {
        $clone = clone $this;
        $clone->refAndNotRefObjectsUnion = $refAndNotRefObjectsUnion;

        return $clone;
    }

    public function withoutRefAndNotRefObjectsUnion(): self
    {
        $clone = clone $this;
        unset($clone->refAndNotRefObjectsUnion);

        return $clone;
    }

    public function getObjAndStringUnion(): MyClassObjAndStringUnionAlternative1|string|null
    {
        return $this->objAndStringUnion ?? null;
    }

    public function withObjAndStringUnion(MyClassObjAndStringUnionAlternative1|string $objAndStringUnion): self
    {
        $clone = clone $this;
        $clone->objAndStringUnion = $objAndStringUnion;

        return $clone;
    }

    public function withoutObjAndStringUnion(): self
    {
        $clone = clone $this;
        unset($clone->objAndStringUnion);

        return $clone;
    }

    public function getUnionOfOneObj(): ?MyClassUnionOfOneObj
    {
        return $this->unionOfOneObj ?? null;
    }

    public function withUnionOfOneObj(MyClassUnionOfOneObj $unionOfOneObj): self
    {
        $clone = clone $this;
        $clone->unionOfOneObj = $unionOfOneObj;

        return $clone;
    }

    public function withoutUnionOfOneObj(): self
    {
        $clone = clone $this;
        unset($clone->unionOfOneObj);

        return $clone;
    }

    /**
     * @return null
     */
    public function getUnionOfOneNull()
    {
        return $this->unionOfOneNull ?? null;
    }

    /**
     * @param null $unionOfOneNull
     */
    public function withUnionOfOneNull($unionOfOneNull, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($unionOfOneNull, self::$_schema['properties']['unionOfOneNull']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->unionOfOneNull = $unionOfOneNull;
        $clone->_providedOptionals['unionOfOneNull'] = true;

        return $clone;
    }

    public function withoutUnionOfOneNull(): self
    {
        $clone = clone $this;
        unset($clone->unionOfOneNull);
        unset($clone->_providedOptionals['unionOfOneNull']);

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

        $_providedOptionals = [];
        $objectsUnion = isset($input->{'objectsUnion'})
            ? match (true) {
                MyClassObjectsUnionAlternative1::validateInput($input->{'objectsUnion'}, true) =>
                    MyClassObjectsUnionAlternative1::fromInput($input->{'objectsUnion'}, $validate),
                MyClassObjectsUnionAlternative2::validateInput($input->{'objectsUnion'}, true) =>
                    MyClassObjectsUnionAlternative2::fromInput($input->{'objectsUnion'}, $validate),
                default => null,
            }
            : null;
        $refObjectsUnion = isset($input->{'refObjectsUnion'})
            ? match (true) {
                SomeObj1::validateInput($input->{'refObjectsUnion'}, true) => SomeObj1::fromInput($input->{'refObjectsUnion'}, $validate),
                SomeObj2::validateInput($input->{'refObjectsUnion'}, true) => SomeObj2::fromInput($input->{'refObjectsUnion'}, $validate),
                default => null,
            }
            : null;
        $refAndNotRefObjectsUnion = isset($input->{'refAndNotRefObjectsUnion'})
            ? match (true) {
                SomeObj1::validateInput($input->{'refAndNotRefObjectsUnion'}, true) =>
                    SomeObj1::fromInput($input->{'refAndNotRefObjectsUnion'}, $validate),
                MyClassRefAndNotRefObjectsUnionAlternative2::validateInput($input->{'refAndNotRefObjectsUnion'}, true) =>
                    MyClassRefAndNotRefObjectsUnionAlternative2::fromInput($input->{'refAndNotRefObjectsUnion'}, $validate),
                SomeObj2::validateInput($input->{'refAndNotRefObjectsUnion'}, true) =>
                    SomeObj2::fromInput($input->{'refAndNotRefObjectsUnion'}, $validate),
                MyClassRefAndNotRefObjectsUnionAlternative4::validateInput($input->{'refAndNotRefObjectsUnion'}, true) =>
                    MyClassRefAndNotRefObjectsUnionAlternative4::fromInput($input->{'refAndNotRefObjectsUnion'}, $validate),
                default => null,
            }
            : null;
        $objAndStringUnion = isset($input->{'objAndStringUnion'})
            ? match (true) {
                MyClassObjAndStringUnionAlternative1::validateInput($input->{'objAndStringUnion'}, true) =>
                    MyClassObjAndStringUnionAlternative1::fromInput($input->{'objAndStringUnion'}, $validate),
                is_string($input->{'objAndStringUnion'}) => $input->{'objAndStringUnion'},
                default => null,
            }
            : null;
        $unionOfOneObj = isset($input->{'unionOfOneObj'})
            ? MyClassUnionOfOneObj::fromInput($input->{'unionOfOneObj'}, $validate)
            : null;
        $unionOfOneNull = null;
        if (property_exists($input, 'unionOfOneNull')) {
            $unionOfOneNull = ($input->{'unionOfOneNull'} !== null ? $input->{'unionOfOneNull'} : null);
            $_providedOptionals['unionOfOneNull'] = true;
        }

        $obj = new self(
            $objectsUnion,
            $refObjectsUnion,
            $refAndNotRefObjectsUnion,
            $objAndStringUnion,
            $unionOfOneObj,
            $unionOfOneNull
        );
        $obj->_providedOptionals = $_providedOptionals;

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

        if (isset($this->objectsUnion)) {
            $output['objectsUnion'] = match (true) {
                $this->objectsUnion instanceof MyClassObjectsUnionAlternative1
                    || $this->objectsUnion instanceof MyClassObjectsUnionAlternative2 =>
                    $this->objectsUnion->toArray(),
            };
        }
        if (isset($this->refObjectsUnion)) {
            $output['refObjectsUnion'] = match (true) {
                $this->refObjectsUnion instanceof SomeObj1 || $this->refObjectsUnion instanceof SomeObj2 =>
                    $this->refObjectsUnion->toArray(),
            };
        }
        if (isset($this->refAndNotRefObjectsUnion)) {
            $output['refAndNotRefObjectsUnion'] = match (true) {
                $this->refAndNotRefObjectsUnion instanceof SomeObj1
                    || $this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative2
                    || $this->refAndNotRefObjectsUnion instanceof SomeObj2
                    || $this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative4 =>
                    $this->refAndNotRefObjectsUnion->toArray(),
            };
        }
        if (isset($this->objAndStringUnion)) {
            $output['objAndStringUnion'] = match (true) {
                $this->objAndStringUnion instanceof MyClassObjAndStringUnionAlternative1 => $this->objAndStringUnion->toArray(),
                is_string($this->objAndStringUnion) => $this->objAndStringUnion,
            };
        }
        if (isset($this->unionOfOneObj)) {
            $output['unionOfOneObj'] = $this->unionOfOneObj->toArray();
        }
        if (isset($this->unionOfOneNull) || array_key_exists('unionOfOneNull', $this->_providedOptionals)) {
            $output['unionOfOneNull'] = ($this->unionOfOneNull !== null ? $this->unionOfOneNull : null);
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

        if (isset($this->objectsUnion)) {
            $output->{'objectsUnion'} = match (true) {
                $this->objectsUnion instanceof MyClassObjectsUnionAlternative1
                    || $this->objectsUnion instanceof MyClassObjectsUnionAlternative2 =>
                    $this->objectsUnion->toStdClass(),
            };
        }
        if (isset($this->refObjectsUnion)) {
            $output->{'refObjectsUnion'} = match (true) {
                $this->refObjectsUnion instanceof SomeObj1 || $this->refObjectsUnion instanceof SomeObj2 =>
                    $this->refObjectsUnion->toStdClass(),
            };
        }
        if (isset($this->refAndNotRefObjectsUnion)) {
            $output->{'refAndNotRefObjectsUnion'} = match (true) {
                $this->refAndNotRefObjectsUnion instanceof SomeObj1
                    || $this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative2
                    || $this->refAndNotRefObjectsUnion instanceof SomeObj2
                    || $this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative4 =>
                    $this->refAndNotRefObjectsUnion->toStdClass(),
            };
        }
        if (isset($this->objAndStringUnion)) {
            $output->{'objAndStringUnion'} = match (true) {
                $this->objAndStringUnion instanceof MyClassObjAndStringUnionAlternative1 => $this->objAndStringUnion->toStdClass(),
                is_string($this->objAndStringUnion) => $this->objAndStringUnion,
            };
        }
        if (isset($this->unionOfOneObj)) {
            $output->{'unionOfOneObj'} = $this->unionOfOneObj->toStdClass();
        }
        if (isset($this->unionOfOneNull) || array_key_exists('unionOfOneNull', $this->_providedOptionals)) {
            $output->{'unionOfOneNull'} = ($this->unionOfOneNull !== null ? $this->unionOfOneNull : null);
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
        if (isset($this->objectsUnion)) {
            $this->objectsUnion = match (true) {
                $this->objectsUnion instanceof MyClassObjectsUnionAlternative1
                    || $this->objectsUnion instanceof MyClassObjectsUnionAlternative2 =>
                    clone $this->objectsUnion,
            };
        }
        if (isset($this->refObjectsUnion)) {
            $this->refObjectsUnion = match (true) {
                $this->refObjectsUnion instanceof SomeObj1 || $this->refObjectsUnion instanceof SomeObj2 => clone $this->refObjectsUnion,
            };
        }
        if (isset($this->refAndNotRefObjectsUnion)) {
            $this->refAndNotRefObjectsUnion = match (true) {
                $this->refAndNotRefObjectsUnion instanceof SomeObj1
                    || $this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative2
                    || $this->refAndNotRefObjectsUnion instanceof SomeObj2
                    || $this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative4 =>
                    clone $this->refAndNotRefObjectsUnion,
            };
        }
        if (isset($this->objAndStringUnion)) {
            $this->objAndStringUnion = match (true) {
                $this->objAndStringUnion instanceof MyClassObjAndStringUnionAlternative1 => clone $this->objAndStringUnion,
                is_string($this->objAndStringUnion) => $this->objAndStringUnion,
            };
        }
        if (isset($this->unionOfOneObj)) {
            $this->unionOfOneObj = clone $this->unionOfOneObj;
        }
    }

    /**
     * Checks if an optional nullable property was explicitly set.
     *
     * @param string $propertyName Property name to check (exactly as it appears in the schema).
     * @throws \InvalidArgumentException If property with that name doesn't exist.
     */
    public function isOptionalProvided(string $propertyName): bool
    {
        if (!array_key_exists($propertyName, self::$_namesMap)) {
            throw new \InvalidArgumentException("Unknown property: {$propertyName}");
        }
        return
            array_key_exists($propertyName, $this->_providedOptionals)
            || isset($this->{ self::$_namesMap[$propertyName] });
    }
}
