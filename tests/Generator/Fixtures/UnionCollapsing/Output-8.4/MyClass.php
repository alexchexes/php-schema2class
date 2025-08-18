<?php

declare(strict_types=1);

namespace Ns\UnionCollapsing_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'required' => [
            'foo',
            'bar',
            'baz',
            'qux',
        ],
        'properties' => [
            'foo' => [
                'oneOf' => [
                    [
                        'type' => 'string',
                        'format' => 'uuid',
                        'description' => 'Description of \'uuid\' string',
                    ],
                    [
                        'type' => 'string',
                        'description' => 'Description of \'maxLength\' string',
                        'maxLength' => 0,
                        'deprecated' => true,
                    ],
                ],
            ],
            'bar' => [
                'oneOf' => [
                    [
                        '$ref' => '#/definitions/FooDef',
                    ],
                    [
                        '$ref' => '#/definitions/BarDef',
                    ],
                ],
            ],
            'baz' => [
                'anyOf' => [
                    [
                        '$ref' => '#/definitions/FooDef',
                    ],
                    [
                        '$ref' => '#/definitions/BarDef',
                    ],
                ],
            ],
            'qux' => [
                'oneOf' => [
                    [
                        'type' => 'string',
                        'format' => 'uuid',
                        'description' => 'Description of \'uuid\' string',
                    ],
                    [
                        'type' => [
                            'string',
                            'null',
                        ],
                        'description' => 'Description of \'maxLength\' string',
                        'maxLength' => 0,
                        'deprecated' => true,
                    ],
                    [
                        '$ref' => '#/definitions/FooDef',
                    ],
                    [
                        '$ref' => '#/definitions/BarDef',
                    ],
                ],
            ],
        ],
        'definitions' => [
            'FooDef' => [
                'type' => 'string',
                'format' => 'uuid',
                'description' => 'Description of a definition of \'uuid\' string',
            ],
            'BarDef' => [
                'type' => 'string',
                'description' => 'Description of a definition of \'maxLength\' string',
                'maxLength' => 0,
                'deprecated' => true,
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
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    private string $foo;

    private string $bar;

    private string $baz;

    private ?string $qux;

    public function __construct(string $foo, string $bar, string $baz, ?string $qux)
    {
        $this->_additionalProperties = new \stdClass();

        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->qux = $qux;
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

    public function getFoo(): string
    {
        return $this->foo;
    }

    public function withFoo(string $foo, bool $validate = true): self
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

    public function getBar(): string
    {
        return $this->bar;
    }

    public function withBar(string $bar, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->bar = $bar;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function getBaz(): string
    {
        return $this->baz;
    }

    public function withBaz(string $baz, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->baz = $baz;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function getQux(): ?string
    {
        return $this->qux;
    }

    public function withQux(?string $qux, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->qux = $qux;
        if ($validate) {
            $clone->validate();
        }
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

        $foo = match (true) {
            default => $input->{'foo'},
        };
        $bar = match (true) {
            default => $input->{'bar'},
        };
        $baz = match (true) {
            default => $input->{'baz'},
        };
        $qux = ($input->{'qux'} !== null
            ? match (true) {
                default => $input->{'qux'},
            }
            : null
        );

        $obj = new self($foo, $bar, $baz, $qux);

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

        $output['foo'] = match (true) {
            default => $this->foo,
        };
        $output['bar'] = match (true) {
            default => $this->bar,
        };
        $output['baz'] = match (true) {
            default => $this->baz,
        };
        $output['qux'] = match (true) {
            default => $this->qux,
        };

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
            default => $this->foo,
        };
        $output->{'bar'} = match (true) {
            default => $this->bar,
        };
        $output->{'baz'} = match (true) {
            default => $this->baz,
        };
        $output->{'qux'} = match (true) {
            default => $this->qux,
        };

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
}
