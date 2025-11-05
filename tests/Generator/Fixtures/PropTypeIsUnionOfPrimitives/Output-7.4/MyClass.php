<?php

declare(strict_types=1);

namespace Ns\PropTypeIsUnionOfPrimitives_7_4;

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

    /**
     * @var string|int|float|bool
     */
    private $foo;

    /**
     * @var string|int|float|bool|array
     */
    private $bar;

    /**
     * @var string|int|float|bool|array|object
     */
    private $baz;

    /**
     * @var string|int|float|bool|array|object
     */
    private $qux;

    /**
     * @var string|int|float|bool|array|object|null
     */
    private $thud;

    /**
     * @var string|int|float|bool|null
     */
    private $optFoo = null;

    /**
     * @var string|int|float|bool|array|null
     */
    private $optBar = null;

    /**
     * @var string|int|float|bool|array|object|null
     */
    private $optBaz = null;

    /**
     * @var string|int|float|bool|array|object|null
     */
    private $optQux = null;

    /**
     * @var string|int|float|bool|array|object|null
     */
    private $optThud = null;

    /**
     * @param string|int|float|bool $foo
     * @param string|int|float|bool|array $bar
     * @param string|int|float|bool|array|object $baz
     * @param string|int|float|bool|array|object $qux
     * @param string|int|float|bool|array|object|null $thud
     * @param string|int|float|bool|null $optFoo
     * @param string|int|float|bool|array|null $optBar
     * @param string|int|float|bool|array|object|null $optBaz
     * @param string|int|float|bool|array|object|null $optQux
     * @param string|int|float|bool|array|object|null $optThud
     */
    public function __construct(
        $foo,
        $bar,
        $baz,
        $qux,
        $thud,
        $optFoo = null,
        $optBar = null,
        $optBaz = null,
        $optQux = null,
        $optThud = null
    ) {
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
     * @return string|int|float|bool
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param string|int|float|bool $foo
     */
    public function withFoo($foo, bool $validate = true): self
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
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param string|int|float|bool|array $bar
     */
    public function withBar($bar, bool $validate = true): self
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
    public function getBaz()
    {
        return $this->baz;
    }

    /**
     * @param string|int|float|bool|array|object $baz
     */
    public function withBaz($baz, bool $validate = true): self
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
    public function getQux()
    {
        return $this->qux;
    }

    /**
     * @param string|int|float|bool|array|object $qux
     */
    public function withQux($qux, bool $validate = true): self
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
    public function getThud()
    {
        return $this->thud;
    }

    /**
     * @param string|int|float|bool|array|object|null $thud
     */
    public function withThud($thud, bool $validate = true): self
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
    public function getOptFoo()
    {
        return $this->optFoo ?? null;
    }

    /**
     * @param string|int|float|bool $optFoo
     */
    public function withOptFoo($optFoo, bool $validate = true): self
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

    public function withoutOptFoo(): self
    {
        $clone = clone $this;
        unset($clone->optFoo);

        return $clone;
    }

    /**
     * @return string|int|float|bool|array|null
     */
    public function getOptBar()
    {
        return $this->optBar ?? null;
    }

    /**
     * @param string|int|float|bool|array $optBar
     */
    public function withOptBar($optBar, bool $validate = true): self
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

    public function withoutOptBar(): self
    {
        $clone = clone $this;
        unset($clone->optBar);

        return $clone;
    }

    /**
     * @return string|int|float|bool|array|object|null
     */
    public function getOptBaz()
    {
        return $this->optBaz ?? null;
    }

    /**
     * @param string|int|float|bool|array|object $optBaz
     */
    public function withOptBaz($optBaz, bool $validate = true): self
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

    public function withoutOptBaz(): self
    {
        $clone = clone $this;
        unset($clone->optBaz);

        return $clone;
    }

    /**
     * @return string|int|float|bool|array|object|null
     */
    public function getOptQux()
    {
        return $this->optQux ?? null;
    }

    /**
     * @param string|int|float|bool|array|object $optQux
     */
    public function withOptQux($optQux, bool $validate = true): self
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

    public function withoutOptQux(): self
    {
        $clone = clone $this;
        unset($clone->optQux);

        return $clone;
    }

    /**
     * @return string|int|float|bool|array|object|null
     */
    public function getOptThud()
    {
        return $this->optThud ?? null;
    }

    /**
     * @param string|int|float|bool|array|object|null $optThud
     */
    public function withOptThud($optThud, bool $validate = true): self
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

        $_providedOptionals = [];
        if ((is_int($input->{'foo'}) || is_float($input->{'foo'}))) {
            $foo = (str_contains((string)$input->{'foo'}, '.')
                ? (float)$input->{'foo'}
                : (int)$input->{'foo'}
            );
        } else {
            $foo = $input->{'foo'};
        }
        if ((is_int($input->{'bar'}) || is_float($input->{'bar'}))) {
            $bar = (str_contains((string)$input->{'bar'}, '.')
                ? (float)$input->{'bar'}
                : (int)$input->{'bar'}
            );
        } else {
            $bar = $input->{'bar'};
        }
        if ((is_int($input->{'baz'}) || is_float($input->{'baz'}))) {
            $baz = (str_contains((string)$input->{'baz'}, '.')
                ? (float)$input->{'baz'}
                : (int)$input->{'baz'}
            );
        } else {
            $baz = $input->{'baz'};
        }
        if ((is_int($input->{'qux'}) || is_float($input->{'qux'}))) {
            $qux = (str_contains((string)$input->{'qux'}, '.')
                ? (float)$input->{'qux'}
                : (int)$input->{'qux'}
            );
        } else {
            $qux = $input->{'qux'};
        }
        if ((is_int($input->{'thud'}) || is_float($input->{'thud'}))) {
            $thud = (str_contains((string)$input->{'thud'}, '.')
                ? (float)$input->{'thud'}
                : (int)$input->{'thud'}
            );
        } else {
            $thud = $input->{'thud'};
        }
        $optFoo = isset($input->{'optFoo'})
            ? (((is_int($input->{'optFoo'}) || is_float($input->{'optFoo'})))
                ? (str_contains((string)$input->{'optFoo'}, '.')
                    ? (float)$input->{'optFoo'}
                    : (int)$input->{'optFoo'}
                )
                : $input->{'optFoo'}
            )
            : null;
        $optBar = isset($input->{'optBar'})
            ? (((is_int($input->{'optBar'}) || is_float($input->{'optBar'})))
                ? (str_contains((string)$input->{'optBar'}, '.')
                    ? (float)$input->{'optBar'}
                    : (int)$input->{'optBar'}
                )
                : $input->{'optBar'}
            )
            : null;
        $optBaz = isset($input->{'optBaz'})
            ? (((is_int($input->{'optBaz'}) || is_float($input->{'optBaz'})))
                ? (str_contains((string)$input->{'optBaz'}, '.')
                    ? (float)$input->{'optBaz'}
                    : (int)$input->{'optBaz'}
                )
                : $input->{'optBaz'}
            )
            : null;
        $optQux = isset($input->{'optQux'})
            ? (((is_int($input->{'optQux'}) || is_float($input->{'optQux'})))
                ? (str_contains((string)$input->{'optQux'}, '.')
                    ? (float)$input->{'optQux'}
                    : (int)$input->{'optQux'}
                )
                : $input->{'optQux'}
            )
            : null;
        $optThud = null;
        if (property_exists($input, 'optThud')) {
            $optThud = (((is_int($input->{'optThud'}) || is_float($input->{'optThud'})))
                ? (str_contains((string)$input->{'optThud'}, '.')
                    ? (float)$input->{'optThud'}
                    : (int)$input->{'optThud'}
                )
                : $input->{'optThud'}
            );
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
     * Converts this object to array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

        $output['foo'] = $this->foo;
        $output['bar'] = $this->bar;
        if (is_array($this->baz) || is_object($this->baz)) {
            $output['baz'] = json_decode(json_encode($this->baz), true);
        } else {
            $output['baz'] = $this->baz;
        }
        if (is_array($this->qux) || is_object($this->qux)) {
            $output['qux'] = json_decode(json_encode($this->qux), true);
        } else {
            $output['qux'] = $this->qux;
        }
        if (is_array($this->thud) || is_object($this->thud)) {
            $output['thud'] = json_decode(json_encode($this->thud), true);
        } else {
            $output['thud'] = $this->thud;
        }
        if (isset($this->optFoo)) {
            $output['optFoo'] = $this->optFoo;
        }
        if (isset($this->optBar)) {
            $output['optBar'] = $this->optBar;
        }
        if (isset($this->optBaz)) {
            if (is_array($this->optBaz) || is_object($this->optBaz)) {
                $output['optBaz'] = json_decode(json_encode($this->optBaz), true);
            } else {
                $output['optBaz'] = $this->optBaz;
            }
        }
        if (isset($this->optQux)) {
            if (is_array($this->optQux) || is_object($this->optQux)) {
                $output['optQux'] = json_decode(json_encode($this->optQux), true);
            } else {
                $output['optQux'] = $this->optQux;
            }
        }
        if (isset($this->optThud) || array_key_exists('optThud', $this->_providedOptionals)) {
            $output['optThud'] = ((is_array($this->optThud) || is_object($this->optThud))
                ? json_decode(json_encode($this->optThud), true)
                : $this->optThud
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

        $output->{'foo'} = $this->foo;
        $output->{'bar'} = $this->bar;
        if (is_array($this->baz) || is_object($this->baz)) {
            $output->{'baz'} = json_decode(json_encode($this->baz));
        } else {
            $output->{'baz'} = $this->baz;
        }
        if (is_array($this->qux) || is_object($this->qux)) {
            $output->{'qux'} = json_decode(json_encode($this->qux));
        } else {
            $output->{'qux'} = $this->qux;
        }
        if (is_array($this->thud) || is_object($this->thud)) {
            $output->{'thud'} = json_decode(json_encode($this->thud));
        } else {
            $output->{'thud'} = $this->thud;
        }
        if (isset($this->optFoo)) {
            $output->{'optFoo'} = $this->optFoo;
        }
        if (isset($this->optBar)) {
            $output->{'optBar'} = $this->optBar;
        }
        if (isset($this->optBaz)) {
            if (is_array($this->optBaz) || is_object($this->optBaz)) {
                $output->{'optBaz'} = json_decode(json_encode($this->optBaz));
            } else {
                $output->{'optBaz'} = $this->optBaz;
            }
        }
        if (isset($this->optQux)) {
            if (is_array($this->optQux) || is_object($this->optQux)) {
                $output->{'optQux'} = json_decode(json_encode($this->optQux));
            } else {
                $output->{'optQux'} = $this->optQux;
            }
        }
        if (isset($this->optThud) || array_key_exists('optThud', $this->_providedOptionals)) {
            $output->{'optThud'} = ((is_array($this->optThud) || is_object($this->optThud))
                ? json_decode(json_encode($this->optThud))
                : $this->optThud
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

        $this->baz = ((is_array($this->baz) || is_object($this->baz))
            ? json_decode(json_encode($this->baz), is_array($this->baz))
            : $this->baz
        );
        $this->qux = ((is_array($this->qux) || is_object($this->qux))
            ? json_decode(json_encode($this->qux), is_array($this->qux))
            : $this->qux
        );
        $this->thud = ((is_array($this->thud) || is_object($this->thud))
            ? json_decode(json_encode($this->thud), is_array($this->thud))
            : $this->thud
        );
        if (isset($this->optBaz)) {
            $this->optBaz = ((is_array($this->optBaz) || is_object($this->optBaz))
                ? json_decode(json_encode($this->optBaz), is_array($this->optBaz))
                : $this->optBaz
            );
        }
        if (isset($this->optQux)) {
            $this->optQux = ((is_array($this->optQux) || is_object($this->optQux))
                ? json_decode(json_encode($this->optQux), is_array($this->optQux))
                : $this->optQux
            );
        }
        if (isset($this->optThud)) {
            $this->optThud = ((is_array($this->optThud) || is_object($this->optThud))
                ? json_decode(json_encode($this->optThud), is_array($this->optThud))
                : $this->optThud
            );
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
