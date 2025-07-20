<?php

declare(strict_types=1);

namespace Ns\FallbackNamingPreserve_7_4;

class MyClassTestObj
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'type' => 'object',
        'properties' => [
            'a' => [
                'type' => 'string',
            ],
        ],
    ];

    /**
     * @var string|null
     */
    private ?string $a = null;

    /**
     * @return string|null
     */
    public function getA(): ?string
    {
        return $this->a ?? null;
    }

    /**
     * @param string $a
     * @return self
     * @param bool $validate
     */
    public function withA(string $a, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($a, self::$schema['properties']['a']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->a = $a;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutA(): self
    {
        $clone = clone $this;
        unset($clone->a);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MyClassTestObj Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput($input, bool $validate = true): MyClassTestObj
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

        $a = isset($input->{'a'}) ? $input->{'a'} : null;

        $obj = new self();
        $obj->a = $a;
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

        return $output;
    }

    /**
     * Converts this object back to a stdClass that can be JSON-encoded
     *
     * @return \stdClass Converted object
     */
    public function toObject(): \stdClass
    {
        $output = [];
        if (isset($this->a)) {
            $output['a'] = $this->a;
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
    public static function validateInput($input, bool $return = false): bool
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
}
