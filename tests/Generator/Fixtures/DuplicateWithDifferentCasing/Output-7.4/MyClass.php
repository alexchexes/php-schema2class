<?php

declare(strict_types=1);

namespace Ns\DuplicateWithDifferentCasing_7_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'required' => [
            'fooBar',
        ],
        'properties' => [
            'foobar' => [
                'type' => 'string',
                'deprecated' => true,
            ],
            'fooBar' => [
                'type' => 'string',
            ],
            'bar' => [
                'type' => 'string',
                'deprecated' => true,
            ],
        ],
    ];

    /**
     * @var string|null
     * @deprecated
     */
    private ?string $foobar = null;

    /**
     * @var string
     */
    private string $fooBar;

    /**
     * @var string|null
     * @deprecated
     */
    private ?string $bar = null;

    /**
     * @param string $fooBar
     */
    public function __construct(string $fooBar)
    {
        $this->fooBar = $fooBar;
    }

    /**
     * @return string
     */
    public function getFooBar(): string
    {
        return $this->fooBar;
    }

    /**
     * @param string $fooBar
     * @return self
     */
    public function withFooBar(string $fooBar): self
    {
        $clone = clone $this;
        $clone->fooBar = $fooBar;

        return $clone;
    }

    /**
     * @return string|null
     * @deprecated
     */
    public function getBar(): ?string
    {
        return $this->bar ?? null;
    }

    /**
     * @param string $bar
     * @return self
     * @deprecated
     */
    public function withBar(string $bar): self
    {
        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBar(): self
    {
        $clone = clone $this;
        unset($clone->bar);

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
    public static function fromInput($input, bool $validate = true): MyClass
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

        $foobar = isset($input->{'foobar'}) ? $input->{'foobar'} : null;
        $fooBar = $input->{'fooBar'};
        $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;

        $obj = new self($fooBar);
        $obj->foobar = $foobar;
        $obj->bar = $bar;
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
        if (isset($this->foobar)) {
            $output['foobar'] = $this->foobar;
        }
        $output['fooBar'] = $this->fooBar;
        if (isset($this->bar)) {
            $output['bar'] = $this->bar;
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
        if (isset($this->foobar)) {
            $output->{'foobar'} = $this->foobar;
        }
        $output->{'fooBar'} = $this->fooBar;
        if (isset($this->bar)) {
            $output->{'bar'} = $this->bar;
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
