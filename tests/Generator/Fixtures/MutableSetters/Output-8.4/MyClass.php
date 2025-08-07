<?php

declare(strict_types=1);

namespace Ns\MutableSetters_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'required' => [
            'bar',
        ],
        'properties' => [
            'foo' => [
                'type' => 'string',
            ],
            'bar' => [
                '$ref' => '#/definitions/Baz',
            ],
            'opt' => [
                'type' => [
                    'string',
                    'null',
                ],
            ],
        ],
        'definitions' => [
            'Baz' => [
                'type' => 'object',
                'properties' => [
                    'name' => [
                        'type' => 'string',
                    ],
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

    private ?string $foo = null;

    private Baz $bar;

    private ?string $opt = null;

    public function __construct(Baz $bar, ?string $foo = null, ?string $opt = null)
    {
        $this->bar = $bar;
        $this->foo = $foo;
        $this->opt = $opt;
    }

    public function getFoo(): ?string
    {
        return $this->foo;
    }

    public function setFoo(string $foo): void
    {
        $this->foo = $foo;
    }

    public function unsetFoo(): void
    {
        $this->foo = null;
    }

    public function getBar(): Baz
    {
        return $this->bar;
    }

    public function setBar(Baz $bar): void
    {
        $this->bar = $bar;
    }

    public function getOpt(): ?string
    {
        return $this->opt;
    }

    public function setOpt(?string $opt): void
    {
        $this->opt = $opt;
        $this->_providedOptionals['opt'] = true;
    }

    public function unsetOpt(): void
    {
        $this->opt = null;
        unset($this->_providedOptionals['opt']);
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
        $bar = Baz::fromInput($input->{'bar'}, $validate);
        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $opt = null;
        if (property_exists($input, 'opt')) {
            $opt = ($input->{'opt'} !== null ? $input->{'opt'} : null);
            $__providedOptionals['opt'] = true;
        }

        $obj = new self($bar, $foo, $opt);
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
        if (isset($this->foo)) {
            $output['foo'] = $this->foo;
        }
        $output['bar'] = $this->bar->toArray();
        if (isset($this->opt) || array_key_exists('opt', $this->_providedOptionals)) {
            $output['opt'] = ($this->opt !== null) ? ($this->opt) : null;
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
        $output->{'bar'} = $this->bar->toStdClass();
        if (isset($this->opt) || array_key_exists('opt', $this->_providedOptionals)) {
            $output->{'opt'} = ($this->opt !== null) ? ($this->opt) : null;
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
