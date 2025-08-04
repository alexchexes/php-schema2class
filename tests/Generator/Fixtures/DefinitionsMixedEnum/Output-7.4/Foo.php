<?php

declare(strict_types=1);

namespace Ns\DefinitionsMixedEnum_7_4;

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
    private $val;

    /**
     * @param 5|6|'5'|'6' $val
     */
    public function __construct($val)
    {
        $this->val = $val;
    }

    /**
     * @return 5|6|'5'|'6'
     */
    public function getVal()
    {
        return $this->val;
    }

    /**
     * @param 5|6|'5'|'6' $val
     * @return self
     */
    public function withVal($val): self
    {
        $clone = clone $this;
        $clone->val = $val;

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
    public static function fromInput($input, bool $validate = true): Foo
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
     * Validates an input array
     *
     * @param array|object $input Input data
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result
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
