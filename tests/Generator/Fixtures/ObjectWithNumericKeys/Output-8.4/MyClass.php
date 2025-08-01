<?php

declare(strict_types=1);

namespace Ns\ObjectWithNumericKeys_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'type' => 'object',
        'properties' => [
            1 => [
                'type' => 'string',
            ],
            [
                'type' => 'object',
                'properties' => [
                    1 => [
                        'type' => 'string',
                    ],
                    [
                        'type' => 'string',
                    ],
                    [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var string|null
     */
    private ?string $_1 = null;

    /**
     * @var MyClass_2|null
     */
    private ?MyClass_2 $_2 = null;

    /**
     * @return string|null
     */
    public function get_1(): ?string
    {
        return $this->_1 ?? null;
    }

    /**
     * @return MyClass_2|null
     */
    public function get_2(): ?MyClass_2
    {
        return $this->_2 ?? null;
    }

    /**
     * @param string $_1
     * @return self
     * @param bool $validate
     */
    public function with_1(string $_1, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_1, self::$schema['properties']['1']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_1 = $_1;

        return $clone;
    }

    /**
     * @return self
     */
    public function without_1(): self
    {
        $clone = clone $this;
        unset($clone->_1);

        return $clone;
    }

    /**
     * @param MyClass_2 $_2
     * @return self
     */
    public function with_2(MyClass_2 $_2): self
    {
        $clone = clone $this;
        $clone->_2 = $_2;

        return $clone;
    }

    /**
     * @return self
     */
    public function without_2(): self
    {
        $clone = clone $this;
        unset($clone->_2);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $_1 = isset($input->{'1'}) ? $input->{'1'} : null;
        $_2 = isset($input->{'2'}) ? MyClass_2::fromInput($input->{'2'}, $validate) : null;

        $obj = new self();
        $obj->_1 = $_1;
        $obj->_2 = $_2;
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
        if (isset($this->_1)) {
            $output['1'] = $this->_1;
        }
        if (isset($this->_2)) {
            $output['2'] = ($this->_2)->toArray();
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
        if (isset($this->_1)) {
            $output->{'1'} = $this->_1;
        }
        if (isset($this->_2)) {
            $output->{'2'} = ($this->_2)->toStdClass();
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
        if (isset($this->_2)) {
            $this->_2 = clone $this->_2;
        }
    }
}
