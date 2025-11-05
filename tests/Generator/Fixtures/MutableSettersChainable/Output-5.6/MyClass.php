<?php

namespace Ns\MutableSettersChainable_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
        'required' => [
            'bar',
        ],
        'properties' => [
            'foo' => [
                'type' => 'string',
            ],
            'bar' => [
                '$ref' => '#/definitions/Baz',
            ],
            'opt' => [
                'type' => [
                    'string',
                    'null',
                ],
            ],
        ],
        'definitions' => [
            'Baz' => [
                'type' => 'object',
                'properties' => [
                    'name' => [
                        'type' => 'string',
                    ],
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
        'opt' => 'opt',
    ];

    /**
     * Map of optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private $_providedOptionals = [];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     *
     * @var \stdClass
     */
    private $_additionalProperties;

    /**
     * @var string|null
     */
    private $foo = null;

    private $bar;

    /**
     * @var string|null
     */
    private $opt = null;

    /**
     * @param string|null $foo
     * @param string|null $opt
     */
    public function __construct(Baz $bar, $foo = null, $opt = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->bar = $bar;
        $this->foo = $foo;
        if ($opt !== null) {
            $this->opt = $opt;
            $this->_providedOptionals['opt'] = true;
        };
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
    public function setAdditionalProperties($additionalProperties)
    {
        $this->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        return $this;
    }

    /**
     * Removes all extra properties not specified in the schema.
     *
     * @return self
     */
    public function unsetAdditionalProperties()
    {
        $this->_additionalProperties = new \stdClass();
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFoo()
    {
        return isset($this->foo) ? $this->foo : null;
    }

    /**
     * @param string $foo
     * @param bool $validate
     * @return self
     */
    public function setFoo($foo, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo, self::$_schema['properties']['foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $this->foo = $foo;

        return $this;
    }

    /**
     * @return self
     */
    public function unsetFoo()
    {
        unset($this->foo);

        return $this;
    }

    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @return self
     */
    public function setBar(Baz $bar)
    {
        $this->bar = $bar;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOpt()
    {
        return isset($this->opt) ? $this->opt : null;
    }

    /**
     * @param string|null $opt
     * @param bool $validate
     * @return self
     */
    public function setOpt($opt, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($opt, self::$_schema['properties']['opt']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $this->opt = $opt;
        $this->_providedOptionals['opt'] = true;

        return $this;
    }

    /**
     * @return self
     */
    public function unsetOpt()
    {
        unset($this->opt);
        unset($this->_providedOptionals['opt']);

        return $this;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput($input, $validate = true)
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to fromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $_providedOptionals = [];
        $bar = Baz::fromInput($input->{'bar'}, $validate);
        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $opt = null;
        if (property_exists($input, 'opt')) {
            $opt = $input->{'opt'};
            $_providedOptionals['opt'] = true;
        }

        $obj = new self($bar, $foo, $opt);
        $obj->_providedOptionals = $_providedOptionals;

        $_additionalProperties = array_diff_key(get_object_vars($input), self::$_namesMap);
        if (!empty($_additionalProperties)) {
            $obj->_additionalProperties = (object) $_additionalProperties;
        }

        return $obj;
    }

    /**
     * Converts this object to array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray()
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

        if (isset($this->foo)) {
            $output['foo'] = $this->foo;
        }
        $output['bar'] = $this->bar->toArray();
        if (isset($this->opt) || array_key_exists('opt', $this->_providedOptionals)) {
            $output['opt'] = $this->opt;
        }

        return $output;
    }

    /**
     * Converts this object to a stdClass that can be JSON-serialized
     *
     * @return \stdClass Converted object
     */
    public function toStdClass()
    {
        $output = $this->_additionalProperties;

        if (isset($this->foo)) {
            $output->{'foo'} = $this->foo;
        }
        $output->{'bar'} = $this->bar->toStdClass();
        if (isset($this->opt) || array_key_exists('opt', $this->_providedOptionals)) {
            $output->{'opt'} = $this->opt;
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

        $this->bar = clone $this->bar;
    }

    /**
     * Checks if an optional nullable property was explicitly set.
     *
     * @param string $propertyName Property name to check (exactly as it appears in the schema).
     * @throws \InvalidArgumentException If property with that name doesn't exist.
     * @return bool
     */
    public function isOptionalProvided(string $propertyName)
    {
        if (!array_key_exists($propertyName, self::$_namesMap)) {
            throw new \InvalidArgumentException("Unknown property: {$propertyName}");
        }
        return
            array_key_exists($propertyName, $this->_providedOptionals)
            || isset($this->{ self::$_namesMap[$propertyName] });
    }
}
