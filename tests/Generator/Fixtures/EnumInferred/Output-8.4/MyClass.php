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
     * @var MyClassInferString
     */
    private MyClassInferString $inferString;

    /**
     * @var MyClassInferInt
     */
    private MyClassInferInt $inferInt;

    /**
     * @var MyClassInferMixedAlternative1|MyClassInferMixedAlternative2|42|42.5|false|null
     */
    private MyClassInferMixedAlternative1|MyClassInferMixedAlternative2|int|float|bool|null $inferMixed;

    /**
     * @var MyClassInferStringOpt|null
     */
    private ?MyClassInferStringOpt $inferStringOpt = null;

    /**
     * @var MyClassInferIntOpt|null
     */
    private ?MyClassInferIntOpt $inferIntOpt = null;

    /**
     * @var MyClassInferMixedOptAlternative1|MyClassInferMixedOptAlternative2|42|42.5|false|null
     */
    private MyClassInferMixedOptAlternative1|MyClassInferMixedOptAlternative2|int|float|bool|null $inferMixedOpt = null;

    /**
     * @param MyClassInferString $inferString
     * @param MyClassInferInt $inferInt
     * @param MyClassInferMixedAlternative1|MyClassInferMixedAlternative2|42|42.5|false|null $inferMixed
     * @param MyClassInferStringOpt|null $inferStringOpt
     * @param MyClassInferIntOpt|null $inferIntOpt
     * @param MyClassInferMixedOptAlternative1|MyClassInferMixedOptAlternative2|42|42.5|false|null $inferMixedOpt
     */
    public function __construct(MyClassInferString $inferString, MyClassInferInt $inferInt, MyClassInferMixedAlternative1|MyClassInferMixedAlternative2|bool|int|float|null $inferMixed, ?MyClassInferStringOpt $inferStringOpt = null, ?MyClassInferIntOpt $inferIntOpt = null, MyClassInferMixedOptAlternative1|MyClassInferMixedOptAlternative2|bool|int|float|null $inferMixedOpt = null)
    {
        $this->inferString = $inferString;
        $this->inferInt = $inferInt;
        $this->inferMixed = $inferMixed;
        $this->inferStringOpt = $inferStringOpt;
        $this->inferIntOpt = $inferIntOpt;
        $this->inferMixedOpt = $inferMixedOpt;
    }

    /**
     * @return MyClassInferString
     */
    public function getInferString(): MyClassInferString
    {
        return $this->inferString;
    }

    /**
     * @param MyClassInferString $inferString
     */
    public function withInferString(MyClassInferString $inferString): self
    {
        $clone = clone $this;
        $clone->inferString = $inferString;

        return $clone;
    }

    /**
     * @return MyClassInferInt
     */
    public function getInferInt(): MyClassInferInt
    {
        return $this->inferInt;
    }

    /**
     * @param MyClassInferInt $inferInt
     */
    public function withInferInt(MyClassInferInt $inferInt): self
    {
        $clone = clone $this;
        $clone->inferInt = $inferInt;

        return $clone;
    }

    /**
     * @return MyClassInferMixedAlternative1|MyClassInferMixedAlternative2|42|42.5|false|null
     */
    public function getInferMixed(): MyClassInferMixedAlternative1|MyClassInferMixedAlternative2|bool|int|float|null
    {
        return $this->inferMixed;
    }

    /**
     * @param MyClassInferMixedAlternative1|MyClassInferMixedAlternative2|42|42.5|false|null $inferMixed
     */
    public function withInferMixed(MyClassInferMixedAlternative1|MyClassInferMixedAlternative2|bool|int|float|null $inferMixed, bool $validate = true): self
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
     * @return MyClassInferStringOpt|null
     */
    public function getInferStringOpt(): ?MyClassInferStringOpt
    {
        return $this->inferStringOpt ?? null;
    }

    /**
     * @param MyClassInferStringOpt $inferStringOpt
     */
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

    /**
     * @return MyClassInferIntOpt|null
     */
    public function getInferIntOpt(): ?MyClassInferIntOpt
    {
        return $this->inferIntOpt ?? null;
    }

    /**
     * @param MyClassInferIntOpt $inferIntOpt
     */
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
     * @return MyClassInferMixedOptAlternative1|MyClassInferMixedOptAlternative2|42|42.5|false|null
     */
    public function getInferMixedOpt(): MyClassInferMixedOptAlternative1|MyClassInferMixedOptAlternative2|bool|int|float|null
    {
        return $this->inferMixedOpt;
    }

    /**
     * @param MyClassInferMixedOptAlternative1|MyClassInferMixedOptAlternative2|42|42.5|false|null $inferMixedOpt
     */
    public function withInferMixedOpt(MyClassInferMixedOptAlternative1|MyClassInferMixedOptAlternative2|bool|int|float|null $inferMixedOpt, bool $validate = true): self
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
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
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
        $inferMixed = ($input->{'inferMixed'} !== null ? match (true) {
            MyClassInferMixedAlternative1::tryFrom($input->{'inferMixed'}) !== null => MyClassInferMixedAlternative1::from($input->{'inferMixed'}),
            MyClassInferMixedAlternative2::tryFrom($input->{'inferMixed'}) !== null => MyClassInferMixedAlternative2::from($input->{'inferMixed'}),
            in_array($input->{'inferMixed'}, array (
          0 => 42,
          1 => 42.5,
        ), true) => (str_contains((string)$input->{'inferMixed'}, '.') ? (float)$input->{'inferMixed'} : (int)$input->{'inferMixed'}),
            is_bool($input->{'inferMixed'}) => (bool)$input->{'inferMixed'},
            default => null,
        } : null);
        $inferStringOpt = isset($input->{'inferStringOpt'}) ? MyClassInferStringOpt::from($input->{'inferStringOpt'}) : null;
        $inferIntOpt = isset($input->{'inferIntOpt'}) ? MyClassInferIntOpt::from($input->{'inferIntOpt'}) : null;
        $inferMixedOpt = null;
        if (property_exists($input, 'inferMixedOpt')) {
            $inferMixedOpt = ($input->{'inferMixedOpt'} !== null ? match (true) {
            MyClassInferMixedOptAlternative1::tryFrom($input->{'inferMixedOpt'}) !== null => MyClassInferMixedOptAlternative1::from($input->{'inferMixedOpt'}),
            MyClassInferMixedOptAlternative2::tryFrom($input->{'inferMixedOpt'}) !== null => MyClassInferMixedOptAlternative2::from($input->{'inferMixedOpt'}),
            in_array($input->{'inferMixedOpt'}, array (
          0 => 42,
          1 => 42.5,
        ), true) => (str_contains((string)$input->{'inferMixedOpt'}, '.') ? (float)$input->{'inferMixedOpt'} : (int)$input->{'inferMixedOpt'}),
            is_bool($input->{'inferMixedOpt'}) => (bool)$input->{'inferMixedOpt'},
            default => null,
        } : null);
            $__providedOptionals['inferMixedOpt'] = true;
        }

        $obj = new self($inferString, $inferInt, $inferMixed);
        $obj->inferStringOpt = $inferStringOpt;
        $obj->inferIntOpt = $inferIntOpt;
        $obj->inferMixedOpt = $inferMixedOpt;
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
        $output = [];
        $output['inferString'] = ($this->inferString)->value;
        $output['inferInt'] = ($this->inferInt)->value;
        $output['inferMixed'] = match (true) {
            $this->inferMixed instanceof MyClassInferMixedAlternative1,
            $this->inferMixed instanceof MyClassInferMixedAlternative2 => ($this->inferMixed)->value,
            in_array($this->inferMixed, array (
          0 => 42,
          1 => 42.5,
        ), true),
            is_bool($this->inferMixed) => $this->inferMixed,
        };
        if (isset($this->inferStringOpt)) {
            $output['inferStringOpt'] = ($this->inferStringOpt)->value;
        }
        if (isset($this->inferIntOpt)) {
            $output['inferIntOpt'] = ($this->inferIntOpt)->value;
        }
        if (isset($this->inferMixedOpt) || array_key_exists('inferMixedOpt', $this->_providedOptionals)) {
            $output['inferMixedOpt'] = ($this->inferMixedOpt !== null) ? (match (true) {
                default => null,
                $this->inferMixedOpt instanceof MyClassInferMixedOptAlternative1,
                $this->inferMixedOpt instanceof MyClassInferMixedOptAlternative2 => ($this->inferMixedOpt)->value,
                in_array($this->inferMixedOpt, array (
              0 => 42,
              1 => 42.5,
            ), true),
                is_bool($this->inferMixedOpt) => $this->inferMixedOpt,
            }) : null;
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
        $output->{'inferString'} = ($this->inferString)->value;
        $output->{'inferInt'} = ($this->inferInt)->value;
        $output->{'inferMixed'} = match (true) {
            $this->inferMixed instanceof MyClassInferMixedAlternative1,
            $this->inferMixed instanceof MyClassInferMixedAlternative2 => ($this->inferMixed)->value,
            in_array($this->inferMixed, array (
          0 => 42,
          1 => 42.5,
        ), true),
            is_bool($this->inferMixed) => $this->inferMixed,
        };
        if (isset($this->inferStringOpt)) {
            $output->{'inferStringOpt'} = ($this->inferStringOpt)->value;
        }
        if (isset($this->inferIntOpt)) {
            $output->{'inferIntOpt'} = ($this->inferIntOpt)->value;
        }
        if (isset($this->inferMixedOpt) || array_key_exists('inferMixedOpt', $this->_providedOptionals)) {
            $output->{'inferMixedOpt'} = ($this->inferMixedOpt !== null) ? (match (true) {
                default => null,
                $this->inferMixedOpt instanceof MyClassInferMixedOptAlternative1,
                $this->inferMixedOpt instanceof MyClassInferMixedOptAlternative2 => ($this->inferMixedOpt)->value,
                in_array($this->inferMixedOpt, array (
              0 => 42,
              1 => 42.5,
            ), true),
                is_bool($this->inferMixedOpt) => $this->inferMixedOpt,
            }) : null;
        }

        return $output;
    }

    /**
     * Validates an input array
     *
     * @param array|object $input Input data
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result
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
        $this->inferMixed = match (true) {
            $this->inferMixed instanceof MyClassInferMixedAlternative1,
            $this->inferMixed instanceof MyClassInferMixedAlternative2,
            in_array($this->inferMixed, array (
          0 => 42,
          1 => 42.5,
        ), true),
            is_bool($this->inferMixed) => $this->inferMixed,
        };
        if (isset($this->inferMixedOpt)) {
            $this->inferMixedOpt = match (true) {
                $this->inferMixedOpt instanceof MyClassInferMixedOptAlternative1,
                $this->inferMixedOpt instanceof MyClassInferMixedOptAlternative2,
                in_array($this->inferMixedOpt, array (
              0 => 42,
              1 => 42.5,
            ), true),
                is_bool($this->inferMixedOpt) => $this->inferMixedOpt,
            };
        }
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
