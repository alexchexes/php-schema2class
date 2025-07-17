<?php

declare(strict_types=1);

namespace Ns\MutableSetters_7_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
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
     * @var array
     */
    private $_optionalNullableSet = [
        
    ];

    /**
     * @var string|null
     */
    private ?string $foo = null;

    /**
     * @var Baz
     */
    private Baz $bar;

    /**
     * @var string|null
     */
    private ?string $opt = null;

    /**
     * @param Baz $bar
     */
    public function __construct(Baz $bar)
    {
        $this->bar = $bar;
    }

    /**
     * @return string|null
     */
    public function getFoo() : ?string
    {
        return $this->foo ?? null;
    }

    /**
     * @return Baz
     */
    public function getBar() : Baz
    {
        return $this->bar;
    }

    /**
     * @return string|null
     */
    public function getOpt() : ?string
    {
        return $this->opt ?? null;
    }

    /**
     * @param string $foo
     * @param bool $validate
     */
    public function setFoo(string $foo, bool $validate = true) : void
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo, self::$schema['properties']['foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $this->foo = $foo;
    }

    /**
     * @param Baz $bar
     */
    public function setBar(Baz $bar) : void
    {
        $this->bar = $bar;
    }

    /**
     * @param string $opt
     * @param bool $validate
     */
    public function setOpt(string $opt, bool $validate = true) : void
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($opt, self::$schema['properties']['opt']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $this->opt = $opt;
        $this->_optionalNullableSet['opt'] = true;
    }

    /**
     *
     */
    public function unsetOpt() : void
    {
        $this->opt = null;
        unset($this->_optionalNullableSet['opt']);
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput($input, bool $validate = true) : MyClass
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

        $__optNullables = [];
        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $bar = Baz::buildFromInput($input->{'bar'}, $validate);
        $opt = property_exists($input, 'opt') ? $input->{'opt'} : null;
        if (property_exists($input, 'opt')) { $__optNullables['opt'] = true; }

        $obj = new self($bar);
        $obj->foo = $foo;
        $obj->opt = $opt;
        $obj->_optionalNullableSet = $__optNullables;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray() : array
    {
        $output = [];
        if (isset($this->foo)) {
            $output['foo'] = $this->foo;
        }
        $output['bar'] = $this->bar->toArray();
        if (isset($this->opt) || array_key_exists('opt', $this->_optionalNullableSet)) {
            $output['opt'] = $this->opt;
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
    public static function validateInput($input, bool $return = false) : bool
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

    /**
     * @param string $propertyName
     * @return bool
     */
    public function isSet(string $propertyName) : bool
    {
        return array_key_exists($propertyName, $this->_optionalNullableSet);
    }
}
