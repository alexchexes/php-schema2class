<?php

declare(strict_types=1);

namespace Ns\PreservePropertyNames_7_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
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
     */
    private static array $_namesMap = [
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
     */
    private \stdClass $_additionalProperties;

    private string $foo;

    private string $_foo;

    private string $__foo;

    private string $foo_;

    private string $foo__;

    private string $_foo_;

    private string $__foo__;

    private string $_foo_bar;

    private string $foo_bar;

    private string $baz_qux;

    private string $_123_qwe;

    private string $Gorod;

    private string $nazvanie_iur_litsa;

    private string $IP_adres;

    private string $_tildas;

    private ?string $it_s_A = null;

    public function __construct(
        string $foo,
        string $_foo,
        string $__foo,
        string $foo_,
        string $foo__,
        string $_foo_,
        string $__foo__,
        string $_foo_bar,
        string $foo_bar,
        string $baz_qux,
        string $_123_qwe,
        string $Gorod,
        string $nazvanie_iur_litsa,
        string $IP_adres,
        string $_tildas,
        ?string $it_s_A = null
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
    public function getAdditionalProperties(bool $asArray = true)
    {
        return $asArray
            ? json_decode(json_encode($this->_additionalProperties), true)
            : $this->_additionalProperties;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param \stdClass|array $additionalProperties Map of property name/value pairs to add.
     */
    public function withAdditionalProperties($additionalProperties): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        return $clone;
    }

    /**
     * Removes all extra properties not specified in the schema.
     */
    public function withoutAdditionalProperties(): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = new \stdClass();
        return $clone;
    }

    public function getFoo(): string
    {
        return $this->foo;
    }

    public function withFoo(string $foo): self
    {
        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    public function get_Foo(): string
    {
        return $this->_foo;
    }

    public function with_Foo(string $_foo): self
    {
        $clone = clone $this;
        $clone->_foo = $_foo;

        return $clone;
    }

    public function get__Foo(): string
    {
        return $this->__foo;
    }

    public function with__Foo(string $__foo): self
    {
        $clone = clone $this;
        $clone->__foo = $__foo;

        return $clone;
    }

    public function getFoo_(): string
    {
        return $this->foo_;
    }

    public function withFoo_(string $foo_): self
    {
        $clone = clone $this;
        $clone->foo_ = $foo_;

        return $clone;
    }

    public function getFoo__(): string
    {
        return $this->foo__;
    }

    public function withFoo__(string $foo__): self
    {
        $clone = clone $this;
        $clone->foo__ = $foo__;

        return $clone;
    }

    public function get_Foo_(): string
    {
        return $this->_foo_;
    }

    public function with_Foo_(string $_foo_): self
    {
        $clone = clone $this;
        $clone->_foo_ = $_foo_;

        return $clone;
    }

    public function get__Foo__(): string
    {
        return $this->__foo__;
    }

    public function with__Foo__(string $__foo__): self
    {
        $clone = clone $this;
        $clone->__foo__ = $__foo__;

        return $clone;
    }

    public function get_FooBar(): string
    {
        return $this->_foo_bar;
    }

    public function with_FooBar(string $_foo_bar): self
    {
        $clone = clone $this;
        $clone->_foo_bar = $_foo_bar;

        return $clone;
    }

    public function getFooBar(): string
    {
        return $this->foo_bar;
    }

    public function withFooBar(string $foo_bar): self
    {
        $clone = clone $this;
        $clone->foo_bar = $foo_bar;

        return $clone;
    }

    public function getBazQux(): string
    {
        return $this->baz_qux;
    }

    public function withBazQux(string $baz_qux): self
    {
        $clone = clone $this;
        $clone->baz_qux = $baz_qux;

        return $clone;
    }

    public function get_123Qwe(): string
    {
        return $this->_123_qwe;
    }

    public function with_123Qwe(string $_123_qwe): self
    {
        $clone = clone $this;
        $clone->_123_qwe = $_123_qwe;

        return $clone;
    }

    public function getGorod(): string
    {
        return $this->Gorod;
    }

    public function withGorod(string $Gorod): self
    {
        $clone = clone $this;
        $clone->Gorod = $Gorod;

        return $clone;
    }

    public function getNazvanieIurLitsa(): string
    {
        return $this->nazvanie_iur_litsa;
    }

    public function withNazvanieIurLitsa(string $nazvanie_iur_litsa): self
    {
        $clone = clone $this;
        $clone->nazvanie_iur_litsa = $nazvanie_iur_litsa;

        return $clone;
    }

    public function getIPAdres(): string
    {
        return $this->IP_adres;
    }

    public function withIPAdres(string $IP_adres): self
    {
        $clone = clone $this;
        $clone->IP_adres = $IP_adres;

        return $clone;
    }

    public function getTildas(): string
    {
        return $this->_tildas;
    }

    public function withTildas(string $_tildas): self
    {
        $clone = clone $this;
        $clone->_tildas = $_tildas;

        return $clone;
    }

    public function getItSA(): ?string
    {
        return $this->it_s_A ?? null;
    }

    public function withItSA(string $it_s_A): self
    {
        $clone = clone $this;
        $clone->it_s_A = $it_s_A;

        return $clone;
    }

    public function withoutItSA(): self
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
    public static function fromInput($input, bool $validate = true): MyClass
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
    public function toArray(): array
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
    public function toStdClass(): \stdClass
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
    public function validate(bool $return = false): bool
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
    public static function validateInput($input, bool $return = false): bool
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(
                fn (array $e): string => ($e["property"] ? $e["property"] . ": " : "") . $e["message"],
                $validator->getErrors(),
            );
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
