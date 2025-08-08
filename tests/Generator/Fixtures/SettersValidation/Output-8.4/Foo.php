<?php

declare(strict_types=1);

namespace Ns\SettersValidation_8_4;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'a' => [
                'anyOf' => [
                    [
                        'type' => 'string',
                        'enum' => [
                            'a',
                            'b',
                        ],
                    ],
                    [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                        ],
                        'minItems' => 1,
                    ],
                ],
                'description' => 'Should have validation block',
            ],
            'b' => [
                'type' => 'array',
                'description' => 'Should not have validation block',
            ],
            'c' => [
                'type' => [
                    'number',
                    'null',
                ],
                'description' => 'Should not have validation block',
            ],
            'd' => [
                '$ref' => '#/definitions/Bar',
                'description' => 'Should not have validation block due to presence of type-hint that restricts value to \'Bar\' class instances',
            ],
        ],
        'definitions' => [
            'Bar' => [
                'type' => 'object',
                'properties' => [
                    'a' => [
                        'type' => 'string',
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
     * Map of name/value pairs for properties not specified in the schema.
     */
    private object $_additionalProperties;

    /**
     * @var 'a'|'b'|string[]|null
     */
    private string|array|null $a = null;

    private ?array $b = null;

    private int|float|null $c = null;

    private ?Bar $d = null;

    /**
     * @param 'a'|'b'|string[]|null $a
     */
    public function __construct(string|array|null $a = null, ?array $b = null, int|float|null $c = null, ?Bar $d = null)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
        $this->d = $d;
    }

    /**
     * Object or array containing name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return array instead of `stdClass` object.
     */
    public function getAdditionalProperties(bool $asArray = true): array|object
    {
        return $asArray
            ? json_decode(json_encode($this->_additionalProperties), true)
            : $this->_additionalProperties;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param array|object $additionalProperties Map of property name/value pairs to add.
     */
    public function withAdditionalProperties(array|object $additionalProperties): self
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
        $clone->_additionalProperties = new \stdClass;
        return $clone;
    }

    /**
     * Should have validation block
     *
     * @return 'a'|'b'|string[]|null
     */
    public function getA(): string|array|null
    {
        return $this->a ?? null;
    }

    /**
     * Should have validation block
     *
     * @param 'a'|'b'|string[] $a
     */
    public function withA(string|array $a, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($a, self::$_schema['properties']['a']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->a = $a;

        return $clone;
    }

    public function withoutA(): self
    {
        $clone = clone $this;
        unset($clone->a);

        return $clone;
    }

    /**
     * Should not have validation block
     */
    public function getB(): ?array
    {
        return $this->b ?? null;
    }

    /**
     * Should not have validation block
     */
    public function withB(array $b): self
    {
        $clone = clone $this;
        $clone->b = $b;

        return $clone;
    }

    public function withoutB(): self
    {
        $clone = clone $this;
        unset($clone->b);

        return $clone;
    }

    /**
     * Should not have validation block
     */
    public function getC(): int|float|null
    {
        return $this->c ?? null;
    }

    /**
     * Should not have validation block
     */
    public function withC(int|float|null $c): self
    {
        $clone = clone $this;
        $clone->c = $c;
        $clone->_providedOptionals['c'] = true;

        return $clone;
    }

    public function withoutC(): self
    {
        $clone = clone $this;
        unset($clone->c);
        unset($clone->_providedOptionals['c']);

        return $clone;
    }

    /**
     * Should not have validation block due to presence of type-hint that restricts value to 'Bar' class instances
     */
    public function getD(): ?Bar
    {
        return $this->d ?? null;
    }

    /**
     * Should not have validation block due to presence of type-hint that restricts value to 'Bar' class instances
     */
    public function withD(Bar $d): self
    {
        $clone = clone $this;
        $clone->d = $d;

        return $clone;
    }

    public function withoutD(): self
    {
        $clone = clone $this;
        unset($clone->d);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Foo Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): Foo
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $__providedOptionals = [];
        $a = isset($input->{'a'}) ? match (true) {
            in_array($input->{'a'}, array (
          0 => 'a',
          1 => 'b',
        ), true),
            is_array($input->{'a'}) => $input->{'a'},
            default => null,
        } : null;
        $b = isset($input->{'b'}) ? $input->{'b'} : null;
        $c = null;
        if (property_exists($input, 'c')) {
            $c = ($input->{'c'} !== null ? $input->{'c'} : null);
            $__providedOptionals['c'] = true;
        }
        $d = isset($input->{'d'}) ? Bar::fromInput($input->{'d'}, $validate) : null;

        $obj = new self($a, $b, $c, $d);
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
        if (isset($this->a)) {
            $output['a'] = match (true) {
                in_array($this->a, array (
              0 => 'a',
              1 => 'b',
            ), true),
                is_array($this->a) => $this->a,
            };
        }
        if (isset($this->b)) {
            $output['b'] = $this->b;
        }
        if (isset($this->c) || array_key_exists('c', $this->_providedOptionals)) {
            $output['c'] = ($this->c !== null) ? ($this->c) : null;
        }
        if (isset($this->d)) {
            $output['d'] = $this->d->toArray();
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
        if (isset($this->a)) {
            $output->{'a'} = match (true) {
                in_array($this->a, array (
              0 => 'a',
              1 => 'b',
            ), true),
                is_array($this->a) => $this->a,
            };
        }
        if (isset($this->b)) {
            $output->{'b'} = $this->b;
        }
        if (isset($this->c) || array_key_exists('c', $this->_providedOptionals)) {
            $output->{'c'} = ($this->c !== null) ? ($this->c) : null;
        }
        if (isset($this->d)) {
            $output->{'d'} = $this->d->toStdClass();
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
        if (isset($this->a)) {
            $this->a = match (true) {
                in_array($this->a, array (
              0 => 'a',
              1 => 'b',
            ), true),
                is_array($this->a) => $this->a,
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
