<?php

declare(strict_types=1);

namespace Ns\DuplicateSanitizedNames;

class Foo
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'required' => [
            'foo-bar',
            'foo bar',
        ],
        'properties' => [
            'foo-bar' => [
                'type' => 'string',
            ],
            'foo bar' => [
                'type' => 'string',
            ],
        ],
    ];

    /**
     * @var string
     */
    private string $foo_bar;

    /**
     * @var string
     */
    private string $foo_bar_1;

    /**
     * @param string $foo_bar
     * @param string $foo_bar_1
     */
    public function __construct(string $foo_bar, string $foo_bar_1)
    {
        $this->foo_bar = $foo_bar;
        $this->foo_bar_1 = $foo_bar_1;
    }

    /**
     * @return string
     */
    public function getFooBar() : string
    {
        return $this->foo_bar;
    }

    /**
     * @return string
     */
    public function getFooBar1() : string
    {
        return $this->foo_bar_1;
    }

    /**
     * @param string $foo_bar
     * @return self
     */
    public function withFooBar(string $foo_bar) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($foo_bar, self::$schema['properties']['foo-bar']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->foo_bar = $foo_bar;

        return $clone;
    }

    /**
     * @param string $foo_bar_1
     * @return self
     */
    public function withFooBar1(string $foo_bar_1) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($foo_bar_1, self::$schema['properties']['foo bar']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->foo_bar_1 = $foo_bar_1;

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
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to buildFromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $foo_bar = $input->{'foo-bar'};
        $foo_bar_1 = $input->{'foo bar'};

        $obj = new self($foo_bar, $foo_bar_1);

        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toJson() : array
    {
        $output = [];
        $output['foo-bar'] = $this->foo_bar;
        $output['foo bar'] = $this->foo_bar_1;

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
