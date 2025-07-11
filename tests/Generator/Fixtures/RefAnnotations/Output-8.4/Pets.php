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
                '$ref' => '#/definitions/schemas/GenericPet',
            ],
            'cat' => [
                '$ref' => '#/definitions/schemas/Cat',
            ],
        ],
    ];

    /**
     * @var mixed|null
     */
    private mixed $pet = null;

    /**
     * @var mixed|null
     */
    private mixed $cat = null;

    /**
     * @return mixed|null
     */
    public function getPet() : mixed
    {
        return $this->pet;
    }

    /**
     * @return mixed|null
     */
    public function getCat() : mixed
    {
        return $this->cat;
    }

    /**
     * @param mixed $pet
     * @return self
     */
    public function withPet(mixed $pet) : self
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
     * @param mixed $cat
     * @return self
     */
    public function withCat(mixed $cat) : self
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

        $pet = isset($input->{'pet'}) ? $input->{'pet'} : null;
        $cat = isset($input->{'cat'}) ? $input->{'cat'} : null;

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
            $output['pet'] = $this->pet;
        }
        if (isset($this->cat)) {
            $output['cat'] = $this->cat;
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
