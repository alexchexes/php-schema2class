<?php

namespace Ns\AnyOfDefault_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
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
    private static $_defaults = [
        'foo' => 'hello',
    ];

    /**
     * @var string|int|null
     */
    private $foo = null;

    /**
     * @return string|int|null
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param string|int $foo
     * @return self
     */
    public function withFoo($foo)
    {
        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutFoo()
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
    public static function buildFromInput($input, bool $validate = true, bool $materializeDefaults = false)
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to buildFromInput must be array or object, got ' . gettype($input)
            );
        }

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

        $foo = isset($input->{'foo'}) ? (is_int($input->{'foo'})) ? ((int)($input->{'foo'})) : ((is_string($input->{'foo'})) ? ($input->{'foo'}) : (null)) : null;

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
    public function toArray(bool $includeDefaults = false)
    {
        $output = [];
        if (isset($this->foo)) {
            if ((is_string($this->foo)) || (is_int($this->foo))) {
                $output['foo'] = $this->foo;
            }
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
    public function toStdClass(bool $includeDefaults = false)
    {
        $output = new \stdClass();
        if (isset($this->foo)) {
            if ((is_string($this->foo)) || (is_int($this->foo))) {
            $output->{'foo'} = $this->foo;
            }
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
    public static function validateInput($input, $return = false)
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function($e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }

    public function __clone()
    {
        if (isset($this->foo)) {
            $this->foo = (is_int($this->foo)) ? ($this->foo) : ((is_string($this->foo)) ? ($this->foo) : ($this->foo));
        }
    }
}
