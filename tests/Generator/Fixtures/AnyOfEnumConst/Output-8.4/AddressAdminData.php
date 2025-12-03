<?php

declare(strict_types=1);

namespace Ns\AnyOfEnumConst_8_4;

class AddressAdminData
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'fias_level' => [
                'anyOf' => [
                    [
                        'type' => 'null',
                    ],
                    [
                        '$ref' => '#/definitions/AddressFiasLevel',
                    ],
                ],
            ],
        ],
        'required' => [
            'fias_level',
        ],
        'definitions' => [
            'AddressFiasLevel' => [
                'anyOf' => [
                    [
                        'type' => 'string',
                        'const' => '0',
                    ],
                    [
                        'type' => 'string',
                        'enum' => [
                            '1',
                            '8',
                        ],
                    ],
                    [
                        'type' => 'string',
                        'const' => '75',
                    ],
                    [
                        'type' => 'string',
                        'const' => '-1',
                    ],
                ],
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'fias_level' => 'fiasLevel',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    /**
     * @var '0'|'1'|'8'|'75'|'-1'|null
     */
    private ?string $fiasLevel;

    /**
     * @param '0'|'1'|'8'|'75'|'-1'|null $fiasLevel
     */
    public function __construct(?string $fiasLevel)
    {
        $this->_additionalProperties = new \stdClass();

        $this->fiasLevel = $fiasLevel;
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

    /**
     * @return '0'|'1'|'8'|'75'|'-1'|null
     */
    public function getFiasLevel(): ?string
    {
        return $this->fiasLevel;
    }

    /**
     * @param '0'|'1'|'8'|'75'|'-1'|null $fiasLevel
     */
    public function withFiasLevel(?string $fiasLevel, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($fiasLevel, self::$_schema['properties']['fias_level']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->fiasLevel = $fiasLevel;

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return AddressAdminData Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): AddressAdminData
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $fiasLevel = $input->{'fias_level'};

        $obj = new self($fiasLevel);

        $_additionalProperties = array_diff_key(get_object_vars($input), self::$_namesMap);
        if (!empty($_additionalProperties)) {
            $obj->_additionalProperties = (object) $_additionalProperties;
        }

        return $obj;
    }

    /**
     * Converts this object to array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

        $output['fias_level'] = $this->fiasLevel;

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

        $output->{'fias_level'} = $this->fiasLevel;

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
            $errors = array_map(
                fn (array $e): string => ($e["property"] ? $e["property"] . ": " : "") . $e["message"],
                $validator->getErrors(),
            );
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }

    public function __clone()
    {
        $this->_additionalProperties = json_decode(json_encode($this->_additionalProperties));
    }
}
