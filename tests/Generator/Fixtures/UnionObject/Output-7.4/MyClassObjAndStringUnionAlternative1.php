<?php

declare(strict_types=1);

namespace Ns\UnionObject_7_4;

class MyClassObjAndStringUnionAlternative1
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'properties' => [
            'type' => [
                'type' => 'string',
            ],
        ],
        'definitions' => [
            'SomeObj1' => [
                'properties' => [
                    'a' => [
                        'type' => 'string a',
                    ],
                ],
            ],
            'SomeObj2' => [
                'properties' => [
                    'a' => [
                        'type' => 'string b',
                    ],
                ],
            ],
        ],
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private object $_additionalProperties;

    private ?string $type = null;

    public function __construct(?string $type = null)
    {
        $this->type = $type;
    }

    /**
     * Object or array containing name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return array instead of `stdClass` object.
     * @return array|object
     */
    public function getAdditionalProperties(bool $asArray = true)
    {
        return $asArray
            ? json_decode(json_encode($this->_additionalProperties), true)
            : $this->_additionalProperties;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param array|object $additionalProperties Map of property name/value pairs to add.
     */
    public function withAdditionalProperties($additionalProperties): self
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
        $clone->_additionalProperties = new \stdClass;
        return $clone;
    }

    public function getType(): ?string
    {
        return $this->type ?? null;
    }

    public function withType(string $type): self
    {
        $clone = clone $this;
        $clone->type = $type;

        return $clone;
    }

    public function withoutType(): self
    {
        $clone = clone $this;
        unset($clone->type);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return MyClassObjAndStringUnionAlternative1 Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput($input, bool $validate = true): MyClassObjAndStringUnionAlternative1
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to fromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $type = isset($input->{'type'}) ? $input->{'type'} : null;

        $obj = new self($type);
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
        if (isset($this->type)) {
            $output['type'] = $this->type;
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
        if (isset($this->type)) {
            $output->{'type'} = $this->type;
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
    public static function validateInput($input, bool $return = false): bool
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
