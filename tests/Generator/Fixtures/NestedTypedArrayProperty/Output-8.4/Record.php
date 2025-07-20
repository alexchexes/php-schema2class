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
    private static array $schema = [
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
     * @return Phone[]|null
     */
    public function getDataArray(): ?array
    {
        return $this->dataArray ?? null;
    }

    /**
     * @return Phone[][]|null
     */
    public function getDataArrayNested(): ?array
    {
        return $this->dataArrayNested ?? null;
    }

    /**
     * @return (Phone|Fio)[]|null
     */
    public function getDataArrayAnyOf(): ?array
    {
        return $this->dataArrayAnyOf ?? null;
    }

    /**
     * @return ((Phone|Fio)[])[]|null
     */
    public function getDataArrayNestedAnyOf(): ?array
    {
        return $this->dataArrayNestedAnyOf ?? null;
    }

    /**
     * @param Phone[] $dataArray
     * @return self
     * @param bool $validate
     */
    public function withDataArray(array $dataArray, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($dataArray, self::$schema['properties']['dataArray']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->dataArray = $dataArray;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutDataArray() : self
    {
        $clone = clone $this;
        unset($clone->dataArray);

        return $clone;
    }

    /**
     * @param Phone[][] $dataArrayNested
     * @return self
     * @param bool $validate
     */
    public function withDataArrayNested(array $dataArrayNested, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($dataArrayNested, self::$schema['properties']['dataArrayNested']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->dataArrayNested = $dataArrayNested;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutDataArrayNested() : self
    {
        $clone = clone $this;
        unset($clone->dataArrayNested);

        return $clone;
    }

    /**
     * @param (Phone|Fio)[] $dataArrayAnyOf
     * @return self
     * @param bool $validate
     */
    public function withDataArrayAnyOf(array $dataArrayAnyOf, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($dataArrayAnyOf, self::$schema['properties']['dataArrayAnyOf']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->dataArrayAnyOf = $dataArrayAnyOf;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutDataArrayAnyOf() : self
    {
        $clone = clone $this;
        unset($clone->dataArrayAnyOf);

        return $clone;
    }

    /**
     * @param ((Phone|Fio)[])[] $dataArrayNestedAnyOf
     * @return self
     * @param bool $validate
     */
    public function withDataArrayNestedAnyOf(array $dataArrayNestedAnyOf, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($dataArrayNestedAnyOf, self::$schema['properties']['dataArrayNestedAnyOf']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->dataArrayNestedAnyOf = $dataArrayNestedAnyOf;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutDataArrayNestedAnyOf() : self
    {
        $clone = clone $this;
        unset($clone->dataArrayNestedAnyOf);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return Record Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true): Record
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $dataArray = isset($input->{'dataArray'}) ? array_map(
            fn(array|object $i): Phone => Phone::buildFromInput($i, $validate),
            $input->{'dataArray'}
        ) : null;
        $dataArrayNested = isset($input->{'dataArrayNested'}) ? array_map(fn($i) => array_map(
            fn(array|object $i): Phone => Phone::buildFromInput($i, $validate),
            $i
        ), $input->{'dataArrayNested'}) : null;
        $dataArrayAnyOf = isset($input->{'dataArrayAnyOf'}) ? array_map(fn($i) => match (true) {
            Phone::validateInput($i, true) => Phone::buildFromInput($i, $validate),
            Fio::validateInput($i, true) => Fio::buildFromInput($i, $validate),
            default => null,
        }, $input->{'dataArrayAnyOf'}) : null;
        $dataArrayNestedAnyOf = isset($input->{'dataArrayNestedAnyOf'}) ? array_map(fn($i) => array_map(fn($i) => match (true) {
            Phone::validateInput($i, true) => Phone::buildFromInput($i, $validate),
            Fio::validateInput($i, true) => Fio::buildFromInput($i, $validate),
            default => null,
        }, $i), $input->{'dataArrayNestedAnyOf'}) : null;

        $obj = new self();
        $obj->dataArray = $dataArray;
        $obj->dataArrayNested = $dataArrayNested;
        $obj->dataArrayAnyOf = $dataArrayAnyOf;
        $obj->dataArrayNestedAnyOf = $dataArrayNestedAnyOf;
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
