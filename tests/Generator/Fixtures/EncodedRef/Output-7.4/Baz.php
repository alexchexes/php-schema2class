<?php

declare(strict_types=1);

namespace Ns\EncodedRef_7_4;

class Baz
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'type' => 'object',
        'properties' => [
            'a' => [
                '$ref' => '#/definitions/Foo<Test>',
            ],
            'b' => [
                '$ref' => '#/definitions/Foo<Test>',
            ],
            'c' => [
                '$ref' => '#/definitions/Bar<Test>',
            ],
        ],
        'definitions' => [
            'Foo<Test>' => [
                'type' => 'object',
                'properties' => [
                    'foo' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'Bar<Test>' => [
                'type' => 'object',
                'properties' => [
                    'bar' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var FooTest|null
     */
    private ?FooTest $a = null;

    /**
     * @var FooTest|null
     */
    private ?FooTest $b = null;

    /**
     * @var BarTest|null
     */
    private ?BarTest $c = null;

    /**
     * @return FooTest|null
     */
    public function getA(): ?FooTest
    {
        return $this->a ?? null;
    }

    /**
     * @return FooTest|null
     */
    public function getB(): ?FooTest
    {
        return $this->b ?? null;
    }

    /**
     * @return BarTest|null
     */
    public function getC(): ?BarTest
    {
        return $this->c ?? null;
    }

    /**
     * @param FooTest $a
     * @return self
     */
    public function withA(FooTest $a) : self
    {
        $clone = clone $this;
        $clone->a = $a;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutA() : self
    {
        $clone = clone $this;
        unset($clone->a);

        return $clone;
    }

    /**
     * @param FooTest $b
     * @return self
     */
    public function withB(FooTest $b) : self
    {
        $clone = clone $this;
        $clone->b = $b;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutB() : self
    {
        $clone = clone $this;
        unset($clone->b);

        return $clone;
    }

    /**
     * @param BarTest $c
     * @return self
     */
    public function withC(BarTest $c) : self
    {
        $clone = clone $this;
        $clone->c = $c;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutC() : self
    {
        $clone = clone $this;
        unset($clone->c);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return Baz Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput($input, bool $validate = true): Baz
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to buildFromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $a = isset($input->{'a'}) ? FooTest::buildFromInput($input->{'a'}, $validate) : null;
        $b = isset($input->{'b'}) ? FooTest::buildFromInput($input->{'b'}, $validate) : null;
        $c = isset($input->{'c'}) ? BarTest::buildFromInput($input->{'c'}, $validate) : null;

        $obj = new self();
        $obj->a = $a;
        $obj->b = $b;
        $obj->c = $c;
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
        if (isset($this->a)) {
            $output['a'] = $this->a->toArray();
        }
        if (isset($this->b)) {
            $output['b'] = $this->b->toArray();
        }
        if (isset($this->c)) {
            $output['c'] = $this->c->toArray();
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
}
