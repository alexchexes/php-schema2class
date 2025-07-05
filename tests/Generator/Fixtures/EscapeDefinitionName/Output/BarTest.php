<?php

declare(strict_types=1);

namespace Ns\EscapeDefinitionName;

class BarTest
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'type' => 'object',
        'properties' => [
            'c' => [
                'anyOf' => [
                    [
                        '$ref' => '#/definitions/Foo<Test>',
                    ],
                    [
                        '$ref' => '#/definitions/FooTest',
                    ],
                ],
            ],
        ],
        'definitions' => [
            'Foo<Test>' => [
                'type' => 'object',
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'FooTest' => [
                'type' => 'object',
                'properties' => [
                    'b' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var FooTest|null
     */
    private ?FooTest $c = null;

    /**
     * @return FooTest|null
     */
    public function getC() : ?FooTest
    {
        return $this->c ?? null;
    }

    /**
     * @param FooTest $c
     * @return self
     */
    public function withC(FooTest $c) : self
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
     * @return BarTest Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : BarTest
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $c = isset($input->{'c'}) ? match (true) {
            FooTest::validateInput($input->{'c'}, true) => FooTest::buildFromInput($input->{'c'}, $validate),
            default => null,
        } : null;

        $obj = new self();
        $obj->c = $c;
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
        if (isset($this->c)) {
            $output['c'] = match (true) {
                ($this->c) instanceof FooTest => $this->c->toArray(),
            };
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
        if (isset($this->c)) {
            $this->c = match (true) {
                ($this->c) instanceof FooTest => $this->c,
            };
        }
    }
}
