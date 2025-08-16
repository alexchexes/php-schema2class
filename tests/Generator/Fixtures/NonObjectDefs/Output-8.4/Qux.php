<?php

declare(strict_types=1);

namespace Ns\NonObjectDefs_8_4;

class Qux
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'grox' => [
                '$ref' => '#/definitions/SkippedDef4',
            ],
        ],
        'definitions' => [
            'Foo' => [
                'type' => 'object',
                'properties' => [
                    'a' => [
                        '$ref' => '#/definitions/SkippedDef1',
                    ],
                ],
            ],
            'Bar' => [
                'properties' => [
                    'b' => [
                        '$ref' => '#/definitions/SkippedDef2',
                    ],
                ],
            ],
            'SkippedDef1' => [
                'type' => 'string',
            ],
            'SkippedDef2' => [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                ],
            ],
            'SkippedDef3' => [
                'anyOf' => [
                    [
                        '$ref' => '#/definitions/Foo',
                    ],
                    [
                        '$ref' => '#/definitions/Bar',
                    ],
                ],
            ],
            'SkippedDef4' => [
                'anyOf' => [
                    [
                        '$ref' => '#/definitions/SkippedDef1',
                    ],
                    [
                        '$ref' => '#/definitions/SkippedDef2',
                    ],
                    [
                        '$ref' => '#/definitions/SkippedDef3',
                    ],
                ],
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'grox' => 'grox',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    /**
     * @var string|string[]|Foo|Bar|null
     */
    private Bar|Foo|string|array|null $grox = null;

    /**
     * @param string|string[]|Foo|Bar|null $grox
     */
    public function __construct(Bar|Foo|string|array|null $grox = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->grox = $grox;
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

    /**
     * @return string|string[]|Foo|Bar|null
     */
    public function getGrox(): Bar|Foo|string|array|null
    {
        return $this->grox ?? null;
    }

    /**
     * @param string|string[]|Foo|Bar $grox
     */
    public function withGrox(Bar|Foo|string|array $grox, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->grox = $grox;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutGrox(): self
    {
        $clone = clone $this;
        unset($clone->grox);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Qux Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): Qux
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $grox = isset($input->{'grox'})
            ? match (true) {
                is_string($input->{'grox'}) || is_array($input->{'grox'}) => $input->{'grox'},
                (Foo::validateInput($input->{'grox'}, true) || Bar::validateInput($input->{'grox'}, true)) =>
                    match (true) {
                        Foo::validateInput($input->{'grox'}, true) => Foo::fromInput($input->{'grox'}, $validate),
                        Bar::validateInput($input->{'grox'}, true) => Bar::fromInput($input->{'grox'}, $validate),
                        default => null,
                    },
                default => null,
            }
            : null;

        $obj = new self($grox);

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

        if (isset($this->grox)) {
            $output['grox'] = match (true) {
                is_string($this->grox) || is_array($this->grox) => $this->grox,
                ($this->grox instanceof Foo || $this->grox instanceof Bar) =>
                    match (true) {
                        $this->grox instanceof Foo || $this->grox instanceof Bar => $this->grox->toArray(),
                        default => null,
                    },
            };
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

        if (isset($this->grox)) {
            $output->{'grox'} = match (true) {
                is_string($this->grox) || is_array($this->grox) => $this->grox,
                ($this->grox instanceof Foo || $this->grox instanceof Bar) =>
                    match (true) {
                        $this->grox instanceof Foo || $this->grox instanceof Bar => $this->grox->toStdClass(),
                        default => null,
                    },
            };
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
        if (isset($this->grox)) {
            $this->grox = match (true) {
                is_string($this->grox) || is_array($this->grox) => $this->grox,
                ($this->grox instanceof Foo || $this->grox instanceof Bar) =>
                    match (true) {
                        $this->grox instanceof Foo || $this->grox instanceof Bar => clone $this->grox,
                    },
            };
        }
    }
}
