<?php

declare(strict_types=1);

namespace Ns\MaterializeDefaults_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'type' => 'object',
        'properties' => [
            'foo' => [
                'type' => 'string',
                'default' => 'some default value for foo',
            ],
            'bar' => [
                'type' => 'object',
                'properties' => [
                    'nestedFoo' => [
                        'type' => 'string',
                    ],
                ],
                'required' => [
                    'nestedFoo',
                ],
                'default' => [
                    'nestedFoo' => 'some value inside default value for \'bar\' object',
                ],
            ],
            'baz' => [
                'type' => 'string',
            ],
        ],
        'required' => [
            'foo',
            'bar',
        ],
    ];

    /**
     * Default values from the schema
     *
     * @var array
     */
    private static array $_defaults = [
        'foo' => 'some default value for foo',
        'bar' => [
            'nestedFoo' => 'some value inside default value for \'bar\' object',
        ],
    ];

    /**
     * @var string
     */
    private string $foo;

    /**
     * @var MyClassBar
     */
    private MyClassBar $bar;

    /**
     * @var string|null
     */
    private ?string $baz = null;

    /**
     * @param string $foo
     * @param MyClassBar $bar
     */
    public function __construct(string $foo, MyClassBar $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

    /**
     * @return string
     */
    public function getFoo(): string
    {
        return $this->foo;
    }

    /**
     * @return MyClassBar
     */
    public function getBar(): MyClassBar
    {
        return $this->bar;
    }

    /**
     * @return string|null
     */
    public function getBaz(): ?string
    {
        return $this->baz ?? null;
    }

    /**
     * @param string $foo
     * @return self
     * @param bool $validate
     */
    public function withFoo(string $foo, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo, self::$schema['properties']['foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    /**
     * @param MyClassBar $bar
     * @return self
     */
    public function withBar(MyClassBar $bar): self
    {
        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    /**
     * @param string $baz
     * @return self
     * @param bool $validate
     */
    public function withBaz(string $baz, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($baz, self::$schema['properties']['baz']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->baz = $baz;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBaz(): self
    {
        $clone = clone $this;
        unset($clone->baz);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @param bool $materializeDefaults Apply defaults defined in schema when missing
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true, bool $materializeDefaults = false): MyClass
    {
        $input = is_array($input)
            ? \JsonSchema\Validator::arrayToObjectRecursive($input)
            : ($materializeDefaults ? clone $input : $input);

        if ($materializeDefaults) {
            foreach (self::$_defaults as $__k => $__v) {
                if (!property_exists($input, $__k)) {
                    $input->{$__k} = is_array($__v) ? \JsonSchema\Validator::arrayToObjectRecursive($__v) : $__v;
                }
            }
        }

        if ($validate) {
            static::validateInput($input);
        }

        $foo = $input->{'foo'};
        $bar = MyClassBar::buildFromInput($input->{'bar'}, $validate, $materializeDefaults);
        $baz = isset($input->{'baz'}) ? $input->{'baz'} : null;

        $obj = new self($foo, $bar);
        $obj->baz = $baz;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return array Converted array
     */
    public function toArray(bool $includeDefaults = false): array
    {
        $output = [];
        $output['foo'] = $this->foo;
        $output['bar'] = ($this->bar)->toArray();
        if (isset($this->baz)) {
            $output['baz'] = $this->baz;
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!array_key_exists($k, $output)) {
                    $output[$k] = $v;
                }
            }
        }

        return $output;
    }

    /**
     * Converts this object to a stdClass that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return \stdClass Converted object
     */
    public function toStdClass(bool $includeDefaults = false): \stdClass
    {
        $output = new \stdClass();
        $output->{'foo'} = $this->foo;
        $output->{'bar'} = ($this->bar)->toStdClass();
        if (isset($this->baz)) {
            $output->{'baz'} = $this->baz;
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!property_exists($output, $k)) {
                    $output->{$k} = is_array($v) ? \JsonSchema\Validator::arrayToObjectRecursive($v) : $v;
                }
            }
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
        $validator->validate($input, self::$schema);

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
        $this->bar = clone $this->bar;
    }
}
