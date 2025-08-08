<?php

declare(strict_types=1);

namespace Ns\UnionWithRepeatedType_8_4;

class Cat
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
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
     * Map of optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private array $_providedOptionals = [];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private object $_additionalProperties;

    private bool|int|float|string|null $hasFur = null;

    public function __construct(bool|int|float|string|null $hasFur = null)
    {
        $this->hasFur = $hasFur;
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

        $__providedOptionals = [];
        $hasFur = null;
        if (property_exists($input, 'hasFur')) {
            $hasFur = ($input->{'hasFur'} !== null ? match (true) {
            (($input->{'hasFur'}) === null) || (is_bool($input->{'hasFur'})) => ($input->{'hasFur'} !== null ? (bool)$input->{'hasFur'} : null),
            (($input->{'hasFur'}) === null) || ((is_string($input->{'hasFur'})) || (is_int($input->{'hasFur'}) || is_float($input->{'hasFur'}))) => ($input->{'hasFur'} !== null ? match (true) {
            is_string($input->{'hasFur'}) => $input->{'hasFur'},
            is_int($input->{'hasFur'}) || is_float($input->{'hasFur'}) => (str_contains((string)$input->{'hasFur'}, '.') ? (float)$input->{'hasFur'} : (int)$input->{'hasFur'}),
            default => null,
        } : null),
            (is_string($input->{'hasFur'})) || (is_int($input->{'hasFur'}) || is_float($input->{'hasFur'})) || (is_bool($input->{'hasFur'})) => match (true) {
            is_string($input->{'hasFur'}) => $input->{'hasFur'},
            is_int($input->{'hasFur'}) || is_float($input->{'hasFur'}) => (str_contains((string)$input->{'hasFur'}, '.') ? (float)$input->{'hasFur'} : (int)$input->{'hasFur'}),
            is_bool($input->{'hasFur'}) => (bool)$input->{'hasFur'},
            default => null,
        },
            default => null,
        } : null);
            $__providedOptionals['hasFur'] = true;
        }

        $obj = new self($hasFur);
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
        if (isset($this->hasFur) || array_key_exists('hasFur', $this->_providedOptionals)) {
            $output['hasFur'] = ($this->hasFur !== null) ? (match (true) {
                default => null,
                (($this->hasFur) === null) || (is_bool($this->hasFur)) => ($this->hasFur !== null) ? ($this->hasFur) : null,
                (($this->hasFur) === null) || ((is_string($this->hasFur)) || (is_int($this->hasFur) || is_float($this->hasFur))) => ($this->hasFur !== null) ? (match (true) {
                default => null,
                is_string($this->hasFur),
                is_int($this->hasFur) || is_float($this->hasFur) => $this->hasFur,
            }) : null,
                (is_string($this->hasFur)) || (is_int($this->hasFur) || is_float($this->hasFur)) || (is_bool($this->hasFur)) => match (true) {
                default => null,
                is_string($this->hasFur),
                is_int($this->hasFur) || is_float($this->hasFur),
                is_bool($this->hasFur) => $this->hasFur,
            },
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
        if (isset($this->hasFur) || array_key_exists('hasFur', $this->_providedOptionals)) {
            $output->{'hasFur'} = ($this->hasFur !== null) ? (match (true) {
                default => null,
                (($this->hasFur) === null) || (is_bool($this->hasFur)) => ($this->hasFur !== null) ? ($this->hasFur) : null,
                (($this->hasFur) === null) || ((is_string($this->hasFur)) || (is_int($this->hasFur) || is_float($this->hasFur))) => ($this->hasFur !== null) ? (match (true) {
                default => null,
                is_string($this->hasFur),
                is_int($this->hasFur) || is_float($this->hasFur) => $this->hasFur,
            }) : null,
                (is_string($this->hasFur)) || (is_int($this->hasFur) || is_float($this->hasFur)) || (is_bool($this->hasFur)) => match (true) {
                default => null,
                is_string($this->hasFur),
                is_int($this->hasFur) || is_float($this->hasFur),
                is_bool($this->hasFur) => $this->hasFur,
            },
            }) : null;
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
        if (isset($this->hasFur)) {
            $this->hasFur = match (true) {
                (($this->hasFur) === null) || (is_bool($this->hasFur)),
                (($this->hasFur) === null) || ((is_string($this->hasFur)) || (is_int($this->hasFur) || is_float($this->hasFur))) => isset($this->hasFur) ? (clone $this->hasFur) : null,
                (is_string($this->hasFur)) || (is_int($this->hasFur) || is_float($this->hasFur)) || (is_bool($this->hasFur)) => match (true) {
                is_string($this->hasFur),
                is_int($this->hasFur) || is_float($this->hasFur),
                is_bool($this->hasFur) => $this->hasFur,
            },
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
