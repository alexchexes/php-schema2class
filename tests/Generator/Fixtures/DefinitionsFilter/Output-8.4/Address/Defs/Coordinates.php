<?php

declare(strict_types=1);

namespace Ns\DefinitionsFilter_8_4\Address\Defs;

class Coordinates
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
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
    ];

    private int|float $lat;

    private int|float $lng;

    public function __construct(int|float $lat, int|float $lng)
    {
        $this->lat = $lat;
        $this->lng = $lng;
    }

    public function getLat(): int|float
    {
        return $this->lat;
    }

    public function withLat(int|float $lat): self
    {
        $clone = clone $this;
        $clone->lat = $lat;

        return $clone;
    }

    public function getLng(): int|float
    {
        return $this->lng;
    }

    public function withLng(int|float $lng): self
    {
        $clone = clone $this;
        $clone->lng = $lng;

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Coordinates Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): Coordinates
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $lat = (str_contains((string)$input->{'lat'}, '.')
            ? (float)$input->{'lat'}
            : (int)$input->{'lat'}
        );
        $lng = (str_contains((string)$input->{'lng'}, '.')
            ? (float)$input->{'lng'}
            : (int)$input->{'lng'}
        );

        $obj = new self($lat, $lng);

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
        $output['lat'] = $this->lat;
        $output['lng'] = $this->lng;

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
        $output->{'lat'} = $this->lat;
        $output->{'lng'} = $this->lng;

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
}
