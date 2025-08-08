<?php

declare(strict_types=1);

namespace Ns\DefinitionsEnum_7_4;

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

    /**
     * @var 'red'|'green'
     */
    private string $color;

    /**
     * @var 'small'|'big'|null
     */
    private ?string $size = null;

    /**
     * @param 'red'|'green' $color
     * @param 'small'|'big'|null $size
     */
    public function __construct(string $color, ?string $size = null)
    {
        $this->color = $color;
        $this->size = $size;
    }

    /**
     * @return 'red'|'green'
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param 'red'|'green' $color
     */
    public function withColor(string $color, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->color = $color;

        if ($validate) {
            $clone->validate();
        }

        return $clone;
    }

    /**
     * @return 'small'|'big'|null
     */
    public function getSize(): ?string
    {
        return $this->size;
    }

    /**
     * @param 'small'|'big' $size
     */
    public function withSize(string $size, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->size = $size;

        if ($validate) {
            $clone->validate();
        }

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

        $color = $input->{'color'};
        $size = isset($input->{'size'}) ? $input->{'size'} : null;

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
        $output['color'] = $this->color;
        if (isset($this->size)) {
            $output['size'] = $this->size;
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
        $output->{'color'} = $this->color;
        if (isset($this->size)) {
            $output->{'size'} = $this->size;
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
