<?php

declare(strict_types=1);

namespace Ns\MutableSettersChainable_7_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
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

    /**
     * @var string|null
     */
    private ?string $foo = null;

    /**
     * @var Baz
     */
    private Baz $bar;

    /**
     * @var string|null
     */
    private ?string $opt = null;

    /**
     * @param Baz $bar
     */
    public function __construct(Baz $bar)
    {
        $this->bar = $bar;
    }

    /**
     * @return string|null
     */
    public function getFoo(): ?string
    {
        return $this->foo ?? null;
    }

    /**
     * @param string $foo
     * @return self
     * @param bool $validate
     */
    public function setFoo(string $foo, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo, self::$schema['properties']['foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $this->foo = $foo;

        return $this;
    }

    /**
     * @return Baz
     */
    public function getBar(): Baz
    {
        return $this->bar;
    }

    /**
     * @param Baz $bar
     * @return self
     */
    public function setBar(Baz $bar): self
    {
        $this->bar = $bar;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOpt(): ?string
    {
        return $this->opt ?? null;
    }

    /**
     * @param string $opt
     * @return self
     * @param bool $validate
     */
    public function setOpt(string $opt, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($opt, self::$schema['properties']['opt']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $this->opt = $opt;
        $this->_providedOptionals['opt'] = true;

        return $this;
    }

    /**
     * @return self
     */
    public function unsetOpt(): self
    {
        $this->opt = null;
        unset($this->_providedOptionals['opt']);

        return $this;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
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

        $__providedOptionals = [];
        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $bar = Baz::fromInput($input->{'bar'}, $validate);
        $opt = property_exists($input, 'opt') ? ($input->{'opt'} !== null ? $input->{'opt'} : null) : null;
        if (property_exists($input, 'opt')) {
            $__providedOptionals['opt'] = true;
        }

        $obj = new self($bar);
        $obj->foo = $foo;
        $obj->opt = $opt;
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
     * Validates an input array
     *
     * @param array|object $input Input data
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result
     * @throws \InvalidArgumentException
     */
    public static function validateInput($input, bool $return = false): bool
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$schema);

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
