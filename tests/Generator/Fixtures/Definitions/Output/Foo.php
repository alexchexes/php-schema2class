<?php

declare(strict_types=1);

namespace Ns\Definitions;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
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

    /**
     * @var int
     */
    private int $id;

    /**
     * @var Ns\Definitions\Definitions\Address|null
     */
    private ?Ns\Definitions\DefinitionsAddress $address = null;

    /**
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return Ns\Definitions\Definitions\Address|null
     */
    public function getAddress() : ?DefinitionsAddress
    {
        return $this->address ?? null;
    }

    /**
     * @param int $id
     * @return self
     */
    public function withId(int $id) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($id, self::$schema['properties']['id']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->id = $id;

        return $clone;
    }

    /**
     * @param Ns\Definitions\DefinitionsAddress $address
     * @return self
     */
    public function withAddress(DefinitionsAddress $address) : self
    {
        $clone = clone $this;
        $clone->address = $address;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutAddress() : self
    {
        $clone = clone $this;
        unset($clone->address);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return Foo Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : Foo
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

        $id = (int)($input->{'id'});
        $address = isset($input->{'address'}) ? Ns\Definitions\Definitions\Address::buildFromInput($input->{'address'}, $validate) : null;

        $obj = new self($id);
        $obj->address = $address;
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
        $output['id'] = $this->id;
        if (isset($this->address)) {
            $output['address'] = $this->address->toJson();
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
