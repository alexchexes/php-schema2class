<?php

declare(strict_types=1);

namespace Ns\EscapeDefinitionName_7_4;

class MoiKlass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'c' => [
                'type' => 'string',
            ],
        ],
    ];

    private ?string $c = null;

    public function getC(): ?string
    {
        return $this->c ?? null;
    }

    public function withC(string $c): self
    {
        $clone = clone $this;
        $clone->c = $c;

        return $clone;
    }

    public function withoutC(): self
    {
        $clone = clone $this;
        unset($clone->c);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MoiKlass Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput($input, bool $validate = true): MoiKlass
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

        $c = isset($input->{'c'}) ? $input->{'c'} : null;

        $obj = new self();
        $obj->c = $c;
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
        if (isset($this->c)) {
            $output['c'] = $this->c;
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
        if (isset($this->c)) {
            $output->{'c'} = $this->c;
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
