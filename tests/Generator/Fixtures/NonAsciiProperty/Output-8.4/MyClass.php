<?php

declare(strict_types=1);

namespace Ns\NonAsciiProperty_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'required' => [
            'Город',
            'название юр.лица',
            'IP-адрес',
        ],
        'properties' => [
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
    private string $Gorod;

    /**
     * @var string
     */
    private string $nazvanieIurLitsa;

    /**
     * @var string
     */
    private string $IPAdres;

    /**
     * @param string $Gorod
     * @param string $nazvanieIurLitsa
     * @param string $IPAdres
     */
    public function __construct(string $Gorod, string $nazvanieIurLitsa, string $IPAdres)
    {
        $this->Gorod = $Gorod;
        $this->nazvanieIurLitsa = $nazvanieIurLitsa;
        $this->IPAdres = $IPAdres;
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
        return $this->nazvanieIurLitsa;
    }

    /**
     * @return string
     */
    public function getIPAdres() : string
    {
        return $this->IPAdres;
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
     * @param string $nazvanieIurLitsa
     * @return self
     * @param bool $validate
     */
    public function withNazvanieIurLitsa(string $nazvanieIurLitsa, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($nazvanieIurLitsa, self::$schema['properties']['название юр.лица']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->nazvanieIurLitsa = $nazvanieIurLitsa;

        return $clone;
    }

    /**
     * @param string $IPAdres
     * @return self
     * @param bool $validate
     */
    public function withIPAdres(string $IPAdres, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($IPAdres, self::$schema['properties']['IP-адрес']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->IPAdres = $IPAdres;

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

        $Gorod = $input->{'Город'};
        $nazvanieIurLitsa = $input->{'название юр.лица'};
        $IPAdres = $input->{'IP-адрес'};

        $obj = new self($Gorod, $nazvanieIurLitsa, $IPAdres);

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
        $output['Город'] = $this->Gorod;
        $output['название юр.лица'] = $this->nazvanieIurLitsa;
        $output['IP-адрес'] = $this->IPAdres;

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
