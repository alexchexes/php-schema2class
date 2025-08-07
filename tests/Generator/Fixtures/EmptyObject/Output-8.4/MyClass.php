<?php

declare(strict_types=1);

namespace Ns\EmptyObject_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'a' => [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                ],
            ],
            'b' => [
                'type' => 'object',
                'properties' => [
                    
                ],
            ],
        ],
    ];

    /**
     * @var string[]|null
     */
    private ?array $a = null;

    private array|object|null $b = null;

    /**
     * @param string[]|null $a
     */
    public function __construct(?array $a = null, array|object|null $b = null)
    {
        $this->a = $a;
        $this->b = $b;
    }

    /**
     * @return string[]|null
     */
    public function getA(): ?array
    {
        return $this->a;
    }

    /**
     * @param string[] $a
     */
    public function withA(array $a, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($a, self::$_schema['properties']['a']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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

    public function getB(): array|object|null
    {
        return $this->b;
    }

    public function withB(array|object $b): self
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
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $a = isset($input->{'a'}) ? $input->{'a'} : null;
        $b = isset($input->{'b'}) ? $input->{'b'} : null;

        $obj = new self($a, $b);
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
            $output['b'] = json_decode(json_encode($this->b), true);
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
            $output->{'b'} = json_decode(json_encode($this->b));
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
