<?php

declare(strict_types=1);

namespace Ns\SingleElemTypeArray_8_4;

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
                'type' => [
                    'string',
                ],
            ],
            'bar' => [
                'type' => [
                    'number',
                ],
            ],
            'baz' => [
                'type' => [
                    'integer',
                ],
            ],
            'qux' => [
                'type' => [
                    'boolean',
                ],
            ],
            'grox' => [
                'type' => [
                    'null',
                ],
            ],
            'quux' => [
                'type' => [
                    'object',
                ],
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'thud' => [
                'type' => [
                    'array',
                ],
                'items' => [
                    'type' => 'string',
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
     * @var int|float|null
     */
    private int|float|null $bar = null;

    /**
     * @var int|null
     */
    private ?int $baz = null;

    /**
     * @var bool|null
     */
    private ?bool $qux = null;

    /**
     * @var null
     */
    private $grox = null;

    /**
     * @var MyClassQuux|null
     */
    private ?MyClassQuux $quux = null;

    /**
     * @var string[]|null
     */
    private ?array $thud = null;

    /**
     * @return string|null
     */
    public function getFoo(): ?string
    {
        return $this->foo ?? null;
    }

    /**
     * @return int|float|null
     */
    public function getBar(): int|float|null
    {
        return $this->bar;
    }

    /**
     * @return int|null
     */
    public function getBaz(): ?int
    {
        return $this->baz ?? null;
    }

    /**
     * @return bool|null
     */
    public function getQux(): ?bool
    {
        return $this->qux ?? null;
    }

    /**
     * @return null
     */
    public function getGrox()
    {
        return $this->grox;
    }

    /**
     * @return MyClassQuux|null
     */
    public function getQuux(): ?MyClassQuux
    {
        return $this->quux ?? null;
    }

    /**
     * @return string[]|null
     */
    public function getThud(): ?array
    {
        return $this->thud ?? null;
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
     * @return self
     */
    public function withoutFoo(): self
    {
        $clone = clone $this;
        unset($clone->foo);

        return $clone;
    }

    /**
     * @param int|float $bar
     * @return self
     * @param bool $validate
     */
    public function withBar(int|float $bar, bool $validate = true): self
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
     * @param bool $qux
     * @return self
     * @param bool $validate
     */
    public function withQux(bool $qux, bool $validate = true): self
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
     * @param null $grox
     * @return self
     * @param bool $validate
     */
    public function withGrox($grox, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($grox, self::$schema['properties']['grox']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->grox = $grox;
        $clone->_providedOptionals['grox'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutGrox(): self
    {
        $clone = clone $this;
        unset($clone->grox);
        unset($clone->_providedOptionals['grox']);

        return $clone;
    }

    /**
     * @param MyClassQuux $quux
     * @return self
     */
    public function withQuux(MyClassQuux $quux): self
    {
        $clone = clone $this;
        $clone->quux = $quux;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutQuux(): self
    {
        $clone = clone $this;
        unset($clone->quux);

        return $clone;
    }

    /**
     * @param string[] $thud
     * @return self
     * @param bool $validate
     */
    public function withThud(array $thud, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($thud, self::$schema['properties']['thud']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->thud = $thud;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutThud(): self
    {
        $clone = clone $this;
        unset($clone->thud);

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
        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;
        $baz = isset($input->{'baz'}) ? $input->{'baz'} : null;
        $qux = isset($input->{'qux'}) ? $input->{'qux'} : null;
        $grox = property_exists($input, 'grox') ? ($input->{'grox'} !== null ? $input->{'grox'} : null) : null;
        if (property_exists($input, 'grox')) {
            $__providedOptionals['grox'] = true;
        }
        $quux = isset($input->{'quux'}) ? MyClassQuux::fromInput($input->{'quux'}, $validate) : null;
        $thud = isset($input->{'thud'}) ? $input->{'thud'} : null;

        $obj = new self();
        $obj->foo = $foo;
        $obj->bar = $bar;
        $obj->baz = $baz;
        $obj->qux = $qux;
        $obj->grox = $grox;
        $obj->quux = $quux;
        $obj->thud = $thud;
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
        if (isset($this->bar)) {
            $output['bar'] = $this->bar;
        }
        if (isset($this->baz)) {
            $output['baz'] = $this->baz;
        }
        if (isset($this->qux)) {
            $output['qux'] = $this->qux;
        }
        if (isset($this->grox) || array_key_exists('grox', $this->_providedOptionals)) {
            $output['grox'] = ($this->grox !== null) ? ($this->grox) : null;
        }
        if (isset($this->quux)) {
            $output['quux'] = ($this->quux)->toArray();
        }
        if (isset($this->thud)) {
            $output['thud'] = $this->thud;
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
            $output->{'bar'} = $this->bar;
        }
        if (isset($this->baz)) {
            $output->{'baz'} = $this->baz;
        }
        if (isset($this->qux)) {
            $output->{'qux'} = $this->qux;
        }
        if (isset($this->grox) || array_key_exists('grox', $this->_providedOptionals)) {
            $output->{'grox'} = ($this->grox !== null) ? ($this->grox) : null;
        }
        if (isset($this->quux)) {
            $output->{'quux'} = ($this->quux)->toStdClass();
        }
        if (isset($this->thud)) {
            $output->{'thud'} = $this->thud;
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
        if (isset($this->quux)) {
            $this->quux = clone $this->quux;
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
