<?php

declare(strict_types=1);

namespace Ns\DefinitionsFilter_8_4;

class Address
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
        'type' => 'object',
        'additionalProperties' => false,
        'properties' => [
            'line1' => [
                'type' => 'string',
            ],
            'country' => [
                '$ref' => '#/definitions/Country',
            ],
            'coordinates' => [
                '$ref' => '#/definitions/Address/$defs/Coordinates',
            ],
        ],
        'required' => [
            'line1',
            'country',
        ],
        '$defs' => [
            'Coordinates' => [
                'type' => 'object',
                'additionalProperties' => false,
                'properties' => [
                    'lat' => [
                        'type' => 'number',
                    ],
                    'lng' => [
                        'type' => 'number',
                    ],
                ],
                'required' => [
                    'lat',
                    'lng',
                ],
            ],
        ],
        'definitions' => [
            'Country' => [
                'type' => 'object',
                'additionalProperties' => false,
                'properties' => [
                    'name' => [
                        'type' => 'string',
                    ],
                    'region' => [
                        '$ref' => '#/definitions/Region',
                    ],
                ],
                'required' => [
                    'name',
                ],
            ],
            'Region' => [
                'type' => 'object',
                'additionalProperties' => false,
                'properties' => [
                    'code' => [
                        'type' => 'string',
                    ],
                    'description' => [
                        'type' => 'string',
                    ],
                ],
                'required' => [
                    'code',
                ],
            ],
        ],
    ];

    private string $line1;

    private Country $country;

    private ?Address\Defs\Coordinates $coordinates = null;

    public function __construct(string $line1, Country $country, ?Address\Defs\Coordinates $coordinates = null)
    {
        $this->line1 = $line1;
        $this->country = $country;
        $this->coordinates = $coordinates;
    }

    public function getLine1(): string
    {
        return $this->line1;
    }

    public function withLine1(string $line1): self
    {
        $clone = clone $this;
        $clone->line1 = $line1;

        return $clone;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function withCountry(Country $country): self
    {
        $clone = clone $this;
        $clone->country = $country;

        return $clone;
    }

    public function getCoordinates(): ?Address\Defs\Coordinates
    {
        return $this->coordinates ?? null;
    }

    public function withCoordinates(Address\Defs\Coordinates $coordinates): self
    {
        $clone = clone $this;
        $clone->coordinates = $coordinates;

        return $clone;
    }

    public function withoutCoordinates(): self
    {
        $clone = clone $this;
        unset($clone->coordinates);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Address Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): Address
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $line1 = $input->{'line1'};
        $country = Country::fromInput($input->{'country'}, $validate);
        $coordinates = isset($input->{'coordinates'})
            ? Address\Defs\Coordinates::fromInput($input->{'coordinates'}, $validate)
            : null;

        $obj = new self($line1, $country, $coordinates);

        return $obj;
    }

    /**
     * Converts this object to array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = [];
        $output['line1'] = $this->line1;
        $output['country'] = $this->country->toArray();
        if (isset($this->coordinates)) {
            $output['coordinates'] = $this->coordinates->toArray();
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
        $output = new \stdClass();
        $output->{'line1'} = $this->line1;
        $output->{'country'} = $this->country->toStdClass();
        if (isset($this->coordinates)) {
            $output->{'coordinates'} = $this->coordinates->toStdClass();
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
    public static function validateInput(array|object $input, bool $return = false): bool
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

    public function __clone()
    {
        $this->country = clone $this->country;
        if (isset($this->coordinates)) {
            $this->coordinates = clone $this->coordinates;
        }
    }
}
