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
     * Map of name/value pairs for properties not specified in the schema.
     *
     * @var \stdClass
     */
    private $_additionalProperties;

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
        $this->_additionalProperties = new \stdClass();

        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->qux = $qux;
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
        $clone = clone $this;
        $clone->bar = $bar;
        if ($validate) {
            $clone->validate();
        }
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
        $clone = clone $this;
        $clone->baz = $baz;
        if ($validate) {
            $clone->validate();
        }
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
        $clone = clone $this;
        $clone->qux = $qux;
        if ($validate) {
            $clone->validate();
        }
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
        $qux = ($input->{'qux'} !== null ? ((is_string($input->{'qux'})) ? $input->{'qux'} : (((is_string($input->{'qux'})) ? $input->{'qux'} : (((is_string($input->{'qux'})) ? $input->{'qux'} : (((is_string($input->{'qux'})) ? $input->{'qux'} : (null)))))))) : null);

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
        if ((is_string($this->qux)) || (is_string($this->qux)) || (is_string($this->qux)) || (is_string($this->qux))) {
            $output['qux'] = $this->qux;
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
        if ((is_string($this->qux)) || (is_string($this->qux)) || (is_string($this->qux)) || (is_string($this->qux))) {
        $output->{'qux'} = $this->qux;
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
        $this->foo = (is_string($this->foo) ? $this->foo : (is_string($this->foo) ? $this->foo : $this->foo));
        $this->bar = (is_string($this->bar) ? $this->bar : (is_string($this->bar) ? $this->bar : $this->bar));
        $this->baz = (is_string($this->baz) ? $this->baz : (is_string($this->baz) ? $this->baz : $this->baz));
        $this->qux = (is_string($this->qux) ? $this->qux : (is_string($this->qux) ? $this->qux : (is_string($this->qux) ? $this->qux : (is_string($this->qux) ? $this->qux : $this->qux))));
    }
}
