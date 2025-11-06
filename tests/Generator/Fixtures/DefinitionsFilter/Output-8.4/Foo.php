<?php

declare(strict_types=1);

namespace Ns\DefinitionsFilter_8_4;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'bar' => [
                '$ref' => '#/definitions/Bar',
            ],
            'embedded' => [
                'type' => 'object',
                'properties' => [
                    'baz' => [
                        '$ref' => '#/definitions/Baz',
                    ],
                ],
                'required' => [
                    'baz',
                ],
            ],
        ],
        'required' => [
            'bar',
        ],
        'definitions' => [
            'Bar' => [
                'type' => 'object',
                'properties' => [
                    'baz' => [
                        '$ref' => '#/definitions/Baz',
                    ],
                ],
                'required' => [
                    'baz',
                ],
            ],
            'Baz' => [
                'type' => 'object',
                'properties' => [
                    'name' => [
                        'type' => 'string',
                    ],
                ],
                'required' => [
                    'name',
                ],
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'bar' => 'bar',
        'embedded' => 'embedded',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    private Bar $bar;

    private ?FooEmbedded $embedded = null;

    public function __construct(Bar $bar, ?FooEmbedded $embedded = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->bar = $bar;
        $this->embedded = $embedded;
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

    public function getBar(): Bar
    {
        return $this->bar;
    }

    public function withBar(Bar $bar): self
    {
        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    public function getEmbedded(): ?FooEmbedded
    {
        return $this->embedded ?? null;
    }

    public function withEmbedded(FooEmbedded $embedded): self
    {
        $clone = clone $this;
        $clone->embedded = $embedded;

        return $clone;
    }

    public function withoutEmbedded(): self
    {
        $clone = clone $this;
        unset($clone->embedded);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Foo Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): Foo
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $bar = Bar::fromInput($input->{'bar'}, $validate);
        $embedded = isset($input->{'embedded'})
            ? FooEmbedded::fromInput($input->{'embedded'}, $validate)
            : null;

        $obj = new self($bar, $embedded);

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

        $output['bar'] = $this->bar->toArray();
        if (isset($this->embedded)) {
            $output['embedded'] = $this->embedded->toArray();
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

        $output->{'bar'} = $this->bar->toStdClass();
        if (isset($this->embedded)) {
            $output->{'embedded'} = $this->embedded->toStdClass();
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

        $this->bar = clone $this->bar;
        if (isset($this->embedded)) {
            $this->embedded = clone $this->embedded;
        }
    }
}
