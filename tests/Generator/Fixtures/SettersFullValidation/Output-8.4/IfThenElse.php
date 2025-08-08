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
     *
     * @var array
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
     * Map of name/value pairs for properties not specified in the schema.
     */
    private object $_additionalProperties;

    private ?string $kind;

    private mixed $value = null;

    public function __construct(?string $kind, mixed $value = null)
    {
        $this->kind = $kind;
        $this->value = $value;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param array|object $additionalProperties Map of property name/value pairs to add.
     * @param bool $validate Whether to revalidate the resulting object.
     */
    public function withAdditionalProperties(array|object $additionalProperties, bool $validate = true): self
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
        $output = new \stdClass();
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
