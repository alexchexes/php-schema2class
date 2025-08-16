<?php

declare(strict_types=1);

namespace Ns\UnionWithRepeatedType_8_4;

class Cat
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'hasFur' => [
                'anyOf' => [
                    [
                        '$ref' => '#/definitions/foo',
                    ],
                    [
                        '$ref' => '#/definitions/bar',
                    ],
                    [
                        '$ref' => '#/definitions/baz',
                    ],
                ],
            ],
        ],
        'definitions' => [
            'foo' => [
                'type' => [
                    'boolean',
                    'null',
                ],
            ],
            'bar' => [
                'type' => [
                    'string',
                    'number',
                    'null',
                ],
            ],
            'baz' => [
                'type' => [
                    'string',
                    'number',
                    'boolean',
                ],
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'hasFur' => 'hasFur',
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

    private bool|int|float|string|null $hasFur = null;

    public function __construct(bool|int|float|string|null $hasFur = null)
    {
        $this->_additionalProperties = new \stdClass();

        if ($hasFur !== null) {
            $this->hasFur = $hasFur;
            $this->_providedOptionals['hasFur'] = true;
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

    public function getHasFur(): bool|int|float|string|null
    {
        return $this->hasFur ?? null;
    }

    public function withHasFur(bool|int|float|string|null $hasFur): self
    {
        $clone = clone $this;
        $clone->hasFur = $hasFur;
        $clone->_providedOptionals['hasFur'] = true;

        return $clone;
    }

    public function withoutHasFur(): self
    {
        $clone = clone $this;
        unset($clone->hasFur);
        unset($clone->_providedOptionals['hasFur']);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Cat Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): Cat
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $_providedOptionals = [];
        $hasFur = null;
        if (property_exists($input, 'hasFur')) {
            $hasFur = ($input->{'hasFur'} !== null
                ? match (true) {
                    ($input->{'hasFur'} === null || is_bool($input->{'hasFur'})) =>
                        ($input->{'hasFur'} !== null ? (bool)$input->{'hasFur'} : null),
                    ($input->{'hasFur'} === null
                        || (is_string($input->{'hasFur'}) || (is_int($input->{'hasFur'}) || is_float($input->{'hasFur'})))
                    ) =>
                        ($input->{'hasFur'} !== null
                            ? match (true) {
                                is_string($input->{'hasFur'}) => $input->{'hasFur'},
                                (is_int($input->{'hasFur'}) || is_float($input->{'hasFur'})) =>
                                    (str_contains((string)$input->{'hasFur'}, '.')
                                        ? (float)$input->{'hasFur'}
                                        : (int)$input->{'hasFur'}
                                    ),
                                default => null,
                            }
                            : null
                        ),
                    (is_string($input->{'hasFur'})
                        || (is_int($input->{'hasFur'}) || is_float($input->{'hasFur'}))
                        || is_bool($input->{'hasFur'})
                    ) =>
                        match (true) {
                            is_string($input->{'hasFur'}) => $input->{'hasFur'},
                            (is_int($input->{'hasFur'}) || is_float($input->{'hasFur'})) =>
                                (str_contains((string)$input->{'hasFur'}, '.')
                                    ? (float)$input->{'hasFur'}
                                    : (int)$input->{'hasFur'}
                                ),
                            is_bool($input->{'hasFur'}) => (bool)$input->{'hasFur'},
                            default => null,
                        },
                    default => null,
                }
                : null
            );
            $_providedOptionals['hasFur'] = true;
        }

        $obj = new self($hasFur);
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

        if (isset($this->hasFur) || array_key_exists('hasFur', $this->_providedOptionals)) {
            $output['hasFur'] = ($this->hasFur !== null
                ? match (true) {
                    ($this->hasFur === null || is_bool($this->hasFur)) => ($this->hasFur !== null ? $this->hasFur : null),
                    ($this->hasFur === null
                        || (is_string($this->hasFur) || (is_int($this->hasFur) || is_float($this->hasFur)))
                    ) =>
                        ($this->hasFur !== null
                            ? match (true) {
                                is_string($this->hasFur) || (is_int($this->hasFur) || is_float($this->hasFur)) => $this->hasFur,
                                default => null,
                            }
                            : null
                        ),
                    (is_string($this->hasFur)
                        || (is_int($this->hasFur) || is_float($this->hasFur))
                        || is_bool($this->hasFur)
                    ) =>
                        match (true) {
                            is_string($this->hasFur)
                                || (is_int($this->hasFur) || is_float($this->hasFur))
                                || is_bool($this->hasFur) =>
                                $this->hasFur,
                            default => null,
                        },
                    default => null,
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

        if (isset($this->hasFur) || array_key_exists('hasFur', $this->_providedOptionals)) {
            $output->{'hasFur'} = ($this->hasFur !== null
                ? match (true) {
                    ($this->hasFur === null || is_bool($this->hasFur)) => ($this->hasFur !== null ? $this->hasFur : null),
                    ($this->hasFur === null
                        || (is_string($this->hasFur) || (is_int($this->hasFur) || is_float($this->hasFur)))
                    ) =>
                        ($this->hasFur !== null
                            ? match (true) {
                                is_string($this->hasFur) || (is_int($this->hasFur) || is_float($this->hasFur)) => $this->hasFur,
                                default => null,
                            }
                            : null
                        ),
                    (is_string($this->hasFur)
                        || (is_int($this->hasFur) || is_float($this->hasFur))
                        || is_bool($this->hasFur)
                    ) =>
                        match (true) {
                            is_string($this->hasFur)
                                || (is_int($this->hasFur) || is_float($this->hasFur))
                                || is_bool($this->hasFur) =>
                                $this->hasFur,
                            default => null,
                        },
                    default => null,
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
        if (isset($this->hasFur)) {
            $this->hasFur = match (true) {
                ($this->hasFur === null || is_bool($this->hasFur))
                    || ($this->hasFur === null
                        || (is_string($this->hasFur) || (is_int($this->hasFur) || is_float($this->hasFur)))
                    ) =>
                    (isset($this->hasFur) ? clone $this->hasFur : null),
                (is_string($this->hasFur)
                    || (is_int($this->hasFur) || is_float($this->hasFur))
                    || is_bool($this->hasFur)
                ) =>
                    $this->hasFur,
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
