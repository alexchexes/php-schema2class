<?php

namespace Ns\NestedTypedArrayProperty_5_6;

class Record
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
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
    private $dataArray = null;

    /**
     * @var Phone[][]|null
     */
    private $dataArrayNested = null;

    /**
     * @var (Phone|Fio)[]|null
     */
    private $dataArrayAnyOf = null;

    /**
     * @var ((Phone|Fio)[])[]|null
     */
    private $dataArrayNestedAnyOf = null;

    /**
     * @param Phone[]|null $dataArray
     * @param Phone[][]|null $dataArrayNested
     * @param (Phone|Fio)[]|null $dataArrayAnyOf
     * @param ((Phone|Fio)[])[]|null $dataArrayNestedAnyOf
     */
    public function __construct(array $dataArray = null, array $dataArrayNested = null, array $dataArrayAnyOf = null, array $dataArrayNestedAnyOf = null)
    {
        $this->dataArray = $dataArray;
        $this->dataArrayNested = $dataArrayNested;
        $this->dataArrayAnyOf = $dataArrayAnyOf;
        $this->dataArrayNestedAnyOf = $dataArrayNestedAnyOf;
    }

    /**
     * @return Phone[]|null
     */
    public function getDataArray()
    {
        return $this->dataArray;
    }

    /**
     * @param Phone[] $dataArray
     * @param bool $validate
     * @return self
     */
    public function withDataArray(array $dataArray, $validate = true)
    {
        $clone = clone $this;
        $clone->dataArray = $dataArray;

        if ($validate) {
            $clone->validate();
        }

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutDataArray()
    {
        $clone = clone $this;
        unset($clone->dataArray);

        return $clone;
    }

    /**
     * @return Phone[][]|null
     */
    public function getDataArrayNested()
    {
        return $this->dataArrayNested;
    }

    /**
     * @param Phone[][] $dataArrayNested
     * @param bool $validate
     * @return self
     */
    public function withDataArrayNested(array $dataArrayNested, $validate = true)
    {
        $clone = clone $this;
        $clone->dataArrayNested = $dataArrayNested;

        if ($validate) {
            $clone->validate();
        }

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutDataArrayNested()
    {
        $clone = clone $this;
        unset($clone->dataArrayNested);

        return $clone;
    }

    /**
     * @return (Phone|Fio)[]|null
     */
    public function getDataArrayAnyOf()
    {
        return $this->dataArrayAnyOf;
    }

    /**
     * @param (Phone|Fio)[] $dataArrayAnyOf
     * @param bool $validate
     * @return self
     */
    public function withDataArrayAnyOf(array $dataArrayAnyOf, $validate = true)
    {
        $clone = clone $this;
        $clone->dataArrayAnyOf = $dataArrayAnyOf;

        if ($validate) {
            $clone->validate();
        }

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutDataArrayAnyOf()
    {
        $clone = clone $this;
        unset($clone->dataArrayAnyOf);

        return $clone;
    }

    /**
     * @return ((Phone|Fio)[])[]|null
     */
    public function getDataArrayNestedAnyOf()
    {
        return $this->dataArrayNestedAnyOf;
    }

    /**
     * @param ((Phone|Fio)[])[] $dataArrayNestedAnyOf
     * @param bool $validate
     * @return self
     */
    public function withDataArrayNestedAnyOf(array $dataArrayNestedAnyOf, $validate = true)
    {
        $clone = clone $this;
        $clone->dataArrayNestedAnyOf = $dataArrayNestedAnyOf;

        if ($validate) {
            $clone->validate();
        }

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutDataArrayNestedAnyOf()
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
    public static function fromInput($input, $validate = true)
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

        $dataArray = isset($input->{'dataArray'}) ? array_map(
            fn($i) => Phone::fromInput($i, $validate),
            $input->{'dataArray'}
        ) : null;
        $dataArrayNested = isset($input->{'dataArrayNested'}) ? array_map(function($i) use ($validate) { return array_map(
            fn($i) => Phone::fromInput($i, $validate),
            $i
        ); }, $input->{'dataArrayNested'}) : null;
        $dataArrayAnyOf = isset($input->{'dataArrayAnyOf'}) ? array_map(function($i) use ($validate) { return ((Fio::validateInput($i, true)) ? Fio::fromInput($i, $validate) : (((Phone::validateInput($i, true)) ? Phone::fromInput($i, $validate) : (null)))); }, $input->{'dataArrayAnyOf'}) : null;
        $dataArrayNestedAnyOf = isset($input->{'dataArrayNestedAnyOf'}) ? array_map(function($i) use ($validate) { return array_map(function($i) use ($validate) { return ((Fio::validateInput($i, true)) ? Fio::fromInput($i, $validate) : (((Phone::validateInput($i, true)) ? Phone::fromInput($i, $validate) : (null)))); }, $i); }, $input->{'dataArrayNestedAnyOf'}) : null;

        $obj = new self($dataArray, $dataArrayNested, $dataArrayAnyOf, $dataArrayNestedAnyOf);
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray()
    {
        $output = [];
        if (isset($this->dataArray)) {
            $output['dataArray'] = array_map(fn(Phone $i): array => $i->toArray(), $this->dataArray);
        }
        if (isset($this->dataArrayNested)) {
            $output['dataArrayNested'] = array_map(function($i) { return array_map(fn(Phone $i): array => $i->toArray(), $i); }, $this->dataArrayNested);
        }
        if (isset($this->dataArrayAnyOf)) {
            $output['dataArrayAnyOf'] = array_map(function($i) { return ($i instanceof Fio) ? ($i->toArray()) : (($i instanceof Phone) ? ($i->toArray()) : (null)); }, $this->dataArrayAnyOf);
        }
        if (isset($this->dataArrayNestedAnyOf)) {
            $output['dataArrayNestedAnyOf'] = array_map(function($i) { return array_map(function($i) { return ($i instanceof Fio) ? ($i->toArray()) : (($i instanceof Phone) ? ($i->toArray()) : (null)); }, $i); }, $this->dataArrayNestedAnyOf);
        }

        return $output;
    }

    /**
     * Converts this object to a stdClass that can be JSON-serialized
     *
     * @return \stdClass Converted object
     */
    public function toStdClass()
    {
        $output = new \stdClass();
        if (isset($this->dataArray)) {
            $output->{'dataArray'} = array_map(fn(Phone $i): object => $i->toStdClass(), $this->dataArray);
        }
        if (isset($this->dataArrayNested)) {
            $output->{'dataArrayNested'} = array_map(function($i) { return array_map(fn(Phone $i): object => $i->toStdClass(), $i); }, $this->dataArrayNested);
        }
        if (isset($this->dataArrayAnyOf)) {
            $output->{'dataArrayAnyOf'} = array_map(function($i) { return ($i instanceof Fio) ? ($i->toStdClass()) : (($i instanceof Phone) ? ($i->toStdClass()) : (null)); }, $this->dataArrayAnyOf);
        }
        if (isset($this->dataArrayNestedAnyOf)) {
            $output->{'dataArrayNestedAnyOf'} = array_map(function($i) { return array_map(function($i) { return ($i instanceof Fio) ? ($i->toStdClass()) : (($i instanceof Phone) ? ($i->toStdClass()) : (null)); }, $i); }, $this->dataArrayNestedAnyOf);
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
    public function validate($return = false)
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
    public static function validateInput($input, $return = false)
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function($e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }

    public function __clone()
    {
        if (isset($this->dataArrayNested)) {
            $this->dataArrayNested = array_map(function($i) { return $i; }, $this->dataArrayNested);
        }
        if (isset($this->dataArrayAnyOf)) {
            $this->dataArrayAnyOf = array_map(function($i) { return ($i instanceof Fio ? $i : ($i instanceof Phone ? $i : $i)); }, $this->dataArrayAnyOf);
        }
        if (isset($this->dataArrayNestedAnyOf)) {
            $this->dataArrayNestedAnyOf = array_map(function($i) { return array_map(function($i) { return ($i instanceof Fio ? $i : ($i instanceof Phone ? $i : $i)); }, $i); }, $this->dataArrayNestedAnyOf);
        }
    }
}
