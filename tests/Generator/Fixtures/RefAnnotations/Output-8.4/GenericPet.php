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
     * Default values from the schema
     *
     * @var array
     */
    private static array $_defaults = [
        'hasFur' => [
            'default' => false,
        ],
    ];

    /**
     * Map of optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private array $_providedOptionals = [];

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
        $clone->_providedOptionals['hasFur'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutHasFur(): self
    {
        $clone = clone $this;
        unset($clone->hasFur);
        unset($clone->_providedOptionals['hasFur']);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @param bool $materializeDefaults Apply defaults defined in schema when missing
     * @return GenericPet Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true, bool $materializeDefaults = false): GenericPet
    {
        $input = is_array($input)
            ? \JsonSchema\Validator::arrayToObjectRecursive($input)
            : ($materializeDefaults ? clone $input : $input);

        if ($materializeDefaults) {
            foreach (self::$_defaults as $__k => $__v) {
                if (!property_exists($input, $__k)) {
                   $input->{$__k} = $__v['default'];
                }
            }
        }

        if ($validate) {
            static::validateInput($input);
        }

        $__providedOptionals = [];
        $hasFur = property_exists($input, 'hasFur') ? $input->{'hasFur'} : null;
        if (property_exists($input, 'hasFur')) {
            $__providedOptionals['hasFur'] = true;
        }

        $obj = new self();
        $obj->hasFur = $hasFur;
        $obj->_providedOptionals = $__providedOptionals;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return array Converted array
     */
    public function toArray(bool $includeDefaults = false): array
    {
        $output = [];
        if (isset($this->hasFur) || array_key_exists('hasFur', $this->_providedOptionals)) {
            $output['hasFur'] = $this->hasFur;
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!array_key_exists($k, $output)) {
                    $output[$k] = $v['default'];
                }
            }
        }

        return $output;
    }

    /**
     * Converts this object to a stdClass that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return \stdClass Converted object
     */
    public function toStdClass(bool $includeDefaults = false): \stdClass
    {
        $output = new \stdClass();
        if (isset($this->hasFur) || array_key_exists('hasFur', $this->_providedOptionals)) {
            $output->{'hasFur'} = $this->hasFur;
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!property_exists($output, $k)) {
                    $output->{$k} = (isset($v['type']) && $v['type'] === 'object')
                       ? \JsonSchema\Validator::arrayToObjectRecursive($v['default'])
                       : $v['default'];
                }
            }
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
     * Checks if an optional nullable property was explicitly set
     *
     * @param string $propertyName Property name to check (exactly as it appears in the schema)
     * @return bool
     */
    public function isProvidedOptional(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->_providedOptionals);
    }
}
