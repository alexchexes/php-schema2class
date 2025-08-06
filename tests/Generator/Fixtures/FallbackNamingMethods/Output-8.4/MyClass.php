<?php

declare(strict_types=1);

namespace Ns\FallbackNamingMethods_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'properties' => [
            'bound' => [
                'type' => 'string',
            ],
            'outbound' => [
                'type' => 'string',
            ],
            '_outbound' => [
                'type' => 'string',
            ],
        ],
    ];

    private ?string $bound = null;

    private ?string $outbound = null;

    private ?string $_outbound = null;

    public function __construct(?string $bound = null, ?string $outbound = null, ?string $_outbound = null)
    {
        $this->bound = $bound;
        $this->outbound = $outbound;
        $this->_outbound = $_outbound;
    }

    public function getBound(): ?string
    {
        return $this->bound ?? null;
    }

    public function withBound(string $bound): self
    {
        $clone = clone $this;
        $clone->bound = $bound;

        return $clone;
    }

    public function withoutBound(): self
    {
        $clone = clone $this;
        unset($clone->bound);

        return $clone;
    }

    public function get_Outbound(): ?string
    {
        return $this->outbound ?? null;
    }

    public function with_Outbound(string $outbound): self
    {
        $clone = clone $this;
        $clone->outbound = $outbound;

        return $clone;
    }

    public function without_Outbound(): self
    {
        $clone = clone $this;
        unset($clone->outbound);

        return $clone;
    }

    public function get_Outbound_1(): ?string
    {
        return $this->_outbound ?? null;
    }

    public function with_Outbound_1(string $_outbound): self
    {
        $clone = clone $this;
        $clone->_outbound = $_outbound;

        return $clone;
    }

    public function without_Outbound_1(): self
    {
        $clone = clone $this;
        unset($clone->_outbound);

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


        $bound = isset($input->{'bound'}) ? $input->{'bound'} : null;
        $outbound = isset($input->{'outbound'}) ? $input->{'outbound'} : null;
        $_outbound = isset($input->{'_outbound'}) ? $input->{'_outbound'} : null;

        $obj = new self($bound, $outbound, $_outbound);
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
        if (isset($this->bound)) {
            $output['bound'] = $this->bound;
        }
        if (isset($this->outbound)) {
            $output['outbound'] = $this->outbound;
        }
        if (isset($this->_outbound)) {
            $output['_outbound'] = $this->_outbound;
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
        if (isset($this->bound)) {
            $output->{'bound'} = $this->bound;
        }
        if (isset($this->outbound)) {
            $output->{'outbound'} = $this->outbound;
        }
        if (isset($this->_outbound)) {
            $output->{'_outbound'} = $this->_outbound;
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
