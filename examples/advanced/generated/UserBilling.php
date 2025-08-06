<?php

declare(strict_types=1);

namespace Example\Advanced;

class UserBilling
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'required' => [
            'vatID',
        ],
        'properties' => [
            'vatID' => [
                'type' => 'string',
            ],
            'creditLevel' => [
                'type' => 'integer',
            ],
            'foo' => [
                'type' => 'int',
            ],
            'bar' => [
                'type' => 'string',
            ],
        ],
    ];

    private string $vatID;

    private ?int $creditLevel = null;

    private ?int $foo = null;

    private ?string $bar = null;

    public function __construct(string $vatID, ?int $creditLevel = null, ?int $foo = null, ?string $bar = null)
    {
        $this->vatID = $vatID;
        $this->creditLevel = $creditLevel;
        $this->foo = $foo;
        $this->bar = $bar;
    }

    public function getVatID(): string
    {
        return $this->vatID;
    }

    public function withVatID(string $vatID): self
    {
        $clone = clone $this;
        $clone->vatID = $vatID;

        return $clone;
    }

    public function getCreditLevel(): ?int
    {
        return $this->creditLevel;
    }

    public function withCreditLevel(int $creditLevel): self
    {
        $clone = clone $this;
        $clone->creditLevel = $creditLevel;

        return $clone;
    }

    public function withoutCreditLevel(): self
    {
        $clone = clone $this;
        unset($clone->creditLevel);

        return $clone;
    }

    public function getFoo(): ?int
    {
        return $this->foo;
    }

    public function withFoo(int $foo): self
    {
        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    public function withoutFoo(): self
    {
        $clone = clone $this;
        unset($clone->foo);

        return $clone;
    }

    public function getBar(): ?string
    {
        return $this->bar;
    }

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
     * @return UserBilling Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): UserBilling
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $vatID = $input->{'vatID'};
        $creditLevel = isset($input->{'creditLevel'}) ? $input->{'creditLevel'} : null;
        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $bar = isset($input->{'bar'}) ? $input->{'bar'} : null;

        $obj = new self($vatID, $creditLevel, $foo, $bar);
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
        $output['vatID'] = $this->vatID;
        if (isset($this->creditLevel)) {
            $output['creditLevel'] = $this->creditLevel;
        }
        if (isset($this->foo)) {
            $output['foo'] = $this->foo;
        }
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
        $output->{'vatID'} = $this->vatID;
        if (isset($this->creditLevel)) {
            $output->{'creditLevel'} = $this->creditLevel;
        }
        if (isset($this->foo)) {
            $output->{'foo'} = $this->foo;
        }
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

