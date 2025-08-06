<?php

declare(strict_types=1);

namespace Ns\TopLevelRefDefs_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'properties' => [
            'foo' => [
                '$ref' => '#/definitions/Bar',
            ],
            'encoded' => [
                '$ref' => '#/definitions/Encoded<Test>',
            ],
        ],
        'definitions' => [
            'Foo' => [
                'properties' => [
                    'foo' => [
                        '$ref' => '#/definitions/Bar',
                    ],
                    'encoded' => [
                        '$ref' => '#/definitions/Encoded<Test>',
                    ],
                ],
            ],
            'Bar' => [
                'type' => 'object',
            ],
            'Encoded<Test>' => [
                'type' => 'object',
            ],
        ],
    ];

    private array|object|null $foo = null;

    private array|object|null $encoded = null;

    public function __construct(array|object|null $foo = null, array|object|null $encoded = null)
    {
        $this->foo = $foo;
        $this->encoded = $encoded;
    }

    public function getFoo(): array|object|null
    {
        return $this->foo;
    }

    public function withFoo(array|object $foo): self
    {
        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    public function withoutFoo(): self
    {
        $clone = clone $this;
        unset($clone->foo);

        return $clone;
    }

    public function getEncoded(): array|object|null
    {
        return $this->encoded;
    }

    public function withEncoded(array|object $encoded): self
    {
        $clone = clone $this;
        $clone->encoded = $encoded;

        return $clone;
    }

    public function withoutEncoded(): self
    {
        $clone = clone $this;
        unset($clone->encoded);

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
    public static function fromInput(array|object $input, bool $validate = true): MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $encoded = isset($input->{'encoded'}) ? $input->{'encoded'} : null;

        $obj = new self($foo, $encoded);
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
            $output['foo'] = json_decode(json_encode($this->foo), true);
        }
        if (isset($this->encoded)) {
            $output['encoded'] = json_decode(json_encode($this->encoded), true);
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
            $output->{'foo'} = json_decode(json_encode($this->foo));
        }
        if (isset($this->encoded)) {
            $output->{'encoded'} = json_decode(json_encode($this->encoded));
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
}
