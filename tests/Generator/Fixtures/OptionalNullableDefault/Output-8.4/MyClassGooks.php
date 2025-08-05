<?php

declare(strict_types=1);

namespace Ns\OptionalNullableDefault_8_4;

/**
 * optional, nullable, with default, object, and default is empty object
 */
class MyClassGooks
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'type' => 'object',
        'description' => 'optional, nullable, with default, object, and default is empty object',
        'properties' => [
            'a' => [
                'type' => 'string',
            ],
            'b' => [
                'type' => 'number',
            ],
        ],
        'default' => [
            
        ],
    ];

    private ?string $a = null;

    private int|float|null $b = null;

    public function __construct(?string $a = null, int|float|null $b = null)
    {
        $this->a = $a;
        $this->b = $b;
    }

    public function getA(): ?string
    {
        return $this->a ?? null;
    }

    public function withA(string $a): self
    {
        $clone = clone $this;
        $clone->a = $a;

        return $clone;
    }

    public function withoutA(): self
    {
        $clone = clone $this;
        unset($clone->a);

        return $clone;
    }

    public function getB(): int|float|null
    {
        return $this->b;
    }

    public function withB(int|float $b): self
    {
        $clone = clone $this;
        $clone->b = $b;

        return $clone;
    }

    public function withoutB(): self
    {
        $clone = clone $this;
        unset($clone->b);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MyClassGooks Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): MyClassGooks
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $a = isset($input->{'a'}) ? $input->{'a'} : null;
        $b = isset($input->{'b'}) ? $input->{'b'} : null;

        $obj = new self();
        $obj->a = $a;
        $obj->b = $b;
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
        if (isset($this->a)) {
            $output['a'] = $this->a;
        }
        if (isset($this->b)) {
            $output['b'] = $this->b;
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
        if (isset($this->a)) {
            $output->{'a'} = $this->a;
        }
        if (isset($this->b)) {
            $output->{'b'} = $this->b;
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
