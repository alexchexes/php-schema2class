<?php

declare(strict_types=1);

namespace Ns\DuplicateWithDifferentCasingPreserve_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'required' => [
            'fooBar',
        ],
        'properties' => [
            'foobar' => [
                'type' => 'string',
                'deprecated' => true,
            ],
            'fooBar' => [
                'type' => 'string',
            ],
            'bar' => [
                'type' => 'string',
                'deprecated' => true,
            ],
        ],
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private object $_additionalProperties;

    /**
     * @deprecated
     */
    private ?string $foobar = null;

    private string $fooBar;

    /**
     * @deprecated
     */
    private ?string $bar = null;

    public function __construct(string $fooBar, ?string $foobar = null, ?string $bar = null)
    {
        $this->fooBar = $fooBar;
        $this->foobar = $foobar;
        $this->bar = $bar;
    }

    /**
     * Object or array containing name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return array instead of `stdClass` object.
     */
    public function getAdditionalProperties(bool $asArray = true): array|object
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
    public function withAdditionalProperties(array|object $additionalProperties): self
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

    public function getFooBar(): string
    {
        return $this->fooBar;
    }

    public function withFooBar(string $fooBar): self
    {
        $clone = clone $this;
        $clone->fooBar = $fooBar;

        return $clone;
    }

    /**
     * @deprecated
     */
    public function getBar(): ?string
    {
        return $this->bar ?? null;
    }

    /**
     * @deprecated
     */
    public function withBar(string $bar): self
    {
        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    public function withoutBar(): self
    {
        $clone = clone $this;
        unset($clone->bar);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $fooBar = $input->{'fooBar'};
        $foobar = isset($input->{'foobar'}) ? $input->{'foobar'} : null;
        $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;

        $obj = new self($fooBar, $foobar, $bar);
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
        if (isset($this->foobar)) {
            $output['foobar'] = $this->foobar;
        }
        $output['fooBar'] = $this->fooBar;
        if (isset($this->bar)) {
            $output['bar'] = $this->bar;
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
        if (isset($this->foobar)) {
            $output->{'foobar'} = $this->foobar;
        }
        $output->{'fooBar'} = $this->fooBar;
        if (isset($this->bar)) {
            $output->{'bar'} = $this->bar;
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
