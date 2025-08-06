<?php

declare(strict_types=1);

namespace Ns\AnyOfSetterValidation_8_4;

class Cat
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'hasFur' => [
                'anyOf' => [
                    [
                        'type' => 'string',
                        'enum' => [
                            'a',
                            'b',
                        ],
                    ],
                    [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                        ],
                        'minItems' => 1,
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var 'a'|'b'|string[]|null
     */
    private string|array|null $hasFur = null;

    /**
     * @param 'a'|'b'|string[]|null $hasFur
     */
    public function __construct(string|array|null $hasFur = null)
    {
        $this->hasFur = $hasFur;
    }

    /**
     * @return 'a'|'b'|string[]|null
     */
    public function getHasFur(): string|array|null
    {
        return $this->hasFur;
    }

    /**
     * @param 'a'|'b'|string[] $hasFur
     */
    public function withHasFur(string|array $hasFur, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($hasFur, self::$_schema['properties']['hasFur']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->hasFur = $hasFur;

        return $clone;
    }

    public function withoutHasFur(): self
    {
        $clone = clone $this;
        unset($clone->hasFur);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Cat Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): Cat
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }


        $hasFur = isset($input->{'hasFur'}) ? match (true) {
            in_array($input->{'hasFur'}, array (
          0 => 'a',
          1 => 'b',
        ), true),
            is_array($input->{'hasFur'}) => $input->{'hasFur'},
            default => null,
        } : null;

        $obj = new self($hasFur);
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
        if (isset($this->hasFur)) {
            $output['hasFur'] = match (true) {
                in_array($this->hasFur, array (
              0 => 'a',
              1 => 'b',
            ), true),
                is_array($this->hasFur) => $this->hasFur,
            };
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
        if (isset($this->hasFur)) {
            $output->{'hasFur'} = match (true) {
                in_array($this->hasFur, array (
              0 => 'a',
              1 => 'b',
            ), true),
                is_array($this->hasFur) => $this->hasFur,
            };
        }

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
        $validator->validate($input, self::$_schema);

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
        if (isset($this->hasFur)) {
            $this->hasFur = match (true) {
                in_array($this->hasFur, array (
              0 => 'a',
              1 => 'b',
            ), true),
                is_array($this->hasFur) => $this->hasFur,
            };
        }
    }
}
