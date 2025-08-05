<?php

declare(strict_types=1);

namespace Ns\DefaultValue_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'required' => [
            
        ],
        'properties' => [
            'foo' => [
                'type' => 'integer',
                'default' => 0,
                'minimum' => 1,
            ],
            'bar' => [
                'type' => 'string',
                'default' => 'xyz',
            ],
            'baz' => [
                'type' => [
                    'integer',
                    'null',
                ],
                'default' => null,
            ],
            'qux' => [
                '$ref' => '#/definitions/Def1',
            ],
            'thud' => [
                '$ref' => '#/definitions/Def1',
                'default' => 'more specific default near the "$ref"',
            ],
            'grox' => [
                '$ref' => '#/definitions/Def2',
                'default' => 'default near the "$ref"',
            ],
            'qwert' => [
                'anyOf' => [
                    [
                        '$ref' => '#/definitions/Def2',
                    ],
                    [
                        '$ref' => '#/definitions/Def1',
                    ],
                    [
                        'type' => 'number',
                        'default' => 42,
                    ],
                ],
            ],
            'zyx' => [
                'type' => 'string',
                'default' => '',
            ],
        ],
        'definitions' => [
            'Def1' => [
                'type' => 'string',
                'description' => 'Description of Def1 (string) which has default value',
                'default' => 'default from the referenced definition',
            ],
            'Def2' => [
                'type' => 'string',
                'description' => 'Description of Def2 (string) which doesn\'t have default value',
            ],
        ],
    ];

    /**
     * Default values from the schema
     *
     * @var array
     */
    private static array $_defaults = [
        'foo' => [
            'default' => 0,
        ],
        'bar' => [
            'default' => 'xyz',
        ],
        'baz' => [
            'default' => null,
        ],
        'qux' => [
            'default' => 'default from the referenced definition',
        ],
        'thud' => [
            'default' => 'more specific default near the "$ref"',
        ],
        'grox' => [
            'default' => 'default near the "$ref"',
        ],
        'qwert' => [
            'default' => 'default from the referenced definition',
        ],
        'zyx' => [
            'default' => '',
        ],
    ];

    /**
     * Map of optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private array $_providedOptionals = [];

    private ?int $foo = null;

    private ?string $bar = null;

    private ?int $baz = null;

    private ?string $qux = null;

    private ?string $thud = null;

    private ?string $grox = null;

    private string|int|float|null $qwert = null;

    private ?string $zyx = null;

    public function getFoo(): ?int
    {
        return $this->foo ?? null;
    }

    public function withFoo(int $foo, bool $validate = true): self
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

    public function withoutFoo(): self
    {
        $clone = clone $this;
        unset($clone->foo);

        return $clone;
    }

    public function getBar(): ?string
    {
        return $this->bar ?? null;
    }

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

    public function getBaz(): ?int
    {
        return $this->baz ?? null;
    }

    public function withBaz(?int $baz): self
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
     * Description of Def1 (string) which has default value
     */
    public function getQux(): ?string
    {
        return $this->qux ?? null;
    }

    /**
     * Description of Def1 (string) which has default value
     */
    public function withQux(string $qux): self
    {
        $clone = clone $this;
        $clone->qux = $qux;

        return $clone;
    }

    public function withoutQux(): self
    {
        $clone = clone $this;
        unset($clone->qux);

        return $clone;
    }

    /**
     * Description of Def1 (string) which has default value
     */
    public function getThud(): ?string
    {
        return $this->thud ?? null;
    }

    /**
     * Description of Def1 (string) which has default value
     */
    public function withThud(string $thud): self
    {
        $clone = clone $this;
        $clone->thud = $thud;

        return $clone;
    }

    public function withoutThud(): self
    {
        $clone = clone $this;
        unset($clone->thud);

        return $clone;
    }

    /**
     * Description of Def2 (string) which doesn't have default value
     */
    public function getGrox(): ?string
    {
        return $this->grox ?? null;
    }

    /**
     * Description of Def2 (string) which doesn't have default value
     */
    public function withGrox(string $grox): self
    {
        $clone = clone $this;
        $clone->grox = $grox;

        return $clone;
    }

    public function withoutGrox(): self
    {
        $clone = clone $this;
        unset($clone->grox);

        return $clone;
    }

    public function getQwert(): int|float|string|null
    {
        return $this->qwert;
    }

    public function withQwert(int|float|string $qwert): self
    {
        $clone = clone $this;
        $clone->qwert = $qwert;

        return $clone;
    }

    public function withoutQwert(): self
    {
        $clone = clone $this;
        unset($clone->qwert);

        return $clone;
    }

    public function getZyx(): ?string
    {
        return $this->zyx ?? null;
    }

    public function withZyx(string $zyx): self
    {
        $clone = clone $this;
        $clone->zyx = $zyx;

        return $clone;
    }

    public function withoutZyx(): self
    {
        $clone = clone $this;
        unset($clone->zyx);

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
        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;
        $baz = null;
        if (property_exists($input, 'baz')) {
            $baz = ($input->{'baz'} !== null ? $input->{'baz'} : null);
            $__providedOptionals['baz'] = true;
        }
        $qux = isset($input->{'qux'}) ? $input->{'qux'} : null;
        $thud = isset($input->{'thud'}) ? $input->{'thud'} : null;
        $grox = isset($input->{'grox'}) ? $input->{'grox'} : null;
        $qwert = isset($input->{'qwert'}) ? match (true) {
            is_string($input->{'qwert'}) => $input->{'qwert'},
            is_int($input->{'qwert'}) || is_float($input->{'qwert'}) => (str_contains((string)$input->{'qwert'}, '.') ? (float)$input->{'qwert'} : (int)$input->{'qwert'}),
            default => null,
        } : null;
        $zyx = isset($input->{'zyx'}) ? $input->{'zyx'} : null;

        $obj = new self();
        $obj->foo = $foo;
        $obj->bar = $bar;
        $obj->baz = $baz;
        $obj->qux = $qux;
        $obj->thud = $thud;
        $obj->grox = $grox;
        $obj->qwert = $qwert;
        $obj->zyx = $zyx;
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
        if (isset($this->foo)) {
            $output['foo'] = $this->foo;
        }
        if (isset($this->bar)) {
            $output['bar'] = $this->bar;
        }
        if (isset($this->baz) || array_key_exists('baz', $this->_providedOptionals)) {
            $output['baz'] = ($this->baz !== null) ? ($this->baz) : null;
        }
        if (isset($this->qux)) {
            $output['qux'] = $this->qux;
        }
        if (isset($this->thud)) {
            $output['thud'] = $this->thud;
        }
        if (isset($this->grox)) {
            $output['grox'] = $this->grox;
        }
        if (isset($this->qwert)) {
            $output['qwert'] = match (true) {
                is_string($this->qwert),
                is_int($this->qwert) || is_float($this->qwert) => $this->qwert,
            };
        }
        if (isset($this->zyx)) {
            $output['zyx'] = $this->zyx;
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
        if (isset($this->foo)) {
            $output->{'foo'} = $this->foo;
        }
        if (isset($this->bar)) {
            $output->{'bar'} = $this->bar;
        }
        if (isset($this->baz) || array_key_exists('baz', $this->_providedOptionals)) {
            $output->{'baz'} = ($this->baz !== null) ? ($this->baz) : null;
        }
        if (isset($this->qux)) {
            $output->{'qux'} = $this->qux;
        }
        if (isset($this->thud)) {
            $output->{'thud'} = $this->thud;
        }
        if (isset($this->grox)) {
            $output->{'grox'} = $this->grox;
        }
        if (isset($this->qwert)) {
            $output->{'qwert'} = match (true) {
                is_string($this->qwert),
                is_int($this->qwert) || is_float($this->qwert) => $this->qwert,
            };
        }
        if (isset($this->zyx)) {
            $output->{'zyx'} = $this->zyx;
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
        if (isset($this->qwert)) {
            $this->qwert = match (true) {
                is_string($this->qwert),
                is_int($this->qwert) || is_float($this->qwert) => $this->qwert,
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
