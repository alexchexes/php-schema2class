<?php

namespace Ns\EnumMixed_5_6;

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
                'type' => [
                    'integer',
                    'string',
                ],
                'enum' => [
                    1,
                    2,
                    '1',
                    '2',
                ],
            ],
            'bar' => [
                'enum' => [
                    3,
                    4,
                    '3',
                    '4',
                ],
            ],
            'baz' => [
                'enum' => [
                    'red',
                    'amber',
                    'green',
                    '42',
                    42,
                    42.5,
                    false,
                    null,
                ],
            ],
            'inferString' => [
                'enum' => [
                    '3',
                    '4',
                ],
            ],
            'inferInt' => [
                'enum' => [
                    3,
                    4,
                ],
            ],
            'contradiction' => [
                'type' => 'integer',
                'enum' => [
                    1,
                    'one',
                    false,
                    null,
                ],
            ],
            'contradiction2' => [
                'type' => [
                    'string',
                    'integer',
                    'number',
                ],
                'enum' => [
                    1,
                    2,
                    'one',
                    false,
                    null,
                ],
            ],
            'nullable' => [
                'type' => [
                    'string',
                    null,
                ],
                'enum' => [
                    'red',
                    'green',
                ],
            ],
            'optionalNullable' => [
                'type' => [
                    'string',
                    null,
                ],
                'enum' => [
                    'red',
                    'green',
                ],
            ],
        ],
        'required' => [
            'foo',
            'bar',
            'baz',
            'contradiction',
            'contradiction2',
            'nullable',
        ],
    ];

    /**
     * Map of optional nullable property names that were explicitly set
     *
     * @var array<string,true>
     */
    private $_providedOptionals = [];

    /**
     * @var 1|2|'1'|'2'
     */
    private $foo;

    /**
     * @var 3|4|'3'|'4'
     */
    private $bar;

    /**
     * @var 'red'|'amber'|'green'|'42'|42|42.5|false|null
     */
    private $baz;

    /**
     * @var '3'|'4'|null
     */
    private $inferString = null;

    /**
     * @var 3|4|null
     */
    private $inferInt = null;

    /**
     * @var 1
     */
    private $contradiction;

    /**
     * @var 1|2|'one'
     */
    private $contradiction2;

    /**
     * @var 'red'|'green'|null
     */
    private $nullable;

    /**
     * @var 'red'|'green'|null
     */
    private $optionalNullable = null;

    /**
     * @param 1|2|'1'|'2' $foo
     * @param 3|4|'3'|'4' $bar
     * @param 'red'|'amber'|'green'|'42'|42|42.5|false|null $baz
     * @param 1 $contradiction
     * @param 1|2|'one' $contradiction2
     * @param 'red'|'green'|null $nullable
     * @param '3'|'4'|null $inferString
     * @param 3|4|null $inferInt
     * @param 'red'|'green'|null $optionalNullable
     */
    public function __construct($foo, $bar, $baz, $contradiction, $contradiction2, $nullable, $inferString = null, $inferInt = null, $optionalNullable = null)
    {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->contradiction = $contradiction;
        $this->contradiction2 = $contradiction2;
        $this->nullable = $nullable;
        $this->inferString = $inferString;
        $this->inferInt = $inferInt;
        $this->optionalNullable = $optionalNullable;
    }

    /**
     * @return 1|2|'1'|'2'
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param 1|2|'1'|'2' $foo
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
     * @return 3|4|'3'|'4'
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param 3|4|'3'|'4' $bar
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
     * @return 'red'|'amber'|'green'|'42'|42|42.5|false|null
     */
    public function getBaz()
    {
        return $this->baz;
    }

    /**
     * @param 'red'|'amber'|'green'|'42'|42|42.5|false|null $baz
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
     * @return '3'|'4'|null
     */
    public function getInferString()
    {
        return $this->inferString;
    }

    /**
     * @param '3'|'4' $inferString
     * @param bool $validate
     * @return self
     */
    public function withInferString($inferString, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inferString, self::$_schema['properties']['inferString']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->inferString = $inferString;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutInferString()
    {
        $clone = clone $this;
        unset($clone->inferString);

        return $clone;
    }

    /**
     * @return 3|4|null
     */
    public function getInferInt()
    {
        return $this->inferInt;
    }

    /**
     * @param 3|4 $inferInt
     * @param bool $validate
     * @return self
     */
    public function withInferInt($inferInt, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inferInt, self::$_schema['properties']['inferInt']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->inferInt = $inferInt;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutInferInt()
    {
        $clone = clone $this;
        unset($clone->inferInt);

        return $clone;
    }

    /**
     * @return 1
     */
    public function getContradiction()
    {
        return $this->contradiction;
    }

    /**
     * @param 1 $contradiction
     * @param bool $validate
     * @return self
     */
    public function withContradiction($contradiction, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($contradiction, self::$_schema['properties']['contradiction']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->contradiction = $contradiction;

        return $clone;
    }

    /**
     * @return 1|2|'one'
     */
    public function getContradiction2()
    {
        return $this->contradiction2;
    }

    /**
     * @param 1|2|'one' $contradiction2
     * @param bool $validate
     * @return self
     */
    public function withContradiction2($contradiction2, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($contradiction2, self::$_schema['properties']['contradiction2']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->contradiction2 = $contradiction2;

        return $clone;
    }

    /**
     * @return 'red'|'green'|null
     */
    public function getNullable()
    {
        return $this->nullable;
    }

    /**
     * @param 'red'|'green'|null $nullable
     * @param bool $validate
     * @return self
     */
    public function withNullable($nullable, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($nullable, self::$_schema['properties']['nullable']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->nullable = $nullable;

        return $clone;
    }

    /**
     * @return 'red'|'green'|null
     */
    public function getOptionalNullable()
    {
        return $this->optionalNullable;
    }

    /**
     * @param 'red'|'green'|null $optionalNullable
     * @param bool $validate
     * @return self
     */
    public function withOptionalNullable($optionalNullable, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($optionalNullable, self::$_schema['properties']['optionalNullable']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->optionalNullable = $optionalNullable;
        $clone->_providedOptionals['optionalNullable'] = true;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptionalNullable()
    {
        $clone = clone $this;
        unset($clone->optionalNullable);
        unset($clone->_providedOptionals['optionalNullable']);

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

        $__providedOptionals = [];
        $foo = $input->{'foo'};
        $bar = $input->{'bar'};
        $baz = ($input->{'baz'} !== null ? ((is_bool($input->{'baz'})) ? (bool)$input->{'baz'} : (((in_array($input->{'baz'}, array (
          0 => 42,
          1 => 42.5,
        ), true)) ? (str_contains((string)$input->{'baz'}, '.') ? (float)$input->{'baz'} : (int)$input->{'baz'}) : (((in_array($input->{'baz'}, array (
          0 => 42,
        ), true)) ? $input->{'baz'} : (((in_array($input->{'baz'}, array (
          0 => 'red',
          1 => 'amber',
          2 => 'green',
          3 => '42',
        ), true)) ? $input->{'baz'} : (null)))))))) : null);
        $inferString = isset($input->{'inferString'}) ? $input->{'inferString'} : null;
        $inferInt = isset($input->{'inferInt'}) ? $input->{'inferInt'} : null;
        $contradiction = $input->{'contradiction'};
        $contradiction2 = $input->{'contradiction2'};
        $nullable = ($input->{'nullable'} !== null ? $input->{'nullable'} : null);
        $optionalNullable = null;
        if (property_exists($input, 'optionalNullable')) {
            $optionalNullable = ($input->{'optionalNullable'} !== null ? $input->{'optionalNullable'} : null);
            $__providedOptionals['optionalNullable'] = true;
        }

        $obj = new self($foo, $bar, $baz, $contradiction, $contradiction2, $nullable);
        $obj->inferString = $inferString;
        $obj->inferInt = $inferInt;
        $obj->optionalNullable = $optionalNullable;
        $obj->_providedOptionals = $__providedOptionals;
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
        $output['bar'] = $this->bar;
        if ((in_array($this->baz, array (
          0 => 'red',
          1 => 'amber',
          2 => 'green',
          3 => '42',
        ), true)) || (in_array($this->baz, array (
          0 => 42,
        ), true)) || (in_array($this->baz, array (
          0 => 42,
          1 => 42.5,
        ), true)) || (is_bool($this->baz))) {
            $output['baz'] = $this->baz;
        }
        if (isset($this->inferString)) {
            $output['inferString'] = $this->inferString;
        }
        if (isset($this->inferInt)) {
            $output['inferInt'] = $this->inferInt;
        }
        $output['contradiction'] = $this->contradiction;
        $output['contradiction2'] = $this->contradiction2;
        $output['nullable'] = $this->nullable;
        if (isset($this->optionalNullable) || array_key_exists('optionalNullable', $this->_providedOptionals)) {
            $output['optionalNullable'] = ($this->optionalNullable !== null) ? ($this->optionalNullable) : null;
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
        $output->{'foo'} = $this->foo;
        $output->{'bar'} = $this->bar;
        if ((in_array($this->baz, array (
          0 => 'red',
          1 => 'amber',
          2 => 'green',
          3 => '42',
        ), true)) || (in_array($this->baz, array (
          0 => 42,
        ), true)) || (in_array($this->baz, array (
          0 => 42,
          1 => 42.5,
        ), true)) || (is_bool($this->baz))) {
        $output->{'baz'} = $this->baz;
        }
        if (isset($this->inferString)) {
            $output->{'inferString'} = $this->inferString;
        }
        if (isset($this->inferInt)) {
            $output->{'inferInt'} = $this->inferInt;
        }
        $output->{'contradiction'} = $this->contradiction;
        $output->{'contradiction2'} = $this->contradiction2;
        $output->{'nullable'} = $this->nullable;
        if (isset($this->optionalNullable) || array_key_exists('optionalNullable', $this->_providedOptionals)) {
            $output->{'optionalNullable'} = ($this->optionalNullable !== null) ? ($this->optionalNullable) : null;
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
        $this->baz = (is_bool($this->baz)) ? ($this->baz) : ((in_array($this->baz, array (
          0 => 42,
          1 => 42.5,
        ), true)) ? ($this->baz) : ((in_array($this->baz, array (
          0 => 42,
        ), true)) ? ($this->baz) : ((in_array($this->baz, array (
          0 => 'red',
          1 => 'amber',
          2 => 'green',
          3 => '42',
        ), true)) ? ($this->baz) : ($this->baz))));
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
