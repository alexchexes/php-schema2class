<?php

declare(strict_types=1);

namespace Ns\UnionObject_7_4;

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

    /**
     * @var MyClassObjectsUnionAlternative1|MyClassObjectsUnionAlternative2|null
     */
    private ?object $objectsUnion = null;

    /**
     * @var SomeObj1|SomeObj2|null
     */
    private ?object $refObjectsUnion = null;

    /**
     * @var SomeObj1|MyClassRefAndNotRefObjectsUnionAlternative2|SomeObj2|MyClassRefAndNotRefObjectsUnionAlternative4|null
     */
    private ?object $refAndNotRefObjectsUnion = null;

    /**
     * @var MyClassObjAndStringUnionAlternative1|string|null
     */
    private $objAndStringUnion = null;

    private ?MyClassUnionOfOneObj $unionOfOneObj = null;

    /**
     * @var null
     */
    private $unionOfOneNull = null;

    /**
     * @param MyClassObjectsUnionAlternative1|MyClassObjectsUnionAlternative2|null $objectsUnion
     * @param SomeObj1|SomeObj2|null $refObjectsUnion
     * @param SomeObj1|MyClassRefAndNotRefObjectsUnionAlternative2|SomeObj2|MyClassRefAndNotRefObjectsUnionAlternative4|null $refAndNotRefObjectsUnion
     * @param MyClassObjAndStringUnionAlternative1|string|null $objAndStringUnion
     * @param null $unionOfOneNull
     */
    public function __construct(?object $objectsUnion = null, ?object $refObjectsUnion = null, ?object $refAndNotRefObjectsUnion = null, $objAndStringUnion = null, ?MyClassUnionOfOneObj $unionOfOneObj = null, $unionOfOneNull = null)
    {
        $this->objectsUnion = $objectsUnion;
        $this->refObjectsUnion = $refObjectsUnion;
        $this->refAndNotRefObjectsUnion = $refAndNotRefObjectsUnion;
        $this->objAndStringUnion = $objAndStringUnion;
        $this->unionOfOneObj = $unionOfOneObj;
        $this->unionOfOneNull = $unionOfOneNull;
    }

    /**
     * @return MyClassObjectsUnionAlternative1|MyClassObjectsUnionAlternative2|null
     */
    public function getObjectsUnion(): ?object
    {
        return $this->objectsUnion;
    }

    /**
     * @param MyClassObjectsUnionAlternative1|MyClassObjectsUnionAlternative2 $objectsUnion
     */
    public function withObjectsUnion(object $objectsUnion, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($objectsUnion, self::$_schema['properties']['objectsUnion']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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

    /**
     * @return SomeObj1|SomeObj2|null
     */
    public function getRefObjectsUnion(): ?object
    {
        return $this->refObjectsUnion;
    }

    /**
     * @param SomeObj1|SomeObj2 $refObjectsUnion
     */
    public function withRefObjectsUnion(object $refObjectsUnion, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($refObjectsUnion, self::$_schema['properties']['refObjectsUnion']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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

    /**
     * @return SomeObj1|MyClassRefAndNotRefObjectsUnionAlternative2|SomeObj2|MyClassRefAndNotRefObjectsUnionAlternative4|null
     */
    public function getRefAndNotRefObjectsUnion(): ?object
    {
        return $this->refAndNotRefObjectsUnion;
    }

    /**
     * @param SomeObj1|MyClassRefAndNotRefObjectsUnionAlternative2|SomeObj2|MyClassRefAndNotRefObjectsUnionAlternative4 $refAndNotRefObjectsUnion
     */
    public function withRefAndNotRefObjectsUnion(object $refAndNotRefObjectsUnion, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($refAndNotRefObjectsUnion, self::$_schema['properties']['refAndNotRefObjectsUnion']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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

    /**
     * @return MyClassObjAndStringUnionAlternative1|string|null
     */
    public function getObjAndStringUnion()
    {
        return $this->objAndStringUnion;
    }

    /**
     * @param MyClassObjAndStringUnionAlternative1|string $objAndStringUnion
     */
    public function withObjAndStringUnion($objAndStringUnion, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($objAndStringUnion, self::$_schema['properties']['objAndStringUnion']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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

        $__providedOptionals = [];
        $objectsUnion = isset($input->{'objectsUnion'}) ? ((MyClassObjectsUnionAlternative2::validateInput($input->{'objectsUnion'}, true)) ? MyClassObjectsUnionAlternative2::fromInput($input->{'objectsUnion'}, $validate) : (((MyClassObjectsUnionAlternative1::validateInput($input->{'objectsUnion'}, true)) ? MyClassObjectsUnionAlternative1::fromInput($input->{'objectsUnion'}, $validate) : (null)))) : null;
        $refObjectsUnion = isset($input->{'refObjectsUnion'}) ? ((SomeObj2::validateInput($input->{'refObjectsUnion'}, true)) ? SomeObj2::fromInput($input->{'refObjectsUnion'}, $validate) : (((SomeObj1::validateInput($input->{'refObjectsUnion'}, true)) ? SomeObj1::fromInput($input->{'refObjectsUnion'}, $validate) : (null)))) : null;
        $refAndNotRefObjectsUnion = isset($input->{'refAndNotRefObjectsUnion'}) ? ((MyClassRefAndNotRefObjectsUnionAlternative4::validateInput($input->{'refAndNotRefObjectsUnion'}, true)) ? MyClassRefAndNotRefObjectsUnionAlternative4::fromInput($input->{'refAndNotRefObjectsUnion'}, $validate) : (((SomeObj2::validateInput($input->{'refAndNotRefObjectsUnion'}, true)) ? SomeObj2::fromInput($input->{'refAndNotRefObjectsUnion'}, $validate) : (((MyClassRefAndNotRefObjectsUnionAlternative2::validateInput($input->{'refAndNotRefObjectsUnion'}, true)) ? MyClassRefAndNotRefObjectsUnionAlternative2::fromInput($input->{'refAndNotRefObjectsUnion'}, $validate) : (((SomeObj1::validateInput($input->{'refAndNotRefObjectsUnion'}, true)) ? SomeObj1::fromInput($input->{'refAndNotRefObjectsUnion'}, $validate) : (null)))))))) : null;
        $objAndStringUnion = isset($input->{'objAndStringUnion'}) ? ((is_string($input->{'objAndStringUnion'})) ? $input->{'objAndStringUnion'} : (((MyClassObjAndStringUnionAlternative1::validateInput($input->{'objAndStringUnion'}, true)) ? MyClassObjAndStringUnionAlternative1::fromInput($input->{'objAndStringUnion'}, $validate) : (null)))) : null;
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
            if (($this->objectsUnion instanceof MyClassObjectsUnionAlternative1) || ($this->objectsUnion instanceof MyClassObjectsUnionAlternative2)) {
                $output['objectsUnion'] = $this->objectsUnion->toArray();
            }
        }
        if (isset($this->refObjectsUnion)) {
            if (($this->refObjectsUnion instanceof SomeObj1) || ($this->refObjectsUnion instanceof SomeObj2)) {
                $output['refObjectsUnion'] = $this->refObjectsUnion->toArray();
            }
        }
        if (isset($this->refAndNotRefObjectsUnion)) {
            if (($this->refAndNotRefObjectsUnion instanceof SomeObj1) || ($this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative2) || ($this->refAndNotRefObjectsUnion instanceof SomeObj2) || ($this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative4)) {
                $output['refAndNotRefObjectsUnion'] = $this->refAndNotRefObjectsUnion->toArray();
            }
        }
        if (isset($this->objAndStringUnion)) {
            if (($this->objAndStringUnion instanceof MyClassObjAndStringUnionAlternative1)) {
                $output['objAndStringUnion'] = $this->objAndStringUnion->toArray();
            } else if ((is_string($this->objAndStringUnion))) {
                $output['objAndStringUnion'] = $this->objAndStringUnion;
            }
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
            if (($this->objectsUnion instanceof MyClassObjectsUnionAlternative1) || ($this->objectsUnion instanceof MyClassObjectsUnionAlternative2)) {
            $output->{'objectsUnion'} = $this->objectsUnion->toStdClass();
            }
        }
        if (isset($this->refObjectsUnion)) {
            if (($this->refObjectsUnion instanceof SomeObj1) || ($this->refObjectsUnion instanceof SomeObj2)) {
            $output->{'refObjectsUnion'} = $this->refObjectsUnion->toStdClass();
            }
        }
        if (isset($this->refAndNotRefObjectsUnion)) {
            if (($this->refAndNotRefObjectsUnion instanceof SomeObj1) || ($this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative2) || ($this->refAndNotRefObjectsUnion instanceof SomeObj2) || ($this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative4)) {
            $output->{'refAndNotRefObjectsUnion'} = $this->refAndNotRefObjectsUnion->toStdClass();
            }
        }
        if (isset($this->objAndStringUnion)) {
            if (($this->objAndStringUnion instanceof MyClassObjAndStringUnionAlternative1)) {
            $output->{'objAndStringUnion'} = $this->objAndStringUnion->toStdClass();
            } else if ((is_string($this->objAndStringUnion))) {
            $output->{'objAndStringUnion'} = $this->objAndStringUnion;
            }
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
            $this->objectsUnion = ($this->objectsUnion instanceof MyClassObjectsUnionAlternative2 ? clone $this->objectsUnion : ($this->objectsUnion instanceof MyClassObjectsUnionAlternative1 ? clone $this->objectsUnion : $this->objectsUnion));
        }
        if (isset($this->refObjectsUnion)) {
            $this->refObjectsUnion = ($this->refObjectsUnion instanceof SomeObj2 ? $this->refObjectsUnion : ($this->refObjectsUnion instanceof SomeObj1 ? $this->refObjectsUnion : $this->refObjectsUnion));
        }
        if (isset($this->refAndNotRefObjectsUnion)) {
            $this->refAndNotRefObjectsUnion = ($this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative4 ? clone $this->refAndNotRefObjectsUnion : ($this->refAndNotRefObjectsUnion instanceof SomeObj2 ? $this->refAndNotRefObjectsUnion : ($this->refAndNotRefObjectsUnion instanceof MyClassRefAndNotRefObjectsUnionAlternative2 ? clone $this->refAndNotRefObjectsUnion : ($this->refAndNotRefObjectsUnion instanceof SomeObj1 ? $this->refAndNotRefObjectsUnion : $this->refAndNotRefObjectsUnion))));
        }
        if (isset($this->objAndStringUnion)) {
            $this->objAndStringUnion = (is_string($this->objAndStringUnion) ? $this->objAndStringUnion : ($this->objAndStringUnion instanceof MyClassObjAndStringUnionAlternative1 ? clone $this->objAndStringUnion : $this->objAndStringUnion));
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
