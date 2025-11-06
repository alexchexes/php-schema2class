<?php

declare(strict_types=1);

namespace Ns\DefinitionFilter_8_4;

class Country
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
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
        'definitions' => [
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

    private string $name;

    private ?Region $region = null;

    public function __construct(string $name, ?Region $region = null)
    {
        $this->name = $name;
        $this->region = $region;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function withName(string $name): self
    {
        $clone = clone $this;
        $clone->name = $name;

        return $clone;
    }

    public function getRegion(): ?Region
    {
        return $this->region ?? null;
    }

    public function withRegion(Region $region): self
    {
        $clone = clone $this;
        $clone->region = $region;

        return $clone;
    }

    public function withoutRegion(): self
    {
        $clone = clone $this;
        unset($clone->region);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Country Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): Country
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $name = $input->{'name'};
        $region = isset($input->{'region'}) ? Region::fromInput($input->{'region'}, $validate) : null;

        $obj = new self($name, $region);

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
        $output['name'] = $this->name;
        if (isset($this->region)) {
            $output['region'] = $this->region->toArray();
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
        $output->{'name'} = $this->name;
        if (isset($this->region)) {
            $output->{'region'} = $this->region->toStdClass();
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
        if (isset($this->region)) {
            $this->region = clone $this->region;
        }
    }
}
