<?php

declare(strict_types=1);

namespace Ns\RefAnnotations;

class Pets
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'type' => 'object',
        'properties' => [
            'pet' => [
                '$ref' => '#/definitions/GenericPet',
            ],
            'cat' => [
                '$ref' => '#/definitions/Cat',
            ],
        ],
        'definitions' => [
            'GenericPet' => [
                'type' => 'object',
                'properties' => [
                    'hasFur' => [
                        '$ref' => '#/definitions/furBoolean',
                    ],
                ],
            ],
            'Cat' => [
                'type' => 'object',
                'properties' => [
                    'hasFur' => [
                        '$ref' => '#/definitions/furBoolean',
                        'description' => 'Whether the cat has fur. True by default for most cats',
                        'default' => true,
                    ],
                ],
            ],
            'furBoolean' => [
                'description' => 'Whether the animal has fur (true), doesn\'t (false), or it\'s unknown or varies (null)',
                'type' => [
                    'boolean',
                    'null',
                ],
                'default' => false,
            ],
        ],
    ];

    /**
     * @var GenericPet|null
     */
    private ?GenericPet $pet = null;

    /**
     * @var Cat|null
     */
    private ?Cat $cat = null;

    /**
     * @return GenericPet|null
     */
    public function getPet() : ?GenericPet
    {
        return $this->pet ?? null;
    }

    /**
     * @return Cat|null
     */
    public function getCat() : ?Cat
    {
        return $this->cat ?? null;
    }

    /**
     * @param GenericPet $pet
     * @return self
     */
    public function withPet(GenericPet $pet) : self
    {
        $clone = clone $this;
        $clone->pet = $pet;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutPet() : self
    {
        $clone = clone $this;
        unset($clone->pet);

        return $clone;
    }

    /**
     * @param Cat $cat
     * @return self
     */
    public function withCat(Cat $cat) : self
    {
        $clone = clone $this;
        $clone->cat = $cat;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutCat() : self
    {
        $clone = clone $this;
        unset($clone->cat);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return Pets Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : Pets
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $pet = property_exists($input, 'pet') ? GenericPet::buildFromInput($input->{'pet'}, $validate) : null;
        $cat = property_exists($input, 'cat') ? Cat::buildFromInput($input->{'cat'}, $validate) : null;

        $obj = new self();
        $obj->pet = $pet;
        $obj->cat = $cat;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray() : array
    {
        $output = [];
        if (isset($this->pet)) {
            $output['pet'] = $this->pet->toArray();
        }
        if (isset($this->cat)) {
            $output['cat'] = $this->cat->toArray();
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
}
