<?php

declare(strict_types=1);

namespace Ns\RefSetterValidation;

class Cat
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
                '$ref' => '#/definitions/Bar',
            ],
            'bar' => [
                'anyOf' => [
                    [
                        'type' => 'string',
                    ],
                    [
                        '$ref' => '#/definitions/Bar',
                    ],
                ],
            ],
            'baz' => [
                'type' => 'object',
                'properties' => [
                    'nestedFoo' => [
                        '$ref' => '#/definitions/Bar',
                    ],
                ],
            ],
        ],
        'definitions' => [
            'Bar' => [
                'type' => 'number',
                'enum' => [
                    1,
                    2,
                ],
            ],
        ],
    ];

    /**
     * @var 1|2|null
     */
    private ?int $foo = null;

    /**
     * @var string|1|2|null
     */
    private string|int|null $bar = null;

    /**
     * @var CatBaz|null
     */
    private ?CatBaz $baz = null;

    /**
     * @return 1|2|null
     */
    public function getFoo() : ?int
    {
        return $this->foo ?? null;
    }

    /**
     * @return string|1|2|null
     */
    public function getBar() : int|string|null
    {
        return $this->bar;
    }

    /**
     * @return CatBaz|null
     */
    public function getBaz() : ?CatBaz
    {
        return $this->baz ?? null;
    }

    /**
     * @param 1|2 $foo
     * @return self
     * @param bool $validate
     */
    public function withFoo(int $foo, bool $validate = true) : self
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
    public function withoutFoo() : self
    {
        $clone = clone $this;
        unset($clone->foo);

        return $clone;
    }

    /**
     * @param string|1|2 $bar
     * @return self
     */
    public function withBar(int|string $bar) : self
    {
        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBar() : self
    {
        $clone = clone $this;
        unset($clone->bar);

        return $clone;
    }

    /**
     * @param CatBaz $baz
     * @return self
     */
    public function withBaz(CatBaz $baz) : self
    {
        $clone = clone $this;
        $clone->baz = $baz;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBaz() : self
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
     * @return Cat Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : Cat
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $bar = isset($input->{'bar'}) ? match (true) {
            is_string($input->{'bar'}),
            in_array($input->{'bar'}, array (
          0 => 1,
          1 => 2,
        ), true) => $input->{'bar'},
            default => null,
        } : null;
        $baz = isset($input->{'baz'}) ? CatBaz::buildFromInput($input->{'baz'}, validate: $validate) : null;

        $obj = new self();
        $obj->foo = $foo;
        $obj->bar = $bar;
        $obj->baz = $baz;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray() : array
    {
        $output = [];
        if (isset($this->foo)) {
            $output['foo'] = $this->foo;
        }
        if (isset($this->bar)) {
            $output['bar'] = match (true) {
                is_string($this->bar),
                in_array($this->bar, array (
              0 => 1,
              1 => 2,
            ), true) => $this->bar,
            };
        }
        if (isset($this->baz)) {
            $output['baz'] = ($this->baz)->toArray();
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
    public static function validateInput(array|object $input, bool $return = false) : bool
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
        if (isset($this->bar)) {
            $this->bar = match (true) {
                is_string($this->bar),
                in_array($this->bar, array (
              0 => 1,
              1 => 2,
            ), true) => $this->bar,
            };
        }
        if (isset($this->baz)) {
            $this->baz = clone $this->baz;
        }
    }
}
