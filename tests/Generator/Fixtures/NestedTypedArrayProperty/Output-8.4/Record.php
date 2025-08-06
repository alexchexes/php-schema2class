<?php

declare(strict_types=1);

namespace Ns\NestedTypedArrayProperty_8_4;

class Record
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
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
        $this->dataArray = $dataArray;
        $this->dataArrayNested = $dataArrayNested;
        $this->dataArrayAnyOf = $dataArrayAnyOf;
        $this->dataArrayNestedAnyOf = $dataArrayNestedAnyOf;
    }

    /**
     * @return Phone[]|null
     */
    public function getDataArray(): ?array
    {
        return $this->dataArray;
    }

    /**
     * @param Phone[] $dataArray
     */
    public function withDataArray(array $dataArray, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($dataArray, self::$_schema['properties']['dataArray']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->dataArray = $dataArray;

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
        return $this->dataArrayNested;
    }

    /**
     * @param Phone[][] $dataArrayNested
     */
    public function withDataArrayNested(array $dataArrayNested, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($dataArrayNested, self::$_schema['properties']['dataArrayNested']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->dataArrayNested = $dataArrayNested;

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
        return $this->dataArrayAnyOf;
    }

    /**
     * @param (Phone|Fio)[] $dataArrayAnyOf
     */
    public function withDataArrayAnyOf(array $dataArrayAnyOf, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($dataArrayAnyOf, self::$_schema['properties']['dataArrayAnyOf']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->dataArrayAnyOf = $dataArrayAnyOf;

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
        return $this->dataArrayNestedAnyOf;
    }

    /**
     * @param ((Phone|Fio)[])[] $dataArrayNestedAnyOf
     */
    public function withDataArrayNestedAnyOf(array $dataArrayNestedAnyOf, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($dataArrayNestedAnyOf, self::$_schema['properties']['dataArrayNestedAnyOf']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->dataArrayNestedAnyOf = $dataArrayNestedAnyOf;

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
    public static function fromInput(array|object $input, bool $validate = true): Record
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $dataArray = isset($input->{'dataArray'}) ? array_map(
            fn(array|object $i): Phone => Phone::fromInput($i, $validate),
            $input->{'dataArray'}
        ) : null;
        $dataArrayNested = isset($input->{'dataArrayNested'}) ? array_map(fn($i) => array_map(
            fn(array|object $i): Phone => Phone::fromInput($i, $validate),
            $i
        ), $input->{'dataArrayNested'}) : null;
        $dataArrayAnyOf = isset($input->{'dataArrayAnyOf'}) ? array_map(fn($i) => match (true) {
            Phone::validateInput($i, true) => Phone::fromInput($i, $validate),
            Fio::validateInput($i, true) => Fio::fromInput($i, $validate),
            default => null,
        }, $input->{'dataArrayAnyOf'}) : null;
        $dataArrayNestedAnyOf = isset($input->{'dataArrayNestedAnyOf'}) ? array_map(fn($i) => array_map(fn($i) => match (true) {
            Phone::validateInput($i, true) => Phone::fromInput($i, $validate),
            Fio::validateInput($i, true) => Fio::fromInput($i, $validate),
            default => null,
        }, $i), $input->{'dataArrayNestedAnyOf'}) : null;

        $obj = new self($dataArray, $dataArrayNested, $dataArrayAnyOf, $dataArrayNestedAnyOf);
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
        if (isset($this->dataArray)) {
            $output['dataArray'] = array_map(fn(Phone $i): array => $i->toArray(), $this->dataArray);
        }
        if (isset($this->dataArrayNested)) {
            $output['dataArrayNested'] = array_map(fn($i) => array_map(fn(Phone $i): array => $i->toArray(), $i), $this->dataArrayNested);
        }
        if (isset($this->dataArrayAnyOf)) {
            $output['dataArrayAnyOf'] = array_map(fn($i) => match (true) {
                default => null,
                ($i) instanceof Phone,
                ($i) instanceof Fio => $i->toArray(),
            }, $this->dataArrayAnyOf);
        }
        if (isset($this->dataArrayNestedAnyOf)) {
            $output['dataArrayNestedAnyOf'] = array_map(fn($i) => array_map(fn($i) => match (true) {
                default => null,
                ($i) instanceof Phone,
                ($i) instanceof Fio => $i->toArray(),
            }, $i), $this->dataArrayNestedAnyOf);
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
        if (isset($this->dataArray)) {
            $output->{'dataArray'} = array_map(fn(Phone $i): object => $i->toStdClass(), $this->dataArray);
        }
        if (isset($this->dataArrayNested)) {
            $output->{'dataArrayNested'} = array_map(fn($i) => array_map(fn(Phone $i): object => $i->toStdClass(), $i), $this->dataArrayNested);
        }
        if (isset($this->dataArrayAnyOf)) {
            $output->{'dataArrayAnyOf'} = array_map(fn($i) => match (true) {
                default => null,
                ($i) instanceof Phone,
                ($i) instanceof Fio => $i->toStdClass(),
            }, $this->dataArrayAnyOf);
        }
        if (isset($this->dataArrayNestedAnyOf)) {
            $output->{'dataArrayNestedAnyOf'} = array_map(fn($i) => array_map(fn($i) => match (true) {
                default => null,
                ($i) instanceof Phone,
                ($i) instanceof Fio => $i->toStdClass(),
            }, $i), $this->dataArrayNestedAnyOf);
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
        if (isset($this->dataArrayNested)) {
            $this->dataArrayNested = array_map(fn($i) => $i, $this->dataArrayNested);
        }
        if (isset($this->dataArrayAnyOf)) {
            $this->dataArrayAnyOf = array_map(fn($i) => match (true) {
                ($i) instanceof Phone,
                ($i) instanceof Fio => $i,
            }, $this->dataArrayAnyOf);
        }
        if (isset($this->dataArrayNestedAnyOf)) {
            $this->dataArrayNestedAnyOf = array_map(fn($i) => array_map(fn($i) => match (true) {
                ($i) instanceof Phone,
                ($i) instanceof Fio => $i,
            }, $i), $this->dataArrayNestedAnyOf);
        }
    }
}
