<?php

declare(strict_types=1);

namespace Ns\RefAnnotations_8_4;

class Pets
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
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
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    private ?GenericPet $pet = null;

    private ?Cat $cat = null;

    public function __construct(?GenericPet $pet = null, ?Cat $cat = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->pet = $pet;
        $this->cat = $cat;
    }

    /**
     * Object (`stdClass`) or array with name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return an associative array instead of `stdClass` object.
     */
    public function getAdditionalProperties(bool $asArray = true): \stdClass|array
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
    public function withAdditionalProperties(\stdClass|array $additionalProperties): self
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

    public function getPet(): ?GenericPet
    {
        return $this->pet ?? null;
    }

    public function withPet(GenericPet $pet): self
    {
        $clone = clone $this;
        $clone->pet = $pet;

        return $clone;
    }

    public function withoutPet(): self
    {
        $clone = clone $this;
        unset($clone->pet);

        return $clone;
    }

    public function getCat(): ?Cat
    {
        return $this->cat ?? null;
    }

    public function withCat(Cat $cat): self
    {
        $clone = clone $this;
        $clone->cat = $cat;

        return $clone;
    }

    public function withoutCat(): self
    {
        $clone = clone $this;
        unset($clone->cat);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Pets Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): Pets
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $pet = isset($input->{'pet'}) ? GenericPet::fromInput($input->{'pet'}, $validate) : null;
        $cat = isset($input->{'cat'}) ? Cat::fromInput($input->{'cat'}, $validate) : null;

        $obj = new self($pet, $cat);

        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

        if (isset($this->pet)) {
            $output['pet'] = $this->pet->toArray();
        }
        if (isset($this->cat)) {
            $output['cat'] = $this->cat->toArray();
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

        if (isset($this->pet)) {
            $output->{'pet'} = $this->pet->toStdClass();
        }
        if (isset($this->cat)) {
            $output->{'cat'} = $this->cat->toStdClass();
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
}
