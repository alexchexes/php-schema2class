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
        ],
        'required' => [
            'foo',
            'quux',
            'thud',
        ],
    ];

    /**
     * Optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private $_explicitlySet = [];

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
    private $qux = 'a qux string';

    /**
     * required, nullable, with default
     *
     * @var string|null
     */
    private $quux = 'a quux string';

    /**
     * optional, not nullable, with default
     *
     * @var string|null
     */
    private $xyyz = 'a xyyz string';

    /**
     * required, not nullable, with default
     *
     * @var string
     */
    private $thud = 'a thud string';

    /**
     * optional, nullable, with default, object
     *
     * @var MyClassGrox|null
     */
    private $grox = [
        'a' => 'a string',
        'b' => 123,
    ];

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
     * @return string
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
     * @return string
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
        $clone->_explicitlySet['baz'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBaz()
    {
        $clone = clone $this;
        unset($clone->baz);
        unset($clone->_explicitlySet['baz']);

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
        $clone->_explicitlySet['qux'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutQux()
    {
        $clone = clone $this;
        $clone->qux = 'a qux string';
        unset($clone->_explicitlySet['qux']);

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
        $clone->xyyz = 'a xyyz string';

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
        $clone->_explicitlySet['grox'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutGrox()
    {
        $clone = clone $this;
        $clone->grox = [
                'a' => 'a string',
                'b' => 123,
            ];
        unset($clone->_explicitlySet['grox']);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput($input, bool $validate = true)
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

        $__explicitlySet = [];
        $foo = $input->{'foo'};
        $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;
        $baz = property_exists($input, 'baz') ? $input->{'baz'} : null;
        if (property_exists($input, 'baz')) {
            $__explicitlySet['baz'] = true;
        }
        $qux = property_exists($input, 'qux') ? $input->{'qux'} : 'a qux string';
        if (property_exists($input, 'qux')) {
            $__explicitlySet['qux'] = true;
        }
        $quux = $input->{'quux'};
        $xyyz = isset($input->{'xyyz'}) ? $input->{'xyyz'} : 'a xyyz string';
        $thud = $input->{'thud'};
        $grox = property_exists($input, 'grox') ? MyClassGrox::buildFromInput($input->{'grox'}, $validate) : [
                'a' => 'a string',
                'b' => 123,
            ];
        if (property_exists($input, 'grox')) {
            $__explicitlySet['grox'] = true;
        }

        $obj = new self($foo, $quux, $thud);
        $obj->bar = $bar;
        $obj->baz = $baz;
        $obj->qux = $qux;
        $obj->xyyz = $xyyz;
        $obj->grox = $grox;
        $obj->_explicitlySet = $__explicitlySet;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray()
    {
        $output = [];
        $output['foo'] = $this->foo;
        if (isset($this->bar)) {
            $output['bar'] = $this->bar;
        }
        if (isset($this->baz) || array_key_exists('baz', $this->_explicitlySet)) {
            $output['baz'] = $this->baz;
        }
        if (isset($this->qux) || array_key_exists('qux', $this->_explicitlySet)) {
            $output['qux'] = $this->qux;
        }
        $output['quux'] = $this->quux;
        if (isset($this->xyyz)) {
            $output['xyyz'] = $this->xyyz;
        }
        $output['thud'] = $this->thud;
        if (isset($this->grox) || array_key_exists('grox', $this->_explicitlySet)) {
            if (isset($this->grox)) {
                $output['grox'] = ($this->grox)->toArray();
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
    }

    /**
     * Checks if an optional nullable property was set
     *
     * @param string $propertyName
     * @return bool
     */
    public function isDefined(string $propertyName)
    {
        return array_key_exists($propertyName, $this->_explicitlySet);
    }
}
