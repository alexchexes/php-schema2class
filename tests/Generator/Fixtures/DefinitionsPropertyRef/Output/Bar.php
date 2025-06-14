<?php

declare(strict_types=1);

namespace Ns\DefinitionsPropertyRef;

class Bar
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
                '$ref' => '#/definitions/Foo',
            ],
        ],
        'definitions' => [
            'Foo' => [
                'type' => 'object',
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var \Foo|null
     */
    private ?Foo $a = null;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return \Foo|null
     */
    public function getA() : ?Foo
    {
        return $this->a ?? null;
    }

    /**
     * @param Foo $a
     * @return self
     */
    public function withA(Foo $a) : self
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
     * Builds a new instance from an input array
     *
     * @param mixed $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return Bar Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(mixed $input, bool $validate = true) : Bar
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

        $a = isset($input->{'a'}) ? \Foo::buildFromInput($input->{'a'}, $validate) : null;

        $obj = new self();
        $obj->a = $a;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toJson() : array
    {
        $output = [];
        if (isset($this->a)) {
            $output['a'] = $this->a->toJson();
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
    }
}
