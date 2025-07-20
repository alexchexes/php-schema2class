<?php

namespace Ns\DefinitionsEnum_5_6;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
        'type' => 'object',
        'additionalProperties' => false,
        'properties' => [
            'color' => [
                '$ref' => '#/definitions/Color',
            ],
            'size' => [
                '$ref' => '#/definitions/Size',
            ],
        ],
        'required' => [
            'color',
        ],
        'definitions' => [
            'Color' => [
                'enum' => [
                    'red',
                    'green',
                ],
                'type' => 'string',
            ],
            'Size' => [
                'enum' => [
                    'small',
                    'big',
                ],
                'type' => 'string',
            ],
        ],
    ];

    /**
     * @var 'red'|'green'
     */
    private $color;

    /**
     * @var 'small'|'big'|null
     */
    private $size = null;

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
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return 'small'|'big'|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param 'red'|'green' $color
     * @return self
     * @param bool $validate
     */
    public function withColor(string $color, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($color, self::$schema['properties']['color']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->color = $color;

        return $clone;
    }

    /**
     * @param 'small'|'big' $size
     * @return self
     * @param bool $validate
     */
    public function withSize(string $size, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($size, self::$schema['properties']['size']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->size = $size;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutSize()
    {
        $clone = clone $this;
        unset($clone->size);

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
    public static function buildFromInput($input, bool $validate = true)
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to buildFromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $color = $input->{'color'};
        $size = isset($input->{'size'}) ? $input->{'size'} : null;

        $obj = new self($color);
        $obj->size = $size;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray()
    {
        $output = [];
        $output['color'] = $this->color;
        if (isset($this->size)) {
            $output['size'] = $this->size;
        }

        return $output;
    }

    /**
     * Converts this object back to a stdClass that can be JSON-encoded
     *
     * @return \stdClass Converted object
     */
    public function toObject()
    {
        $output = [];
        $output['color'] = $this->color;
        if (isset($this->size)) {
            $output['size'] = $this->size;
        }

        return \JsonSchema\Validator::arrayToObjectRecursive($output);
    }

    /**
     * Validates an input array
     *
     * @param array|object $input Input data
     * @param bool $return Return instead of throwing errors
     * @return bool Validation result
     * @throws \InvalidArgumentException
     */
    public static function validateInput($input, $return = false)
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function($e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
