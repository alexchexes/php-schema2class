<?php

namespace Ns\UnionCollapsing_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
        'required' => [
            'foo',
            'bar',
            'baz',
            'qux',
        ],
        'properties' => [
            'foo' => [
                'oneOf' => [
                    [
                        'type' => 'string',
                        'format' => 'uuid',
                        'description' => 'Description of \'uuid\' string',
                    ],
                    [
                        'type' => 'string',
                        'description' => 'Description of \'maxLength\' string',
                        'maxLength' => 0,
                        'deprecated' => true,
                    ],
                ],
            ],
            'bar' => [
                'oneOf' => [
                    [
                        '$ref' => '#/definitions/FooDef',
                    ],
                    [
                        '$ref' => '#/definitions/BarDef',
                    ],
                ],
            ],
            'baz' => [
                'anyOf' => [
                    [
                        '$ref' => '#/definitions/FooDef',
                    ],
                    [
                        '$ref' => '#/definitions/BarDef',
                    ],
                ],
            ],
            'qux' => [
                'oneOf' => [
                    [
                        'type' => 'string',
                        'format' => 'uuid',
                        'description' => 'Description of \'uuid\' string',
                    ],
                    [
                        'type' => [
                            'string',
                            'null',
                        ],
                        'description' => 'Description of \'maxLength\' string',
                        'maxLength' => 0,
                        'deprecated' => true,
                    ],
                    [
                        '$ref' => '#/definitions/FooDef',
                    ],
                    [
                        '$ref' => '#/definitions/BarDef',
                    ],
                ],
            ],
        ],
        'definitions' => [
            'FooDef' => [
                'type' => 'string',
                'format' => 'uuid',
                'description' => 'Description of a definition of \'uuid\' string',
            ],
            'BarDef' => [
                'type' => 'string',
                'description' => 'Description of a definition of \'maxLength\' string',
                'maxLength' => 0,
                'deprecated' => true,
            ],
        ],
    ];

    /**
     * @var string
     */
    private $foo;

    /**
     * @var string
     */
    private $bar;

    /**
     * @var string
     */
    private $baz;

    /**
     * @var string|null
     */
    private $qux;

    /**
     * @param string $foo
     * @param string $bar
     * @param string $baz
     * @param string|null $qux
     */
    public function __construct($foo, $bar, $baz, $qux)
    {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->qux = $qux;
    }

    /**
     * @return string
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
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
     * @return string
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
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
     * @return string
     */
    public function getBaz()
    {
        return $this->baz;
    }

    /**
     * @param string $baz
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

        return $clone;
    }

    /**
     * @return string|null
     */
    public function getQux()
    {
        return $this->qux;
    }

    /**
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

        return $clone;
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

        $foo = $input->{'foo'};
        $bar = $input->{'bar'};
        $baz = $input->{'baz'};
        $qux = ($input->{'qux'} !== null ? ((is_string($input->{'qux'})) ? $input->{'qux'} : (((is_string($input->{'qux'})) ? $input->{'qux'} : ((((($input->{'qux'}) === null) || (is_string($input->{'qux'}))) ? ($input->{'qux'} !== null ? $input->{'qux'} : null) : (((is_string($input->{'qux'})) ? $input->{'qux'} : (null)))))))) : null);

        $obj = new self($foo, $bar, $baz, $qux);
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
        if ((is_string($this->foo)) || (is_string($this->foo))) {
            $output['foo'] = $this->foo;
        }
        if ((is_string($this->bar)) || (is_string($this->bar))) {
            $output['bar'] = $this->bar;
        }
        if ((is_string($this->baz)) || (is_string($this->baz))) {
            $output['baz'] = $this->baz;
        }
        if ((is_string($this->qux)) || (is_string($this->qux)) || (is_string($this->qux))) {
            $output['qux'] = $this->qux;
        } else if (((($this->qux) === null) || (is_string($this->qux)))) {
            $output['qux'] = ($this->qux !== null) ? ($this->qux) : null;
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
        $output = new \stdClass();
        if ((is_string($this->foo)) || (is_string($this->foo))) {
        $output->{'foo'} = $this->foo;
        }
        if ((is_string($this->bar)) || (is_string($this->bar))) {
        $output->{'bar'} = $this->bar;
        }
        if ((is_string($this->baz)) || (is_string($this->baz))) {
        $output->{'baz'} = $this->baz;
        }
        if ((is_string($this->qux)) || (is_string($this->qux)) || (is_string($this->qux))) {
        $output->{'qux'} = $this->qux;
        } else if (((($this->qux) === null) || (is_string($this->qux)))) {
        $output->{'qux'} = ($this->qux !== null) ? ($this->qux) : null;
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
        $this->foo = (is_string($this->foo)) ? ($this->foo) : ((is_string($this->foo)) ? ($this->foo) : ($this->foo));
        $this->bar = (is_string($this->bar)) ? ($this->bar) : ((is_string($this->bar)) ? ($this->bar) : ($this->bar));
        $this->baz = (is_string($this->baz)) ? ($this->baz) : ((is_string($this->baz)) ? ($this->baz) : ($this->baz));
        $this->qux = (is_string($this->qux)) ? ($this->qux) : ((is_string($this->qux)) ? ($this->qux) : (((($this->qux) === null) || (is_string($this->qux))) ? (isset($this->qux) ? (clone $this->qux) : null) : ((is_string($this->qux)) ? ($this->qux) : ($this->qux))));
    }
}
