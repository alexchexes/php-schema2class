<?php

declare(strict_types=1);

namespace Ns\RefAnnotations;

class Cat
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
                'description' => 'Whether the cat has fur. True by default for most cats',
                'default' => true,
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
     * Optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private array $_explicitlySet = [];

    /**
     * Whether the cat has fur. True by default for most cats
     *
     * @var bool|null
     */
    private ?bool $hasFur = true;

    /**
     * Whether the cat has fur. True by default for most cats
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
        $clone->_explicitlySet['hasFur'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutHasFur() : self
    {
        $clone = clone $this;
        $clone->hasFur = true;
        unset($clone->_explicitlySet['hasFur']);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return Cat Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : Cat
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $__explicitlySet = [];
        $hasFur = property_exists($input, 'hasFur') ? $input->{'hasFur'} : true;
        if (property_exists($input, 'hasFur')) {
            $__explicitlySet['hasFur'] = true;
        }

        $obj = new self();
        $obj->hasFur = $hasFur;
        $obj->_explicitlySet = $__explicitlySet;
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
        if (isset($this->hasFur) || array_key_exists('hasFur', $this->_explicitlySet)) {
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
     * Checks if an optional nullable property was set
     *
     * @param string $propertyName
     * @return bool
     */
    public function isDefined(string $propertyName) : bool
    {
        return array_key_exists($propertyName, $this->_explicitlySet);
    }
}
