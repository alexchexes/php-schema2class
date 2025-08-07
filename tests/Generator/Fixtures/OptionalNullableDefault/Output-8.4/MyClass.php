<?php

declare(strict_types=1);

namespace Ns\OptionalNullableDefault_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'foo' => [
                'type' => 'string',
                'description' => 'required, not nullable, no default',
            ],
            'bar' => [
                'type' => 'string',
                'description' => 'optional, not nullable, no default',
            ],
            'baz' => [
                'type' => [
                    'string',
                    'null',
                ],
                'description' => 'optional, nullable, no default',
            ],
            'qux' => [
                'type' => [
                    'string',
                    'null',
                ],
                'default' => 'a qux string',
                'description' => 'optional, nullable, with default',
            ],
            'quux' => [
                'type' => [
                    'string',
                    'null',
                ],
                'default' => 'a quux string',
                'description' => 'required, nullable, with default',
            ],
            'xyyz' => [
                'type' => 'string',
                'default' => 'a xyyz string',
                'description' => 'optional, not nullable, with default',
            ],
            'thud' => [
                'type' => 'string',
                'default' => 'a thud string',
                'description' => 'required, not nullable, with default',
            ],
            'grox' => [
                'type' => [
                    'object',
                    'null',
                ],
                'description' => 'optional, nullable, with default, object',
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                    'b' => [
                        'type' => 'number',
                    ],
                ],
                'default' => [
                    'a' => 'a string',
                    'b' => 123,
                ],
            ],
            'gooks' => [
                'type' => [
                    'object',
                    'null',
                ],
                'description' => 'optional, nullable, with default, object, and default is empty object',
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                    'b' => [
                        'type' => 'number',
                    ],
                ],
                'default' => [
                    
                ],
            ],
        ],
        'required' => [
            'foo',
            'quux',
            'thud',
        ],
    ];

    /**
     * Default values from the schema
     *
     * @var array
     */
    private static array $_defaults = [
        'qux' => [
            'default' => 'a qux string',
        ],
        'quux' => [
            'default' => 'a quux string',
        ],
        'xyyz' => [
            'default' => 'a xyyz string',
        ],
        'thud' => [
            'default' => 'a thud string',
        ],
        'grox' => [
            'default' => [
                'a' => 'a string',
                'b' => 123,
            ],
            'type' => 'object',
        ],
        'gooks' => [
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

    private string $foo;

    private ?string $bar = null;

    private ?string $baz = null;

    private ?string $qux = null;

    private ?string $quux;

    private ?string $xyyz = null;

    private string $thud;

    private ?MyClassGrox $grox = null;

    private ?MyClassGooks $gooks = null;

    public function __construct(string $foo, ?string $quux, string $thud, ?string $bar = null, ?string $baz = null, ?string $qux = null, ?string $xyyz = null, ?MyClassGrox $grox = null, ?MyClassGooks $gooks = null)
    {
        $this->foo = $foo;
        $this->quux = $quux;
        $this->thud = $thud;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->qux = $qux;
        $this->xyyz = $xyyz;
        $this->grox = $grox;
        $this->gooks = $gooks;
    }

    /**
     * required, not nullable, no default
     */
    public function getFoo(): string
    {
        return $this->foo;
    }

    /**
     * required, not nullable, no default
     */
    public function withFoo(string $foo): self
    {
        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    /**
     * optional, not nullable, no default
     */
    public function getBar(): ?string
    {
        return $this->bar;
    }

    /**
     * optional, not nullable, no default
     */
    public function withBar(string $bar): self
    {
        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    public function withoutBar(): self
    {
        $clone = clone $this;
        unset($clone->bar);

        return $clone;
    }

    /**
     * optional, nullable, no default
     */
    public function getBaz(): ?string
    {
        return $this->baz;
    }

    /**
     * optional, nullable, no default
     */
    public function withBaz(?string $baz): self
    {
        $clone = clone $this;
        $clone->baz = $baz;
        $clone->_providedOptionals['baz'] = true;

        return $clone;
    }

    public function withoutBaz(): self
    {
        $clone = clone $this;
        unset($clone->baz);
        unset($clone->_providedOptionals['baz']);

        return $clone;
    }

    /**
     * optional, nullable, with default
     */
    public function getQux(): ?string
    {
        return $this->qux;
    }

    /**
     * optional, nullable, with default
     */
    public function withQux(?string $qux): self
    {
        $clone = clone $this;
        $clone->qux = $qux;
        $clone->_providedOptionals['qux'] = true;

        return $clone;
    }

    public function withoutQux(): self
    {
        $clone = clone $this;
        unset($clone->qux);
        unset($clone->_providedOptionals['qux']);

        return $clone;
    }

    /**
     * required, nullable, with default
     */
    public function getQuux(): ?string
    {
        return $this->quux;
    }

    /**
     * required, nullable, with default
     */
    public function withQuux(?string $quux): self
    {
        $clone = clone $this;
        $clone->quux = $quux;

        return $clone;
    }

    /**
     * optional, not nullable, with default
     */
    public function getXyyz(): ?string
    {
        return $this->xyyz;
    }

    /**
     * optional, not nullable, with default
     */
    public function withXyyz(string $xyyz): self
    {
        $clone = clone $this;
        $clone->xyyz = $xyyz;

        return $clone;
    }

    public function withoutXyyz(): self
    {
        $clone = clone $this;
        unset($clone->xyyz);

        return $clone;
    }

    /**
     * required, not nullable, with default
     */
    public function getThud(): string
    {
        return $this->thud;
    }

    /**
     * required, not nullable, with default
     */
    public function withThud(string $thud): self
    {
        $clone = clone $this;
        $clone->thud = $thud;

        return $clone;
    }

    /**
     * optional, nullable, with default, object
     */
    public function getGrox(): ?MyClassGrox
    {
        return $this->grox;
    }

    /**
     * optional, nullable, with default, object
     */
    public function withGrox(?MyClassGrox $grox): self
    {
        $clone = clone $this;
        $clone->grox = $grox;
        $clone->_providedOptionals['grox'] = true;

        return $clone;
    }

    public function withoutGrox(): self
    {
        $clone = clone $this;
        unset($clone->grox);
        unset($clone->_providedOptionals['grox']);

        return $clone;
    }

    /**
     * optional, nullable, with default, object, and default is empty object
     */
    public function getGooks(): ?MyClassGooks
    {
        return $this->gooks;
    }

    /**
     * optional, nullable, with default, object, and default is empty object
     */
    public function withGooks(?MyClassGooks $gooks): self
    {
        $clone = clone $this;
        $clone->gooks = $gooks;
        $clone->_providedOptionals['gooks'] = true;

        return $clone;
    }

    public function withoutGooks(): self
    {
        $clone = clone $this;
        unset($clone->gooks);
        unset($clone->_providedOptionals['gooks']);

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

        $__providedOptionals = [];
        $foo = $input->{'foo'};
        $quux = $input->{'quux'};
        $thud = $input->{'thud'};
        $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;
        $baz = null;
        if (property_exists($input, 'baz')) {
            $baz = ($input->{'baz'} !== null ? $input->{'baz'} : null);
            $__providedOptionals['baz'] = true;
        }
        $qux = null;
        if (property_exists($input, 'qux')) {
            $qux = ($input->{'qux'} !== null ? $input->{'qux'} : null);
            $__providedOptionals['qux'] = true;
        }
        $xyyz = isset($input->{'xyyz'}) ? $input->{'xyyz'} : null;
        $grox = null;
        if (property_exists($input, 'grox')) {
            $grox = ($input->{'grox'} !== null ? MyClassGrox::fromInput($input->{'grox'}, $validate, $materializeDefaults) : null);
            $__providedOptionals['grox'] = true;
        }
        $gooks = null;
        if (property_exists($input, 'gooks')) {
            $gooks = ($input->{'gooks'} !== null ? MyClassGooks::fromInput($input->{'gooks'}, $validate, $materializeDefaults) : null);
            $__providedOptionals['gooks'] = true;
        }

        $obj = new self($foo, $quux, $thud, $bar, $baz, $qux, $xyyz, $grox, $gooks);
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
        if (isset($this->bar)) {
            $output['bar'] = $this->bar;
        }
        if (isset($this->baz) || array_key_exists('baz', $this->_providedOptionals)) {
            $output['baz'] = ($this->baz !== null) ? ($this->baz) : null;
        }
        if (isset($this->qux) || array_key_exists('qux', $this->_providedOptionals)) {
            $output['qux'] = ($this->qux !== null) ? ($this->qux) : null;
        }
        $output['quux'] = $this->quux;
        if (isset($this->xyyz)) {
            $output['xyyz'] = $this->xyyz;
        }
        $output['thud'] = $this->thud;
        if (isset($this->grox) || array_key_exists('grox', $this->_providedOptionals)) {
            $output['grox'] = ($this->grox !== null) ? ($this->grox->toArray($includeDefaults)) : null;
        }
        if (isset($this->gooks) || array_key_exists('gooks', $this->_providedOptionals)) {
            $output['gooks'] = ($this->gooks !== null) ? ($this->gooks->toArray($includeDefaults)) : null;
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
        $output = new \stdClass();
        $output->{'foo'} = $this->foo;
        if (isset($this->bar)) {
            $output->{'bar'} = $this->bar;
        }
        if (isset($this->baz) || array_key_exists('baz', $this->_providedOptionals)) {
            $output->{'baz'} = ($this->baz !== null) ? ($this->baz) : null;
        }
        if (isset($this->qux) || array_key_exists('qux', $this->_providedOptionals)) {
            $output->{'qux'} = ($this->qux !== null) ? ($this->qux) : null;
        }
        $output->{'quux'} = $this->quux;
        if (isset($this->xyyz)) {
            $output->{'xyyz'} = $this->xyyz;
        }
        $output->{'thud'} = $this->thud;
        if (isset($this->grox) || array_key_exists('grox', $this->_providedOptionals)) {
            $output->{'grox'} = ($this->grox !== null) ? ($this->grox->toStdClass($includeDefaults)) : null;
        }
        if (isset($this->gooks) || array_key_exists('gooks', $this->_providedOptionals)) {
            $output->{'gooks'} = ($this->gooks !== null) ? ($this->gooks->toStdClass($includeDefaults)) : null;
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
            $errors = array_map(function(array $e): string {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }

    public function __clone()
    {
        if (isset($this->grox)) {
            if (isset($this->grox)) {
                $this->grox = clone $this->grox;
            }
        }
        if (isset($this->gooks)) {
            if (isset($this->gooks)) {
                $this->gooks = clone $this->gooks;
            }
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
