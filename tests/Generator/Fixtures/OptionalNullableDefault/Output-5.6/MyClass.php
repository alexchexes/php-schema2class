<?php

namespace Ns\OptionalNullableDefault_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
        'type' => 'object',
        'properties' => [
            'foo' => [
                'type' => 'string',
                'description' => 'required, not nullable, no default',
            ],
            'bar' => [
                'type' => 'string',
                'description' => 'optional, not nullable, no default',
            ],
            'baz' => [
                'type' => [
                    'string',
                    'null',
                ],
                'description' => 'optional, nullable, no default',
            ],
            'qux' => [
                'type' => [
                    'string',
                    'null',
                ],
                'default' => 'a qux string',
                'description' => 'optional, nullable, with default',
            ],
            'quux' => [
                'type' => [
                    'string',
                    'null',
                ],
                'default' => 'a quux string',
                'description' => 'required, nullable, with default',
            ],
            'xyyz' => [
                'type' => 'string',
                'default' => 'a xyyz string',
                'description' => 'optional, not nullable, with default',
            ],
            'thud' => [
                'type' => 'string',
                'default' => 'a thud string',
                'description' => 'required, not nullable, with default',
            ],
            'grox' => [
                'type' => [
                    'object',
                    'null',
                ],
                'description' => 'optional, nullable, with default, object',
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                    'b' => [
                        'type' => 'number',
                    ],
                ],
                'default' => [
                    'a' => 'a string',
                    'b' => 123,
                ],
            ],
            'gooks' => [
                'type' => [
                    'object',
                    'null',
                ],
                'description' => 'optional, nullable, with default, object, and default is empty object',
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                    'b' => [
                        'type' => 'number',
                    ],
                ],
                'default' => [
                    
                ],
            ],
        ],
        'required' => [
            'foo',
            'quux',
            'thud',
        ],
    ];

    /**
     * Default values from the schema
     *
     * @var array
     */
    private static $_defaults = [
        'qux' => [
            'default' => 'a qux string',
        ],
        'quux' => [
            'default' => 'a quux string',
        ],
        'xyyz' => [
            'default' => 'a xyyz string',
        ],
        'thud' => [
            'default' => 'a thud string',
        ],
        'grox' => [
            'default' => [
                'a' => 'a string',
                'b' => 123,
            ],
            'type' => 'object',
        ],
        'gooks' => [
            'default' => [
                
            ],
            'type' => 'object',
        ],
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
     * @var object
     */
    private $_additionalProperties;

    /**
     * @var string
     */
    private $foo;

    /**
     * @var string|null
     */
    private $bar = null;

    /**
     * @var string|null
     */
    private $baz = null;

    /**
     * @var string|null
     */
    private $qux = null;

    /**
     * @var string|null
     */
    private $quux;

    /**
     * @var string|null
     */
    private $xyyz = null;

    /**
     * @var string
     */
    private $thud;

    /**
     * @var MyClassGrox|null
     */
    private $grox = null;

    /**
     * @var MyClassGooks|null
     */
    private $gooks = null;

    /**
     * @param string $foo
     * @param string|null $quux
     * @param string $thud
     * @param string|null $bar
     * @param string|null $baz
     * @param string|null $qux
     * @param string|null $xyyz
     * @param MyClassGrox|null $grox
     * @param MyClassGooks|null $gooks
     */
    public function __construct($foo, $quux, $thud, $bar = null, $baz = null, $qux = null, $xyyz = null, MyClassGrox $grox = null, MyClassGooks $gooks = null)
    {
        $this->foo = $foo;
        $this->quux = $quux;
        $this->thud = $thud;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->qux = $qux;
        $this->xyyz = $xyyz;
        $this->grox = $grox;
        $this->gooks = $gooks;
    }

    /**
     * Object or array containing name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return array instead of `stdClass` object.
     * @return array|object
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
     * @param array|object $additionalProperties Map of property name/value pairs to add.
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
        $clone->_additionalProperties = new \stdClass;
        return $clone;
    }

    /**
     * required, not nullable, no default
     *
     * @return string
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * required, not nullable, no default
     *
     * @param string $foo
     * @param bool $validate
     * @return self
     */
    public function withFoo($foo, $validate = true)
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
     * optional, not nullable, no default
     *
     * @return string|null
     */
    public function getBar()
    {
        return isset($this->bar) ? $this->bar : null;
    }

    /**
     * optional, not nullable, no default
     *
     * @param string $bar
     * @param bool $validate
     * @return self
     */
    public function withBar($bar, $validate = true)
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
     * optional, nullable, no default
     *
     * @return string|null
     */
    public function getBaz()
    {
        return isset($this->baz) ? $this->baz : null;
    }

    /**
     * optional, nullable, no default
     *
     * @param string|null $baz
     * @param bool $validate
     * @return self
     */
    public function withBaz($baz, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($baz, self::$_schema['properties']['baz']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->baz = $baz;
        $clone->_providedOptionals['baz'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBaz()
    {
        $clone = clone $this;
        unset($clone->baz);
        unset($clone->_providedOptionals['baz']);

        return $clone;
    }

    /**
     * optional, nullable, with default
     *
     * @return string|null
     */
    public function getQux()
    {
        return isset($this->qux) ? $this->qux : null;
    }

    /**
     * optional, nullable, with default
     *
     * @param string|null $qux
     * @param bool $validate
     * @return self
     */
    public function withQux($qux, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($qux, self::$_schema['properties']['qux']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->qux = $qux;
        $clone->_providedOptionals['qux'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutQux()
    {
        $clone = clone $this;
        unset($clone->qux);
        unset($clone->_providedOptionals['qux']);

        return $clone;
    }

    /**
     * required, nullable, with default
     *
     * @return string|null
     */
    public function getQuux()
    {
        return $this->quux;
    }

    /**
     * required, nullable, with default
     *
     * @param string|null $quux
     * @param bool $validate
     * @return self
     */
    public function withQuux($quux, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($quux, self::$_schema['properties']['quux']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->quux = $quux;

        return $clone;
    }

    /**
     * optional, not nullable, with default
     *
     * @return string|null
     */
    public function getXyyz()
    {
        return isset($this->xyyz) ? $this->xyyz : null;
    }

    /**
     * optional, not nullable, with default
     *
     * @param string $xyyz
     * @param bool $validate
     * @return self
     */
    public function withXyyz($xyyz, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($xyyz, self::$_schema['properties']['xyyz']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->xyyz = $xyyz;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutXyyz()
    {
        $clone = clone $this;
        unset($clone->xyyz);

        return $clone;
    }

    /**
     * required, not nullable, with default
     *
     * @return string
     */
    public function getThud()
    {
        return $this->thud;
    }

    /**
     * required, not nullable, with default
     *
     * @param string $thud
     * @param bool $validate
     * @return self
     */
    public function withThud($thud, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($thud, self::$_schema['properties']['thud']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->thud = $thud;

        return $clone;
    }

    /**
     * optional, nullable, with default, object
     *
     * @return MyClassGrox|null
     */
    public function getGrox()
    {
        return isset($this->grox) ? $this->grox : null;
    }

    /**
     * optional, nullable, with default, object
     *
     * @param MyClassGrox|null $grox
     * @return self
     */
    public function withGrox(MyClassGrox $grox)
    {
        $clone = clone $this;
        $clone->grox = $grox;
        $clone->_providedOptionals['grox'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutGrox()
    {
        $clone = clone $this;
        unset($clone->grox);
        unset($clone->_providedOptionals['grox']);

        return $clone;
    }

    /**
     * optional, nullable, with default, object, and default is empty object
     *
     * @return MyClassGooks|null
     */
    public function getGooks()
    {
        return isset($this->gooks) ? $this->gooks : null;
    }

    /**
     * optional, nullable, with default, object, and default is empty object
     *
     * @param MyClassGooks|null $gooks
     * @return self
     */
    public function withGooks(MyClassGooks $gooks)
    {
        $clone = clone $this;
        $clone->gooks = $gooks;
        $clone->_providedOptionals['gooks'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutGooks()
    {
        $clone = clone $this;
        unset($clone->gooks);
        unset($clone->_providedOptionals['gooks']);

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

        $__providedOptionals = [];
        $foo = $input->{'foo'};
        $quux = $input->{'quux'};
        $thud = $input->{'thud'};
        $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;
        $baz = null;
        if (property_exists($input, 'baz')) {
            $baz = ($input->{'baz'} !== null ? $input->{'baz'} : null);
            $__providedOptionals['baz'] = true;
        }
        $qux = null;
        if (property_exists($input, 'qux')) {
            $qux = ($input->{'qux'} !== null ? $input->{'qux'} : null);
            $__providedOptionals['qux'] = true;
        }
        $xyyz = isset($input->{'xyyz'}) ? $input->{'xyyz'} : null;
        $grox = null;
        if (property_exists($input, 'grox')) {
            $grox = ($input->{'grox'} !== null ? MyClassGrox::fromInput($input->{'grox'}, $validate, $materializeDefaults) : null);
            $__providedOptionals['grox'] = true;
        }
        $gooks = null;
        if (property_exists($input, 'gooks')) {
            $gooks = ($input->{'gooks'} !== null ? MyClassGooks::fromInput($input->{'gooks'}, $validate, $materializeDefaults) : null);
            $__providedOptionals['gooks'] = true;
        }

        $obj = new self($foo, $quux, $thud, $bar, $baz, $qux, $xyyz, $grox, $gooks);
        $obj->_providedOptionals = $__providedOptionals;
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
        $output['foo'] = $this->foo;
        if (isset($this->bar)) {
            $output['bar'] = $this->bar;
        }
        if (isset($this->baz) || array_key_exists('baz', $this->_providedOptionals)) {
            $output['baz'] = ($this->baz !== null) ? ($this->baz) : null;
        }
        if (isset($this->qux) || array_key_exists('qux', $this->_providedOptionals)) {
            $output['qux'] = ($this->qux !== null) ? ($this->qux) : null;
        }
        $output['quux'] = $this->quux;
        if (isset($this->xyyz)) {
            $output['xyyz'] = $this->xyyz;
        }
        $output['thud'] = $this->thud;
        if (isset($this->grox) || array_key_exists('grox', $this->_providedOptionals)) {
            $output['grox'] = ($this->grox !== null) ? ($this->grox->toArray($includeDefaults)) : null;
        }
        if (isset($this->gooks) || array_key_exists('gooks', $this->_providedOptionals)) {
            $output['gooks'] = ($this->gooks !== null) ? ($this->gooks->toArray($includeDefaults)) : null;
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
        $output = new \stdClass();
        $output->{'foo'} = $this->foo;
        if (isset($this->bar)) {
            $output->{'bar'} = $this->bar;
        }
        if (isset($this->baz) || array_key_exists('baz', $this->_providedOptionals)) {
            $output->{'baz'} = ($this->baz !== null) ? ($this->baz) : null;
        }
        if (isset($this->qux) || array_key_exists('qux', $this->_providedOptionals)) {
            $output->{'qux'} = ($this->qux !== null) ? ($this->qux) : null;
        }
        $output->{'quux'} = $this->quux;
        if (isset($this->xyyz)) {
            $output->{'xyyz'} = $this->xyyz;
        }
        $output->{'thud'} = $this->thud;
        if (isset($this->grox) || array_key_exists('grox', $this->_providedOptionals)) {
            $output->{'grox'} = ($this->grox !== null) ? ($this->grox->toStdClass($includeDefaults)) : null;
        }
        if (isset($this->gooks) || array_key_exists('gooks', $this->_providedOptionals)) {
            $output->{'gooks'} = ($this->gooks !== null) ? ($this->gooks->toStdClass($includeDefaults)) : null;
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
            $errors = array_map(function($e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }

    public function __clone()
    {
        if (isset($this->grox)) {
            if (isset($this->grox)) {
                $this->grox = clone $this->grox;
            }
        }
        if (isset($this->gooks)) {
            if (isset($this->gooks)) {
                $this->gooks = clone $this->gooks;
            }
        }
    }

    /**
     * Checks if an optional nullable property was explicitly set
     *
     * @param string $propertyName Property name to check (exactly as it appears in the schema)
     * @return bool
     */
    public function isOptionalProvided(string $propertyName)
    {
        return array_key_exists($propertyName, $this->_providedOptionals);
    }
}
