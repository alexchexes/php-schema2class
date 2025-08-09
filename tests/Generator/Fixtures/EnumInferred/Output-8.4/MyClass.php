<?php

declare(strict_types=1);

namespace Ns\EnumInferred_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'required' => [
            'inferString',
            'inferInt',
            'inferMixed',
        ],
        'properties' => [
            'inferString' => [
                'enum' => [
                    '3',
                    '4',
                    '',
                ],
            ],
            'inferInt' => [
                'enum' => [
                    3,
                    4,
                    5,
                ],
            ],
            'inferMixed' => [
                'enum' => [
                    '42',
                    42,
                    42.5,
                    false,
                    null,
                ],
            ],
            'inferStringOpt' => [
                'enum' => [
                    '3',
                    '4',
                    '',
                ],
            ],
            'inferIntOpt' => [
                'enum' => [
                    3,
                    4,
                    5,
                ],
            ],
            'inferMixedOpt' => [
                'enum' => [
                    '42',
                    42,
                    42.5,
                    false,
                    null,
                ],
            ],
        ],
    ];

    /**
     * Map of optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private array $_providedOptionals = [];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    private MyClassInferString $inferString;

    private MyClassInferInt $inferInt;

    /**
     * @var '42'|42|42.5|false|null
     */
    private bool|int|float|string|null $inferMixed;

    private ?MyClassInferStringOpt $inferStringOpt = null;

    private ?MyClassInferIntOpt $inferIntOpt = null;

    /**
     * @var '42'|42|42.5|false|null
     */
    private bool|int|float|string|null $inferMixedOpt = null;

    /**
     * @param '42'|42|42.5|false|null $inferMixed
     * @param '42'|42|42.5|false|null $inferMixedOpt
     */
    public function __construct(MyClassInferString $inferString, MyClassInferInt $inferInt, bool|int|float|string|null $inferMixed, ?MyClassInferStringOpt $inferStringOpt = null, ?MyClassInferIntOpt $inferIntOpt = null, bool|int|float|string|null $inferMixedOpt = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->inferString = $inferString;
        $this->inferInt = $inferInt;
        $this->inferMixed = $inferMixed;
        $this->inferStringOpt = $inferStringOpt;
        $this->inferIntOpt = $inferIntOpt;
        if ($inferMixedOpt !== null) {
            $this->inferMixedOpt = $inferMixedOpt;
            $this->_providedOptionals['inferMixedOpt'] = true;
        };
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

    public function getInferString(): MyClassInferString
    {
        return $this->inferString;
    }

    public function withInferString(MyClassInferString $inferString): self
    {
        $clone = clone $this;
        $clone->inferString = $inferString;

        return $clone;
    }

    public function getInferInt(): MyClassInferInt
    {
        return $this->inferInt;
    }

    public function withInferInt(MyClassInferInt $inferInt): self
    {
        $clone = clone $this;
        $clone->inferInt = $inferInt;

        return $clone;
    }

    /**
     * @return '42'|42|42.5|false|null
     */
    public function getInferMixed(): bool|int|float|string|null
    {
        return $this->inferMixed;
    }

    /**
     * @param '42'|42|42.5|false|null $inferMixed
     */
    public function withInferMixed(bool|int|float|string|null $inferMixed, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inferMixed, self::$_schema['properties']['inferMixed']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->inferMixed = $inferMixed;

        return $clone;
    }

    public function getInferStringOpt(): ?MyClassInferStringOpt
    {
        return $this->inferStringOpt ?? null;
    }

    public function withInferStringOpt(MyClassInferStringOpt $inferStringOpt): self
    {
        $clone = clone $this;
        $clone->inferStringOpt = $inferStringOpt;

        return $clone;
    }

    public function withoutInferStringOpt(): self
    {
        $clone = clone $this;
        unset($clone->inferStringOpt);

        return $clone;
    }

    public function getInferIntOpt(): ?MyClassInferIntOpt
    {
        return $this->inferIntOpt ?? null;
    }

    public function withInferIntOpt(MyClassInferIntOpt $inferIntOpt): self
    {
        $clone = clone $this;
        $clone->inferIntOpt = $inferIntOpt;

        return $clone;
    }

    public function withoutInferIntOpt(): self
    {
        $clone = clone $this;
        unset($clone->inferIntOpt);

        return $clone;
    }

    /**
     * @return '42'|42|42.5|false|null
     */
    public function getInferMixedOpt(): bool|int|float|string|null
    {
        return $this->inferMixedOpt ?? null;
    }

    /**
     * @param '42'|42|42.5|false|null $inferMixedOpt
     */
    public function withInferMixedOpt(bool|int|float|string|null $inferMixedOpt, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inferMixedOpt, self::$_schema['properties']['inferMixedOpt']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->inferMixedOpt = $inferMixedOpt;
        $clone->_providedOptionals['inferMixedOpt'] = true;

        return $clone;
    }

    public function withoutInferMixedOpt(): self
    {
        $clone = clone $this;
        unset($clone->inferMixedOpt);
        unset($clone->_providedOptionals['inferMixedOpt']);

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

        $__providedOptionals = [];
        $inferString = MyClassInferString::from($input->{'inferString'});
        $inferInt = MyClassInferInt::from($input->{'inferInt'});
        $inferMixed = ($input->{'inferMixed'} !== null ? $input->{'inferMixed'} : null);
        $inferStringOpt = isset($input->{'inferStringOpt'}) ? MyClassInferStringOpt::from($input->{'inferStringOpt'}) : null;
        $inferIntOpt = isset($input->{'inferIntOpt'}) ? MyClassInferIntOpt::from($input->{'inferIntOpt'}) : null;
        $inferMixedOpt = null;
        if (property_exists($input, 'inferMixedOpt')) {
            $inferMixedOpt = ($input->{'inferMixedOpt'} !== null ? $input->{'inferMixedOpt'} : null);
            $__providedOptionals['inferMixedOpt'] = true;
        }

        $obj = new self(
            $inferString,
            $inferInt,
            $inferMixed,
            $inferStringOpt,
            $inferIntOpt,
            $inferMixedOpt
        );
        $obj->_providedOptionals = $__providedOptionals;

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

        $output['inferString'] = ($this->inferString)->value;
        $output['inferInt'] = $this->inferInt->value;
        $output['inferMixed'] = $this->inferMixed;
        if (isset($this->inferStringOpt)) {
            $output['inferStringOpt'] = ($this->inferStringOpt)->value;
        }
        if (isset($this->inferIntOpt)) {
            $output['inferIntOpt'] = $this->inferIntOpt->value;
        }
        if (isset($this->inferMixedOpt) || array_key_exists('inferMixedOpt', $this->_providedOptionals)) {
            $output['inferMixedOpt'] = ($this->inferMixedOpt !== null) ? ($this->inferMixedOpt) : null;
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

        $output->{'inferString'} = ($this->inferString)->value;
        $output->{'inferInt'} = $this->inferInt->value;
        $output->{'inferMixed'} = $this->inferMixed;
        if (isset($this->inferStringOpt)) {
            $output->{'inferStringOpt'} = ($this->inferStringOpt)->value;
        }
        if (isset($this->inferIntOpt)) {
            $output->{'inferIntOpt'} = $this->inferIntOpt->value;
        }
        if (isset($this->inferMixedOpt) || array_key_exists('inferMixedOpt', $this->_providedOptionals)) {
            $output->{'inferMixedOpt'} = ($this->inferMixedOpt !== null) ? ($this->inferMixedOpt) : null;
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

    /**
     * Checks if an optional nullable property was explicitly set
     *
     * @param string $propertyName Property name to check (exactly as it appears in the schema)
     * @return bool
     */
    public function isOptionalProvided(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->_providedOptionals);
    }
}
