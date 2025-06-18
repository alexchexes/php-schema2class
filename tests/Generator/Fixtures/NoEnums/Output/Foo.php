<?php

declare(strict_types=1);

namespace Ns\NoEnums;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'required' => [
            'color',
        ],
        'properties' => [
            'color' => [
                'type' => 'string',
                'enum' => [
                    'red',
                    'green',
                ],
            ],
        ],
    ];

    /**
     * @var 'red'|'green'
     */
    private string $color;

    /**
     * @param 'red'|'green' $color
     */
    public function __construct(string $color)
    {
        $this->color = $color;
    }

    /**
     * @return 'red'|'green'
     */
    public function getColor() : string
    {
        return $this->color;
    }

    /**
     * @param 'red'|'green' $color
     * @return self
     */
    public function withColor(string $color) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($color, self::$schema['properties']['color']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->color = $color;

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
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $color = $input->{'color'};

        $obj = new self($color);

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
        $output['color'] = $this->color;

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
