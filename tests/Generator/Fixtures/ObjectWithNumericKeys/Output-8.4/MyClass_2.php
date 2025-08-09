<?php

declare(strict_types=1);

namespace Ns\ObjectWithNumericKeys_8_4;

class MyClass_2
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            1 => [
                'type' => 'string',
            ],
            [
                'type' => 'string',
            ],
            [
                'type' => 'string',
            ],
        ],
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    private ?string $_1 = null;

    private ?string $_2 = null;

    private ?string $_3 = null;

    public function __construct(?string $_1 = null, ?string $_2 = null, ?string $_3 = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->_1 = $_1;
        $this->_2 = $_2;
        $this->_3 = $_3;
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

    public function get_1(): ?string
    {
        return $this->_1 ?? null;
    }

    public function with_1(string $_1): self
    {
        $clone = clone $this;
        $clone->_1 = $_1;

        return $clone;
    }

    public function without_1(): self
    {
        $clone = clone $this;
        unset($clone->_1);

        return $clone;
    }

    public function get_2(): ?string
    {
        return $this->_2 ?? null;
    }

    public function with_2(string $_2): self
    {
        $clone = clone $this;
        $clone->_2 = $_2;

        return $clone;
    }

    public function without_2(): self
    {
        $clone = clone $this;
        unset($clone->_2);

        return $clone;
    }

    public function get_3(): ?string
    {
        return $this->_3 ?? null;
    }

    public function with_3(string $_3): self
    {
        $clone = clone $this;
        $clone->_3 = $_3;

        return $clone;
    }

    public function without_3(): self
    {
        $clone = clone $this;
        unset($clone->_3);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return MyClass_2 Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): MyClass_2
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $_1 = isset($input->{'1'}) ? $input->{'1'} : null;
        $_2 = isset($input->{'2'}) ? $input->{'2'} : null;
        $_3 = isset($input->{'3'}) ? $input->{'3'} : null;

        $obj = new self($_1, $_2, $_3);

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

        if (isset($this->_1)) {
            $output['1'] = $this->_1;
        }
        if (isset($this->_2)) {
            $output['2'] = $this->_2;
        }
        if (isset($this->_3)) {
            $output['3'] = $this->_3;
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

        if (isset($this->_1)) {
            $output->{'1'} = $this->_1;
        }
        if (isset($this->_2)) {
            $output->{'2'} = $this->_2;
        }
        if (isset($this->_3)) {
            $output->{'3'} = $this->_3;
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
