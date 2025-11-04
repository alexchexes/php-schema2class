<?php

declare(strict_types=1);

namespace Ns\ArrayWithoutItems_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
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
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'a' => 'a',
        'b' => 'b',
        'c' => 'c',
        'd' => 'd',
        'e' => 'e',
        'f' => 'f',
        'g' => 'g',
        'h' => 'h',
        'i' => 'i',
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

    private array $a;

    private string|array $b;

    private ?array $c;

    private string|array|null $d;

    private ?array $e = null;

    private string|array|null $f = null;

    private ?array $g = null;

    private string|array|null $h = null;

    private string|array|object|null $i = null;

    public function __construct(
        array $a,
        string|array $b,
        ?array $c,
        string|array|null $d,
        ?array $e = null,
        string|array|null $f = null,
        ?array $g = null,
        string|array|null $h = null,
        string|array|object|null $i = null
    ) {
        $this->_additionalProperties = new \stdClass();

        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
        $this->d = $d;
        $this->e = $e;
        $this->f = $f;
        if ($g !== null) {
            $this->g = $g;
            $this->_providedOptionals['g'] = true;
        };
        if ($h !== null) {
            $this->h = $h;
            $this->_providedOptionals['h'] = true;
        };
        if ($i !== null) {
            $this->i = $i;
            $this->_providedOptionals['i'] = true;
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

    public function getA(): array
    {
        return $this->a;
    }

    public function withA(array $a): self
    {
        $clone = clone $this;
        $clone->a = $a;

        return $clone;
    }

    public function getB(): string|array
    {
        return $this->b;
    }

    public function withB(string|array $b): self
    {
        $clone = clone $this;
        $clone->b = $b;

        return $clone;
    }

    public function getC(): ?array
    {
        return $this->c;
    }

    public function withC(?array $c): self
    {
        $clone = clone $this;
        $clone->c = $c;

        return $clone;
    }

    public function getD(): string|array|null
    {
        return $this->d;
    }

    public function withD(string|array|null $d): self
    {
        $clone = clone $this;
        $clone->d = $d;

        return $clone;
    }

    public function getE(): ?array
    {
        return $this->e ?? null;
    }

    public function withE(array $e): self
    {
        $clone = clone $this;
        $clone->e = $e;

        return $clone;
    }

    public function withoutE(): self
    {
        $clone = clone $this;
        unset($clone->e);

        return $clone;
    }

    public function getF(): string|array|null
    {
        return $this->f ?? null;
    }

    public function withF(string|array $f): self
    {
        $clone = clone $this;
        $clone->f = $f;

        return $clone;
    }

    public function withoutF(): self
    {
        $clone = clone $this;
        unset($clone->f);

        return $clone;
    }

    public function getG(): ?array
    {
        return $this->g ?? null;
    }

    public function withG(?array $g): self
    {
        $clone = clone $this;
        $clone->g = $g;
        $clone->_providedOptionals['g'] = true;

        return $clone;
    }

    public function withoutG(): self
    {
        $clone = clone $this;
        unset($clone->g);
        unset($clone->_providedOptionals['g']);

        return $clone;
    }

    public function getH(): string|array|null
    {
        return $this->h ?? null;
    }

    public function withH(string|array|null $h): self
    {
        $clone = clone $this;
        $clone->h = $h;
        $clone->_providedOptionals['h'] = true;

        return $clone;
    }

    public function withoutH(): self
    {
        $clone = clone $this;
        unset($clone->h);
        unset($clone->_providedOptionals['h']);

        return $clone;
    }

    public function getI(): string|array|object|null
    {
        return $this->i ?? null;
    }

    public function withI(string|array|object|null $i): self
    {
        $clone = clone $this;
        $clone->i = $i;
        $clone->_providedOptionals['i'] = true;

        return $clone;
    }

    public function withoutI(): self
    {
        $clone = clone $this;
        unset($clone->i);
        unset($clone->_providedOptionals['i']);

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
        $a = $input->{'a'};
        $b = $input->{'b'};
        $c = $input->{'c'};
        $d = $input->{'d'};
        $e = isset($input->{'e'}) ? $input->{'e'} : null;
        $f = isset($input->{'f'}) ? $input->{'f'} : null;
        $g = null;
        if (property_exists($input, 'g')) {
            $g = ($input->{'g'} !== null ? $input->{'g'} : null);
            $_providedOptionals['g'] = true;
        }
        $h = null;
        if (property_exists($input, 'h')) {
            $h = ($input->{'h'} !== null ? $input->{'h'} : null);
            $_providedOptionals['h'] = true;
        }
        $i = null;
        if (property_exists($input, 'i')) {
            $i = ($input->{'i'} !== null ? $input->{'i'} : null);
            $_providedOptionals['i'] = true;
        }

        $obj = new self($a, $b, $c, $d, $e, $f, $g, $h, $i);
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

        $output['a'] = $this->a;
        $output['b'] = $this->b;
        $output['c'] = $this->c;
        $output['d'] = $this->d;
        if (isset($this->e)) {
            $output['e'] = $this->e;
        }
        if (isset($this->f)) {
            $output['f'] = $this->f;
        }
        if (isset($this->g) || array_key_exists('g', $this->_providedOptionals)) {
            $output['g'] = ($this->g !== null ? $this->g : null);
        }
        if (isset($this->h) || array_key_exists('h', $this->_providedOptionals)) {
            $output['h'] = ($this->h !== null ? $this->h : null);
        }
        if (isset($this->i) || array_key_exists('i', $this->_providedOptionals)) {
            $output['i'] = ($this->i !== null
                ? match (true) {
                    is_array($this->i) || is_object($this->i) => json_decode(json_encode($this->i), true),
                    default => $this->i,
                }
                : null
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

        $output->{'a'} = $this->a;
        $output->{'b'} = $this->b;
        $output->{'c'} = $this->c;
        $output->{'d'} = $this->d;
        if (isset($this->e)) {
            $output->{'e'} = $this->e;
        }
        if (isset($this->f)) {
            $output->{'f'} = $this->f;
        }
        if (isset($this->g) || array_key_exists('g', $this->_providedOptionals)) {
            $output->{'g'} = ($this->g !== null ? $this->g : null);
        }
        if (isset($this->h) || array_key_exists('h', $this->_providedOptionals)) {
            $output->{'h'} = ($this->h !== null ? $this->h : null);
        }
        if (isset($this->i) || array_key_exists('i', $this->_providedOptionals)) {
            $output->{'i'} = ($this->i !== null
                ? match (true) {
                    is_array($this->i) || is_object($this->i) => json_decode(json_encode($this->i)),
                    default => $this->i,
                }
                : null
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
        if (isset($this->i)) {
            $this->i = match (true) {
                is_array($this->i) || is_string($this->i) => $this->i,
                is_array($this->i) || is_object($this->i) => json_decode(json_encode($this->i), is_array($this->i)),
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
