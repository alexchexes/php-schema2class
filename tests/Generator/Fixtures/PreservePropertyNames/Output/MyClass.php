<?php

declare(strict_types=1);

namespace Ns\PreservePropertyNames;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'required' => [
            'foo-bar',
            'foo bar',
            'baz qux',
            '123 qwe',
            'Город',
            'название юр.лица',
            'IP-адрес',
        ],
        'properties' => [
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
        ],
    ];

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
     * @param string $foo_bar
     * @param string $_foo_bar
     * @param string $baz_qux
     * @param string $_123_qwe
     * @param string $Gorod
     * @param string $nazvanie_iur_litsa
     * @param string $IP_adres
     */
    public function __construct(string $foo_bar, string $_foo_bar, string $baz_qux, string $_123_qwe, string $Gorod, string $nazvanie_iur_litsa, string $IP_adres)
    {
        $this->foo_bar = $foo_bar;
        $this->_foo_bar = $_foo_bar;
        $this->baz_qux = $baz_qux;
        $this->_123_qwe = $_123_qwe;
        $this->Gorod = $Gorod;
        $this->nazvanie_iur_litsa = $nazvanie_iur_litsa;
        $this->IP_adres = $IP_adres;
    }

    /**
     * @return string
     */
    public function getFooBar() : string
    {
        return $this->foo_bar;
    }

    /**
     * @return string
     */
    public function get_FooBar() : string
    {
        return $this->_foo_bar;
    }

    /**
     * @return string
     */
    public function getBazQux() : string
    {
        return $this->baz_qux;
    }

    /**
     * @return string
     */
    public function get_123Qwe() : string
    {
        return $this->_123_qwe;
    }

    /**
     * @return string
     */
    public function getGorod() : string
    {
        return $this->Gorod;
    }

    /**
     * @return string
     */
    public function getNazvanieIurLitsa() : string
    {
        return $this->nazvanie_iur_litsa;
    }

    /**
     * @return string
     */
    public function getIPAdres() : string
    {
        return $this->IP_adres;
    }

    /**
     * @param string $foo_bar
     * @return self
     * @param bool $validate
     */
    public function withFooBar(string $foo_bar, bool $validate = true) : self
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
    public function with_FooBar(string $_foo_bar, bool $validate = true) : self
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
    public function withBazQux(string $baz_qux, bool $validate = true) : self
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
    public function with_123Qwe(string $_123_qwe, bool $validate = true) : self
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
    public function withGorod(string $Gorod, bool $validate = true) : self
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
    public function withNazvanieIurLitsa(string $nazvanie_iur_litsa, bool $validate = true) : self
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
    public function withIPAdres(string $IP_adres, bool $validate = true) : self
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
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $foo_bar = $input->{'foo-bar'};
        $_foo_bar = $input->{'foo bar'};
        $baz_qux = $input->{'baz qux'};
        $_123_qwe = $input->{'123 qwe'};
        $Gorod = $input->{'Город'};
        $nazvanie_iur_litsa = $input->{'название юр.лица'};
        $IP_adres = $input->{'IP-адрес'};

        $obj = new self($foo_bar, $_foo_bar, $baz_qux, $_123_qwe, $Gorod, $nazvanie_iur_litsa, $IP_adres);

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
        $output['foo-bar'] = $this->foo_bar;
        $output['foo bar'] = $this->_foo_bar;
        $output['baz qux'] = $this->baz_qux;
        $output['123 qwe'] = $this->_123_qwe;
        $output['Город'] = $this->Gorod;
        $output['название юр.лица'] = $this->nazvanie_iur_litsa;
        $output['IP-адрес'] = $this->IP_adres;

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
}
