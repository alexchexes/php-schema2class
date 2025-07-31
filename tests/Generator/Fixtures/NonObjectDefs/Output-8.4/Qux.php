<?php

declare(strict_types=1);

namespace Ns\NonObjectDefs_8_4;

class Qux
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
                '$ref' => '#/definitions/SkippedDef4',
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
            'SkippedDef4' => [
                'anyOf' => [
                    [
                        '$ref' => '#/definitions/SkippedDef1',
                    ],
                    [
                        '$ref' => '#/definitions/SkippedDef2',
                    ],
                    [
                        '$ref' => '#/definitions/SkippedDef3',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var string|string[]|Foo|Bar|null
     */
    private string|array|Foo|Bar|null $grox = null;

    /**
     * @return string|string[]|Foo|Bar|null
     */
    public function getGrox(): Bar|Foo|string|array|null
    {
        return $this->grox;
    }

    /**
     * @param string|string[]|Foo|Bar $grox
     * @return self
     */
    public function withGrox(Bar|Foo|string|array $grox): self
    {
        $clone = clone $this;
        $clone->grox = $grox;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutGrox(): self
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
     * @return Qux Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true): Qux
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $grox = isset($input->{'grox'}) ? (match (true) {
            is_string($input->{'grox'}),
            is_array($input->{'grox'}) => $input->{'grox'},
            (Foo::validateInput($input->{'grox'}, true)) || (Bar::validateInput($input->{'grox'}, true)) => match (true) {
            Foo::validateInput($input->{'grox'}, true) => Foo::buildFromInput($input->{'grox'}, $validate),
            Bar::validateInput($input->{'grox'}, true) => Bar::buildFromInput($input->{'grox'}, $validate),
            default => null,
        },
            default => null,
        }) : null;

        $obj = new self();
        $obj->grox = $grox;
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
        if (isset($this->grox)) {
            $output['grox'] = match (true) {
                is_string($this->grox),
                is_array($this->grox) => $this->grox,
                (($this->grox) instanceof Foo) || (($this->grox) instanceof Bar) => match (true) {
                default => null,
                ($this->grox) instanceof Foo,
                ($this->grox) instanceof Bar => $this->grox->toArray(),
            },
            };
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
        if (isset($this->grox)) {
            $output->{'grox'} = match (true) {
                is_string($this->grox),
                is_array($this->grox) => $this->grox,
                (($this->grox) instanceof Foo) || (($this->grox) instanceof Bar) => match (true) {
                default => null,
                ($this->grox) instanceof Foo,
                ($this->grox) instanceof Bar => $this->grox->toStdClass(),
            },
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
        if (isset($this->grox)) {
            $this->grox = match (true) {
                is_string($this->grox),
                is_array($this->grox) => $this->grox,
                (($this->grox) instanceof Foo) || (($this->grox) instanceof Bar) => match (true) {
                ($this->grox) instanceof Foo,
                ($this->grox) instanceof Bar => $this->grox,
            },
            };
        }
    }
}
