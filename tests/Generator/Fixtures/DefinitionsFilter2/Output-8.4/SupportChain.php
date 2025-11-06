<?php

declare(strict_types=1);

namespace Ns\DefinitionsFilter2_8_4;

class SupportChain
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'support' => [
                '$ref' => '#/$defs/Support',
            ],
            'extra' => [
                '$ref' => '#/definitions/Dependency',
            ],
        ],
        'required' => [
            'support',
        ],
        'definitions' => [
            'Dependency' => [
                'type' => 'object',
                'properties' => [
                    'leaf' => [
                        '$ref' => '#/definitions/Leaf',
                    ],
                ],
                'required' => [
                    'leaf',
                ],
            ],
            'Leaf' => [
                'type' => 'object',
                'properties' => [
                    'value' => [
                        'type' => 'string',
                    ],
                ],
                'required' => [
                    'value',
                ],
            ],
            'Support' => [
                'type' => 'object',
                'properties' => [
                    'detail' => [
                        'type' => 'string',
                    ],
                ],
                'required' => [
                    'detail',
                ],
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'support' => 'support',
        'extra' => 'extra',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    private Support $support;

    private ?Dependency $extra = null;

    public function __construct(Support $support, ?Dependency $extra = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->support = $support;
        $this->extra = $extra;
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

    public function getSupport(): Support
    {
        return $this->support;
    }

    public function withSupport(Support $support): self
    {
        $clone = clone $this;
        $clone->support = $support;

        return $clone;
    }

    public function getExtra(): ?Dependency
    {
        return $this->extra ?? null;
    }

    public function withExtra(Dependency $extra): self
    {
        $clone = clone $this;
        $clone->extra = $extra;

        return $clone;
    }

    public function withoutExtra(): self
    {
        $clone = clone $this;
        unset($clone->extra);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return SupportChain Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): SupportChain
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $support = Support::fromInput($input->{'support'}, $validate);
        $extra = isset($input->{'extra'}) ? Dependency::fromInput($input->{'extra'}, $validate) : null;

        $obj = new self($support, $extra);

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

        $output['support'] = $this->support->toArray();
        if (isset($this->extra)) {
            $output['extra'] = $this->extra->toArray();
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

        $output->{'support'} = $this->support->toStdClass();
        if (isset($this->extra)) {
            $output->{'extra'} = $this->extra->toStdClass();
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

        $this->support = clone $this->support;
        if (isset($this->extra)) {
            $this->extra = clone $this->extra;
        }
    }
}
