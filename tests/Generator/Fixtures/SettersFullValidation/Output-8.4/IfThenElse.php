<?php

declare(strict_types=1);

namespace Ns\SettersFullValidation_8_4;

/**
 * Class generated from this definition should add full re-validation to each property setter as its schema contains conditional validation which might affect any of its properties
 */
class IfThenElse
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'description' => 'Class generated from this definition should add full re-validation to each property setter as its schema contains conditional validation which might affect any of its properties',
        'type' => 'object',
        'properties' => [
            'kind' => [
                'type' => [
                    'string',
                    'null',
                ],
            ],
            'value' => [
                
            ],
        ],
        'required' => [
            'kind',
        ],
        'if' => [
            'properties' => [
                'kind' => [
                    'type' => 'null',
                ],
            ],
        ],
        'then' => [
            'properties' => [
                'value' => [
                    'type' => 'number',
                    'enum' => [
                        1,
                        2,
                    ],
                ],
            ],
            'required' => [
                'value',
            ],
        ],
        'else' => [
            'properties' => [
                'value' => [
                    'type' => 'string',
                    'minLength' => 1,
                ],
            ],
            'required' => [
                'value',
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'kind' => 'kind',
        'value' => 'value',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    private ?string $kind;

    private mixed $value = null;

    public function __construct(?string $kind, mixed $value = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->kind = $kind;
        $this->value = $value;
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
     * @param bool $validate Whether to revalidate the resulting object.
     */
    public function withAdditionalProperties(\stdClass|array $additionalProperties, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    /**
     * Removes all extra properties not specified in the schema.
     *
     * @param bool $validate Whether to revalidate the resulting object.
     */
    public function withoutAdditionalProperties(bool $validate = true): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = new \stdClass();
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function getKind(): ?string
    {
        return $this->kind;
    }

    public function withKind(?string $kind, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->kind = $kind;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function getValue(): mixed
    {
        return $this->value ?? null;
    }

    public function withValue(mixed $value, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->value = $value;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutValue(bool $validate = true): self
    {
        $clone = clone $this;
        unset($clone->value);
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return IfThenElse Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): IfThenElse
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $kind = $input->{'kind'};
        $value = isset($input->{'value'}) ? $input->{'value'} : null;

        $obj = new self($kind, $value);

        $_additionalProperties = array_diff_key(get_object_vars($input), self::$_namesMap);
        if (!empty($_additionalProperties)) {
            $obj->_additionalProperties = (object) $_additionalProperties;
        }

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

        $output['kind'] = $this->kind;
        if (isset($this->value)) {
            $output['value'] = $this->value;
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

        $output->{'kind'} = $this->kind;
        if (isset($this->value)) {
            $output->{'value'} = $this->value;
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
