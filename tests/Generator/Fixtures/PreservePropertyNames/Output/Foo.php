<?php

declare(strict_types=1);

namespace Ns\PreservePropertyNames;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'required' => [
            'foo-bar',
            'baz qux',
            'Город',
            'название юр.лица',
            'IP-адрес',
        ],
        'properties' => [
            'foo-bar' => [
                'type' => 'string',
            ],
            'baz qux' => [
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
    private string $baz_qux;

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
     * @param string $baz_qux
     * @param string $Gorod
     * @param string $nazvanie_iur_litsa
     * @param string $IP_adres
     */
    public function __construct(string $foo_bar, string $baz_qux, string $Gorod, string $nazvanie_iur_litsa, string $IP_adres)
    {
        $this->foo_bar = $foo_bar;
        $this->baz_qux = $baz_qux;
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
    public function getBazQux() : string
    {
        return $this->baz_qux;
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
     */
    public function withFooBar(string $foo_bar) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($foo_bar, self::$schema['properties']['foo-bar']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->foo_bar = $foo_bar;

        return $clone;
    }

    /**
     * @param string $baz_qux
     * @return self
     */
    public function withBazQux(string $baz_qux) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($baz_qux, self::$schema['properties']['baz qux']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->baz_qux = $baz_qux;

        return $clone;
    }

    /**
     * @param string $Gorod
     * @return self
     */
    public function withGorod(string $Gorod) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($Gorod, self::$schema['properties']['Город']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->Gorod = $Gorod;

        return $clone;
    }

    /**
     * @param string $nazvanie_iur_litsa
     * @return self
     */
    public function withNazvanieIurLitsa(string $nazvanie_iur_litsa) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($nazvanie_iur_litsa, self::$schema['properties']['название юр.лица']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->nazvanie_iur_litsa = $nazvanie_iur_litsa;

        return $clone;
    }

    /**
     * @param string $IP_adres
     * @return self
     */
    public function withIPAdres(string $IP_adres) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($IP_adres, self::$schema['properties']['IP-адрес']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
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
     * @return Foo Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : Foo
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

        $foo_bar = $input->{'foo-bar'};
        $baz_qux = $input->{'baz qux'};
        $Gorod = $input->{'Город'};
        $nazvanie_iur_litsa = $input->{'название юр.лица'};
        $IP_adres = $input->{'IP-адрес'};

        $obj = new self($foo_bar, $baz_qux, $Gorod, $nazvanie_iur_litsa, $IP_adres);

        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toJson() : array
    {
        $output = [];
        $output['foo-bar'] = $this->foo_bar;
        $output['baz qux'] = $this->baz_qux;
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

    public function __clone()
    {
    }
}
