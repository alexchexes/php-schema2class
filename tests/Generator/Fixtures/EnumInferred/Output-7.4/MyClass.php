<?php

declare(strict_types=1);

namespace Ns\EnumInferred_7_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
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
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'inferString' => 'inferString',
        'inferInt' => 'inferInt',
        'inferMixed' => 'inferMixed',
        'inferStringOpt' => 'inferStringOpt',
        'inferIntOpt' => 'inferIntOpt',
        'inferMixedOpt' => 'inferMixedOpt',
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

    /**
     * @var '3'|'4'|''
     */
    private string $inferString;

    /**
     * @var 3|4|5
     */
    private int $inferInt;

    /**
     * @var '42'|42|42.5|false|null
     */
    private $inferMixed;

    /**
     * @var '3'|'4'|''|null
     */
    private ?string $inferStringOpt = null;

    /**
     * @var 3|4|5|null
     */
    private ?int $inferIntOpt = null;

    /**
     * @var '42'|42|42.5|false|null
     */
    private $inferMixedOpt = null;

    /**
     * @param '3'|'4'|'' $inferString
     * @param 3|4|5 $inferInt
     * @param '42'|42|42.5|false|null $inferMixed
     * @param '3'|'4'|''|null $inferStringOpt
     * @param 3|4|5|null $inferIntOpt
     * @param '42'|42|42.5|false|null $inferMixedOpt
     */
    public function __construct(string $inferString, int $inferInt, $inferMixed, ?string $inferStringOpt = null, ?int $inferIntOpt = null, $inferMixedOpt = null)
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
     * @return '3'|'4'|''
     */
    public function getInferString(): string
    {
        return $this->inferString;
    }

    /**
     * @param '3'|'4'|'' $inferString
     */
    public function withInferString(string $inferString, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inferString, self::$_schema['properties']['inferString']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->inferString = $inferString;

        return $clone;
    }

    /**
     * @return 3|4|5
     */
    public function getInferInt(): int
    {
        return $this->inferInt;
    }

    /**
     * @param 3|4|5 $inferInt
     */
    public function withInferInt(int $inferInt, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inferInt, self::$_schema['properties']['inferInt']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->inferInt = $inferInt;

        return $clone;
    }

    /**
     * @return '42'|42|42.5|false|null
     */
    public function getInferMixed()
    {
        return $this->inferMixed;
    }

    /**
     * @param '42'|42|42.5|false|null $inferMixed
     */
    public function withInferMixed($inferMixed, bool $validate = true): self
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

    /**
     * @return '3'|'4'|''|null
     */
    public function getInferStringOpt(): ?string
    {
        return $this->inferStringOpt ?? null;
    }

    /**
     * @param '3'|'4'|'' $inferStringOpt
     */
    public function withInferStringOpt(string $inferStringOpt, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inferStringOpt, self::$_schema['properties']['inferStringOpt']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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

    /**
     * @return 3|4|5|null
     */
    public function getInferIntOpt(): ?int
    {
        return $this->inferIntOpt ?? null;
    }

    /**
     * @param 3|4|5 $inferIntOpt
     */
    public function withInferIntOpt(int $inferIntOpt, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inferIntOpt, self::$_schema['properties']['inferIntOpt']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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
    public function getInferMixedOpt()
    {
        return $this->inferMixedOpt ?? null;
    }

    /**
     * @param '42'|42|42.5|false|null $inferMixedOpt
     */
    public function withInferMixedOpt($inferMixedOpt, bool $validate = true): self
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
    public static function fromInput($input, bool $validate = true): MyClass
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

        $_providedOptionals = [];
        $inferString = $input->{'inferString'};
        $inferInt = (int)$input->{'inferInt'};
        $inferMixed = ($input->{'inferMixed'} !== null ? $input->{'inferMixed'} : null);
        $inferStringOpt = isset($input->{'inferStringOpt'}) ? $input->{'inferStringOpt'} : null;
        $inferIntOpt = isset($input->{'inferIntOpt'}) ? $input->{'inferIntOpt'} : null;
        $inferMixedOpt = null;
        if (property_exists($input, 'inferMixedOpt')) {
            $inferMixedOpt = ($input->{'inferMixedOpt'} !== null ? $input->{'inferMixedOpt'} : null);
            $_providedOptionals['inferMixedOpt'] = true;
        }

        $obj = new self(
            $inferString,
            $inferInt,
            $inferMixed,
            $inferStringOpt,
            $inferIntOpt,
            $inferMixedOpt
        );
        $obj->_providedOptionals = $_providedOptionals;

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

        $output['inferString'] = $this->inferString;
        $output['inferInt'] = $this->inferInt;
        $output['inferMixed'] = $this->inferMixed;
        if (isset($this->inferStringOpt)) {
            $output['inferStringOpt'] = $this->inferStringOpt;
        }
        if (isset($this->inferIntOpt)) {
            $output['inferIntOpt'] = $this->inferIntOpt;
        }
        if (isset($this->inferMixedOpt) || array_key_exists('inferMixedOpt', $this->_providedOptionals)) {
            $output['inferMixedOpt'] = ($this->inferMixedOpt !== null ? $this->inferMixedOpt : null);
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

        $output->{'inferString'} = $this->inferString;
        $output->{'inferInt'} = $this->inferInt;
        $output->{'inferMixed'} = $this->inferMixed;
        if (isset($this->inferStringOpt)) {
            $output->{'inferStringOpt'} = $this->inferStringOpt;
        }
        if (isset($this->inferIntOpt)) {
            $output->{'inferIntOpt'} = $this->inferIntOpt;
        }
        if (isset($this->inferMixedOpt) || array_key_exists('inferMixedOpt', $this->_providedOptionals)) {
            $output->{'inferMixedOpt'} = ($this->inferMixedOpt !== null ? $this->inferMixedOpt : null);
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

    /**
     * Checks if an optional nullable property was explicitly set.
     *
     * @param string $propertyName Property name to check (exactly as it appears in the schema).
     * @throws \InvalidArgumentException If property with that name doesn't exist.
     */
    public function isOptionalProvided(string $propertyName): bool
    {
        if (!array_key_exists($propertyName, self::$_namesMap)) {
            throw new \InvalidArgumentException("Unknown property: {$propertyName}");
        }
        return
            array_key_exists($propertyName, $this->_providedOptionals)
            || isset($this->{ self::$_namesMap[$propertyName] });
    }
}
