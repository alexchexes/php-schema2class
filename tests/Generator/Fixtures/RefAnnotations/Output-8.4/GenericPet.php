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
                '$ref' => '#/definitions/furBoolean',
            ],
        ],
        'definitions' => [
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
     * @var array
     */
    private $_optionalNullableSet = [
        
    ];

    /**
     * Whether the animal has fur (true), doesn't (false), or it's unknown or varies (null)
     *
     * @var bool|null
     */
    private ?bool $hasFur = false;

    /**
     * Whether the animal has fur (true), doesn't (false), or it's unknown or varies (null)
     *
     * @return bool
     */
    public function getHasFur() : bool
    {
        return $this->hasFur;
    }

    /**
     * @param bool $hasFur
     * @return self
     * @param bool $validate
     */
    public function withHasFur(bool $hasFur, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($hasFur, self::$schema['properties']['hasFur']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->hasFur = $hasFur;
        $clone->_optionalNullableSet['hasFur'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutHasFur() : self
    {
        $clone = clone $this;
        $clone->hasFur = false;
        unset($clone->_optionalNullableSet['hasFur']);

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

        $__optNullables = [];
        $hasFur = property_exists($input, 'hasFur') ? $input->{'hasFur'} : false;
        if (property_exists($input, 'hasFur')) { $__optNullables['hasFur'] = true; }

        $obj = new self();
        $obj->hasFur = $hasFur;
        $obj->_optionalNullableSet = $__optNullables;
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
        if (isset($this->hasFur) || array_key_exists('hasFur', $this->_optionalNullableSet)) {
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

    /**
     * @param string $propertyName
     * @return bool
     */
    public function isSet(string $propertyName) : bool
    {
        return array_key_exists($propertyName, $this->_optionalNullableSet);
    }
}
