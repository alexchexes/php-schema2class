<?php

declare(strict_types=1);

namespace Ns\NonObjectDefs_8_4;

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
            'grox' => [
                '$ref' => '#/definitions/SkippedDef3',
            ],
        ],
        'definitions' => [
            'Foo' => [
                'type' => 'object',
                'properties' => [
                    'a' => [
                        '$ref' => '#/definitions/SkippedDef1',
                    ],
                ],
            ],
            'Bar' => [
                'properties' => [
                    'b' => [
                        '$ref' => '#/definitions/SkippedDef2',
                    ],
                ],
            ],
            'SkippedDef1' => [
                'type' => 'string',
            ],
            'SkippedDef2' => [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                ],
            ],
            'SkippedDef3' => [
                'anyOf' => [
                    [
                        '$ref' => '#/definitions/Foo',
                    ],
                    [
                        '$ref' => '#/definitions/Bar',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var Foo|Bar|null
     */
    private Foo|Bar|null $grox = null;

    /**
     * @return Foo|Bar|null
     */
    public function getGrox() : Bar|Foo|null
    {
        return $this->grox;
    }

    /**
     * @param Foo|Bar $grox
     * @return self
     */
    public function withGrox(Bar|Foo $grox) : self
    {
        $clone = clone $this;
        $clone->grox = $grox;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutGrox() : self
    {
        $clone = clone $this;
        unset($clone->grox);

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
    public static function buildFromInput(array|object $input, bool $validate = true) : Baz
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $grox = isset($input->{'grox'}) ? match (true) {
            Foo::validateInput($input->{'grox'}, true) => Foo::buildFromInput($input->{'grox'}, $validate),
            Bar::validateInput($input->{'grox'}, true) => Bar::buildFromInput($input->{'grox'}, $validate),
            default => null,
        } : null;

        $obj = new self();
        $obj->grox = $grox;
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
        if (isset($this->grox)) {
            $output['grox'] = match (true) {
                ($this->grox) instanceof Foo,
                ($this->grox) instanceof Bar => $this->grox->toArray(),
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
        if (isset($this->grox)) {
            $this->grox = match (true) {
                ($this->grox) instanceof Foo,
                ($this->grox) instanceof Bar => $this->grox,
            };
        }
    }
}
