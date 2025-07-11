<?php

declare(strict_types=1);

namespace Ns\RefAnnotations;

class GenericPet
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'type' => 'object',
        'properties' => [
            'hasFur' => [
                '$ref' => '#/definitions/schemas/furBoolean',
            ],
        ],
    ];

    /**
     * @var mixed|null
     */
    private mixed $hasFur = null;

    /**
     * @return mixed|null
     */
    public function getHasFur() : mixed
    {
        return $this->hasFur;
    }

    /**
     * @param mixed $hasFur
     * @return self
     */
    public function withHasFur(mixed $hasFur) : self
    {
        $clone = clone $this;
        $clone->hasFur = $hasFur;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutHasFur() : self
    {
        $clone = clone $this;
        unset($clone->hasFur);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return GenericPet Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : GenericPet
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $hasFur = isset($input->{'hasFur'}) ? $input->{'hasFur'} : null;

        $obj = new self();
        $obj->hasFur = $hasFur;
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
        if (isset($this->hasFur)) {
            $output['hasFur'] = $this->hasFur;
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
