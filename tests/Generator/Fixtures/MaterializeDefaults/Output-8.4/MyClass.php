<?php

declare(strict_types=1);

namespace Ns\MaterializeDefaults_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'type' => 'object',
        'required' => [
            'foo',
            'bar',
        ],
        'properties' => [
            'foo' => [
                'type' => 'string',
            ],
            'bar' => [
                'type' => 'string',
                'default' => 'some default value for foo',
            ],
            'baz' => [
                'type' => 'object',
                'properties' => [
                    'nestedFoo' => [
                        'type' => 'string',
                    ],
                ],
                'required' => [
                    'nestedFoo',
                ],
                'default' => [
                    'nestedFoo' => 'some value inside default value for \'bar\' object',
                ],
            ],
            'quxObj' => [
                'type' => [
                    'object',
                    'null',
                ],
                'description' => 'optional nullable object with default value that is empty object',
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                ],
                'default' => [
                    
                ],
            ],
            'quxObjNest' => [
                'type' => [
                    'object',
                    'null',
                ],
                'description' => 'optional nullable object with default empty object value, and with nested default for its property',
                'properties' => [
                    'a' => [
                        'type' => 'object',
                        'default' => [
                            
                        ],
                    ],
                ],
                'default' => [
                    
                ],
            ],
            'thudArray' => [
                'type' => [
                    'array',
                    'null',
                ],
                'description' => 'optional nullable array with default value that is empty array',
                'items' => [
                    'type' => 'string',
                ],
                'default' => [
                    
                ],
            ],
            'xyyz' => [
                'anyOf' => [
                    [
                        'type' => 'string',
                    ],
                    [
                        '$ref' => '#/definitions/ObjDef',
                    ],
                    [
                        '$ref' => '#/definitions/ArrayDef',
                    ],
                ],
            ],
            'buux' => [
                'anyOf' => [
                    [
                        'type' => 'string',
                    ],
                    [
                        '$ref' => '#/definitions/ArrayDef',
                    ],
                    [
                        '$ref' => '#/definitions/ObjDef',
                    ],
                ],
            ],
            'boic' => [
                'anyOf' => [
                    [
                        'type' => 'string',
                        'default' => 'a string',
                    ],
                    [
                        '$ref' => '#/definitions/ArrayDef',
                    ],
                    [
                        '$ref' => '#/definitions/ObjDef',
                    ],
                ],
            ],
            'poox' => [
                'anyOf' => [
                    [
                        'type' => 'string',
                    ],
                    [
                        '$ref' => '#/definitions/NumericKeysObj',
                    ],
                ],
            ],
            'arrObjUnion' => [
                'type' => [
                    'array',
                    'object',
                ],
                'default' => [
                    
                ],
            ],
            'objArrUnion' => [
                'type' => [
                    'array',
                    'object',
                ],
                'default' => [
                    
                ],
            ],
            'numKeysDefaults' => [
                'type' => 'object',
                'properties' => [
                    [
                        'type' => 'string',
                        'default' => 'default for \'0\'',
                    ],
                    [
                        'type' => 'string',
                        'default' => 'default for \'1\'',
                    ],
                    [
                        'type' => 'string',
                        'default' => 'default for \'2\'',
                    ],
                ],
                'default' => [
                    
                ],
            ],
        ],
        'definitions' => [
            'ObjDef' => [
                'type' => 'object',
                'description' => 'Definition of an object with default value that is empty object',
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                ],
                'default' => [
                    
                ],
            ],
            'ArrayDef' => [
                'type' => 'array',
                'description' => 'Definition of an array with default value that is empty array',
                'items' => [
                    'type' => 'string',
                ],
                'default' => [
                    
                ],
            ],
            'NumericKeysObj' => [
                'type' => 'object',
                'properties' => [
                    [
                        'type' => 'string',
                    ],
                    [
                        'type' => 'string',
                    ],
                    [
                        'type' => 'string',
                    ],
                ],
                'default' => [
                    'a default string for \'0\'',
                    'a default string for \'1\'',
                    'a default string for \'2\'',
                ],
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'foo' => 'foo',
        'bar' => 'bar',
        'baz' => 'baz',
        'quxObj' => 'quxObj',
        'quxObjNest' => 'quxObjNest',
        'thudArray' => 'thudArray',
        'xyyz' => 'xyyz',
        'buux' => 'buux',
        'boic' => 'boic',
        'poox' => 'poox',
        'arrObjUnion' => 'arrObjUnion',
        'objArrUnion' => 'objArrUnion',
        'numKeysDefaults' => 'numKeysDefaults',
    ];

    /**
     * Default values from the schema
     */
    private static array $_defaults = [
        'bar' => [
            'default' => 'some default value for foo',
        ],
        'baz' => [
            'default' => [
                'nestedFoo' => 'some value inside default value for \'bar\' object',
            ],
            'type' => 'object',
        ],
        'quxObj' => [
            'default' => [
                
            ],
            'type' => 'object',
        ],
        'quxObjNest' => [
            'default' => [
                
            ],
            'type' => 'object',
        ],
        'thudArray' => [
            'default' => [
                
            ],
            'type' => 'array',
        ],
        'xyyz' => [
            'default' => [
                
            ],
            'type' => 'object',
        ],
        'buux' => [
            'default' => [
                
            ],
            'type' => 'array',
        ],
        'boic' => [
            'default' => 'a string',
        ],
        'poox' => [
            'default' => [
                'a default string for \'0\'',
                'a default string for \'1\'',
                'a default string for \'2\'',
            ],
            'type' => 'object',
        ],
        'arrObjUnion' => [
            'default' => [
                
            ],
            'type' => 'array',
        ],
        'objArrUnion' => [
            'default' => [
                
            ],
            'type' => 'object',
        ],
        'numKeysDefaults' => [
            'default' => [
                
            ],
            'type' => 'object',
        ],
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

    private string $foo;

    private string $bar;

    private ?MyClassBaz $baz = null;

    private ?MyClassQuxObj $quxObj = null;

    private ?MyClassQuxObjNest $quxObjNest = null;

    /**
     * @var string[]|null
     */
    private ?array $thudArray = null;

    /**
     * @var string|ObjDef|string[]|null
     */
    private ObjDef|string|array|null $xyyz = null;

    /**
     * @var string|string[]|ObjDef|null
     */
    private ObjDef|string|array|null $buux = null;

    /**
     * @var string|string[]|ObjDef|null
     */
    private ObjDef|string|array|null $boic = null;

    private NumericKeysObj|string|null $poox = null;

    private array|object|null $arrObjUnion = null;

    private array|object|null $objArrUnion = null;

    private ?MyClassNumKeysDefaults $numKeysDefaults = null;

    /**
     * @param string[]|null $thudArray
     * @param string|ObjDef|string[]|null $xyyz
     * @param string|string[]|ObjDef|null $buux
     * @param string|string[]|ObjDef|null $boic
     */
    public function __construct(string $foo, string $bar, ?MyClassBaz $baz = null, ?MyClassQuxObj $quxObj = null, ?MyClassQuxObjNest $quxObjNest = null, ?array $thudArray = null, ObjDef|string|array|null $xyyz = null, ObjDef|string|array|null $buux = null, ObjDef|string|array|null $boic = null, NumericKeysObj|string|null $poox = null, array|object|null $arrObjUnion = null, array|object|null $objArrUnion = null, ?MyClassNumKeysDefaults $numKeysDefaults = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        if ($quxObj !== null) {
            $this->quxObj = $quxObj;
            $this->_providedOptionals['quxObj'] = true;
        };
        if ($quxObjNest !== null) {
            $this->quxObjNest = $quxObjNest;
            $this->_providedOptionals['quxObjNest'] = true;
        };
        if ($thudArray !== null) {
            $this->thudArray = $thudArray;
            $this->_providedOptionals['thudArray'] = true;
        };
        $this->xyyz = $xyyz;
        $this->buux = $buux;
        $this->boic = $boic;
        $this->poox = $poox;
        $this->arrObjUnion = $arrObjUnion;
        $this->objArrUnion = $objArrUnion;
        $this->numKeysDefaults = $numKeysDefaults;
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

    public function getFoo(): string
    {
        return $this->foo;
    }

    public function withFoo(string $foo): self
    {
        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    public function getBar(): string
    {
        return $this->bar;
    }

    public function withBar(string $bar): self
    {
        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    public function getBaz(): ?MyClassBaz
    {
        return $this->baz ?? null;
    }

    public function withBaz(MyClassBaz $baz): self
    {
        $clone = clone $this;
        $clone->baz = $baz;

        return $clone;
    }

    public function withoutBaz(): self
    {
        $clone = clone $this;
        unset($clone->baz);

        return $clone;
    }

    /**
     * optional nullable object with default value that is empty object
     */
    public function getQuxObj(): ?MyClassQuxObj
    {
        return $this->quxObj ?? null;
    }

    /**
     * optional nullable object with default value that is empty object
     */
    public function withQuxObj(?MyClassQuxObj $quxObj): self
    {
        $clone = clone $this;
        $clone->quxObj = $quxObj;
        $clone->_providedOptionals['quxObj'] = true;

        return $clone;
    }

    public function withoutQuxObj(): self
    {
        $clone = clone $this;
        unset($clone->quxObj);
        unset($clone->_providedOptionals['quxObj']);

        return $clone;
    }

    /**
     * optional nullable object with default empty object value, and with nested default for its property
     */
    public function getQuxObjNest(): ?MyClassQuxObjNest
    {
        return $this->quxObjNest ?? null;
    }

    /**
     * optional nullable object with default empty object value, and with nested default for its property
     */
    public function withQuxObjNest(?MyClassQuxObjNest $quxObjNest): self
    {
        $clone = clone $this;
        $clone->quxObjNest = $quxObjNest;
        $clone->_providedOptionals['quxObjNest'] = true;

        return $clone;
    }

    public function withoutQuxObjNest(): self
    {
        $clone = clone $this;
        unset($clone->quxObjNest);
        unset($clone->_providedOptionals['quxObjNest']);

        return $clone;
    }

    /**
     * optional nullable array with default value that is empty array
     *
     * @return string[]|null
     */
    public function getThudArray(): ?array
    {
        return $this->thudArray ?? null;
    }

    /**
     * optional nullable array with default value that is empty array
     *
     * @param string[]|null $thudArray
     */
    public function withThudArray(?array $thudArray, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($thudArray, self::$_schema['properties']['thudArray']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->thudArray = $thudArray;
        $clone->_providedOptionals['thudArray'] = true;

        return $clone;
    }

    public function withoutThudArray(): self
    {
        $clone = clone $this;
        unset($clone->thudArray);
        unset($clone->_providedOptionals['thudArray']);

        return $clone;
    }

    /**
     * @return string|ObjDef|string[]|null
     */
    public function getXyyz(): ObjDef|string|array|null
    {
        return $this->xyyz ?? null;
    }

    /**
     * @param string|ObjDef|string[] $xyyz
     */
    public function withXyyz(ObjDef|string|array $xyyz, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->xyyz = $xyyz;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutXyyz(): self
    {
        $clone = clone $this;
        unset($clone->xyyz);

        return $clone;
    }

    /**
     * @return string|string[]|ObjDef|null
     */
    public function getBuux(): ObjDef|string|array|null
    {
        return $this->buux ?? null;
    }

    /**
     * @param string|string[]|ObjDef $buux
     */
    public function withBuux(ObjDef|string|array $buux, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->buux = $buux;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutBuux(): self
    {
        $clone = clone $this;
        unset($clone->buux);

        return $clone;
    }

    /**
     * @return string|string[]|ObjDef|null
     */
    public function getBoic(): ObjDef|string|array|null
    {
        return $this->boic ?? null;
    }

    /**
     * @param string|string[]|ObjDef $boic
     */
    public function withBoic(ObjDef|string|array $boic, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->boic = $boic;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutBoic(): self
    {
        $clone = clone $this;
        unset($clone->boic);

        return $clone;
    }

    public function getPoox(): NumericKeysObj|string|null
    {
        return $this->poox ?? null;
    }

    public function withPoox(NumericKeysObj|string $poox): self
    {
        $clone = clone $this;
        $clone->poox = $poox;

        return $clone;
    }

    public function withoutPoox(): self
    {
        $clone = clone $this;
        unset($clone->poox);

        return $clone;
    }

    public function getArrObjUnion(): array|object|null
    {
        return $this->arrObjUnion ?? null;
    }

    public function withArrObjUnion(array|object $arrObjUnion): self
    {
        $clone = clone $this;
        $clone->arrObjUnion = $arrObjUnion;

        return $clone;
    }

    public function withoutArrObjUnion(): self
    {
        $clone = clone $this;
        unset($clone->arrObjUnion);

        return $clone;
    }

    public function getObjArrUnion(): array|object|null
    {
        return $this->objArrUnion ?? null;
    }

    public function withObjArrUnion(array|object $objArrUnion): self
    {
        $clone = clone $this;
        $clone->objArrUnion = $objArrUnion;

        return $clone;
    }

    public function withoutObjArrUnion(): self
    {
        $clone = clone $this;
        unset($clone->objArrUnion);

        return $clone;
    }

    public function getNumKeysDefaults(): ?MyClassNumKeysDefaults
    {
        return $this->numKeysDefaults ?? null;
    }

    public function withNumKeysDefaults(MyClassNumKeysDefaults $numKeysDefaults): self
    {
        $clone = clone $this;
        $clone->numKeysDefaults = $numKeysDefaults;

        return $clone;
    }

    public function withoutNumKeysDefaults(): self
    {
        $clone = clone $this;
        unset($clone->numKeysDefaults);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @param bool $materializeDefaults Apply defaults defined in schema when missing
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true, bool $materializeDefaults = false): MyClass
    {
        $input = is_array($input)
            ? \JsonSchema\Validator::arrayToObjectRecursive($input)
            : ($materializeDefaults ? clone $input : $input);

        if ($materializeDefaults) {
            foreach (self::$_defaults as $__k => $__v) {
                if (!property_exists($input, (string) $__k)) {
                    $input->{$__k} = ($__v['type'] ?? null) === 'object'
                        ? \JsonSchema\Validator::arrayToObjectRecursive($__v['default'])
                        : $__v['default'];
                }
            }
        }

        if ($validate) {
            static::validateInput($input);
        }

        $_providedOptionals = [];
        $foo = $input->{'foo'};
        $bar = $input->{'bar'};
        $baz = isset($input->{'baz'})
            ? MyClassBaz::fromInput($input->{'baz'}, $validate, $materializeDefaults)
            : null;
        $quxObj = null;
        if (property_exists($input, 'quxObj')) {
            $quxObj = ($input->{'quxObj'} !== null
                ? MyClassQuxObj::fromInput($input->{'quxObj'}, $validate, $materializeDefaults)
                : null
            );
            $_providedOptionals['quxObj'] = true;
        }
        $quxObjNest = null;
        if (property_exists($input, 'quxObjNest')) {
            $quxObjNest = ($input->{'quxObjNest'} !== null
                ? MyClassQuxObjNest::fromInput($input->{'quxObjNest'}, $validate, $materializeDefaults)
                : null
            );
            $_providedOptionals['quxObjNest'] = true;
        }
        $thudArray = null;
        if (property_exists($input, 'thudArray')) {
            $thudArray = ($input->{'thudArray'} !== null ? $input->{'thudArray'} : null);
            $_providedOptionals['thudArray'] = true;
        }
        $xyyz = isset($input->{'xyyz'})
            ? match (true) {
                (is_object($input->{'xyyz'}) && ObjDef::validateInput($input->{'xyyz'}, true)) =>
                    ObjDef::fromInput($input->{'xyyz'}, $validate, $materializeDefaults),
                default => $input->{'xyyz'},
            }
            : null;
        $buux = isset($input->{'buux'})
            ? match (true) {
                (is_object($input->{'buux'}) && ObjDef::validateInput($input->{'buux'}, true)) =>
                    ObjDef::fromInput($input->{'buux'}, $validate, $materializeDefaults),
                default => $input->{'buux'},
            }
            : null;
        $boic = isset($input->{'boic'})
            ? match (true) {
                (is_object($input->{'boic'}) && ObjDef::validateInput($input->{'boic'}, true)) =>
                    ObjDef::fromInput($input->{'boic'}, $validate, $materializeDefaults),
                default => $input->{'boic'},
            }
            : null;
        $poox = isset($input->{'poox'})
            ? match (true) {
                ((is_object($input->{'poox'}) || is_array($input->{'poox'})) && NumericKeysObj::validateInput($input->{'poox'}, true)) =>
                    NumericKeysObj::fromInput($input->{'poox'}, $validate, $materializeDefaults),
                default => $input->{'poox'},
            }
            : null;
        $arrObjUnion = isset($input->{'arrObjUnion'}) ? $input->{'arrObjUnion'} : null;
        $objArrUnion = isset($input->{'objArrUnion'}) ? $input->{'objArrUnion'} : null;
        $numKeysDefaults = isset($input->{'numKeysDefaults'})
            ? MyClassNumKeysDefaults::fromInput($input->{'numKeysDefaults'}, $validate, $materializeDefaults)
            : null;

        $obj = new self(
            $foo,
            $bar,
            $baz,
            $quxObj,
            $quxObjNest,
            $thudArray,
            $xyyz,
            $buux,
            $boic,
            $poox,
            $arrObjUnion,
            $objArrUnion,
            $numKeysDefaults
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
     * @param bool $includeDefaults Add defaults for missing properties
     * @return array Converted array
     */
    public function toArray(bool $includeDefaults = false): array
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

        $output['foo'] = $this->foo;
        $output['bar'] = $this->bar;
        if (isset($this->baz)) {
            $output['baz'] = $this->baz->toArray($includeDefaults);
        }
        if (isset($this->quxObj) || array_key_exists('quxObj', $this->_providedOptionals)) {
            $output['quxObj'] = ($this->quxObj !== null ? $this->quxObj->toArray($includeDefaults) : null);
        }
        if (isset($this->quxObjNest) || array_key_exists('quxObjNest', $this->_providedOptionals)) {
            $output['quxObjNest'] = ($this->quxObjNest !== null ? $this->quxObjNest->toArray($includeDefaults) : null);
        }
        if (isset($this->thudArray) || array_key_exists('thudArray', $this->_providedOptionals)) {
            $output['thudArray'] = ($this->thudArray !== null ? $this->thudArray : null);
        }
        if (isset($this->xyyz)) {
            $output['xyyz'] = match (true) {
                is_string($this->xyyz) || is_array($this->xyyz) => $this->xyyz,
                $this->xyyz instanceof ObjDef => $this->xyyz->toArray($includeDefaults),
            };
        }
        if (isset($this->buux)) {
            $output['buux'] = match (true) {
                is_string($this->buux) || is_array($this->buux) => $this->buux,
                $this->buux instanceof ObjDef => $this->buux->toArray($includeDefaults),
            };
        }
        if (isset($this->boic)) {
            $output['boic'] = match (true) {
                is_string($this->boic) || is_array($this->boic) => $this->boic,
                $this->boic instanceof ObjDef => $this->boic->toArray($includeDefaults),
            };
        }
        if (isset($this->poox)) {
            $output['poox'] = match (true) {
                is_string($this->poox) => $this->poox,
                $this->poox instanceof NumericKeysObj => $this->poox->toArray($includeDefaults),
            };
        }
        if (isset($this->arrObjUnion)) {
            $output['arrObjUnion'] = match (true) {
                is_array($this->arrObjUnion) => $this->arrObjUnion,
                is_array($this->arrObjUnion) || is_object($this->arrObjUnion) => json_decode(json_encode($this->arrObjUnion), true),
            };
        }
        if (isset($this->objArrUnion)) {
            $output['objArrUnion'] = match (true) {
                is_array($this->objArrUnion) => $this->objArrUnion,
                is_array($this->objArrUnion) || is_object($this->objArrUnion) => json_decode(json_encode($this->objArrUnion), true),
            };
        }
        if (isset($this->numKeysDefaults)) {
            $output['numKeysDefaults'] = $this->numKeysDefaults->toArray($includeDefaults);
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!array_key_exists($k, $output)) {
                    $output[$k] = $v['default'];
                }
            }
        }

        return $output;
    }

    /**
     * Converts this object to a stdClass that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return \stdClass Converted object
     */
    public function toStdClass(bool $includeDefaults = false): \stdClass
    {
        $output = $this->_additionalProperties;

        $output->{'foo'} = $this->foo;
        $output->{'bar'} = $this->bar;
        if (isset($this->baz)) {
            $output->{'baz'} = $this->baz->toStdClass($includeDefaults);
        }
        if (isset($this->quxObj) || array_key_exists('quxObj', $this->_providedOptionals)) {
            $output->{'quxObj'} = ($this->quxObj !== null ? $this->quxObj->toStdClass($includeDefaults) : null);
        }
        if (isset($this->quxObjNest) || array_key_exists('quxObjNest', $this->_providedOptionals)) {
            $output->{'quxObjNest'} = ($this->quxObjNest !== null ? $this->quxObjNest->toStdClass($includeDefaults) : null);
        }
        if (isset($this->thudArray) || array_key_exists('thudArray', $this->_providedOptionals)) {
            $output->{'thudArray'} = ($this->thudArray !== null ? $this->thudArray : null);
        }
        if (isset($this->xyyz)) {
            $output->{'xyyz'} = match (true) {
                is_string($this->xyyz) || is_array($this->xyyz) => $this->xyyz,
                $this->xyyz instanceof ObjDef => $this->xyyz->toStdClass($includeDefaults),
            };
        }
        if (isset($this->buux)) {
            $output->{'buux'} = match (true) {
                is_string($this->buux) || is_array($this->buux) => $this->buux,
                $this->buux instanceof ObjDef => $this->buux->toStdClass($includeDefaults),
            };
        }
        if (isset($this->boic)) {
            $output->{'boic'} = match (true) {
                is_string($this->boic) || is_array($this->boic) => $this->boic,
                $this->boic instanceof ObjDef => $this->boic->toStdClass($includeDefaults),
            };
        }
        if (isset($this->poox)) {
            $output->{'poox'} = match (true) {
                is_string($this->poox) => $this->poox,
                $this->poox instanceof NumericKeysObj => $this->poox->toStdClass($includeDefaults),
            };
        }
        if (isset($this->arrObjUnion)) {
            $output->{'arrObjUnion'} = match (true) {
                is_array($this->arrObjUnion) => $this->arrObjUnion,
                is_array($this->arrObjUnion) || is_object($this->arrObjUnion) => json_decode(json_encode($this->arrObjUnion)),
            };
        }
        if (isset($this->objArrUnion)) {
            $output->{'objArrUnion'} = match (true) {
                is_array($this->objArrUnion) => $this->objArrUnion,
                is_array($this->objArrUnion) || is_object($this->objArrUnion) => json_decode(json_encode($this->objArrUnion)),
            };
        }
        if (isset($this->numKeysDefaults)) {
            $output->{'numKeysDefaults'} = $this->numKeysDefaults->toStdClass($includeDefaults);
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!property_exists($output, (string) $k)) {
                    $output->{$k} = (isset($v['type']) && $v['type'] === 'object')
                       ? \JsonSchema\Validator::arrayToObjectRecursive($v['default'])
                       : $v['default'];
                }
            }
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
        if (isset($this->baz)) {
            $this->baz = clone $this->baz;
        }
        if (isset($this->quxObj)) {
            if (isset($this->quxObj)) {
                $this->quxObj = clone $this->quxObj;
            }
        }
        if (isset($this->quxObjNest)) {
            if (isset($this->quxObjNest)) {
                $this->quxObjNest = clone $this->quxObjNest;
            }
        }
        if (isset($this->xyyz)) {
            $this->xyyz = match (true) {
                is_string($this->xyyz) || is_array($this->xyyz) => $this->xyyz,
                $this->xyyz instanceof ObjDef => clone $this->xyyz,
            };
        }
        if (isset($this->buux)) {
            $this->buux = match (true) {
                is_string($this->buux) || is_array($this->buux) => $this->buux,
                $this->buux instanceof ObjDef => clone $this->buux,
            };
        }
        if (isset($this->boic)) {
            $this->boic = match (true) {
                is_string($this->boic) || is_array($this->boic) => $this->boic,
                $this->boic instanceof ObjDef => clone $this->boic,
            };
        }
        if (isset($this->poox)) {
            $this->poox = match (true) {
                is_string($this->poox) => $this->poox,
                $this->poox instanceof NumericKeysObj => clone $this->poox,
            };
        }
        if (isset($this->arrObjUnion)) {
            $this->arrObjUnion = match (true) {
                is_array($this->arrObjUnion) => $this->arrObjUnion,
                is_array($this->arrObjUnion) || is_object($this->arrObjUnion) =>
                    json_decode(json_encode($this->arrObjUnion), is_array($this->arrObjUnion)),
            };
        }
        if (isset($this->objArrUnion)) {
            $this->objArrUnion = match (true) {
                is_array($this->objArrUnion) => $this->objArrUnion,
                is_array($this->objArrUnion) || is_object($this->objArrUnion) =>
                    json_decode(json_encode($this->objArrUnion), is_array($this->objArrUnion)),
            };
        }
        if (isset($this->numKeysDefaults)) {
            $this->numKeysDefaults = clone $this->numKeysDefaults;
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
