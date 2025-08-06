<?php

declare(strict_types=1);

namespace Ns\DefinitionsEnum_8_4;

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

    private Color $color;

    private ?Size $size = null;

    public function __construct(Color $color, ?Size $size = null)
    {
        $this->color = $color;
        $this->size = $size;
    }

    public function getColor(): Color
    {
        return $this->color;
    }

    public function withColor(Color $color): self
    {
        $clone = clone $this;
        $clone->color = $color;

        return $clone;
    }

    public function getSize(): ?Size
    {
        return $this->size ?? null;
    }

    public function withSize(Size $size): self
    {
        $clone = clone $this;
        $clone->size = $size;

        return $clone;
    }

    public function withoutSize(): self
    {
        $clone = clone $this;
        unset($clone->size);

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

        $color = Color::from($input->{'color'});
        $size = isset($input->{'size'}) ? Size::from($input->{'size'}) : null;

        $obj = new self($color, $size);
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
        $output['color'] = $this->color->value;
        if (isset($this->size)) {
            $output['size'] = $this->size->value;
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
        $output->{'color'} = $this->color->value;
        if (isset($this->size)) {
            $output->{'size'} = $this->size->value;
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
}
