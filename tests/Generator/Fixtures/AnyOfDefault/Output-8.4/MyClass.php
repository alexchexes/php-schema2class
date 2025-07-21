<?php

declare(strict_types=1);

namespace Ns\AnyOfDefault_8_4;

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
                'anyOf' => [
                    [
                        'type' => 'string',
                        'default' => 'hello',
                    ],
                    [
                        'type' => 'integer',
                        'default' => 42,
                    ],
                ],
            ],
        ],
    ];

    /**
     * Default values from the schema
     *
     * @var array
     */
    private static array $_defaults = [
        'foo' => 'hello',
    ];

    /**
     * @var string|int|null
     */
    private string|int|null $foo = null;

    /**
     * @return string|int|null
     */
    public function getFoo(): int|string|null
    {
        return $this->foo;
    }

    /**
     * @param string|int $foo
     * @return self
     */
    public function withFoo(int|string $foo): self
    {
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
                    if (is_array($__v) && array_key_exists('default', $__v)) {
                        $input->{$__k} = (isset($__v['type']) && $__v['type'] === 'object') ? \JsonSchema\Validator::arrayToObjectRecursive($__v['default']) : $__v['default'];
                    } else {
                        $input->{$__k} = is_array($__v) ? \JsonSchema\Validator::arrayToObjectRecursive($__v) : $__v;
                    }
                }
            }
        }

        if ($validate) {
            static::validateInput($input);
        }

        $foo = isset($input->{'foo'}) ? match (true) {
            is_string($input->{'foo'}) => $input->{'foo'},
            is_int($input->{'foo'}) => (int)($input->{'foo'}),
            default => null,
        } : null;

        $obj = new self();
        $obj->foo = $foo;
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
            $output['foo'] = match (true) {
                is_string($this->foo),
                is_int($this->foo) => $this->foo,
            };
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!array_key_exists($k, $output)) {
                    if (is_array($v) && array_key_exists('default', $v)) {
                        $output[$k] = $v['default'];
                    } else {
                        $output[$k] = $v;
                    }
                }
            }
        }

        return $output;
    }

    /**
     * Converts this object to a stdClass that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return \stdClass Converted object
     */
    public function toStdClass(bool $includeDefaults = false): \stdClass
    {
        $output = new \stdClass();
        if (isset($this->foo)) {
            $output->{'foo'} = match (true) {
                is_string($this->foo),
                is_int($this->foo) => $this->foo,
            };
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!property_exists($output, $k)) {
                    if (is_array($v) && array_key_exists('default', $v)) {
                        $output->{$k} = (isset($v['type']) && $v['type'] === 'object') ? \JsonSchema\Validator::arrayToObjectRecursive($v['default']) : $v['default'];
                    } else {
                        $output->{$k} = is_array($v) ? \JsonSchema\Validator::arrayToObjectRecursive($v) : $v;
                    }
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

    public function __clone()
    {
        if (isset($this->foo)) {
            $this->foo = match (true) {
                is_string($this->foo),
                is_int($this->foo) => $this->foo,
            };
        }
    }
}
