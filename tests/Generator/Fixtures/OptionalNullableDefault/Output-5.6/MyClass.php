<?php

namespace Ns\OptionalNullableDefault_5_6;

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
     * required, not nullable, no default
     *
     * @var string
     */
    private $foo;

    /**
     * optional, not nullable, no default
     *
     * @var string|null
     */
    private $bar = null;

    /**
     * optional, nullable, no default
     *
     * @var string|null
     */
    private $baz = null;

    /**
     * optional, nullable, with default
     *
     * @var string|null
     */
    private $qux = null;

    /**
     * required, nullable, with default
     *
     * @var string|null
     */
    private $quux;

    /**
     * optional, not nullable, with default
     *
     * @var string|null
     */
    private $xyyz = null;

    /**
     * required, not nullable, with default
     *
     * @var string
     */
    private $thud;

    /**
     * optional, nullable, with default, object
     *
     * @var MyClassGrox|null
     */
    private $grox = null;

    /**
     * optional, nullable, with default, object, and default is empty object
     *
     * @var MyClassGooks|null
     */
    private $gooks = null;

    /**
     * @param string $foo
     * @param string|null $quux
     * @param string $thud
     */
    public function __construct($foo, $quux, $thud)
    {
        $this->foo = $foo;
        $this->quux = $quux;
        $this->thud = $thud;
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
     * optional, not nullable, no default
     *
     * @return string|null
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * optional, nullable, no default
     *
     * @return string|null
     */
    public function getBaz()
    {
        return $this->baz;
    }

    /**
     * optional, nullable, with default
     *
     * @return string|null
     */
    public function getQux()
    {
        return $this->qux;
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
     * optional, not nullable, with default
     *
     * @return string|null
     */
    public function getXyyz()
    {
        return $this->xyyz;
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
     * optional, nullable, with default, object
     *
     * @return MyClassGrox|null
     */
    public function getGrox()
    {
        return $this->grox;
    }

    /**
     * optional, nullable, with default, object, and default is empty object
     *
     * @return MyClassGooks|null
     */
    public function getGooks()
    {
        return $this->gooks;
    }

    /**
     * @param string $foo
     * @return self
     * @param bool $validate
     */
    public function withFoo($foo, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo, self::$schema['properties']['foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    /**
     * @param string $bar
     * @return self
     * @param bool $validate
     */
    public function withBar($bar, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($bar, self::$schema['properties']['bar']);
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
     * @param string $baz
     * @return self
     * @param bool $validate
     */
    public function withBaz($baz, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($baz, self::$schema['properties']['baz']);
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
     * @param string $qux
     * @return self
     * @param bool $validate
     */
    public function withQux($qux, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($qux, self::$schema['properties']['qux']);
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
     * @param string $quux
     * @return self
     * @param bool $validate
     */
    public function withQuux($quux, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($quux, self::$schema['properties']['quux']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->quux = $quux;

        return $clone;
    }

    /**
     * @param string $xyyz
     * @return self
     * @param bool $validate
     */
    public function withXyyz($xyyz, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($xyyz, self::$schema['properties']['xyyz']);
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
     * @param string $thud
     * @return self
     * @param bool $validate
     */
    public function withThud($thud, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($thud, self::$schema['properties']['thud']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->thud = $thud;

        return $clone;
    }

    /**
     * @param MyClassGrox $grox
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
     * @param MyClassGooks $gooks
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
        $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;
        $baz = property_exists($input, 'baz') ? $input->{'baz'} : null;
        if (property_exists($input, 'baz')) {
            $__providedOptionals['baz'] = true;
        }
        $qux = property_exists($input, 'qux') ? $input->{'qux'} : null;
        if (property_exists($input, 'qux')) {
            $__providedOptionals['qux'] = true;
        }
        $quux = $input->{'quux'};
        $xyyz = isset($input->{'xyyz'}) ? $input->{'xyyz'} : null;
        $thud = $input->{'thud'};
        $grox = property_exists($input, 'grox') ? MyClassGrox::buildFromInput($input->{'grox'}, $validate, $materializeDefaults) : null;
        if (property_exists($input, 'grox')) {
            $__providedOptionals['grox'] = true;
        }
        $gooks = property_exists($input, 'gooks') ? MyClassGooks::buildFromInput($input->{'gooks'}, $validate, $materializeDefaults) : null;
        if (property_exists($input, 'gooks')) {
            $__providedOptionals['gooks'] = true;
        }

        $obj = new self($foo, $quux, $thud);
        $obj->bar = $bar;
        $obj->baz = $baz;
        $obj->qux = $qux;
        $obj->xyyz = $xyyz;
        $obj->grox = $grox;
        $obj->gooks = $gooks;
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
            $output['baz'] = $this->baz;
        }
        if (isset($this->qux) || array_key_exists('qux', $this->_providedOptionals)) {
            $output['qux'] = $this->qux;
        }
        $output['quux'] = $this->quux;
        if (isset($this->xyyz)) {
            $output['xyyz'] = $this->xyyz;
        }
        $output['thud'] = $this->thud;
        if (isset($this->grox) || array_key_exists('grox', $this->_providedOptionals)) {
            if (isset($this->grox)) {
                $output['grox'] = ($this->grox)->toArray($includeDefaults);
            }
        }
        if (isset($this->gooks) || array_key_exists('gooks', $this->_providedOptionals)) {
            if (isset($this->gooks)) {
                $output['gooks'] = ($this->gooks)->toArray($includeDefaults);
            }
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
            $output->{'baz'} = $this->baz;
        }
        if (isset($this->qux) || array_key_exists('qux', $this->_providedOptionals)) {
            $output->{'qux'} = $this->qux;
        }
        $output->{'quux'} = $this->quux;
        if (isset($this->xyyz)) {
            $output->{'xyyz'} = $this->xyyz;
        }
        $output->{'thud'} = $this->thud;
        if (isset($this->grox) || array_key_exists('grox', $this->_providedOptionals)) {
            if (isset($this->grox)) {
                $output->{'grox'} = ($this->grox)->toStdClass($includeDefaults);
            }
        }
        if (isset($this->gooks) || array_key_exists('gooks', $this->_providedOptionals)) {
            if (isset($this->gooks)) {
                $output->{'gooks'} = ($this->gooks)->toStdClass($includeDefaults);
            }
        }

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!property_exists($output, $k)) {
                    $output->{$k} = (isset($v['type']) && $v['type'] === 'object')
                       ? \JsonSchema\Validator::arrayToObjectRecursive($v['default'])
                       : $v['default'];
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
    public function isProvidedOptional(string $propertyName)
    {
        return array_key_exists($propertyName, $this->_providedOptionals);
    }
}
