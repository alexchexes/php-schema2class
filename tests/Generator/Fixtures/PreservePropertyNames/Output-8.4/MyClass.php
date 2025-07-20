<?php

declare(strict_types=1);

namespace Ns\PreservePropertyNames_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
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
        ],
    ];

    /**
     * @var string
     */
    private string $foo;

    /**
     * @var string
     */
    private string $_foo;

    /**
     * @var string
     */
    private string $__foo;

    /**
     * @var string
     */
    private string $foo_;

    /**
     * @var string
     */
    private string $foo__;

    /**
     * @var string
     */
    private string $_foo_;

    /**
     * @var string
     */
    private string $__foo__;

    /**
     * @var string
     */
    private string $foo_bar;

    /**
     * @var string
     */
    private string $_foo_bar;

    /**
     * @var string
     */
    private string $baz_qux;

    /**
     * @var string
     */
    private string $_123_qwe;

    /**
     * @var string
     */
    private string $Gorod;

    /**
     * @var string
     */
    private string $nazvanie_iur_litsa;

    /**
     * @var string
     */
    private string $IP_adres;

    /**
     * @var string
     */
    private string $_tildas;

    /**
     * @param string $foo
     * @param string $_foo
     * @param string $__foo
     * @param string $foo_
     * @param string $foo__
     * @param string $_foo_
     * @param string $__foo__
     * @param string $foo_bar
     * @param string $_foo_bar
     * @param string $baz_qux
     * @param string $_123_qwe
     * @param string $Gorod
     * @param string $nazvanie_iur_litsa
     * @param string $IP_adres
     * @param string $_tildas
     */
    public function __construct(string $foo, string $_foo, string $__foo, string $foo_, string $foo__, string $_foo_, string $__foo__, string $foo_bar, string $_foo_bar, string $baz_qux, string $_123_qwe, string $Gorod, string $nazvanie_iur_litsa, string $IP_adres, string $_tildas)
    {
        $this->foo = $foo;
        $this->_foo = $_foo;
        $this->__foo = $__foo;
        $this->foo_ = $foo_;
        $this->foo__ = $foo__;
        $this->_foo_ = $_foo_;
        $this->__foo__ = $__foo__;
        $this->foo_bar = $foo_bar;
        $this->_foo_bar = $_foo_bar;
        $this->baz_qux = $baz_qux;
        $this->_123_qwe = $_123_qwe;
        $this->Gorod = $Gorod;
        $this->nazvanie_iur_litsa = $nazvanie_iur_litsa;
        $this->IP_adres = $IP_adres;
        $this->_tildas = $_tildas;
    }

    /**
     * @return string
     */
    public function getFoo(): string
    {
        return $this->foo;
    }

    /**
     * @return string
     */
    public function get_Foo(): string
    {
        return $this->_foo;
    }

    /**
     * @return string
     */
    public function get__Foo(): string
    {
        return $this->__foo;
    }

    /**
     * @return string
     */
    public function getFoo_(): string
    {
        return $this->foo_;
    }

    /**
     * @return string
     */
    public function getFoo__(): string
    {
        return $this->foo__;
    }

    /**
     * @return string
     */
    public function get_Foo_(): string
    {
        return $this->_foo_;
    }

    /**
     * @return string
     */
    public function get__Foo__(): string
    {
        return $this->__foo__;
    }

    /**
     * @return string
     */
    public function getFooBar(): string
    {
        return $this->foo_bar;
    }

    /**
     * @return string
     */
    public function get_FooBar(): string
    {
        return $this->_foo_bar;
    }

    /**
     * @return string
     */
    public function getBazQux(): string
    {
        return $this->baz_qux;
    }

    /**
     * @return string
     */
    public function get__123Qwe(): string
    {
        return $this->_123_qwe;
    }

    /**
     * @return string
     */
    public function getGorod(): string
    {
        return $this->Gorod;
    }

    /**
     * @return string
     */
    public function getNazvanieIurLitsa(): string
    {
        return $this->nazvanie_iur_litsa;
    }

    /**
     * @return string
     */
    public function getIPAdres(): string
    {
        return $this->IP_adres;
    }

    /**
     * @return string
     */
    public function get_Tildas(): string
    {
        return $this->_tildas;
    }

    /**
     * @param string $foo
     * @return self
     * @param bool $validate
     */
    public function withFoo(string $foo, bool $validate = true): self
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
     * @param string $_foo
     * @return self
     * @param bool $validate
     */
    public function with_Foo(string $_foo, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_foo, self::$schema['properties']['_foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_foo = $_foo;

        return $clone;
    }

    /**
     * @param string $__foo
     * @return self
     * @param bool $validate
     */
    public function with__Foo(string $__foo, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__foo, self::$schema['properties']['__foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__foo = $__foo;

        return $clone;
    }

    /**
     * @param string $foo_
     * @return self
     * @param bool $validate
     */
    public function withFoo_(string $foo_, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo_, self::$schema['properties']['foo_']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->foo_ = $foo_;

        return $clone;
    }

    /**
     * @param string $foo__
     * @return self
     * @param bool $validate
     */
    public function withFoo__(string $foo__, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo__, self::$schema['properties']['foo__']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->foo__ = $foo__;

        return $clone;
    }

    /**
     * @param string $_foo_
     * @return self
     * @param bool $validate
     */
    public function with_Foo_(string $_foo_, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_foo_, self::$schema['properties']['_foo_']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_foo_ = $_foo_;

        return $clone;
    }

    /**
     * @param string $__foo__
     * @return self
     * @param bool $validate
     */
    public function with__Foo__(string $__foo__, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($__foo__, self::$schema['properties']['__foo__']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->__foo__ = $__foo__;

        return $clone;
    }

    /**
     * @param string $foo_bar
     * @return self
     * @param bool $validate
     */
    public function withFooBar(string $foo_bar, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo_bar, self::$schema['properties']['foo-bar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->foo_bar = $foo_bar;

        return $clone;
    }

    /**
     * @param string $_foo_bar
     * @return self
     * @param bool $validate
     */
    public function with_FooBar(string $_foo_bar, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_foo_bar, self::$schema['properties']['foo bar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_foo_bar = $_foo_bar;

        return $clone;
    }

    /**
     * @param string $baz_qux
     * @return self
     * @param bool $validate
     */
    public function withBazQux(string $baz_qux, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($baz_qux, self::$schema['properties']['baz qux']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->baz_qux = $baz_qux;

        return $clone;
    }

    /**
     * @param string $_123_qwe
     * @return self
     * @param bool $validate
     */
    public function with__123Qwe(string $_123_qwe, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_123_qwe, self::$schema['properties']['123 qwe']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_123_qwe = $_123_qwe;

        return $clone;
    }

    /**
     * @param string $Gorod
     * @return self
     * @param bool $validate
     */
    public function withGorod(string $Gorod, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($Gorod, self::$schema['properties']['Город']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->Gorod = $Gorod;

        return $clone;
    }

    /**
     * @param string $nazvanie_iur_litsa
     * @return self
     * @param bool $validate
     */
    public function withNazvanieIurLitsa(string $nazvanie_iur_litsa, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($nazvanie_iur_litsa, self::$schema['properties']['название юр.лица']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->nazvanie_iur_litsa = $nazvanie_iur_litsa;

        return $clone;
    }

    /**
     * @param string $IP_adres
     * @return self
     * @param bool $validate
     */
    public function withIPAdres(string $IP_adres, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($IP_adres, self::$schema['properties']['IP-адрес']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->IP_adres = $IP_adres;

        return $clone;
    }

    /**
     * @param string $_tildas
     * @return self
     * @param bool $validate
     */
    public function with_Tildas(string $_tildas, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_tildas, self::$schema['properties']['~~tildas~~']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_tildas = $_tildas;

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
    public static function buildFromInput(array|object $input, bool $validate = true): MyClass
    {
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
        $foo_bar = $input->{'foo-bar'};
        $_foo_bar = $input->{'foo bar'};
        $baz_qux = $input->{'baz qux'};
        $_123_qwe = $input->{'123 qwe'};
        $Gorod = $input->{'Город'};
        $nazvanie_iur_litsa = $input->{'название юр.лица'};
        $IP_adres = $input->{'IP-адрес'};
        $_tildas = $input->{'~~tildas~~'};

        $obj = new self($foo, $_foo, $__foo, $foo_, $foo__, $_foo_, $__foo__, $foo_bar, $_foo_bar, $baz_qux, $_123_qwe, $Gorod, $nazvanie_iur_litsa, $IP_adres, $_tildas);

        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = [];
        $output['foo'] = $this->foo;
        $output['_foo'] = $this->_foo;
        $output['__foo'] = $this->__foo;
        $output['foo_'] = $this->foo_;
        $output['foo__'] = $this->foo__;
        $output['_foo_'] = $this->_foo_;
        $output['__foo__'] = $this->__foo__;
        $output['foo-bar'] = $this->foo_bar;
        $output['foo bar'] = $this->_foo_bar;
        $output['baz qux'] = $this->baz_qux;
        $output['123 qwe'] = $this->_123_qwe;
        $output['Город'] = $this->Gorod;
        $output['название юр.лица'] = $this->nazvanie_iur_litsa;
        $output['IP-адрес'] = $this->IP_adres;
        $output['~~tildas~~'] = $this->_tildas;

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
    public static function validateInput(array|object $input, bool $return = false): bool
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
}
