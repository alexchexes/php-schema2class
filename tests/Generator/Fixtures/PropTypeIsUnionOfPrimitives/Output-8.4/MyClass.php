<?php

declare(strict_types=1);

namespace Ns\PropTypeIsUnionOfPrimitives_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'type' => 'object',
        'required' => [
            'foo',
            'bar',
            'baz',
            'qux',
            'thud',
        ],
        'properties' => [
            'foo' => [
                'type' => [
                    'string',
                    'number',
                    'boolean',
                ],
            ],
            'bar' => [
                'type' => [
                    'string',
                    'number',
                    'boolean',
                    'array',
                ],
            ],
            'baz' => [
                'type' => [
                    'string',
                    'number',
                    'boolean',
                    'object',
                ],
            ],
            'qux' => [
                'type' => [
                    'string',
                    'number',
                    'boolean',
                    'array',
                    'object',
                ],
            ],
            'thud' => [
                'type' => [
                    'string',
                    'number',
                    'boolean',
                    'array',
                    'object',
                    'null',
                ],
            ],
            'optFoo' => [
                'type' => [
                    'string',
                    'number',
                    'boolean',
                ],
            ],
            'optBar' => [
                'type' => [
                    'string',
                    'number',
                    'boolean',
                    'array',
                ],
            ],
            'optBaz' => [
                'type' => [
                    'string',
                    'number',
                    'boolean',
                    'object',
                ],
            ],
            'optQux' => [
                'type' => [
                    'string',
                    'number',
                    'boolean',
                    'array',
                    'object',
                ],
            ],
            'optThud' => [
                'type' => [
                    'string',
                    'number',
                    'boolean',
                    'array',
                    'object',
                    'null',
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
     * @var string|int|float|bool
     */
    private string|int|float|bool $foo;

    /**
     * @var string|int|float|bool|array
     */
    private string|int|float|bool|array $bar;

    /**
     * @var string|int|float|bool|array|object
     */
    private string|int|float|bool|array|object $baz;

    /**
     * @var string|int|float|bool|array|object
     */
    private string|int|float|bool|array|object $qux;

    /**
     * @var string|int|float|bool|array|object|null
     */
    private string|int|float|bool|array|object|null $thud;

    /**
     * @var string|int|float|bool|null
     */
    private string|int|float|bool|null $optFoo = null;

    /**
     * @var string|int|float|bool|array|null
     */
    private string|int|float|bool|array|null $optBar = null;

    /**
     * @var string|int|float|bool|array|object|null
     */
    private string|int|float|bool|array|object|null $optBaz = null;

    /**
     * @var string|int|float|bool|array|object|null
     */
    private string|int|float|bool|array|object|null $optQux = null;

    /**
     * @var string|int|float|bool|array|object|null
     */
    private string|int|float|bool|array|object|null $optThud = null;

    /**
     * @param string|int|float|bool $foo
     * @param string|int|float|bool|array $bar
     * @param string|int|float|bool|array|object $baz
     * @param string|int|float|bool|array|object $qux
     * @param string|int|float|bool|array|object|null $thud
     */
    public function __construct(bool|int|float|string $foo, bool|int|float|string|array $bar, bool|int|float|string|array|object $baz, bool|int|float|string|array|object $qux, bool|int|float|string|array|object|null $thud)
    {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->qux = $qux;
        $this->thud = $thud;
    }

    /**
     * @return string|int|float|bool
     */
    public function getFoo(): bool|int|float|string
    {
        return $this->foo;
    }

    /**
     * @param string|int|float|bool $foo
     * @return self
     * @param bool $validate
     */
    public function withFoo(bool|int|float|string $foo, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo, self::$_schema['properties']['foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    /**
     * @return string|int|float|bool|array
     */
    public function getBar(): bool|int|float|string|array
    {
        return $this->bar;
    }

    /**
     * @param string|int|float|bool|array $bar
     * @return self
     * @param bool $validate
     */
    public function withBar(bool|int|float|string|array $bar, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($bar, self::$_schema['properties']['bar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    /**
     * @return string|int|float|bool|array|object
     */
    public function getBaz(): bool|int|float|string|array|object
    {
        return $this->baz;
    }

    /**
     * @param string|int|float|bool|array|object $baz
     * @return self
     * @param bool $validate
     */
    public function withBaz(bool|int|float|string|array|object $baz, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($baz, self::$_schema['properties']['baz']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->baz = $baz;

        return $clone;
    }

    /**
     * @return string|int|float|bool|array|object
     */
    public function getQux(): bool|int|float|string|array|object
    {
        return $this->qux;
    }

    /**
     * @param string|int|float|bool|array|object $qux
     * @return self
     * @param bool $validate
     */
    public function withQux(bool|int|float|string|array|object $qux, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($qux, self::$_schema['properties']['qux']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->qux = $qux;

        return $clone;
    }

    /**
     * @return string|int|float|bool|array|object|null
     */
    public function getThud(): bool|int|float|string|array|object|null
    {
        return $this->thud;
    }

    /**
     * @param string|int|float|bool|array|object|null $thud
     * @return self
     * @param bool $validate
     */
    public function withThud(bool|int|float|string|array|object|null $thud, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($thud, self::$_schema['properties']['thud']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->thud = $thud;

        return $clone;
    }

    /**
     * @return string|int|float|bool|null
     */
    public function getOptFoo(): bool|int|float|string|null
    {
        return $this->optFoo;
    }

    /**
     * @param string|int|float|bool $optFoo
     * @return self
     * @param bool $validate
     */
    public function withOptFoo(bool|int|float|string $optFoo, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optFoo, self::$_schema['properties']['optFoo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optFoo = $optFoo;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptFoo(): self
    {
        $clone = clone $this;
        unset($clone->optFoo);

        return $clone;
    }

    /**
     * @return string|int|float|bool|array|null
     */
    public function getOptBar(): bool|int|float|string|array|null
    {
        return $this->optBar;
    }

    /**
     * @param string|int|float|bool|array $optBar
     * @return self
     * @param bool $validate
     */
    public function withOptBar(bool|int|float|string|array $optBar, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optBar, self::$_schema['properties']['optBar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optBar = $optBar;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptBar(): self
    {
        $clone = clone $this;
        unset($clone->optBar);

        return $clone;
    }

    /**
     * @return string|int|float|bool|array|object|null
     */
    public function getOptBaz(): bool|int|float|string|array|object|null
    {
        return $this->optBaz;
    }

    /**
     * @param string|int|float|bool|array|object $optBaz
     * @return self
     * @param bool $validate
     */
    public function withOptBaz(bool|int|float|string|array|object $optBaz, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optBaz, self::$_schema['properties']['optBaz']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optBaz = $optBaz;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptBaz(): self
    {
        $clone = clone $this;
        unset($clone->optBaz);

        return $clone;
    }

    /**
     * @return string|int|float|bool|array|object|null
     */
    public function getOptQux(): bool|int|float|string|array|object|null
    {
        return $this->optQux;
    }

    /**
     * @param string|int|float|bool|array|object $optQux
     * @return self
     * @param bool $validate
     */
    public function withOptQux(bool|int|float|string|array|object $optQux, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optQux, self::$_schema['properties']['optQux']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optQux = $optQux;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptQux(): self
    {
        $clone = clone $this;
        unset($clone->optQux);

        return $clone;
    }

    /**
     * @return string|int|float|bool|array|object|null
     */
    public function getOptThud(): bool|int|float|string|array|object|null
    {
        return $this->optThud;
    }

    /**
     * @param string|int|float|bool|array|object|null $optThud
     * @return self
     * @param bool $validate
     */
    public function withOptThud(bool|int|float|string|array|object|null $optThud, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optThud, self::$_schema['properties']['optThud']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optThud = $optThud;
        $clone->_providedOptionals['optThud'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptThud(): self
    {
        $clone = clone $this;
        unset($clone->optThud);
        unset($clone->_providedOptionals['optThud']);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
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
        $foo = match (true) {
            is_string($input->{'foo'}),
            is_int($input->{'foo'}) || is_float($input->{'foo'}),
            is_bool($input->{'foo'}) => $input->{'foo'},
            default => throw new \InvalidArgumentException("could not build property 'foo' from JSON"),
        };
        $bar = match (true) {
            is_string($input->{'bar'}),
            is_int($input->{'bar'}) || is_float($input->{'bar'}),
            is_bool($input->{'bar'}),
            is_array($input->{'bar'}) => $input->{'bar'},
            default => throw new \InvalidArgumentException("could not build property 'bar' from JSON"),
        };
        $baz = match (true) {
            is_string($input->{'baz'}),
            is_int($input->{'baz'}) || is_float($input->{'baz'}),
            is_bool($input->{'baz'}),
            is_array($input->{'baz'}) || is_object($input->{'baz'}) => $input->{'baz'},
            default => throw new \InvalidArgumentException("could not build property 'baz' from JSON"),
        };
        $qux = match (true) {
            is_string($input->{'qux'}),
            is_int($input->{'qux'}) || is_float($input->{'qux'}),
            is_bool($input->{'qux'}),
            is_array($input->{'qux'}),
            is_array($input->{'qux'}) || is_object($input->{'qux'}) => $input->{'qux'},
            default => throw new \InvalidArgumentException("could not build property 'qux' from JSON"),
        };
        $thud = ($input->{'thud'} !== null ? match (true) {
            is_string($input->{'thud'}),
            is_array($input->{'thud'}),
            is_array($input->{'thud'}) || is_object($input->{'thud'}) => $input->{'thud'},
            is_int($input->{'thud'}) || is_float($input->{'thud'}) => (str_contains((string)$input->{'thud'}, '.') ? (float)$input->{'thud'} : (int)$input->{'thud'}),
            is_bool($input->{'thud'}) => (bool)$input->{'thud'},
            default => null,
        } : null);
        $optFoo = isset($input->{'optFoo'}) ? match (true) {
            is_string($input->{'optFoo'}) => $input->{'optFoo'},
            is_int($input->{'optFoo'}) || is_float($input->{'optFoo'}) => (str_contains((string)$input->{'optFoo'}, '.') ? (float)$input->{'optFoo'} : (int)$input->{'optFoo'}),
            is_bool($input->{'optFoo'}) => (bool)$input->{'optFoo'},
            default => null,
        } : null;
        $optBar = isset($input->{'optBar'}) ? match (true) {
            is_string($input->{'optBar'}),
            is_array($input->{'optBar'}) => $input->{'optBar'},
            is_int($input->{'optBar'}) || is_float($input->{'optBar'}) => (str_contains((string)$input->{'optBar'}, '.') ? (float)$input->{'optBar'} : (int)$input->{'optBar'}),
            is_bool($input->{'optBar'}) => (bool)$input->{'optBar'},
            default => null,
        } : null;
        $optBaz = isset($input->{'optBaz'}) ? match (true) {
            is_string($input->{'optBaz'}),
            is_array($input->{'optBaz'}) || is_object($input->{'optBaz'}) => $input->{'optBaz'},
            is_int($input->{'optBaz'}) || is_float($input->{'optBaz'}) => (str_contains((string)$input->{'optBaz'}, '.') ? (float)$input->{'optBaz'} : (int)$input->{'optBaz'}),
            is_bool($input->{'optBaz'}) => (bool)$input->{'optBaz'},
            default => null,
        } : null;
        $optQux = isset($input->{'optQux'}) ? match (true) {
            is_string($input->{'optQux'}),
            is_array($input->{'optQux'}),
            is_array($input->{'optQux'}) || is_object($input->{'optQux'}) => $input->{'optQux'},
            is_int($input->{'optQux'}) || is_float($input->{'optQux'}) => (str_contains((string)$input->{'optQux'}, '.') ? (float)$input->{'optQux'} : (int)$input->{'optQux'}),
            is_bool($input->{'optQux'}) => (bool)$input->{'optQux'},
            default => null,
        } : null;
        $optThud = property_exists($input, 'optThud') ? ($input->{'optThud'} !== null ? match (true) {
            is_string($input->{'optThud'}),
            is_array($input->{'optThud'}),
            is_array($input->{'optThud'}) || is_object($input->{'optThud'}) => $input->{'optThud'},
            is_int($input->{'optThud'}) || is_float($input->{'optThud'}) => (str_contains((string)$input->{'optThud'}, '.') ? (float)$input->{'optThud'} : (int)$input->{'optThud'}),
            is_bool($input->{'optThud'}) => (bool)$input->{'optThud'},
            default => null,
        } : null) : null;
        if (property_exists($input, 'optThud')) {
            $__providedOptionals['optThud'] = true;
        }

        $obj = new self($foo, $bar, $baz, $qux, $thud);
        $obj->optFoo = $optFoo;
        $obj->optBar = $optBar;
        $obj->optBaz = $optBaz;
        $obj->optQux = $optQux;
        $obj->optThud = $optThud;
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
        $output['foo'] = match (true) {
            is_string($this->foo),
            is_int($this->foo) || is_float($this->foo),
            is_bool($this->foo) => $this->foo,
        };
        $output['bar'] = match (true) {
            is_string($this->bar),
            is_int($this->bar) || is_float($this->bar),
            is_bool($this->bar),
            is_array($this->bar) => $this->bar,
        };
        $output['baz'] = match (true) {
            is_string($this->baz),
            is_int($this->baz) || is_float($this->baz),
            is_bool($this->baz) => $this->baz,
            is_array($this->baz) || is_object($this->baz) => json_decode(json_encode($this->baz), true),
        };
        $output['qux'] = match (true) {
            is_string($this->qux),
            is_int($this->qux) || is_float($this->qux),
            is_bool($this->qux),
            is_array($this->qux) => $this->qux,
            is_array($this->qux) || is_object($this->qux) => json_decode(json_encode($this->qux), true),
        };
        $output['thud'] = match (true) {
            is_string($this->thud),
            is_int($this->thud) || is_float($this->thud),
            is_bool($this->thud),
            is_array($this->thud) => $this->thud,
            is_array($this->thud) || is_object($this->thud) => json_decode(json_encode($this->thud), true),
        };
        if (isset($this->optFoo)) {
            $output['optFoo'] = match (true) {
                is_string($this->optFoo),
                is_int($this->optFoo) || is_float($this->optFoo),
                is_bool($this->optFoo) => $this->optFoo,
            };
        }
        if (isset($this->optBar)) {
            $output['optBar'] = match (true) {
                is_string($this->optBar),
                is_int($this->optBar) || is_float($this->optBar),
                is_bool($this->optBar),
                is_array($this->optBar) => $this->optBar,
            };
        }
        if (isset($this->optBaz)) {
            $output['optBaz'] = match (true) {
                is_string($this->optBaz),
                is_int($this->optBaz) || is_float($this->optBaz),
                is_bool($this->optBaz) => $this->optBaz,
                is_array($this->optBaz) || is_object($this->optBaz) => json_decode(json_encode($this->optBaz), true),
            };
        }
        if (isset($this->optQux)) {
            $output['optQux'] = match (true) {
                is_string($this->optQux),
                is_int($this->optQux) || is_float($this->optQux),
                is_bool($this->optQux),
                is_array($this->optQux) => $this->optQux,
                is_array($this->optQux) || is_object($this->optQux) => json_decode(json_encode($this->optQux), true),
            };
        }
        if (isset($this->optThud) || array_key_exists('optThud', $this->_providedOptionals)) {
            $output['optThud'] = ($this->optThud !== null) ? (match (true) {
                default => null,
                is_string($this->optThud),
                is_int($this->optThud) || is_float($this->optThud),
                is_bool($this->optThud),
                is_array($this->optThud) => $this->optThud,
                is_array($this->optThud) || is_object($this->optThud) => json_decode(json_encode($this->optThud), true),
            }) : null;
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
        $output->{'foo'} = match (true) {
            is_string($this->foo),
            is_int($this->foo) || is_float($this->foo),
            is_bool($this->foo) => $this->foo,
        };
        $output->{'bar'} = match (true) {
            is_string($this->bar),
            is_int($this->bar) || is_float($this->bar),
            is_bool($this->bar),
            is_array($this->bar) => $this->bar,
        };
        $output->{'baz'} = match (true) {
            is_string($this->baz),
            is_int($this->baz) || is_float($this->baz),
            is_bool($this->baz) => $this->baz,
            is_array($this->baz) || is_object($this->baz) => json_decode(json_encode($this->baz)),
        };
        $output->{'qux'} = match (true) {
            is_string($this->qux),
            is_int($this->qux) || is_float($this->qux),
            is_bool($this->qux),
            is_array($this->qux) => $this->qux,
            is_array($this->qux) || is_object($this->qux) => json_decode(json_encode($this->qux)),
        };
        $output->{'thud'} = match (true) {
            is_string($this->thud),
            is_int($this->thud) || is_float($this->thud),
            is_bool($this->thud),
            is_array($this->thud) => $this->thud,
            is_array($this->thud) || is_object($this->thud) => json_decode(json_encode($this->thud)),
        };
        if (isset($this->optFoo)) {
            $output->{'optFoo'} = match (true) {
                is_string($this->optFoo),
                is_int($this->optFoo) || is_float($this->optFoo),
                is_bool($this->optFoo) => $this->optFoo,
            };
        }
        if (isset($this->optBar)) {
            $output->{'optBar'} = match (true) {
                is_string($this->optBar),
                is_int($this->optBar) || is_float($this->optBar),
                is_bool($this->optBar),
                is_array($this->optBar) => $this->optBar,
            };
        }
        if (isset($this->optBaz)) {
            $output->{'optBaz'} = match (true) {
                is_string($this->optBaz),
                is_int($this->optBaz) || is_float($this->optBaz),
                is_bool($this->optBaz) => $this->optBaz,
                is_array($this->optBaz) || is_object($this->optBaz) => json_decode(json_encode($this->optBaz)),
            };
        }
        if (isset($this->optQux)) {
            $output->{'optQux'} = match (true) {
                is_string($this->optQux),
                is_int($this->optQux) || is_float($this->optQux),
                is_bool($this->optQux),
                is_array($this->optQux) => $this->optQux,
                is_array($this->optQux) || is_object($this->optQux) => json_decode(json_encode($this->optQux)),
            };
        }
        if (isset($this->optThud) || array_key_exists('optThud', $this->_providedOptionals)) {
            $output->{'optThud'} = ($this->optThud !== null) ? (match (true) {
                default => null,
                is_string($this->optThud),
                is_int($this->optThud) || is_float($this->optThud),
                is_bool($this->optThud),
                is_array($this->optThud) => $this->optThud,
                is_array($this->optThud) || is_object($this->optThud) => json_decode(json_encode($this->optThud)),
            }) : null;
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
        $this->foo = match (true) {
            is_string($this->foo),
            is_int($this->foo) || is_float($this->foo),
            is_bool($this->foo) => $this->foo,
        };
        $this->bar = match (true) {
            is_string($this->bar),
            is_int($this->bar) || is_float($this->bar),
            is_bool($this->bar),
            is_array($this->bar) => $this->bar,
        };
        $this->baz = match (true) {
            is_string($this->baz),
            is_int($this->baz) || is_float($this->baz),
            is_bool($this->baz),
            is_array($this->baz) || is_object($this->baz) => $this->baz,
        };
        $this->qux = match (true) {
            is_string($this->qux),
            is_int($this->qux) || is_float($this->qux),
            is_bool($this->qux),
            is_array($this->qux),
            is_array($this->qux) || is_object($this->qux) => $this->qux,
        };
        $this->thud = match (true) {
            is_string($this->thud),
            is_int($this->thud) || is_float($this->thud),
            is_bool($this->thud),
            is_array($this->thud),
            is_array($this->thud) || is_object($this->thud) => $this->thud,
        };
        if (isset($this->optFoo)) {
            $this->optFoo = match (true) {
                is_string($this->optFoo),
                is_int($this->optFoo) || is_float($this->optFoo),
                is_bool($this->optFoo) => $this->optFoo,
            };
        }
        if (isset($this->optBar)) {
            $this->optBar = match (true) {
                is_string($this->optBar),
                is_int($this->optBar) || is_float($this->optBar),
                is_bool($this->optBar),
                is_array($this->optBar) => $this->optBar,
            };
        }
        if (isset($this->optBaz)) {
            $this->optBaz = match (true) {
                is_string($this->optBaz),
                is_int($this->optBaz) || is_float($this->optBaz),
                is_bool($this->optBaz),
                is_array($this->optBaz) || is_object($this->optBaz) => $this->optBaz,
            };
        }
        if (isset($this->optQux)) {
            $this->optQux = match (true) {
                is_string($this->optQux),
                is_int($this->optQux) || is_float($this->optQux),
                is_bool($this->optQux),
                is_array($this->optQux),
                is_array($this->optQux) || is_object($this->optQux) => $this->optQux,
            };
        }
        if (isset($this->optThud)) {
            $this->optThud = match (true) {
                is_string($this->optThud),
                is_int($this->optThud) || is_float($this->optThud),
                is_bool($this->optThud),
                is_array($this->optThud),
                is_array($this->optThud) || is_object($this->optThud) => $this->optThud,
            };
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
