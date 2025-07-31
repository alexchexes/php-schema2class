<?php

declare(strict_types=1);

namespace Ns\ArrayWithoutItems_8_4;

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
            'a',
            'b',
            'c',
            'd',
        ],
        'properties' => [
            'a' => [
                'type' => 'array',
            ],
            'b' => [
                'type' => [
                    'array',
                    'string',
                ],
            ],
            'c' => [
                'type' => [
                    'array',
                    'null',
                ],
            ],
            'd' => [
                'type' => [
                    'array',
                    'string',
                    'null',
                ],
            ],
            'e' => [
                'type' => 'array',
            ],
            'f' => [
                'type' => [
                    'array',
                    'string',
                ],
            ],
            'g' => [
                'type' => [
                    'array',
                    'null',
                ],
            ],
            'h' => [
                'type' => [
                    'array',
                    'string',
                    'null',
                ],
            ],
            'i' => [
                'anyOf' => [
                    [
                        'type' => 'array',
                    ],
                    [
                        'type' => 'string',
                    ],
                    [
                        'type' => 'object',
                    ],
                    [
                        'type' => 'null',
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
     * @var array
     */
    private array $a;

    /**
     * @var array|string
     */
    private array|string $b;

    /**
     * @var array|null
     */
    private ?array $c;

    /**
     * @var array|string|null
     */
    private array|string|null $d;

    /**
     * @var array|null
     */
    private ?array $e = null;

    /**
     * @var array|string|null
     */
    private array|string|null $f = null;

    /**
     * @var array|null
     */
    private ?array $g = null;

    /**
     * @var array|string|null
     */
    private array|string|null $h = null;

    /**
     * @var array|string|object|null
     */
    private array|string|object|null $i = null;

    /**
     * @param array $a
     * @param array|string $b
     * @param array|null $c
     * @param array|string|null $d
     */
    public function __construct(array $a, string|array $b, ?array $c, string|array|null $d)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
        $this->d = $d;
    }

    /**
     * @return array
     */
    public function getA(): array
    {
        return $this->a;
    }

    /**
     * @return array|string
     */
    public function getB(): string|array
    {
        return $this->b;
    }

    /**
     * @return array|null
     */
    public function getC(): ?array
    {
        return $this->c ?? null;
    }

    /**
     * @return array|string|null
     */
    public function getD(): string|array|null
    {
        return $this->d;
    }

    /**
     * @return array|null
     */
    public function getE(): ?array
    {
        return $this->e ?? null;
    }

    /**
     * @return array|string|null
     */
    public function getF(): string|array|null
    {
        return $this->f;
    }

    /**
     * @return array|null
     */
    public function getG(): ?array
    {
        return $this->g ?? null;
    }

    /**
     * @return array|string|null
     */
    public function getH(): string|array|null
    {
        return $this->h;
    }

    /**
     * @return array|string|object|null
     */
    public function getI(): string|array|object|null
    {
        return $this->i;
    }

    /**
     * @param array $a
     * @return self
     * @param bool $validate
     */
    public function withA(array $a, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($a, self::$schema['properties']['a']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->a = $a;

        return $clone;
    }

    /**
     * @param array|string $b
     * @return self
     */
    public function withB(string|array $b): self
    {
        $clone = clone $this;
        $clone->b = $b;

        return $clone;
    }

    /**
     * @param array $c
     * @return self
     * @param bool $validate
     */
    public function withC(?array $c, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($c, self::$schema['properties']['c']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->c = $c;

        return $clone;
    }

    /**
     * @param array|string $d
     * @return self
     */
    public function withD(string|array|null $d): self
    {
        $clone = clone $this;
        $clone->d = $d;

        return $clone;
    }

    /**
     * @param array $e
     * @return self
     * @param bool $validate
     */
    public function withE(array $e, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($e, self::$schema['properties']['e']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->e = $e;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutE(): self
    {
        $clone = clone $this;
        unset($clone->e);

        return $clone;
    }

    /**
     * @param array|string $f
     * @return self
     */
    public function withF(string|array $f): self
    {
        $clone = clone $this;
        $clone->f = $f;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutF(): self
    {
        $clone = clone $this;
        unset($clone->f);

        return $clone;
    }

    /**
     * @param array $g
     * @return self
     * @param bool $validate
     */
    public function withG(?array $g, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($g, self::$schema['properties']['g']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->g = $g;
        $clone->_providedOptionals['g'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutG(): self
    {
        $clone = clone $this;
        unset($clone->g);
        unset($clone->_providedOptionals['g']);

        return $clone;
    }

    /**
     * @param array|string $h
     * @return self
     */
    public function withH(string|array $h): self
    {
        $clone = clone $this;
        $clone->h = $h;
        $clone->_providedOptionals['h'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutH(): self
    {
        $clone = clone $this;
        unset($clone->h);
        unset($clone->_providedOptionals['h']);

        return $clone;
    }

    /**
     * @param array|string|object $i
     * @return self
     */
    public function withI(string|array|object $i): self
    {
        $clone = clone $this;
        $clone->i = $i;
        $clone->_providedOptionals['i'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutI(): self
    {
        $clone = clone $this;
        unset($clone->i);
        unset($clone->_providedOptionals['i']);

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
    public static function buildFromInput(array|object $input, bool $validate = true): MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $__providedOptionals = [];
        $a = $input->{'a'};
        $b = match (true) {
            is_array($input->{'b'}),
            is_string($input->{'b'}) => $input->{'b'},
            default => throw new \InvalidArgumentException("could not build property 'b' from JSON"),
        };
        $c = ($input->{'c'} !== null ? $input->{'c'} : null);
        $d = ($input->{'d'} !== null ? match (true) {
            is_array($input->{'d'}),
            is_string($input->{'d'}) => $input->{'d'},
            default => null,
        } : null);
        $e = isset($input->{'e'}) ? $input->{'e'} : null;
        $f = isset($input->{'f'}) ? match (true) {
            is_array($input->{'f'}),
            is_string($input->{'f'}) => $input->{'f'},
            default => null,
        } : null;
        $g = property_exists($input, 'g') ? ($input->{'g'} !== null ? $input->{'g'} : null) : null;
        if (property_exists($input, 'g')) {
            $__providedOptionals['g'] = true;
        }
        $h = property_exists($input, 'h') ? ($input->{'h'} !== null ? match (true) {
            is_array($input->{'h'}),
            is_string($input->{'h'}) => $input->{'h'},
            default => null,
        } : null) : null;
        if (property_exists($input, 'h')) {
            $__providedOptionals['h'] = true;
        }
        $i = property_exists($input, 'i') ? ($input->{'i'} !== null ? match (true) {
            is_array($input->{'i'}),
            is_string($input->{'i'}),
            is_array($input->{'i'}) || is_object($input->{'i'}) => $input->{'i'},
            default => null,
        } : null) : null;
        if (property_exists($input, 'i')) {
            $__providedOptionals['i'] = true;
        }

        $obj = new self($a, $b, $c, $d);
        $obj->e = $e;
        $obj->f = $f;
        $obj->g = $g;
        $obj->h = $h;
        $obj->i = $i;
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
        $output['a'] = $this->a;
        $output['b'] = match (true) {
            is_array($this->b),
            is_string($this->b) => $this->b,
        };
        $output['c'] = $this->c;
        $output['d'] = match (true) {
            is_array($this->d),
            is_string($this->d) => $this->d,
        };
        if (isset($this->e)) {
            $output['e'] = $this->e;
        }
        if (isset($this->f)) {
            $output['f'] = match (true) {
                is_array($this->f),
                is_string($this->f) => $this->f,
            };
        }
        if (isset($this->g) || array_key_exists('g', $this->_providedOptionals)) {
            $output['g'] = ($this->g !== null) ? (($this->g !== null) ? ($this->g) : null) : null;
        }
        if (isset($this->h) || array_key_exists('h', $this->_providedOptionals)) {
            $output['h'] = ($this->h !== null) ? (match (true) {
                default => null,
                is_array($this->h),
                is_string($this->h) => $this->h,
            }) : null;
        }
        if (isset($this->i) || array_key_exists('i', $this->_providedOptionals)) {
            $output['i'] = ($this->i !== null) ? (match (true) {
                default => null,
                is_array($this->i),
                is_string($this->i) => $this->i,
                is_array($this->i) || is_object($this->i) => json_decode(json_encode($this->i), true),
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
        $output->{'a'} = $this->a;
        $output->{'b'} = match (true) {
            is_array($this->b),
            is_string($this->b) => $this->b,
        };
        $output->{'c'} = $this->c;
        $output->{'d'} = match (true) {
            is_array($this->d),
            is_string($this->d) => $this->d,
        };
        if (isset($this->e)) {
            $output->{'e'} = $this->e;
        }
        if (isset($this->f)) {
            $output->{'f'} = match (true) {
                is_array($this->f),
                is_string($this->f) => $this->f,
            };
        }
        if (isset($this->g) || array_key_exists('g', $this->_providedOptionals)) {
            $output->{'g'} = ($this->g !== null) ? (($this->g !== null) ? ($this->g) : null) : null;
        }
        if (isset($this->h) || array_key_exists('h', $this->_providedOptionals)) {
            $output->{'h'} = ($this->h !== null) ? (match (true) {
                default => null,
                is_array($this->h),
                is_string($this->h) => $this->h,
            }) : null;
        }
        if (isset($this->i) || array_key_exists('i', $this->_providedOptionals)) {
            $output->{'i'} = ($this->i !== null) ? (match (true) {
                default => null,
                is_array($this->i),
                is_string($this->i) => $this->i,
                is_array($this->i) || is_object($this->i) => json_decode(json_encode($this->i)),
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
        $this->b = match (true) {
            is_array($this->b),
            is_string($this->b) => $this->b,
        };
        $this->d = match (true) {
            is_array($this->d),
            is_string($this->d) => $this->d,
        };
        if (isset($this->f)) {
            $this->f = match (true) {
                is_array($this->f),
                is_string($this->f) => $this->f,
            };
        }
        if (isset($this->h)) {
            $this->h = match (true) {
                is_array($this->h),
                is_string($this->h) => $this->h,
            };
        }
        if (isset($this->i)) {
            $this->i = match (true) {
                is_array($this->i),
                is_string($this->i),
                is_array($this->i) || is_object($this->i) => $this->i,
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
