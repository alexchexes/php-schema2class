<?php

declare(strict_types=1);

namespace Ns\DefinitionsIndependet;

class Foo
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
                'type' => 'string',
            ],
        ],
    ];

    /**
     * @var string|null
     */
    private ?string $a = null;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return string|null
     */
    public function getA() : ?string
    {
        return $this->a ?? null;
    }

    /**
     * @param string $a
     * @return self
     */
    public function withA(string $a) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($a, self::$schema['properties']['a']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

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
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return Foo Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : Foo
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

        $a = isset($input->{'a'}) ? $input->{'a'} : null;

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
            $output['a'] = $this->a;
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

