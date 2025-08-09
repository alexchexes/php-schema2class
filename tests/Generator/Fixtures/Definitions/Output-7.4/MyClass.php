<?php

declare(strict_types=1);

namespace Ns\Definitions_7_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        '$schema' => 'http://json-schema.org/draft-07/schema#',
        '$id' => 'http://json-schema.org/draft-07/schema#',
        'title' => 'definitions test',
        'type' => 'object',
        'additionalProperties' => false,
        'definitions' => [
            'address' => [
                'type' => 'object',
                'properties' => [
                    'name' => [
                        '$ref' => '#/definitions/address/$defs/name',
                    ],
                    'city' => [
                        'type' => 'string',
                    ],
                ],
                'required' => [
                    'city',
                ],
                '$defs' => [
                    'name' => [
                        'type' => 'object',
                        'properties' => [
                            'first' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'properties' => [
            'id' => [
                'type' => 'integer',
            ],
            'address' => [
                '$ref' => '#/definitions/address',
            ],
        ],
        'required' => [
            'id',
        ],
    ];

    private int $id;

    private ?Address $address = null;

    public function __construct(int $id, ?Address $address = null)
    {
        $this->id = $id;
        $this->address = $address;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function withId(int $id): self
    {
        $clone = clone $this;
        $clone->id = $id;

        return $clone;
    }

    public function getAddress(): ?Address
    {
        return $this->address ?? null;
    }

    public function withAddress(Address $address): self
    {
        $clone = clone $this;
        $clone->address = $address;

        return $clone;
    }

    public function withoutAddress(): self
    {
        $clone = clone $this;
        unset($clone->address);

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
    public static function fromInput($input, bool $validate = true): MyClass
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

        $id = (int)$input->{'id'};
        $address = isset($input->{'address'}) ? Address::fromInput($input->{'address'}, $validate) : null;

        $obj = new self($id, $address);

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
        $output['id'] = $this->id;
        if (isset($this->address)) {
            $output['address'] = $this->address->toArray();
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
        $output->{'id'} = $this->id;
        if (isset($this->address)) {
            $output->{'address'} = $this->address->toStdClass();
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
            $errors = array_map(function(array $e): string {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
