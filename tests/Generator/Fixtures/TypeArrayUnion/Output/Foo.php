<?php

declare(strict_types=1);

namespace Ns\TypeArrayUnion;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'required' => [
            'input',
        ],
        'properties' => [
            'input' => [
                'type' => [
                    'string',
                    'object',
                ],
                'required' => [
                    'foo',
                ],
                'properties' => [
                    'foo' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var string|FooInputAlternative2
     */
    private string|FooInputAlternative2 $input;

    /**
     * @param string|FooInputAlternative2 $input
     */
    public function __construct(FooInputAlternative2|string $input)
    {
        $this->input = $input;
    }

    /**
     * @return string|FooInputAlternative2
     */
    public function getInput() : FooInputAlternative2|string
    {
        return $this->input;
    }

    /**
     * @param string|FooInputAlternative2 $input
     * @return self
     */
    public function withInput(FooInputAlternative2|string $input) : self
    {
        $clone = clone $this;
        $clone->input = $input;

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input2 Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return Foo Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input2, bool $validate = true) : Foo
    {
        $input2 = is_array($input2) ? \JsonSchema\Validator::arrayToObjectRecursive($input2) : $input2;
        if ($validate) {
            static::validateInput($input2);
        }

        $input = match (true) {
            is_string($input2->{'input'}) => $input2->{'input'},
            FooInputAlternative2::validateInput($input2->{'input'}, true) => FooInputAlternative2::buildFromInput($input2->{'input'}, validate: $validate),
            default => throw new \InvalidArgumentException("could not build property 'input' from JSON"),
        };

        $obj = new self($input);

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
        $output['input'] = match (true) {
            is_string($this->input) => $this->input,
            $this->input instanceof FooInputAlternative2 => ($this->input)->toArray(),
        };

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
        $this->input = match (true) {
            is_string($this->input) => $this->input,
            $this->input instanceof FooInputAlternative2 => clone $this->input,
        };
    }
}
