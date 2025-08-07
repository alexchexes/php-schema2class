<?php

declare(strict_types=1);

namespace Ns\UnionObject_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
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
                        'type' => 'string a',
                    ],
                ],
            ],
            'SomeObj2' => [
                'properties' => [
                    'a' => [
                        'type' => 'string b',
                    ],
                ],
            ],
        ],
    ];

    /**
     * Map of optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private array $_providedOptionals = [];

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
        $this->objectsUnion = $objectsUnion;
        $this->refObjectsUnion = $refObjectsUnion;
        $this->refAndNotRefObjectsUnion = $refAndNotRefObjectsUnion;
        $this->objAndStringUnion = $objAndStringUnion;
        $this->unionOfOneObj = $unionOfOneObj;
        $this->unionOfOneNull = $unionOfOneNull;
    }

    public function getObjectsUnion(): MyClassObjectsUnionAlternative1|MyClassObjectsUnionAlternative2|null
    {
        return $this->objectsUnion;
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
        return $this->refObjectsUnion;
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
        return $this->refAndNotRefObjectsUnion;
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
        return $this->objAndStringUnion;
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
        return $this->unionOfOneObj;
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
        return $this->unionOfOneNull;
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

        $__providedOptionals = [];
        $objectsUnion = isset($input->{'objectsUnion'}) ? match (true) {
            MyClassObjectsUnionAlternative1::validateInput($input->{'objectsUnion'}, true) => MyClassObjectsUnionAlternative1::fromInput($input->{'objectsUnion'}, $validate),
            MyClassObjectsUnionAlternative2::validateInput($input->{'objectsUnion'}, true) => MyClassObjectsUnionAlternative2::fromInput($input->{'objectsUnion'}, $validate),
            default => null,
        } : null;
        $refObjectsUnion = isset($input->{'refObjectsUnion'}) ? match (true) {
            SomeObj1::validateInput($input->{'refObjectsUnion'}, true) => SomeObj1::fromInput($input->{'refObjectsUnion'}, $validate),
            SomeObj2::validateInput($input->{'refObjectsUnion'}, true) => SomeObj2::fromInput($input->{'refObjectsUnion'}, $validate),
            default => null,
        } : null;
        $refAndNotRefObjectsUnion = isset($input->{'refAndNotRefObjectsUnion'}) ? match (true) {
            SomeObj1::validateInput($input->{'refAndNotRefObjectsUnion'}, true) => SomeObj1::fromInput($input->{'refAndNotRefObjectsUnion'}, $validate),
            MyClassRefAndNotRefObjectsUnionAlternative2::validateInput($input->{'refAndNotRefObjectsUnion'}, true) => MyClassRefAndNotRefObjectsUnionAlternative2::fromInput($input->{'refAndNotRefObjectsUnion'}, $validate),
            SomeObj2::validateInput($input->{'refAndNotRefObjectsUnion'}, true) => SomeObj2::fromInput($input->{'refAndNotRefObjectsUnion'}, $validate),
            MyClassRefAndNotRefObjectsUnionAlternative4::validateInput($input->{'refAndNotRefObjectsUnion'}, true) => MyClassRefAndNotRefObjectsUnionAlternative4::fromInput($input->{'refAndNotRefObjectsUnion'}, $validate),
            default => null,
        } : null;
        $objAndStringUnion = isset($input->{'objAndStringUnion'}) ? match (true) {
            MyClassObjAndStringUnionAlternative1::validateInput($input->{'objAndStringUnion'}, true) => MyClassObjAndStringUnionAlternative1::fromInput($input->{'objAndStringUnion'}, $validate),
            is_string($input->{'objAndStringUnion'}) => $input->{'objAndStringUnion'},
            default => null,
        } : null;
        $unionOfOneObj = isset($input->{'unionOfOneObj'}) ? MyClassUnionOfOneObj::fromInput($input->{'unionOfOneObj'}, $validate) : null;
        $unionOfOneNull = null;
        if (property_exists($input, 'unionOfOneNull')) {
            $unionOfOneNull = ($input->{'unionOfOneNull'} !== null ? $input->{'unionOfOneNull'} : null);
            $__providedOptionals['unionOfOneNull'] = true;
        }

        $obj = new self(
            $objectsUnion,
            $refObjectsUnion,
            $refAndNotRefObjectsUnion,
            $objAndStringUnion,
            $unionOfOneObj,
            $unionOfOneNull
        );
        $obj->_providedOptionals = $__providedOptionals;
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
        if (isset($this->objectsUnion)) {
            $output['objectsUnion'] = match (true) {
                $this->objectsUnion instanceof MyClassObjectsUnionAlternative1,
                $this->objectsUnion instanceof MyClassObjectsUnionAlternative2 => $this->objectsUnion->toArray(),
            };
        }
        if (isset($this->refObjectsUnion)) {
            $output['refObjectsUnion'] = match (true) {
                $this->refObjectsUnion instanceof SomeObj1,
                $this->refObjectsUnion instanceof SomeObj2 => $this->refObjectsUnion->toArray(),
            };
        }
        if (isset($this->refAndNotRefObjectsUnion)) {
            $output['refAndNotRefObjectsUnion'] = match (true) {
                $this->refAndNotRefObjectsUnion instanceof SomeObj1,
                $this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative2,
                $this->refAndNotRefObjectsUnion instanceof SomeObj2,
                $this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative4 => $this->refAndNotRefObjectsUnion->toArray(),
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
            $output['unionOfOneNull'] = ($this->unionOfOneNull !== null) ? ($this->unionOfOneNull) : null;
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
        $output = new \stdClass();
        if (isset($this->objectsUnion)) {
            $output->{'objectsUnion'} = match (true) {
                $this->objectsUnion instanceof MyClassObjectsUnionAlternative1,
                $this->objectsUnion instanceof MyClassObjectsUnionAlternative2 => $this->objectsUnion->toStdClass(),
            };
        }
        if (isset($this->refObjectsUnion)) {
            $output->{'refObjectsUnion'} = match (true) {
                $this->refObjectsUnion instanceof SomeObj1,
                $this->refObjectsUnion instanceof SomeObj2 => $this->refObjectsUnion->toStdClass(),
            };
        }
        if (isset($this->refAndNotRefObjectsUnion)) {
            $output->{'refAndNotRefObjectsUnion'} = match (true) {
                $this->refAndNotRefObjectsUnion instanceof SomeObj1,
                $this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative2,
                $this->refAndNotRefObjectsUnion instanceof SomeObj2,
                $this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative4 => $this->refAndNotRefObjectsUnion->toStdClass(),
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
            $output->{'unionOfOneNull'} = ($this->unionOfOneNull !== null) ? ($this->unionOfOneNull) : null;
        }

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

    public function __clone()
    {
        if (isset($this->objectsUnion)) {
            $this->objectsUnion = match (true) {
                $this->objectsUnion instanceof MyClassObjectsUnionAlternative1,
                $this->objectsUnion instanceof MyClassObjectsUnionAlternative2 => clone $this->objectsUnion,
            };
        }
        if (isset($this->refObjectsUnion)) {
            $this->refObjectsUnion = match (true) {
                $this->refObjectsUnion instanceof SomeObj1,
                $this->refObjectsUnion instanceof SomeObj2 => $this->refObjectsUnion,
            };
        }
        if (isset($this->refAndNotRefObjectsUnion)) {
            $this->refAndNotRefObjectsUnion = match (true) {
                $this->refAndNotRefObjectsUnion instanceof SomeObj1,
                $this->refAndNotRefObjectsUnion instanceof SomeObj2 => $this->refAndNotRefObjectsUnion,
                $this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative2,
                $this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative4 => clone $this->refAndNotRefObjectsUnion,
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
     * Checks if an optional nullable property was explicitly set
     *
     * @param string $propertyName Property name to check (exactly as it appears in the schema)
     * @return bool
     */
    public function isOptionalProvided(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->_providedOptionals);
    }
}
