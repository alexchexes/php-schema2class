<?php

declare(strict_types=1);

namespace Ns\RefAnnotations_8_4;

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
     * Map of optional nullable property names that were explicitly set to `null`
     *
     * @var array<string,true>
     */
    private array $_explicitNulls = [];

    /**
     * Whether the animal has fur (true), doesn't (false), or it's unknown or varies (null)
     *
     * @var bool|null
     */
    private ?bool $hasFur = null;

    /**
     * Whether the animal has fur (true), doesn't (false), or it's unknown or varies (null)
     *
     * @return bool|null
     */
    public function getHasFur(): ?bool
    {
        return $this->hasFur ?? null;
    }

    /**
     * @param bool $hasFur
     * @return self
     * @param bool $validate
     */
    public function withHasFur(bool $hasFur, bool $validate = true): self
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
        $clone->_explicitNulls['hasFur'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutHasFur(): self
    {
        $clone = clone $this;
        unset($clone->hasFur);
        unset($clone->_explicitNulls['hasFur']);

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
    public static function buildFromInput(array|object $input, bool $validate = true): GenericPet
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $__explicitNulls = [];
        $hasFur = property_exists($input, 'hasFur') ? $input->{'hasFur'} : null;
        if (property_exists($input, 'hasFur')) {
            $__explicitNulls['hasFur'] = true;
        }

        $obj = new self();
        $obj->hasFur = $hasFur;
        $obj->_explicitNulls = $__explicitNulls;
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
        if (isset($this->hasFur) || array_key_exists('hasFur', $this->_explicitNulls)) {
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
    public static function validateInput(array|object $input, bool $return = false): bool
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
     * Checks if an optional nullable property was explicitly set to `null`
     *
     * @param string $propertyName property name as appears in the schema
     * @return bool
     */
    public function isExplicitNull(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->_explicitNulls);
    }
}
