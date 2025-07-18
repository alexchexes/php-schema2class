<?php

declare(strict_types=1);

namespace Ns\OptionalNullableDefault_8_4;

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
     * Default values defined in the schema
     *
     * @var array
     */
    private static array $_defaults = [
        'qux' => 'a qux string',
        'quux' => 'a quux string',
        'xyyz' => 'a xyyz string',
        'thud' => 'a thud string',
        'grox' => [
            'a' => 'a string',
            'b' => 123,
        ],
    ];

    /**
     * Optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private array $_explicitlySet = [];

    /**
     * required, not nullable, no default
     *
     * @var string
     */
    private string $foo;

    /**
     * optional, not nullable, no default
     *
     * @var string|null
     */
    private ?string $bar = null;

    /**
     * optional, nullable, no default
     *
     * @var string|null
     */
    private ?string $baz = null;

    /**
     * optional, nullable, with default
     *
     * @var string|null
     */
    private ?string $qux = null;

    /**
     * required, nullable, with default
     *
     * @var string|null
     */
    private ?string $quux;

    /**
     * optional, not nullable, with default
     *
     * @var string|null
     */
    private ?string $xyyz = null;

    /**
     * required, not nullable, with default
     *
     * @var string
     */
    private string $thud;

    /**
     * optional, nullable, with default, object
     *
     * @var MyClassGrox|null
     */
    private ?MyClassGrox $grox = null;

    /**
     * @param string $foo
     * @param string|null $quux
     * @param string $thud
     */
    public function __construct(string $foo, ?string $quux, string $thud)
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
    public function getFoo() : string
    {
        return $this->foo;
    }

    /**
     * optional, not nullable, no default
     *
     * @return string|null
     */
    public function getBar() : ?string
    {
        return $this->bar ?? null;
    }

    /**
     * optional, nullable, no default
     *
     * @return string|null
     */
    public function getBaz() : ?string
    {
        return $this->baz ?? null;
    }

    /**
     * optional, nullable, with default
     *
     * @return string|null
     */
    public function getQux() : ?string
    {
        return $this->qux ?? null;
    }

    /**
     * required, nullable, with default
     *
     * @return string|null
     */
    public function getQuux() : ?string
    {
        return $this->quux ?? null;
    }

    /**
     * optional, not nullable, with default
     *
     * @return string|null
     */
    public function getXyyz() : ?string
    {
        return $this->xyyz ?? null;
    }

    /**
     * required, not nullable, with default
     *
     * @return string
     */
    public function getThud() : string
    {
        return $this->thud;
    }

    /**
     * optional, nullable, with default, object
     *
     * @return MyClassGrox|null
     */
    public function getGrox() : ?MyClassGrox
    {
        return $this->grox ?? null;
    }

    /**
     * @param string $foo
     * @return self
     * @param bool $validate
     */
    public function withFoo(string $foo, bool $validate = true) : self
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
    public function withBar(string $bar, bool $validate = true) : self
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
    public function withoutBar() : self
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
    public function withBaz(string $baz, bool $validate = true) : self
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
    public function withoutBaz() : self
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
    public function withQux(string $qux, bool $validate = true) : self
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
    public function withoutQux() : self
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
    public function withQuux(?string $quux, bool $validate = true) : self
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
    public function withXyyz(string $xyyz, bool $validate = true) : self
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
    public function withoutXyyz() : self
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
    public function withThud(string $thud, bool $validate = true) : self
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
    public function withGrox(?MyClassGrox $grox) : self
    {
        $clone = clone $this;
        $clone->grox = $grox;
        $clone->_explicitlySet['grox'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutGrox() : self
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
     * @param bool $materializeDefaults Apply defaults defined in schema when missing
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true, bool $materializeDefaults = false) : MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($materializeDefaults) {
            foreach (self::$_defaults as $__k => $__v) {
                if (!property_exists($input, $__k)) {
                    $input->{$__k} = is_array($__v) ? \JsonSchema\Validator::arrayToObjectRecursive($__v) : $__v;
                }
            }
        }
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
        $qux = property_exists($input, 'qux') ? $input->{'qux'} : null;
        if (property_exists($input, 'qux')) {
            $__explicitlySet['qux'] = true;
        }
        $quux = $input->{'quux'};
        $xyyz = isset($input->{'xyyz'}) ? $input->{'xyyz'} : null;
        $thud = $input->{'thud'};
        $grox = property_exists($input, 'grox') ? MyClassGrox::buildFromInput($input->{'grox'}, validate: $validate) : null;
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
     * @param bool $includeDefaults Add defaults for missing properties
     * @return array Converted array
     */
    public function toArray(bool $includeDefaults = false) : array
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

        if ($includeDefaults) {
            foreach (self::$_defaults as $k => $v) {
                if (!array_key_exists($k, $output)) {
                    $output[$k] = $v;
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
    public function isDefined(string $propertyName) : bool
    {
        return array_key_exists($propertyName, $this->_explicitlySet);
    }
}
