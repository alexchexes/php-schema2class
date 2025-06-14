<?php

declare(strict_types=1);

namespace Ns\ArrayProperty;

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
        ],
    ];

    /**
     * @var \Phone[]|null
     */
    private ?array $dataArray = null;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return \Phone[]|null
     */
    public function getDataArray() : ?array
    {
        return $this->dataArray ?? null;
    }

    /**
     * @param \Phone[] $dataArray
     * @return self
     */
    public function withDataArray(array $dataArray) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($dataArray, self::$schema['properties']['dataArray']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
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
     * Builds a new instance from an input array
     *
     * @param mixed $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return Record Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(mixed $input, bool $validate = true) : Record
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to buildFromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $dataArray = isset($input->{'dataArray'}) ? array_map(fn(array|object $i): Phone => \Phone::buildFromInput($i, $validate), $input->{'dataArray'}) : null;

        $obj = new self();
        $obj->dataArray = $dataArray;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toJson() : array
    {
        $output = [];
        if (isset($this->dataArray)) {
            $output['dataArray'] = array_map(fn(Phone $i): array => $i->toJson(), $this->dataArray);
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
    public static function validateInput(array|object $input, bool $return = false) : bool
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
    }
}
