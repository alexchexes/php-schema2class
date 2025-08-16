<?php

declare(strict_types=1);

namespace Ns\ReferenceArrayPropertyNested_7_4;

class Record
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'dataArray' => [
                'type' => 'array',
                'items' => [
                    '$ref' => '#/definitions/Phone',
                ],
                'minItems' => 1,
                'maxItems' => 1,
            ],
            'dataArrayNested' => [
                'type' => 'array',
                'items' => [
                    'type' => 'array',
                    'items' => [
                        '$ref' => '#/definitions/Phone',
                    ],
                ],
                'minItems' => 1,
                'maxItems' => 1,
            ],
            'dataArrayAnyOf' => [
                'type' => 'array',
                'items' => [
                    'anyOf' => [
                        [
                            '$ref' => '#/definitions/Phone',
                        ],
                        [
                            '$ref' => '#/definitions/Fio',
                        ],
                    ],
                ],
                'minItems' => 1,
                'maxItems' => 1,
            ],
            'dataArrayNestedAnyOf' => [
                'type' => 'array',
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'anyOf' => [
                            [
                                '$ref' => '#/definitions/Phone',
                            ],
                            [
                                '$ref' => '#/definitions/Fio',
                            ],
                        ],
                    ],
                ],
                'minItems' => 1,
                'maxItems' => 1,
            ],
        ],
        'definitions' => [
            'Phone' => [
                'type' => 'object',
                'properties' => [
                    'foo' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'Fio' => [
                'type' => 'object',
                'properties' => [
                    'bar' => [
                        'type' => [
                            'null',
                            'string',
                        ],
                    ],
                ],
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'dataArray' => 'dataArray',
        'dataArrayNested' => 'dataArrayNested',
        'dataArrayAnyOf' => 'dataArrayAnyOf',
        'dataArrayNestedAnyOf' => 'dataArrayNestedAnyOf',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    /**
     * @var Phone[]|null
     */
    private ?array $dataArray = null;

    /**
     * @var Phone[][]|null
     */
    private ?array $dataArrayNested = null;

    /**
     * @var (Phone|Fio)[]|null
     */
    private ?array $dataArrayAnyOf = null;

    /**
     * @var ((Phone|Fio)[])[]|null
     */
    private ?array $dataArrayNestedAnyOf = null;

    /**
     * @param Phone[]|null $dataArray
     * @param Phone[][]|null $dataArrayNested
     * @param (Phone|Fio)[]|null $dataArrayAnyOf
     * @param ((Phone|Fio)[])[]|null $dataArrayNestedAnyOf
     */
    public function __construct(?array $dataArray = null, ?array $dataArrayNested = null, ?array $dataArrayAnyOf = null, ?array $dataArrayNestedAnyOf = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->dataArray = $dataArray;
        $this->dataArrayNested = $dataArrayNested;
        $this->dataArrayAnyOf = $dataArrayAnyOf;
        $this->dataArrayNestedAnyOf = $dataArrayNestedAnyOf;
    }

    /**
     * Object (`stdClass`) or array with name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return an associative array instead of `stdClass` object.
     * @return array|\stdClass
     */
    public function getAdditionalProperties(bool $asArray = true)
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
    public function withAdditionalProperties($additionalProperties): self
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
     * @return Phone[]|null
     */
    public function getDataArray(): ?array
    {
        return $this->dataArray ?? null;
    }

    /**
     * @param Phone[] $dataArray
     */
    public function withDataArray(array $dataArray, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->dataArray = $dataArray;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutDataArray(): self
    {
        $clone = clone $this;
        unset($clone->dataArray);

        return $clone;
    }

    /**
     * @return Phone[][]|null
     */
    public function getDataArrayNested(): ?array
    {
        return $this->dataArrayNested ?? null;
    }

    /**
     * @param Phone[][] $dataArrayNested
     */
    public function withDataArrayNested(array $dataArrayNested, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->dataArrayNested = $dataArrayNested;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutDataArrayNested(): self
    {
        $clone = clone $this;
        unset($clone->dataArrayNested);

        return $clone;
    }

    /**
     * @return (Phone|Fio)[]|null
     */
    public function getDataArrayAnyOf(): ?array
    {
        return $this->dataArrayAnyOf ?? null;
    }

    /**
     * @param (Phone|Fio)[] $dataArrayAnyOf
     */
    public function withDataArrayAnyOf(array $dataArrayAnyOf, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->dataArrayAnyOf = $dataArrayAnyOf;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutDataArrayAnyOf(): self
    {
        $clone = clone $this;
        unset($clone->dataArrayAnyOf);

        return $clone;
    }

    /**
     * @return ((Phone|Fio)[])[]|null
     */
    public function getDataArrayNestedAnyOf(): ?array
    {
        return $this->dataArrayNestedAnyOf ?? null;
    }

    /**
     * @param ((Phone|Fio)[])[] $dataArrayNestedAnyOf
     */
    public function withDataArrayNestedAnyOf(array $dataArrayNestedAnyOf, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->dataArrayNestedAnyOf = $dataArrayNestedAnyOf;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutDataArrayNestedAnyOf(): self
    {
        $clone = clone $this;
        unset($clone->dataArrayNestedAnyOf);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Record Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput($input, bool $validate = true): Record
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to fromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $dataArray = isset($input->{'dataArray'})
            ? array_map(fn ($i): Phone => Phone::fromInput($i, $validate), $input->{'dataArray'})
            : null;
        $dataArrayNested = isset($input->{'dataArrayNested'})
            ? array_map(
                fn ($i) => array_map(fn ($i): Phone => Phone::fromInput($i, $validate), $i),
                $input->{'dataArrayNested'},
            )
            : null;
        $dataArrayAnyOf = isset($input->{'dataArrayAnyOf'})
            ? array_map(fn ($i) => ((Phone::validateInput($i, true))
                ? Phone::fromInput($i, $validate)
                : ((Fio::validateInput($i, true)) ? Fio::fromInput($i, $validate) : null)
            ), $input->{'dataArrayAnyOf'})
            : null;
        $dataArrayNestedAnyOf = isset($input->{'dataArrayNestedAnyOf'})
            ? array_map(
                fn ($i) => array_map(fn ($i) => ((Phone::validateInput($i, true))
                    ? Phone::fromInput($i, $validate)
                    : ((Fio::validateInput($i, true)) ? Fio::fromInput($i, $validate) : null)
                ), $i),
                $input->{'dataArrayNestedAnyOf'},
            )
            : null;

        $obj = new self($dataArray, $dataArrayNested, $dataArrayAnyOf, $dataArrayNestedAnyOf);

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

        if (isset($this->dataArray)) {
            $output['dataArray'] = array_map(fn (Phone $i): array => $i->toArray(), $this->dataArray);
        }
        if (isset($this->dataArrayNested)) {
            $output['dataArrayNested'] = array_map(fn ($i) => array_map(fn (Phone $i): array => $i->toArray(), $i), $this->dataArrayNested);
        }
        if (isset($this->dataArrayAnyOf)) {
            $output['dataArrayAnyOf'] = array_map(
                fn ($i) => (($i instanceof Phone || $i instanceof Fio) ? $i->toArray() : null),
                $this->dataArrayAnyOf,
            );
        }
        if (isset($this->dataArrayNestedAnyOf)) {
            $output['dataArrayNestedAnyOf'] = array_map(
                fn ($i) => array_map(fn ($i) => (($i instanceof Phone || $i instanceof Fio) ? $i->toArray() : null), $i),
                $this->dataArrayNestedAnyOf,
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

        if (isset($this->dataArray)) {
            $output->{'dataArray'} = array_map(fn (Phone $i): object => $i->toStdClass(), $this->dataArray);
        }
        if (isset($this->dataArrayNested)) {
            $output->{'dataArrayNested'} = array_map(
                fn ($i) => array_map(fn (Phone $i): object => $i->toStdClass(), $i),
                $this->dataArrayNested,
            );
        }
        if (isset($this->dataArrayAnyOf)) {
            $output->{'dataArrayAnyOf'} = array_map(
                fn ($i) => (($i instanceof Phone || $i instanceof Fio) ? $i->toStdClass() : null),
                $this->dataArrayAnyOf,
            );
        }
        if (isset($this->dataArrayNestedAnyOf)) {
            $output->{'dataArrayNestedAnyOf'} = array_map(
                fn ($i) => array_map(fn ($i) => (($i instanceof Phone || $i instanceof Fio) ? $i->toStdClass() : null), $i),
                $this->dataArrayNestedAnyOf,
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
    public static function validateInput($input, bool $return = false): bool
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
        if (isset($this->dataArrayNested)) {
            $this->dataArrayNested = array_map(fn ($i) => $i, $this->dataArrayNested);
        }
        if (isset($this->dataArrayAnyOf)) {
            $this->dataArrayAnyOf = array_map(fn ($i) => $i, $this->dataArrayAnyOf);
        }
        if (isset($this->dataArrayNestedAnyOf)) {
            $this->dataArrayNestedAnyOf = array_map(fn ($i) => array_map(fn ($i) => $i, $i), $this->dataArrayNestedAnyOf);
        }
    }
}
