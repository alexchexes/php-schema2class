<?php

namespace Ns\ObjectArrayProperty_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
        'properties' => [
            'foo' => [
                'type' => 'array',
                'items' => [
                    'properties' => [
                        'name' => [
                            'type' => 'string',
                            'default' => 'a string',
                        ],
                    ],
                ],
                'default' => [
                    [
                        'name' => 'foo',
                    ],
                ],
                'minItems' => 1,
                'maxItems' => 1,
            ],
            'bar' => [
                'type' => 'array',
                'items' => [
                    'properties' => [
                        'name' => [
                            'type' => 'string',
                        ],
                    ],
                ],
                'default' => [
                    
                ],
            ],
        ],
    ];

    /**
     * Mapping of schema property names to this class's property names.
     *
     * @var array
     */
    private static $_namesMap = [
        'foo' => 'foo',
        'bar' => 'bar',
    ];

    /**
     * Default values from the schema
     *
     * @var array
     */
    private static $_defaults = [
        'foo' => [
            'default' => [
                [
                    'name' => 'foo',
                ],
            ],
            'type' => 'array',
        ],
        'bar' => [
            'default' => [
                
            ],
            'type' => 'array',
        ],
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     *
     * @var \stdClass
     */
    private $_additionalProperties;

    /**
     * @var MyClassFooItem[]|null
     */
    private $foo = null;

    /**
     * @var MyClassBarItem[]|null
     */
    private $bar = null;

    /**
     * @param MyClassFooItem[]|null $foo
     * @param MyClassBarItem[]|null $bar
     */
    public function __construct(array $foo = null, array $bar = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->foo = $foo;
        $this->bar = $bar;
    }

    /**
     * Object (`stdClass`) or array with name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return an associative array instead of `stdClass` object.
     * @return array|\stdClass
     */
    public function getAdditionalProperties($asArray = true)
    {
        return $asArray
            ? json_decode(json_encode($this->_additionalProperties), true)
            : $this->_additionalProperties;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param \stdClass|array $additionalProperties Map of property name/value pairs to add.
     * @return self
     */
    public function withAdditionalProperties($additionalProperties)
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        return $clone;
    }

    /**
     * Removes all extra properties not specified in the schema.
     *
     * @return self
     */
    public function withoutAdditionalProperties()
    {
        $clone = clone $this;
        $clone->_additionalProperties = new \stdClass();
        return $clone;
    }

    /**
     * @return MyClassFooItem[]|null
     */
    public function getFoo()
    {
        return isset($this->foo) ? $this->foo : null;
    }

    /**
     * @param MyClassFooItem[] $foo
     * @param bool $validate
     * @return self
     */
    public function withFoo(array $foo, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo, self::$_schema['properties']['foo']);
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
    public function withoutFoo()
    {
        $clone = clone $this;
        unset($clone->foo);

        return $clone;
    }

    /**
     * @return MyClassBarItem[]|null
     */
    public function getBar()
    {
        return isset($this->bar) ? $this->bar : null;
    }

    /**
     * @param MyClassBarItem[] $bar
     * @param bool $validate
     * @return self
     */
    public function withBar(array $bar, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($bar, self::$_schema['properties']['bar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBar()
    {
        $clone = clone $this;
        unset($clone->bar);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @param bool $materializeDefaults Apply defaults defined in schema when missing
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput($input, $validate = true, $materializeDefaults = false)
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to fromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input)
            ? \JsonSchema\Validator::arrayToObjectRecursive($input)
            : ($materializeDefaults ? clone $input : $input);

        if ($materializeDefaults) {
            foreach (self::$_defaults as $__k => $__v) {
                if (!property_exists($input, (string) $__k)) {
                    $input->{$__k} = ($__v['type'] ?? null) === 'object'
                        ? \JsonSchema\Validator::arrayToObjectRecursive($__v['default'])
                        : $__v['default'];
                }
            }
        }

        if ($validate) {
            static::validateInput($input);
        }

        $foo = isset($input->{'foo'})
            ? array_map(function($i) use ($validate, $materializeDefaults) {
                return MyClassFooItem::fromInput($i, false, $materializeDefaults);
            }, $input->{'foo'})
            : null;
        $bar = isset($input->{'bar'})
            ? array_map(function($i) use ($validate, $materializeDefaults) {
                return MyClassBarItem::fromInput($i, false, $materializeDefaults);
            }, $input->{'bar'})
            : null;

        $obj = new self($foo, $bar);

        $_additionalProperties = array_diff_key(get_object_vars($input), self::$_namesMap);
        if (!empty($_additionalProperties)) {
            $obj->_additionalProperties = (object) $_additionalProperties;
        }

        return $obj;
    }

    /**
     * Converts this object to array that can be JSON-serialized
     *
     * @param bool $includeDefaults Add defaults for missing properties
     * @return array Converted array
     */
    public function toArray(bool $includeDefaults = false)
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

        if (isset($this->foo)) {
            $output['foo'] = array_map(
                function(MyClassFooItem $i) use ($includeDefaults) { return $i->toArray($includeDefaults); },
                $this->foo
            );
        }
        if (isset($this->bar)) {
            $output['bar'] = array_map(
                function(MyClassBarItem $i) use ($includeDefaults) { return $i->toArray($includeDefaults); },
                $this->bar
            );
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!array_key_exists($k, $output)) {
                    $output[$k] = $v['default'];
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
        $output = $this->_additionalProperties;

        if (isset($this->foo)) {
            $output->{'foo'} = array_map(function(MyClassFooItem $i) use ($includeDefaults) {
                return $i->toStdClass($includeDefaults);
            }, $this->foo);
        }
        if (isset($this->bar)) {
            $output->{'bar'} = array_map(function(MyClassBarItem $i) use ($includeDefaults) {
                return $i->toStdClass($includeDefaults);
            }, $this->bar);
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!property_exists($output, (string) $k)) {
                    $output->{$k} = (isset($v['type']) && $v['type'] === 'object')
                       ? \JsonSchema\Validator::arrayToObjectRecursive($v['default'])
                       : $v['default'];
                }
            }
        }

        return $output;
    }

    /**
     * Validates the current instance against its schema
     *
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result if `$return` is `true`
     * @throws \InvalidArgumentException
     */
    public function validate($return = false)
    {
        return self::validateInput($this->toStdClass(), $return);
    }

    /**
     * Validates an input array
     *
     * @param array|object $input Input data
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result if `$return` is `true`
     * @throws \InvalidArgumentException
     */
    public static function validateInput($input, $return = false)
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function(array $e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }

    public function __clone()
    {
        $this->_additionalProperties = json_decode(json_encode($this->_additionalProperties));

        if (isset($this->foo)) {
            $this->foo = array_map(
                function(MyClassFooItem $i) { return clone $i; },
                $this->foo
            );
        }
        if (isset($this->bar)) {
            $this->bar = array_map(
                function(MyClassBarItem $i) { return clone $i; },
                $this->bar
            );
        }
    }
}
