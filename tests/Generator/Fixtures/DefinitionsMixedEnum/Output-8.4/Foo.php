<?php

declare(strict_types=1);

namespace Ns\DefinitionsMixedEnum_8_4;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'type' => 'object',
        'additionalProperties' => false,
        'properties' => [
            'val' => [
                '$ref' => '#/definitions/Mixed',
            ],
        ],
        'required' => [
            'val',
        ],
        'definitions' => [
            'Mixed' => [
                'enum' => [
                    5,
                    6,
                    '5',
                    '6',
                ],
                'type' => [
                    'integer',
                    'string',
                ],
            ],
        ],
    ];

    /**
     * @var 5|6|'5'|'6'
     */
    private int|string $val;

    /**
     * @param 5|6|'5'|'6' $val
     */
    public function __construct(int|string $val)
    {
        $this->val = $val;
    }

    /**
     * @return 5|6|'5'|'6'
     */
    public function getVal(): int|string
    {
        return $this->val;
    }

    /**
     * @param 5|6|'5'|'6' $val
     */
    public function withVal(int|string $val, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->val = $val;

        if ($validate) {
            $clone->validate();
        }

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Foo Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): Foo
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $val = $input->{'val'};

        $obj = new self($val);
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
        $output['val'] = $this->val;

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
        $output->{'val'} = $this->val;

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
