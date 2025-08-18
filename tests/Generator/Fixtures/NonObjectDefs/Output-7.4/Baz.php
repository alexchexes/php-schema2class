<?php

declare(strict_types=1);

namespace Ns\NonObjectDefs_7_4;

class Baz
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'grox' => [
                '$ref' => '#/definitions/SkippedDef3',
            ],
        ],
        'definitions' => [
            'Foo' => [
                'type' => 'object',
                'properties' => [
                    'a' => [
                        '$ref' => '#/definitions/SkippedDef1',
                    ],
                ],
            ],
            'Bar' => [
                'properties' => [
                    'b' => [
                        '$ref' => '#/definitions/SkippedDef2',
                    ],
                ],
            ],
            'SkippedDef1' => [
                'type' => 'string',
            ],
            'SkippedDef2' => [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                ],
            ],
            'SkippedDef3' => [
                'anyOf' => [
                    [
                        '$ref' => '#/definitions/Foo',
                    ],
                    [
                        '$ref' => '#/definitions/Bar',
                    ],
                ],
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'grox' => 'grox',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    /**
     * @var Foo|Bar|null
     */
    private ?object $grox = null;

    /**
     * @param Foo|Bar|null $grox
     */
    public function __construct(?object $grox = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->grox = $grox;
    }

    /**
     * Object (`stdClass`) or array with name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return an associative array instead of `stdClass` object.
     * @return array|\stdClass
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
     * @param \stdClass|array $additionalProperties Map of property name/value pairs to add.
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
        $clone->_additionalProperties = new \stdClass();
        return $clone;
    }

    /**
     * @return Foo|Bar|null
     */
    public function getGrox(): ?object
    {
        return $this->grox ?? null;
    }

    /**
     * @param Foo|Bar $grox
     */
    public function withGrox(object $grox, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->grox = $grox;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutGrox(): self
    {
        $clone = clone $this;
        unset($clone->grox);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Baz Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput($input, bool $validate = true): Baz
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

        $grox = isset($input->{'grox'})
            ? ((((is_object($input->{'grox'}) || is_array($input->{'grox'})) && Foo::validateInput($input->{'grox'}, true)))
                ? Foo::fromInput($input->{'grox'}, $validate)
                : ((((is_object($input->{'grox'}) || is_array($input->{'grox'})) && Bar::validateInput($input->{'grox'}, true)))
                    ? Bar::fromInput($input->{'grox'}, $validate)
                    : $input->{'grox'}
                )
            )
            : null;

        $obj = new self($grox);

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

        if (isset($this->grox)) {
            if ($this->grox instanceof Foo || $this->grox instanceof Bar) {
                $output['grox'] = $this->grox->toArray();
            }
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

        if (isset($this->grox)) {
            if ($this->grox instanceof Foo || $this->grox instanceof Bar) {
                $output->{'grox'} = $this->grox->toStdClass();
            }
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
        if (isset($this->grox)) {
            $this->grox = (($this->grox instanceof Foo || $this->grox instanceof Bar)
                ? clone $this->grox
                : $this->grox
            );
        }
    }
}
