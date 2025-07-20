<?php

declare(strict_types=1);

namespace Ns\DefaultValue_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'required' => [
            
        ],
        'properties' => [
            'foo' => [
                'type' => 'integer',
                'default' => 0,
                'minimum' => 1,
            ],
            'bar' => [
                'type' => 'string',
                'default' => 'xyz',
            ],
            'baz' => [
                'type' => [
                    'integer',
                    'null',
                ],
                'default' => null,
            ],
            'qux' => [
                '$ref' => '#/definitions/QuxDef',
            ],
        ],
        'definitions' => [
            'QuxDef' => [
                'type' => 'string',
                'default' => 'xyz',
            ],
        ],
    ];

    /**
     * Default values from the schema
     *
     * @var array
     */
    private static array $_defaults = [
        'foo' => 0,
        'bar' => 'xyz',
    ];

    /**
     * Map of optional nullable property names that were explicitly set to `null`
     *
     * @var array<string,true>
     */
    private array $_explicitNulls = [];

    /**
     * @var int|null
     */
    private ?int $foo = null;

    /**
     * @var string|null
     */
    private ?string $bar = null;

    /**
     * @var int|null
     */
    private ?int $baz = null;

    /**
     * @var string|null
     */
    private ?string $qux = null;

    /**
     * @return int|null
     */
    public function getFoo(): ?int
    {
        return $this->foo ?? null;
    }

    /**
     * @return string|null
     */
    public function getBar(): ?string
    {
        return $this->bar ?? null;
    }

    /**
     * @return int|null
     */
    public function getBaz(): ?int
    {
        return $this->baz ?? null;
    }

    /**
     * @return string|null
     */
    public function getQux(): ?string
    {
        return $this->qux ?? null;
    }

    /**
     * @param int $foo
     * @return self
     * @param bool $validate
     */
    public function withFoo(int $foo, bool $validate = true): self
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
     * @return self
     */
    public function withoutFoo(): self
    {
        $clone = clone $this;
        unset($clone->foo);

        return $clone;
    }

    /**
     * @param string $bar
     * @return self
     * @param bool $validate
     */
    public function withBar(string $bar, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($bar, self::$schema['properties']['bar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBar(): self
    {
        $clone = clone $this;
        unset($clone->bar);

        return $clone;
    }

    /**
     * @param int $baz
     * @return self
     * @param bool $validate
     */
    public function withBaz(int $baz, bool $validate = true): self
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
        $clone->_explicitNulls['baz'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBaz(): self
    {
        $clone = clone $this;
        unset($clone->baz);
        unset($clone->_explicitNulls['baz']);

        return $clone;
    }

    /**
     * @param string $qux
     * @return self
     * @param bool $validate
     */
    public function withQux(string $qux, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($qux, self::$schema['properties']['qux']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->qux = $qux;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutQux(): self
    {
        $clone = clone $this;
        unset($clone->qux);

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

        $__explicitNulls = [];
        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;
        $baz = property_exists($input, 'baz') ? $input->{'baz'} : null;
        if (property_exists($input, 'baz')) {
            $__explicitNulls['baz'] = true;
        }
        $qux = isset($input->{'qux'}) ? $input->{'qux'} : null;

        $obj = new self();
        $obj->foo = $foo;
        $obj->bar = $bar;
        $obj->baz = $baz;
        $obj->qux = $qux;
        $obj->_explicitNulls = $__explicitNulls;
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
        if (isset($this->foo)) {
            $output['foo'] = $this->foo;
        }
        if (isset($this->bar)) {
            $output['bar'] = $this->bar;
        }
        if (isset($this->baz) || array_key_exists('baz', $this->_explicitNulls)) {
            $output['baz'] = $this->baz;
        }
        if (isset($this->qux)) {
            $output['qux'] = $this->qux;
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

    /**
     * Checks if an optional nullable property was explicitly set to `null`
     *
     * @param string $propertyName property name as appears in the schema
     * @return bool
     */
    public function isExplicitNull(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->_explicitNulls);
    }
}
