<?php

declare(strict_types=1);

namespace Ns\UnionCollapsing_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
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

    private string $foo;

    private string $bar;

    private string $baz;

    private ?string $qux;

    public function __construct(string $foo, string $bar, string $baz, ?string $qux)
    {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->qux = $qux;
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

    public function getBaz(): string
    {
        return $this->baz;
    }

    public function withBaz(string $baz, bool $validate = true): self
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

    public function getQux(): ?string
    {
        return $this->qux;
    }

    public function withQux(?string $qux, bool $validate = true): self
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
            is_string($input->{'foo'}) => $input->{'foo'},
            default => throw new \InvalidArgumentException("could not build property 'foo' from JSON"),
        };
        $bar = match (true) {
            is_string($input->{'bar'}) => $input->{'bar'},
            default => throw new \InvalidArgumentException("could not build property 'bar' from JSON"),
        };
        $baz = match (true) {
            is_string($input->{'baz'}) => $input->{'baz'},
            default => throw new \InvalidArgumentException("could not build property 'baz' from JSON"),
        };
        $qux = ($input->{'qux'} !== null ? match (true) {
            is_string($input->{'qux'}) => $input->{'qux'},
            default => null,
        } : null);

        $obj = new self($foo, $bar, $baz, $qux);
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
            is_string($this->foo) => $this->foo,
        };
        $output['bar'] = match (true) {
            is_string($this->bar) => $this->bar,
        };
        $output['baz'] = match (true) {
            is_string($this->baz) => $this->baz,
        };
        $output['qux'] = match (true) {
            is_string($this->qux) => $this->qux,
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
        $output = new \stdClass();
        $output->{'foo'} = match (true) {
            is_string($this->foo) => $this->foo,
        };
        $output->{'bar'} = match (true) {
            is_string($this->bar) => $this->bar,
        };
        $output->{'baz'} = match (true) {
            is_string($this->baz) => $this->baz,
        };
        $output->{'qux'} = match (true) {
            is_string($this->qux) => $this->qux,
        };

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
            is_string($this->foo) => $this->foo,
        };
        $this->bar = match (true) {
            is_string($this->bar) => $this->bar,
        };
        $this->baz = match (true) {
            is_string($this->baz) => $this->baz,
        };
        $this->qux = match (true) {
            is_string($this->qux) => $this->qux,
        };
    }
}
