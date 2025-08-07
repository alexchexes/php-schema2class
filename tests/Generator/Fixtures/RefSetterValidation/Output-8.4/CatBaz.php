<?php

declare(strict_types=1);

namespace Ns\RefSetterValidation_8_4;

class CatBaz
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'nestedFoo' => [
                '$ref' => '#/definitions/Bar',
            ],
        ],
        'definitions' => [
            'Bar' => [
                'type' => 'number',
                'enum' => [
                    1,
                    2,
                ],
            ],
        ],
    ];

    /**
     * @var 1|2|null
     */
    private ?int $nestedFoo = null;

    /**
     * @param 1|2|null $nestedFoo
     */
    public function __construct(?int $nestedFoo = null)
    {
        $this->nestedFoo = $nestedFoo;
    }

    /**
     * @return 1|2|null
     */
    public function getNestedFoo(): ?int
    {
        return $this->nestedFoo;
    }

    /**
     * @param 1|2 $nestedFoo
     */
    public function withNestedFoo(int $nestedFoo, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($nestedFoo, self::$_schema['properties']['nestedFoo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->nestedFoo = $nestedFoo;

        return $clone;
    }

    public function withoutNestedFoo(): self
    {
        $clone = clone $this;
        unset($clone->nestedFoo);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return CatBaz Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): CatBaz
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $nestedFoo = isset($input->{'nestedFoo'}) ? $input->{'nestedFoo'} : null;

        $obj = new self($nestedFoo);
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
        if (isset($this->nestedFoo)) {
            $output['nestedFoo'] = $this->nestedFoo;
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
        if (isset($this->nestedFoo)) {
            $output->{'nestedFoo'} = $this->nestedFoo;
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
            $errors = array_map(function(array $e): string {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
