<?php

declare(strict_types=1);

namespace Ns\NonAsciiProperty_7_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
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
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    private string $Gorod;

    private string $nazvanieIurLitsa;

    private string $IPAdres;

    public function __construct(string $Gorod, string $nazvanieIurLitsa, string $IPAdres)
    {
        $this->_additionalProperties = new \stdClass();

        $this->Gorod = $Gorod;
        $this->nazvanieIurLitsa = $nazvanieIurLitsa;
        $this->IPAdres = $IPAdres;
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
        return $this->nazvanieIurLitsa;
    }

    public function withNazvanieIurLitsa(string $nazvanieIurLitsa): self
    {
        $clone = clone $this;
        $clone->nazvanieIurLitsa = $nazvanieIurLitsa;

        return $clone;
    }

    public function getIPAdres(): string
    {
        return $this->IPAdres;
    }

    public function withIPAdres(string $IPAdres): self
    {
        $clone = clone $this;
        $clone->IPAdres = $IPAdres;

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
    public function toArray(): array
    {
        $output = [];
        $output['Город'] = $this->Gorod;
        $output['название юр.лица'] = $this->nazvanieIurLitsa;
        $output['IP-адрес'] = $this->IPAdres;

        return $output;
    }

    /**
     * Converts this object to a stdClass that can be JSON-serialized
     *
     * @return \stdClass Converted object
     */
    public function toStdClass(): \stdClass
    {
        $output = new \stdClass();
        $output->{'Город'} = $this->Gorod;
        $output->{'название юр.лица'} = $this->nazvanieIurLitsa;
        $output->{'IP-адрес'} = $this->IPAdres;

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
            $errors = array_map(function(array $e): string {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
