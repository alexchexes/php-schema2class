<?php

declare(strict_types=1);

namespace Ns\PropTypeIsUnionOfPrimitives_8_4;

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
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'foo' => 'foo',
        'bar' => 'bar',
        'baz' => 'baz',
        'qux' => 'qux',
        'thud' => 'thud',
        'optFoo' => 'optFoo',
        'optBar' => 'optBar',
        'optBaz' => 'optBaz',
        'optQux' => 'optQux',
        'optThud' => 'optThud',
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

    private bool|int|float|string $foo;

    private bool|int|float|string|array $bar;

    private bool|int|float|string|array|object $baz;

    private bool|int|float|string|array|object $qux;

    private bool|int|float|string|array|object|null $thud;

    private bool|int|float|string|null $optFoo = null;

    private bool|int|float|string|array|null $optBar = null;

    private bool|int|float|string|array|object|null $optBaz = null;

    private bool|int|float|string|array|object|null $optQux = null;

    private bool|int|float|string|array|object|null $optThud = null;

    public function __construct(bool|int|float|string $foo, bool|int|float|string|array $bar, bool|int|float|string|array|object $baz, bool|int|float|string|array|object $qux, bool|int|float|string|array|object|null $thud, bool|int|float|string|null $optFoo = null, bool|int|float|string|array|null $optBar = null, bool|int|float|string|array|object|null $optBaz = null, bool|int|float|string|array|object|null $optQux = null, bool|int|float|string|array|object|null $optThud = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->qux = $qux;
        $this->thud = $thud;
        $this->optFoo = $optFoo;
        $this->optBar = $optBar;
        $this->optBaz = $optBaz;
        $this->optQux = $optQux;
        if ($optThud !== null) {
            $this->optThud = $optThud;
            $this->_providedOptionals['optThud'] = true;
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

    public function getFoo(): bool|int|float|string
    {
        return $this->foo;
    }

    public function withFoo(bool|int|float|string $foo): self
    {
        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    public function getBar(): bool|int|float|string|array
    {
        return $this->bar;
    }

    public function withBar(bool|int|float|string|array $bar): self
    {
        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    public function getBaz(): bool|int|float|string|array|object
    {
        return $this->baz;
    }

    public function withBaz(bool|int|float|string|array|object $baz): self
    {
        $clone = clone $this;
        $clone->baz = $baz;

        return $clone;
    }

    public function getQux(): bool|int|float|string|array|object
    {
        return $this->qux;
    }

    public function withQux(bool|int|float|string|array|object $qux): self
    {
        $clone = clone $this;
        $clone->qux = $qux;

        return $clone;
    }

    public function getThud(): bool|int|float|string|array|object|null
    {
        return $this->thud;
    }

    public function withThud(bool|int|float|string|array|object|null $thud): self
    {
        $clone = clone $this;
        $clone->thud = $thud;

        return $clone;
    }

    public function getOptFoo(): bool|int|float|string|null
    {
        return $this->optFoo ?? null;
    }

    public function withOptFoo(bool|int|float|string $optFoo): self
    {
        $clone = clone $this;
        $clone->optFoo = $optFoo;

        return $clone;
    }

    public function withoutOptFoo(): self
    {
        $clone = clone $this;
        unset($clone->optFoo);

        return $clone;
    }

    public function getOptBar(): bool|int|float|string|array|null
    {
        return $this->optBar ?? null;
    }

    public function withOptBar(bool|int|float|string|array $optBar): self
    {
        $clone = clone $this;
        $clone->optBar = $optBar;

        return $clone;
    }

    public function withoutOptBar(): self
    {
        $clone = clone $this;
        unset($clone->optBar);

        return $clone;
    }

    public function getOptBaz(): bool|int|float|string|array|object|null
    {
        return $this->optBaz ?? null;
    }

    public function withOptBaz(bool|int|float|string|array|object $optBaz): self
    {
        $clone = clone $this;
        $clone->optBaz = $optBaz;

        return $clone;
    }

    public function withoutOptBaz(): self
    {
        $clone = clone $this;
        unset($clone->optBaz);

        return $clone;
    }

    public function getOptQux(): bool|int|float|string|array|object|null
    {
        return $this->optQux ?? null;
    }

    public function withOptQux(bool|int|float|string|array|object $optQux): self
    {
        $clone = clone $this;
        $clone->optQux = $optQux;

        return $clone;
    }

    public function withoutOptQux(): self
    {
        $clone = clone $this;
        unset($clone->optQux);

        return $clone;
    }

    public function getOptThud(): bool|int|float|string|array|object|null
    {
        return $this->optThud ?? null;
    }

    public function withOptThud(bool|int|float|string|array|object|null $optThud): self
    {
        $clone = clone $this;
        $clone->optThud = $optThud;
        $clone->_providedOptionals['optThud'] = true;

        return $clone;
    }

    public function withoutOptThud(): self
    {
        $clone = clone $this;
        unset($clone->optThud);
        unset($clone->_providedOptionals['optThud']);

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
        $optThud = null;
        if (property_exists($input, 'optThud')) {
            $optThud = ($input->{'optThud'} !== null ? match (true) {
            is_string($input->{'optThud'}),
            is_array($input->{'optThud'}),
            is_array($input->{'optThud'}) || is_object($input->{'optThud'}) => $input->{'optThud'},
            is_int($input->{'optThud'}) || is_float($input->{'optThud'}) => (str_contains((string)$input->{'optThud'}, '.') ? (float)$input->{'optThud'} : (int)$input->{'optThud'}),
            is_bool($input->{'optThud'}) => (bool)$input->{'optThud'},
            default => null,
        } : null);
            $_providedOptionals['optThud'] = true;
        }

        $obj = new self(
            $foo,
            $bar,
            $baz,
            $qux,
            $thud,
            $optFoo,
            $optBar,
            $optBaz,
            $optQux,
            $optThud
        );
        $obj->_providedOptionals = $_providedOptionals;

        $_additionalProperties = array_diff_key(get_object_vars($input), self::$_namesMap);
        if (!empty($_additionalProperties)) {
            $obj->_additionalProperties = (object) $_additionalProperties;
        }

        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

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
        $output = $this->_additionalProperties;

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
