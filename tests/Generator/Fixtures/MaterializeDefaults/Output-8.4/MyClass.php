<?php

declare(strict_types=1);

namespace Ns\MaterializeDefaults_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
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
     * Default values from the schema
     *
     * @var array
     */
    private static array $_defaults = [
        'bar' => 'some default value for foo',
        'baz' => [
            'nestedFoo' => 'some value inside default value for \'bar\' object',
        ],
        'quxObj' => [
            
        ],
        'thudArray' => [
            
        ],
        'xyyz' => [
            
        ],
        'buux' => [
            
        ],
        'boic' => 'a string',
        'poox' => [
            'a default string for \'0\'',
            'a default string for \'1\'',
            'a default string for \'2\'',
        ],
    ];

    /**
     * Map of optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private array $_providedOptionals = [];

    /**
     * @var string
     */
    private string $foo;

    /**
     * @var string
     */
    private string $bar;

    /**
     * @var MyClassBaz|null
     */
    private ?MyClassBaz $baz = null;

    /**
     * optional nullable object with default value that is empty object
     *
     * @var MyClassQuxObj|null
     */
    private ?MyClassQuxObj $quxObj = null;

    /**
     * optional nullable array with default value that is empty array
     *
     * @var string[]|null
     */
    private ?array $thudArray = null;

    /**
     * @var string|ObjDef|string[]|null
     */
    private string|ObjDef|array|null $xyyz = null;

    /**
     * @var string|string[]|ObjDef|null
     */
    private string|array|ObjDef|null $buux = null;

    /**
     * @var string|string[]|ObjDef|null
     */
    private string|array|ObjDef|null $boic = null;

    /**
     * @var string|NumericKeysObj|null
     */
    private string|NumericKeysObj|null $poox = null;

    /**
     * @param string $foo
     * @param string $bar
     */
    public function __construct(string $foo, string $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

    /**
     * @return string
     */
    public function getFoo(): string
    {
        return $this->foo;
    }

    /**
     * @return string
     */
    public function getBar(): string
    {
        return $this->bar;
    }

    /**
     * @return MyClassBaz|null
     */
    public function getBaz(): ?MyClassBaz
    {
        return $this->baz ?? null;
    }

    /**
     * optional nullable object with default value that is empty object
     *
     * @return MyClassQuxObj|null
     */
    public function getQuxObj(): ?MyClassQuxObj
    {
        return $this->quxObj ?? null;
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
     * @return string|ObjDef|string[]|null
     */
    public function getXyyz(): ObjDef|string|array|null
    {
        return $this->xyyz;
    }

    /**
     * @return string|string[]|ObjDef|null
     */
    public function getBuux(): ObjDef|string|array|null
    {
        return $this->buux;
    }

    /**
     * @return string|string[]|ObjDef|null
     */
    public function getBoic(): ObjDef|string|array|null
    {
        return $this->boic;
    }

    /**
     * @return string|NumericKeysObj|null
     */
    public function getPoox(): NumericKeysObj|string|null
    {
        return $this->poox;
    }

    /**
     * @param string $foo
     * @return self
     * @param bool $validate
     */
    public function withFoo(string $foo, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo, self::$schema['properties']['foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    /**
     * @param string $bar
     * @return self
     * @param bool $validate
     */
    public function withBar(string $bar, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($bar, self::$schema['properties']['bar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    /**
     * @param MyClassBaz $baz
     * @return self
     */
    public function withBaz(MyClassBaz $baz): self
    {
        $clone = clone $this;
        $clone->baz = $baz;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBaz(): self
    {
        $clone = clone $this;
        unset($clone->baz);

        return $clone;
    }

    /**
     * @param MyClassQuxObj $quxObj
     * @return self
     */
    public function withQuxObj(?MyClassQuxObj $quxObj): self
    {
        $clone = clone $this;
        $clone->quxObj = $quxObj;
        $clone->_providedOptionals['quxObj'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutQuxObj(): self
    {
        $clone = clone $this;
        unset($clone->quxObj);
        unset($clone->_providedOptionals['quxObj']);

        return $clone;
    }

    /**
     * @param string[] $thudArray
     * @return self
     * @param bool $validate
     */
    public function withThudArray(?array $thudArray, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($thudArray, self::$schema['properties']['thudArray']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->thudArray = $thudArray;
        $clone->_providedOptionals['thudArray'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutThudArray(): self
    {
        $clone = clone $this;
        unset($clone->thudArray);
        unset($clone->_providedOptionals['thudArray']);

        return $clone;
    }

    /**
     * @param string|ObjDef|string[] $xyyz
     * @return self
     */
    public function withXyyz(ObjDef|string|array $xyyz): self
    {
        $clone = clone $this;
        $clone->xyyz = $xyyz;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutXyyz(): self
    {
        $clone = clone $this;
        unset($clone->xyyz);

        return $clone;
    }

    /**
     * @param string|string[]|ObjDef $buux
     * @return self
     */
    public function withBuux(ObjDef|string|array $buux): self
    {
        $clone = clone $this;
        $clone->buux = $buux;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBuux(): self
    {
        $clone = clone $this;
        unset($clone->buux);

        return $clone;
    }

    /**
     * @param string|string[]|ObjDef $boic
     * @return self
     */
    public function withBoic(ObjDef|string|array $boic): self
    {
        $clone = clone $this;
        $clone->boic = $boic;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBoic(): self
    {
        $clone = clone $this;
        unset($clone->boic);

        return $clone;
    }

    /**
     * @param string|NumericKeysObj $poox
     * @return self
     */
    public function withPoox(NumericKeysObj|string $poox): self
    {
        $clone = clone $this;
        $clone->poox = $poox;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutPoox(): self
    {
        $clone = clone $this;
        unset($clone->poox);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @param bool $materializeDefaults Apply defaults defined in schema when missing
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true, bool $materializeDefaults = false): MyClass
    {
        $input = is_array($input)
            ? \JsonSchema\Validator::arrayToObjectRecursive($input)
            : ($materializeDefaults ? clone $input : $input);

        if ($materializeDefaults) {
            foreach (self::$_defaults as $__k => $__v) {
                if (!property_exists($input, $__k)) {
                    $input->{$__k} = is_array($__v) ? \JsonSchema\Validator::arrayToObjectRecursive($__v) : $__v;
                }
            }
        }

        if ($validate) {
            static::validateInput($input);
        }

        $__providedOptionals = [];
        $foo = $input->{'foo'};
        $bar = $input->{'bar'};
        $baz = isset($input->{'baz'}) ? MyClassBaz::buildFromInput($input->{'baz'}, $validate, $materializeDefaults) : null;
        $quxObj = property_exists($input, 'quxObj') ? MyClassQuxObj::buildFromInput($input->{'quxObj'}, $validate, $materializeDefaults) : null;
        if (property_exists($input, 'quxObj')) {
            $__providedOptionals['quxObj'] = true;
        }
        $thudArray = property_exists($input, 'thudArray') ? $input->{'thudArray'} : null;
        if (property_exists($input, 'thudArray')) {
            $__providedOptionals['thudArray'] = true;
        }
        $xyyz = isset($input->{'xyyz'}) ? match (true) {
            is_string($input->{'xyyz'}),
            is_array($input->{'xyyz'}) => $input->{'xyyz'},
            ObjDef::validateInput($input->{'xyyz'}, true) => ObjDef::buildFromInput($input->{'xyyz'}, $validate),
            default => null,
        } : null;
        $buux = isset($input->{'buux'}) ? match (true) {
            is_string($input->{'buux'}),
            is_array($input->{'buux'}) => $input->{'buux'},
            ObjDef::validateInput($input->{'buux'}, true) => ObjDef::buildFromInput($input->{'buux'}, $validate),
            default => null,
        } : null;
        $boic = isset($input->{'boic'}) ? match (true) {
            is_string($input->{'boic'}),
            is_array($input->{'boic'}) => $input->{'boic'},
            ObjDef::validateInput($input->{'boic'}, true) => ObjDef::buildFromInput($input->{'boic'}, $validate),
            default => null,
        } : null;
        $poox = isset($input->{'poox'}) ? match (true) {
            is_string($input->{'poox'}) => $input->{'poox'},
            NumericKeysObj::validateInput($input->{'poox'}, true) => NumericKeysObj::buildFromInput($input->{'poox'}, $validate),
            default => null,
        } : null;

        $obj = new self($foo, $bar);
        $obj->baz = $baz;
        $obj->quxObj = $quxObj;
        $obj->thudArray = $thudArray;
        $obj->xyyz = $xyyz;
        $obj->buux = $buux;
        $obj->boic = $boic;
        $obj->poox = $poox;
        $obj->_providedOptionals = $__providedOptionals;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return array Converted array
     */
    public function toArray(bool $includeDefaults = false): array
    {
        $output = [];
        $output['foo'] = $this->foo;
        $output['bar'] = $this->bar;
        if (isset($this->baz)) {
            $output['baz'] = ($this->baz)->toArray();
        }
        if (isset($this->quxObj) || array_key_exists('quxObj', $this->_providedOptionals)) {
            if (isset($this->quxObj)) {
                $output['quxObj'] = ($this->quxObj)->toArray();
            }
        }
        if (isset($this->thudArray) || array_key_exists('thudArray', $this->_providedOptionals)) {
            if (isset($this->thudArray)) {
                $output['thudArray'] = $this->thudArray;
            }
        }
        if (isset($this->xyyz)) {
            $output['xyyz'] = match (true) {
                is_string($this->xyyz),
                is_array($this->xyyz) => $this->xyyz,
                ($this->xyyz) instanceof ObjDef => $this->xyyz->toArray(),
            };
        }
        if (isset($this->buux)) {
            $output['buux'] = match (true) {
                is_string($this->buux),
                is_array($this->buux) => $this->buux,
                ($this->buux) instanceof ObjDef => $this->buux->toArray(),
            };
        }
        if (isset($this->boic)) {
            $output['boic'] = match (true) {
                is_string($this->boic),
                is_array($this->boic) => $this->boic,
                ($this->boic) instanceof ObjDef => $this->boic->toArray(),
            };
        }
        if (isset($this->poox)) {
            $output['poox'] = match (true) {
                is_string($this->poox) => $this->poox,
                ($this->poox) instanceof NumericKeysObj => $this->poox->toArray(),
            };
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!array_key_exists($k, $output)) {
                    $output[$k] = $v;
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
        $output = new \stdClass();
        $output->{'foo'} = $this->foo;
        $output->{'bar'} = $this->bar;
        if (isset($this->baz)) {
            $output->{'baz'} = ($this->baz)->toStdClass();
        }
        if (isset($this->quxObj) || array_key_exists('quxObj', $this->_providedOptionals)) {
            if (isset($this->quxObj)) {
                $output->{'quxObj'} = ($this->quxObj)->toStdClass();
            }
        }
        if (isset($this->thudArray) || array_key_exists('thudArray', $this->_providedOptionals)) {
            if (isset($this->thudArray)) {
                $output->{'thudArray'} = $this->thudArray;
            }
        }
        if (isset($this->xyyz)) {
            $output->{'xyyz'} = match (true) {
                is_string($this->xyyz),
                is_array($this->xyyz) => $this->xyyz,
                ($this->xyyz) instanceof ObjDef => $this->xyyz->toStdClass(),
            };
        }
        if (isset($this->buux)) {
            $output->{'buux'} = match (true) {
                is_string($this->buux),
                is_array($this->buux) => $this->buux,
                ($this->buux) instanceof ObjDef => $this->buux->toStdClass(),
            };
        }
        if (isset($this->boic)) {
            $output->{'boic'} = match (true) {
                is_string($this->boic),
                is_array($this->boic) => $this->boic,
                ($this->boic) instanceof ObjDef => $this->boic->toStdClass(),
            };
        }
        if (isset($this->poox)) {
            $output->{'poox'} = match (true) {
                is_string($this->poox) => $this->poox,
                ($this->poox) instanceof NumericKeysObj => $this->poox->toStdClass(),
            };
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!property_exists($output, $k)) {
                    $output->{$k} = $v;
                }
            }
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
        $validator->validate($input, self::$schema);

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
        if (isset($this->baz)) {
            $this->baz = clone $this->baz;
        }
        if (isset($this->quxObj)) {
            if (isset($this->quxObj)) {
                $this->quxObj = clone $this->quxObj;
            }
        }
        if (isset($this->xyyz)) {
            $this->xyyz = match (true) {
                is_string($this->xyyz),
                ($this->xyyz) instanceof ObjDef,
                is_array($this->xyyz) => $this->xyyz,
            };
        }
        if (isset($this->buux)) {
            $this->buux = match (true) {
                is_string($this->buux),
                is_array($this->buux),
                ($this->buux) instanceof ObjDef => $this->buux,
            };
        }
        if (isset($this->boic)) {
            $this->boic = match (true) {
                is_string($this->boic),
                is_array($this->boic),
                ($this->boic) instanceof ObjDef => $this->boic,
            };
        }
        if (isset($this->poox)) {
            $this->poox = match (true) {
                is_string($this->poox),
                ($this->poox) instanceof NumericKeysObj => $this->poox,
            };
        }
    }

    /**
     * Checks if an optional nullable property was explicitly set
     *
     * @param string $propertyName Property name to check (exactly as it appears in the schema)
     * @return bool
     */
    public function isProvidedOptional(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->_providedOptionals);
    }
}
