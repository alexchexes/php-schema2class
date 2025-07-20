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
    private static array $schema = [
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
        'foo' => 0,
        'bar' => 'xyz',
        'baz' => null,
        'qux' => 'default from the referenced definition',
        'thud' => 'more specific default near the "$ref"',
        'grox' => 'default near the "$ref"',
        'qwert' => 'default from the referenced definition',
        'zyx' => '',
    ];

    /**
     * Map of optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private array $_providedOptionals = [];

    /**
     * @var int|null
     */
    private ?int $foo = null;

    /**
     * @var string|null
     */
    private ?string $bar = null;

    /**
     * @var int|null
     */
    private ?int $baz = null;

    /**
     * Description of Def1 (string) which has default value
     *
     * @var string|null
     */
    private ?string $qux = null;

    /**
     * Description of Def1 (string) which has default value
     *
     * @var string|null
     */
    private ?string $thud = null;

    /**
     * Description of Def2 (string) which doesn't have default value
     *
     * @var string|null
     */
    private ?string $grox = null;

    /**
     * @var string|int|float|null
     */
    private string|int|float|null $qwert = null;

    /**
     * @var string|null
     */
    private ?string $zyx = null;

    /**
     * @return int|null
     */
    public function getFoo(): ?int
    {
        return $this->foo ?? null;
    }

    /**
     * @return string|null
     */
    public function getBar(): ?string
    {
        return $this->bar ?? null;
    }

    /**
     * @return int|null
     */
    public function getBaz(): ?int
    {
        return $this->baz ?? null;
    }

    /**
     * Description of Def1 (string) which has default value
     *
     * @return string|null
     */
    public function getQux(): ?string
    {
        return $this->qux ?? null;
    }

    /**
     * Description of Def1 (string) which has default value
     *
     * @return string|null
     */
    public function getThud(): ?string
    {
        return $this->thud ?? null;
    }

    /**
     * Description of Def2 (string) which doesn't have default value
     *
     * @return string|null
     */
    public function getGrox(): ?string
    {
        return $this->grox ?? null;
    }

    /**
     * @return string|int|float|null
     */
    public function getQwert(): int|float|string|null
    {
        return $this->qwert;
    }

    /**
     * @return string|null
     */
    public function getZyx(): ?string
    {
        return $this->zyx ?? null;
    }

    /**
     * @param int $foo
     * @return self
     * @param bool $validate
     */
    public function withFoo(int $foo, bool $validate = true): self
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
     * @return self
     */
    public function withoutFoo(): self
    {
        $clone = clone $this;
        unset($clone->foo);

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
     * @return self
     */
    public function withoutBar(): self
    {
        $clone = clone $this;
        unset($clone->bar);

        return $clone;
    }

    /**
     * @param int $baz
     * @return self
     * @param bool $validate
     */
    public function withBaz(int $baz, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($baz, self::$schema['properties']['baz']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->baz = $baz;
        $clone->_providedOptionals['baz'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBaz(): self
    {
        $clone = clone $this;
        unset($clone->baz);
        unset($clone->_providedOptionals['baz']);

        return $clone;
    }

    /**
     * @param string $qux
     * @return self
     * @param bool $validate
     */
    public function withQux(string $qux, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($qux, self::$schema['properties']['qux']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->qux = $qux;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutQux(): self
    {
        $clone = clone $this;
        unset($clone->qux);

        return $clone;
    }

    /**
     * @param string $thud
     * @return self
     * @param bool $validate
     */
    public function withThud(string $thud, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($thud, self::$schema['properties']['thud']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->thud = $thud;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutThud(): self
    {
        $clone = clone $this;
        unset($clone->thud);

        return $clone;
    }

    /**
     * @param string $grox
     * @return self
     * @param bool $validate
     */
    public function withGrox(string $grox, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($grox, self::$schema['properties']['grox']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->grox = $grox;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutGrox(): self
    {
        $clone = clone $this;
        unset($clone->grox);

        return $clone;
    }

    /**
     * @param string|int|float $qwert
     * @return self
     */
    public function withQwert(int|float|string $qwert): self
    {
        $clone = clone $this;
        $clone->qwert = $qwert;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutQwert(): self
    {
        $clone = clone $this;
        unset($clone->qwert);

        return $clone;
    }

    /**
     * @param string $zyx
     * @return self
     * @param bool $validate
     */
    public function withZyx(string $zyx, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($zyx, self::$schema['properties']['zyx']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->zyx = $zyx;

        return $clone;
    }

    /**
     * @return self
     */
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
        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;
        $baz = property_exists($input, 'baz') ? $input->{'baz'} : null;
        if (property_exists($input, 'baz')) {
            $__providedOptionals['baz'] = true;
        }
        $qux = isset($input->{'qux'}) ? $input->{'qux'} : null;
        $thud = isset($input->{'thud'}) ? $input->{'thud'} : null;
        $grox = isset($input->{'grox'}) ? $input->{'grox'} : null;
        $qwert = isset($input->{'qwert'}) ? match (true) {
            is_string($input->{'qwert'}) => $input->{'qwert'},
            is_int($input->{'qwert'}) || is_float($input->{'qwert'}) => str_contains((string)($input->{'qwert'}), '.') ? (float)($input->{'qwert'}) : (int)($input->{'qwert'}),
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
            $output['baz'] = $this->baz;
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
                    $output[$k] = $v;
                }
            }
        }

        return $output;
    }

    /**
     * Converts this object back to an stdClass that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return \stdClass Converted object
     */
    public function toStdClass(bool $includeDefaults = false): \stdClass
    {
        $array = $this->toArray($includeDefaults);
        return json_decode(json_encode($array));
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
    public function isProvidedOptional(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->_providedOptionals);
    }
}
