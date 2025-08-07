<?php

declare(strict_types=1);

namespace Ns\DuplicateWithDifferentCasingPreserve_7_4;

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
     * @deprecated
     */
    private ?string $foobar = null;

    private string $fooBar;

    /**
     * @deprecated
     */
    private ?string $bar = null;

    public function __construct(string $fooBar, ?string $foobar = null, ?string $bar = null)
    {
        $this->fooBar = $fooBar;
        $this->foobar = $foobar;
        $this->bar = $bar;
    }

    public function getFooBar(): string
    {
        return $this->fooBar;
    }

    public function withFooBar(string $fooBar): self
    {
        $clone = clone $this;
        $clone->fooBar = $fooBar;

        return $clone;
    }

    /**
     * @deprecated
     */
    public function getBar(): ?string
    {
        return $this->bar;
    }

    /**
     * @deprecated
     */
    public function withBar(string $bar): self
    {
        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    public function withoutBar(): self
    {
        $clone = clone $this;
        unset($clone->bar);

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

        $fooBar = $input->{'fooBar'};
        $foobar = isset($input->{'foobar'}) ? $input->{'foobar'} : null;
        $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;

        $obj = new self($fooBar, $foobar, $bar);
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
