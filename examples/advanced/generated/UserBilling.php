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
    private static array $schema = [
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

    /**
     * @var string
     */
    private string $vatID;

    /**
     * @var int|null
     */
    private ?int $creditLevel = null;

    /**
     * @var int|null
     */
    private ?int $foo = null;

    /**
     * @var string|null
     */
    private ?string $bar = null;

    /**
     * @param string $vatID
     */
    public function __construct(string $vatID)
    {
        $this->vatID = $vatID;
    }

    /**
     * @return string
     */
    public function getVatID(): string
    {
        return $this->vatID;
    }

    /**
     * @param string $vatID
     * @return self
     * @param bool $validate
     */
    public function withVatID(string $vatID, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($vatID, self::$schema['properties']['vatID']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->vatID = $vatID;

        return $clone;
    }

    /**
     * @return int|null
     */
    public function getCreditLevel(): ?int
    {
        return $this->creditLevel ?? null;
    }

    /**
     * @param int $creditLevel
     * @return self
     * @param bool $validate
     */
    public function withCreditLevel(int $creditLevel, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($creditLevel, self::$schema['properties']['creditLevel']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->creditLevel = $creditLevel;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutCreditLevel(): self
    {
        $clone = clone $this;
        unset($clone->creditLevel);

        return $clone;
    }

    /**
     * @return int|null
     */
    public function getFoo(): ?int
    {
        return $this->foo ?? null;
    }

    /**
     * @param int $foo
     * @return self
     * @param bool $validate
     */
    public function withFoo(int $foo, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo, self::$schema['properties']['foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutFoo(): self
    {
        $clone = clone $this;
        unset($clone->foo);

        return $clone;
    }

    /**
     * @return string|null
     */
    public function getBar(): ?string
    {
        return $this->bar ?? null;
    }

    /**
     * @param string $bar
     * @return self
     * @param bool $validate
     */
    public function withBar(string $bar, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($bar, self::$schema['properties']['bar']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

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

        $obj = new self($vatID);
        $obj->creditLevel = $creditLevel;
        $obj->foo = $foo;
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

