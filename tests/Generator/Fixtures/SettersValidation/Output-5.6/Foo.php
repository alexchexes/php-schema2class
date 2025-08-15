<?php

namespace Ns\SettersValidation_5_6;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
        'type' => 'object',
        'properties' => [
            'a' => [
                'anyOf' => [
                    [
                        'type' => 'string',
                        'enum' => [
                            'a',
                            'b',
                        ],
                    ],
                    [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                        ],
                        'minItems' => 1,
                    ],
                ],
                'description' => 'Should have validation block',
            ],
            'b' => [
                'type' => 'array',
                'description' => 'Should not have validation block',
            ],
            'c' => [
                'type' => [
                    'number',
                    'null',
                ],
                'description' => 'Should not have validation block',
            ],
            'd' => [
                '$ref' => '#/definitions/Bar',
                'description' => 'Should not have validation block due to presence of type-hint that restricts value to \'Bar\' class instances',
            ],
        ],
        'definitions' => [
            'Bar' => [
                'type' => 'object',
                'properties' => [
                    'a' => [
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
        'a' => 'a',
        'b' => 'b',
        'c' => 'c',
        'd' => 'd',
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
     * @var 'a'|'b'|string[]|null
     */
    private $a = null;

    /**
     * @var array|null
     */
    private $b = null;

    /**
     * @var int|float|null
     */
    private $c = null;

    /**
     * @var Bar|null
     */
    private $d = null;

    /**
     * @param 'a'|'b'|string[]|null $a
     * @param array|null $b
     * @param int|float|null $c
     * @param Bar|null $d
     */
    public function __construct($a = null, array $b = null, $c = null, Bar $d = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->a = $a;
        $this->b = $b;
        if ($c !== null) {
            $this->c = $c;
            $this->_providedOptionals['c'] = true;
        };
        $this->d = $d;
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
     * Should have validation block
     *
     * @return 'a'|'b'|string[]|null
     */
    public function getA()
    {
        return isset($this->a) ? $this->a : null;
    }

    /**
     * Should have validation block
     *
     * @param 'a'|'b'|string[] $a
     * @param bool $validate
     * @return self
     */
    public function withA($a, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($a, self::$_schema['properties']['a']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->a = $a;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutA()
    {
        $clone = clone $this;
        unset($clone->a);

        return $clone;
    }

    /**
     * Should not have validation block
     *
     * @return array|null
     */
    public function getB()
    {
        return isset($this->b) ? $this->b : null;
    }

    /**
     * Should not have validation block
     *
     * @return self
     */
    public function withB(array $b)
    {
        $clone = clone $this;
        $clone->b = $b;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutB()
    {
        $clone = clone $this;
        unset($clone->b);

        return $clone;
    }

    /**
     * Should not have validation block
     *
     * @return int|float|null
     */
    public function getC()
    {
        return isset($this->c) ? $this->c : null;
    }

    /**
     * Should not have validation block
     *
     * @param int|float|null $c
     * @param bool $validate
     * @return self
     */
    public function withC($c, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($c, self::$_schema['properties']['c']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->c = $c;
        $clone->_providedOptionals['c'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutC()
    {
        $clone = clone $this;
        unset($clone->c);
        unset($clone->_providedOptionals['c']);

        return $clone;
    }

    /**
     * Should not have validation block due to presence of type-hint that restricts value to 'Bar' class instances
     *
     * @return Bar|null
     */
    public function getD()
    {
        return isset($this->d) ? $this->d : null;
    }

    /**
     * Should not have validation block due to presence of type-hint that restricts value to 'Bar' class instances
     *
     * @return self
     */
    public function withD(Bar $d)
    {
        $clone = clone $this;
        $clone->d = $d;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutD()
    {
        $clone = clone $this;
        unset($clone->d);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Foo Created instance
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
        $a = isset($input->{'a'})
            ? (is_array($input->{'a'})
                ? $input->{'a'}
                : (in_array($input->{'a'}, ['a', 'b'], true) ? $input->{'a'} : null)
            )
            : null;
        $b = isset($input->{'b'}) ? $input->{'b'} : null;
        $c = null;
        if (property_exists($input, 'c')) {
            $c = ($input->{'c'} !== null ? $input->{'c'} : null);
            $_providedOptionals['c'] = true;
        }
        $d = isset($input->{'d'}) ? Bar::fromInput($input->{'d'}, $validate) : null;

        $obj = new self($a, $b, $c, $d);
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

        if (isset($this->a)) {
            if (in_array($this->a, ['a', 'b'], true) || is_array($this->a)) {
                $output['a'] = $this->a;
            }
        }
        if (isset($this->b)) {
            $output['b'] = $this->b;
        }
        if (isset($this->c) || array_key_exists('c', $this->_providedOptionals)) {
            $output['c'] = ($this->c !== null ? $this->c : null);
        }
        if (isset($this->d)) {
            $output['d'] = $this->d->toArray();
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

        if (isset($this->a)) {
            if (in_array($this->a, ['a', 'b'], true) || is_array($this->a)) {
                $output->{'a'} = $this->a;
            }
        }
        if (isset($this->b)) {
            $output->{'b'} = $this->b;
        }
        if (isset($this->c) || array_key_exists('c', $this->_providedOptionals)) {
            $output->{'c'} = ($this->c !== null ? $this->c : null);
        }
        if (isset($this->d)) {
            $output->{'d'} = $this->d->toStdClass();
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
        if (isset($this->a)) {
            $this->a = (is_array($this->a)
                ? $this->a
                : (in_array($this->a, ['a', 'b'], true) ? $this->a : $this->a)
            );
        }
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
