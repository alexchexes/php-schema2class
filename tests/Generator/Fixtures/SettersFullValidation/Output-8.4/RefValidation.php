<?php

declare(strict_types=1);

namespace Ns\SettersFullValidation_8_4;

class RefValidation
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'foo' => [
                '$ref' => '#/definitions/Bar',
            ],
            'bar' => [
                'description' => 'This must be correctly validated in setter, it contains $ref to another definition with restrictions',
                'anyOf' => [
                    [
                        'type' => 'string',
                    ],
                    [
                        '$ref' => '#/definitions/Bar',
                    ],
                ],
            ],
            'baz' => [
                'type' => 'object',
                'properties' => [
                    'nestedFoo' => [
                        '$ref' => '#/definitions/Bar',
                    ],
                ],
            ],
        ],
        'definitions' => [
            'Bar' => [
                'type' => 'number',
                'enum' => [
                    1,
                    2,
                ],
            ],
        ],
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private object $_additionalProperties;

    /**
     * @var 1|2|null
     */
    private ?int $foo = null;

    /**
     * @var string|1|2|null
     */
    private int|string|null $bar = null;

    private ?RefValidationBaz $baz = null;

    /**
     * @param 1|2|null $foo
     * @param string|1|2|null $bar
     */
    public function __construct(?int $foo = null, int|string|null $bar = null, ?RefValidationBaz $baz = null)
    {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param array|object $additionalProperties Map of property name/value pairs to add.
     */
    public function withAdditionalProperties(array|object $additionalProperties): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        return $clone;
    }

    /**
     * @return 1|2|null
     */
    public function getFoo(): ?int
    {
        return $this->foo ?? null;
    }

    /**
     * @param 1|2 $foo
     */
    public function withFoo(int $foo, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->foo = $foo;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutFoo(): self
    {
        $clone = clone $this;
        unset($clone->foo);

        return $clone;
    }

    /**
     * This must be correctly validated in setter, it contains $ref to another definition with restrictions
     *
     * @return string|1|2|null
     */
    public function getBar(): int|string|null
    {
        return $this->bar ?? null;
    }

    /**
     * This must be correctly validated in setter, it contains $ref to another definition with restrictions
     *
     * @param string|1|2 $bar
     */
    public function withBar(int|string $bar, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->bar = $bar;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutBar(): self
    {
        $clone = clone $this;
        unset($clone->bar);

        return $clone;
    }

    public function getBaz(): ?RefValidationBaz
    {
        return $this->baz ?? null;
    }

    public function withBaz(RefValidationBaz $baz): self
    {
        $clone = clone $this;
        $clone->baz = $baz;

        return $clone;
    }

    public function withoutBaz(): self
    {
        $clone = clone $this;
        unset($clone->baz);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return RefValidation Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): RefValidation
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $bar = isset($input->{'bar'}) ? match (true) {
            is_string($input->{'bar'}),
            in_array($input->{'bar'}, array (
          0 => 1,
          1 => 2,
        ), true) => $input->{'bar'},
            default => null,
        } : null;
        $baz = isset($input->{'baz'}) ? RefValidationBaz::fromInput($input->{'baz'}, $validate) : null;

        $obj = new self($foo, $bar, $baz);
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
        if (isset($this->foo)) {
            $output['foo'] = $this->foo;
        }
        if (isset($this->bar)) {
            $output['bar'] = match (true) {
                is_string($this->bar),
                in_array($this->bar, array (
              0 => 1,
              1 => 2,
            ), true) => $this->bar,
            };
        }
        if (isset($this->baz)) {
            $output['baz'] = $this->baz->toArray();
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
        if (isset($this->foo)) {
            $output->{'foo'} = $this->foo;
        }
        if (isset($this->bar)) {
            $output->{'bar'} = match (true) {
                is_string($this->bar),
                in_array($this->bar, array (
              0 => 1,
              1 => 2,
            ), true) => $this->bar,
            };
        }
        if (isset($this->baz)) {
            $output->{'baz'} = $this->baz->toStdClass();
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

    public function __clone()
    {
        if (isset($this->bar)) {
            $this->bar = match (true) {
                is_string($this->bar),
                in_array($this->bar, array (
              0 => 1,
              1 => 2,
            ), true) => $this->bar,
            };
        }
        if (isset($this->baz)) {
            $this->baz = clone $this->baz;
        }
    }
}
