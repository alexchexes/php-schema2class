<?php

namespace Ns\PreservePropertyNames_5_6;

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
            '_foo',
            '__foo',
            'foo_',
            'foo__',
            '_foo_',
            '__foo__',
            'foo-bar',
            'foo bar',
            'baz qux',
            '123 qwe',
            'Город',
            'название юр.лица',
            'IP-адрес',
            '~~tildas~~',
        ],
        'properties' => [
            'foo' => [
                'type' => 'string',
            ],
            '_foo' => [
                'type' => 'string',
            ],
            '__foo' => [
                'type' => 'string',
            ],
            'foo_' => [
                'type' => 'string',
            ],
            'foo__' => [
                'type' => 'string',
            ],
            '_foo_' => [
                'type' => 'string',
            ],
            '__foo__' => [
                'type' => 'string',
            ],
            'foo-bar' => [
                'type' => 'string',
            ],
            'foo bar' => [
                'type' => 'string',
            ],
            'baz qux' => [
                'type' => 'string',
            ],
            '123 qwe' => [
                'type' => 'string',
            ],
            'Город' => [
                'type' => 'string',
            ],
            'название юр.лица' => [
                'type' => 'string',
            ],
            'IP-адрес' => [
                'type' => 'string',
            ],
            '~~tildas~~' => [
                'type' => 'string',
            ],
            'it\'s "A"' => [
                'type' => 'string',
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
        '_foo' => '_foo',
        '__foo' => '__foo',
        'foo_' => 'foo_',
        'foo__' => 'foo__',
        '_foo_' => '_foo_',
        '__foo__' => '__foo__',
        'foo-bar' => '_foo_bar',
        'foo bar' => 'foo_bar',
        'baz qux' => 'baz_qux',
        '123 qwe' => '_123_qwe',
        'Город' => 'Gorod',
        'название юр.лица' => 'nazvanie_iur_litsa',
        'IP-адрес' => 'IP_adres',
        '~~tildas~~' => '_tildas',
        'it\'s "A"' => 'it_s_A',
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
    private $_foo;

    /**
     * @var string
     */
    private $__foo;

    /**
     * @var string
     */
    private $foo_;

    /**
     * @var string
     */
    private $foo__;

    /**
     * @var string
     */
    private $_foo_;

    /**
     * @var string
     */
    private $__foo__;

    /**
     * @var string
     */
    private $_foo_bar;

    /**
     * @var string
     */
    private $foo_bar;

    /**
     * @var string
     */
    private $baz_qux;

    /**
     * @var string
     */
    private $_123_qwe;

    /**
     * @var string
     */
    private $Gorod;

    /**
     * @var string
     */
    private $nazvanie_iur_litsa;

    /**
     * @var string
     */
    private $IP_adres;

    /**
     * @var string
     */
    private $_tildas;

    /**
     * @var string|null
     */
    private $it_s_A = null;

    /**
     * @param string $foo
     * @param string $_foo
     * @param string $__foo
     * @param string $foo_
     * @param string $foo__
     * @param string $_foo_
     * @param string $__foo__
     * @param string $_foo_bar
     * @param string $foo_bar
     * @param string $baz_qux
     * @param string $_123_qwe
     * @param string $Gorod
     * @param string $nazvanie_iur_litsa
     * @param string $IP_adres
     * @param string $_tildas
     * @param string|null $it_s_A
     */
    public function __construct(
        $foo,
        $_foo,
        $__foo,
        $foo_,
        $foo__,
        $_foo_,
        $__foo__,
        $_foo_bar,
        $foo_bar,
        $baz_qux,
        $_123_qwe,
        $Gorod,
        $nazvanie_iur_litsa,
        $IP_adres,
        $_tildas,
        $it_s_A = null
    ) {
        $this->_additionalProperties = new \stdClass();

        $this->foo = $foo;
        $this->_foo = $_foo;
        $this->__foo = $__foo;
        $this->foo_ = $foo_;
        $this->foo__ = $foo__;
        $this->_foo_ = $_foo_;
        $this->__foo__ = $__foo__;
        $this->_foo_bar = $_foo_bar;
        $this->foo_bar = $foo_bar;
        $this->baz_qux = $baz_qux;
        $this->_123_qwe = $_123_qwe;
        $this->Gorod = $Gorod;
        $this->nazvanie_iur_litsa = $nazvanie_iur_litsa;
        $this->IP_adres = $IP_adres;
        $this->_tildas = $_tildas;
        $this->it_s_A = $it_s_A;
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
    public function get_Foo()
    {
        return $this->_foo;
    }

    /**
     * @param string $_foo
     * @param bool $validate
     * @return self
     */
    public function with_Foo($_foo, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_foo, self::$_schema['properties']['_foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_foo = $_foo;

        return $clone;
    }

    /**
     * @return string
     */
    public function get__Foo()
    {
        return $this->__foo;
    }

    /**
     * @param string $__foo
     * @param bool $validate
     * @return self
     */
    public function with__Foo($__foo, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__foo, self::$_schema['properties']['__foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__foo = $__foo;

        return $clone;
    }

    /**
     * @return string
     */
    public function getFoo_()
    {
        return $this->foo_;
    }

    /**
     * @param string $foo_
     * @param bool $validate
     * @return self
     */
    public function withFoo_($foo_, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo_, self::$_schema['properties']['foo_']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->foo_ = $foo_;

        return $clone;
    }

    /**
     * @return string
     */
    public function getFoo__()
    {
        return $this->foo__;
    }

    /**
     * @param string $foo__
     * @param bool $validate
     * @return self
     */
    public function withFoo__($foo__, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo__, self::$_schema['properties']['foo__']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->foo__ = $foo__;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_Foo_()
    {
        return $this->_foo_;
    }

    /**
     * @param string $_foo_
     * @param bool $validate
     * @return self
     */
    public function with_Foo_($_foo_, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_foo_, self::$_schema['properties']['_foo_']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_foo_ = $_foo_;

        return $clone;
    }

    /**
     * @return string
     */
    public function get__Foo__()
    {
        return $this->__foo__;
    }

    /**
     * @param string $__foo__
     * @param bool $validate
     * @return self
     */
    public function with__Foo__($__foo__, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__foo__, self::$_schema['properties']['__foo__']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__foo__ = $__foo__;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_FooBar()
    {
        return $this->_foo_bar;
    }

    /**
     * @param string $_foo_bar
     * @param bool $validate
     * @return self
     */
    public function with_FooBar($_foo_bar, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_foo_bar, self::$_schema['properties']['foo-bar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_foo_bar = $_foo_bar;

        return $clone;
    }

    /**
     * @return string
     */
    public function getFooBar()
    {
        return $this->foo_bar;
    }

    /**
     * @param string $foo_bar
     * @param bool $validate
     * @return self
     */
    public function withFooBar($foo_bar, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo_bar, self::$_schema['properties']['foo bar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->foo_bar = $foo_bar;

        return $clone;
    }

    /**
     * @return string
     */
    public function getBazQux()
    {
        return $this->baz_qux;
    }

    /**
     * @param string $baz_qux
     * @param bool $validate
     * @return self
     */
    public function withBazQux($baz_qux, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($baz_qux, self::$_schema['properties']['baz qux']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->baz_qux = $baz_qux;

        return $clone;
    }

    /**
     * @return string
     */
    public function get_123Qwe()
    {
        return $this->_123_qwe;
    }

    /**
     * @param string $_123_qwe
     * @param bool $validate
     * @return self
     */
    public function with_123Qwe($_123_qwe, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_123_qwe, self::$_schema['properties']['123 qwe']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_123_qwe = $_123_qwe;

        return $clone;
    }

    /**
     * @return string
     */
    public function getGorod()
    {
        return $this->Gorod;
    }

    /**
     * @param string $Gorod
     * @param bool $validate
     * @return self
     */
    public function withGorod($Gorod, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($Gorod, self::$_schema['properties']['Город']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->Gorod = $Gorod;

        return $clone;
    }

    /**
     * @return string
     */
    public function getNazvanieIurLitsa()
    {
        return $this->nazvanie_iur_litsa;
    }

    /**
     * @param string $nazvanie_iur_litsa
     * @param bool $validate
     * @return self
     */
    public function withNazvanieIurLitsa($nazvanie_iur_litsa, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($nazvanie_iur_litsa, self::$_schema['properties']['название юр.лица']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->nazvanie_iur_litsa = $nazvanie_iur_litsa;

        return $clone;
    }

    /**
     * @return string
     */
    public function getIPAdres()
    {
        return $this->IP_adres;
    }

    /**
     * @param string $IP_adres
     * @param bool $validate
     * @return self
     */
    public function withIPAdres($IP_adres, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($IP_adres, self::$_schema['properties']['IP-адрес']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->IP_adres = $IP_adres;

        return $clone;
    }

    /**
     * @return string
     */
    public function getTildas()
    {
        return $this->_tildas;
    }

    /**
     * @param string $_tildas
     * @param bool $validate
     * @return self
     */
    public function withTildas($_tildas, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_tildas, self::$_schema['properties']['~~tildas~~']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_tildas = $_tildas;

        return $clone;
    }

    /**
     * @return string|null
     */
    public function getItSA()
    {
        return isset($this->it_s_A) ? $this->it_s_A : null;
    }

    /**
     * @param string $it_s_A
     * @param bool $validate
     * @return self
     */
    public function withItSA($it_s_A, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($it_s_A, self::$_schema['properties']['it\'s "A"']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->it_s_A = $it_s_A;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutItSA()
    {
        $clone = clone $this;
        unset($clone->it_s_A);

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
        $_foo = $input->{'_foo'};
        $__foo = $input->{'__foo'};
        $foo_ = $input->{'foo_'};
        $foo__ = $input->{'foo__'};
        $_foo_ = $input->{'_foo_'};
        $__foo__ = $input->{'__foo__'};
        $_foo_bar = $input->{'foo-bar'};
        $foo_bar = $input->{'foo bar'};
        $baz_qux = $input->{'baz qux'};
        $_123_qwe = $input->{'123 qwe'};
        $Gorod = $input->{'Город'};
        $nazvanie_iur_litsa = $input->{'название юр.лица'};
        $IP_adres = $input->{'IP-адрес'};
        $_tildas = $input->{'~~tildas~~'};
        $it_s_A = isset($input->{'it\'s "A"'}) ? $input->{'it\'s "A"'} : null;

        $obj = new self(
            $foo,
            $_foo,
            $__foo,
            $foo_,
            $foo__,
            $_foo_,
            $__foo__,
            $_foo_bar,
            $foo_bar,
            $baz_qux,
            $_123_qwe,
            $Gorod,
            $nazvanie_iur_litsa,
            $IP_adres,
            $_tildas,
            $it_s_A
        );

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

        $output['foo'] = $this->foo;
        $output['_foo'] = $this->_foo;
        $output['__foo'] = $this->__foo;
        $output['foo_'] = $this->foo_;
        $output['foo__'] = $this->foo__;
        $output['_foo_'] = $this->_foo_;
        $output['__foo__'] = $this->__foo__;
        $output['foo-bar'] = $this->_foo_bar;
        $output['foo bar'] = $this->foo_bar;
        $output['baz qux'] = $this->baz_qux;
        $output['123 qwe'] = $this->_123_qwe;
        $output['Город'] = $this->Gorod;
        $output['название юр.лица'] = $this->nazvanie_iur_litsa;
        $output['IP-адрес'] = $this->IP_adres;
        $output['~~tildas~~'] = $this->_tildas;
        if (isset($this->it_s_A)) {
            $output['it\'s "A"'] = $this->it_s_A;
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

        $output->{'foo'} = $this->foo;
        $output->{'_foo'} = $this->_foo;
        $output->{'__foo'} = $this->__foo;
        $output->{'foo_'} = $this->foo_;
        $output->{'foo__'} = $this->foo__;
        $output->{'_foo_'} = $this->_foo_;
        $output->{'__foo__'} = $this->__foo__;
        $output->{'foo-bar'} = $this->_foo_bar;
        $output->{'foo bar'} = $this->foo_bar;
        $output->{'baz qux'} = $this->baz_qux;
        $output->{'123 qwe'} = $this->_123_qwe;
        $output->{'Город'} = $this->Gorod;
        $output->{'название юр.лица'} = $this->nazvanie_iur_litsa;
        $output->{'IP-адрес'} = $this->IP_adres;
        $output->{'~~tildas~~'} = $this->_tildas;
        if (isset($this->it_s_A)) {
            $output->{'it\'s "A"'} = $this->it_s_A;
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
}
