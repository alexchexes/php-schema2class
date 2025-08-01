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
    private static array $schema = [
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

    /**
     * @var string|null
     */
    private ?string $bound = null;

    /**
     * @var string|null
     */
    private ?string $outbound = null;

    /**
     * @var string|null
     */
    private ?string $_outbound = null;

    /**
     * @return string|null
     */
    public function getBound(): ?string
    {
        return $this->bound ?? null;
    }

    /**
     * @return string|null
     */
    public function getOutbound(): ?string
    {
        return $this->outbound ?? null;
    }

    /**
     * @return string|null
     */
    public function get_Outbound(): ?string
    {
        return $this->_outbound ?? null;
    }

    /**
     * @param string $bound
     * @return self
     * @param bool $validate
     */
    public function withBound(string $bound, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($bound, self::$schema['properties']['bound']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->bound = $bound;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBound(): self
    {
        $clone = clone $this;
        unset($clone->bound);

        return $clone;
    }

    /**
     * @param string $outbound
     * @return self
     * @param bool $validate
     */
    public function with_Outbound(string $outbound, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($outbound, self::$schema['properties']['outbound']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->outbound = $outbound;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOutbound(): self
    {
        $clone = clone $this;
        unset($clone->outbound);

        return $clone;
    }

    /**
     * @param string $_outbound
     * @return self
     * @param bool $validate
     */
    public function with__Outbound(string $_outbound, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($_outbound, self::$schema['properties']['_outbound']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->_outbound = $_outbound;

        return $clone;
    }

    /**
     * @return self
     */
    public function without_Outbound(): self
    {
        $clone = clone $this;
        unset($clone->_outbound);

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

        $bound = isset($input->{'bound'}) ? $input->{'bound'} : null;
        $outbound = isset($input->{'outbound'}) ? $input->{'outbound'} : null;
        $_outbound = isset($input->{'_outbound'}) ? $input->{'_outbound'} : null;

        $obj = new self();
        $obj->bound = $bound;
        $obj->outbound = $outbound;
        $obj->_outbound = $_outbound;
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
