<?php

declare(strict_types=1);

namespace Ns\AdditionalPropsRoot_7_4;

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
            'name' => [
                'type' => 'string',
            ],
            'params' => [
                'type' => 'object',
            ],
        ],
        'additionalProperties' => [
            
        ],
    ];

    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @var array|object|null
     */
    private $params = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    /**
     * @param string $name
     * @return self
     */
    public function withName(string $name): self
    {
        $clone = clone $this;
        $clone->name = $name;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutName(): self
    {
        $clone = clone $this;
        unset($clone->name);

        return $clone;
    }

    /**
     * @return array|object|null
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array|object $params
     * @return self
     */
    public function withParams($params): self
    {
        $clone = clone $this;
        $clone->params = $params;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutParams(): self
    {
        $clone = clone $this;
        unset($clone->params);

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

        $name = isset($input->{'name'}) ? $input->{'name'} : null;
        $params = isset($input->{'params'}) ? $input->{'params'} : null;

        $obj = new self();
        $obj->name = $name;
        $obj->params = $params;
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
        if (isset($this->name)) {
            $output['name'] = $this->name;
        }
        if (isset($this->params)) {
            $output['params'] = json_decode(json_encode($this->params), true);
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
        if (isset($this->name)) {
            $output->{'name'} = $this->name;
        }
        if (isset($this->params)) {
            $output->{'params'} = json_decode(json_encode($this->params));
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
