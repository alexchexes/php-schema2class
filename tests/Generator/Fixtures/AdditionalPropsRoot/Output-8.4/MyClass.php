<?php

declare(strict_types=1);

namespace Ns\AdditionalPropsRoot_8_4;

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

    private ?string $name = null;

    private array|object|null $params = null;

    public function __construct(?string $name = null, array|object|null $params = null)
    {
        $this->name = $name;
        $this->params = $params;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function withName(string $name): self
    {
        $clone = clone $this;
        $clone->name = $name;

        return $clone;
    }

    public function withoutName(): self
    {
        $clone = clone $this;
        unset($clone->name);

        return $clone;
    }

    public function getParams(): array|object|null
    {
        return $this->params;
    }

    public function withParams(array|object $params): self
    {
        $clone = clone $this;
        $clone->params = $params;

        return $clone;
    }

    public function withoutParams(): self
    {
        $clone = clone $this;
        unset($clone->params);

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

        $name = isset($input->{'name'}) ? $input->{'name'} : null;
        $params = isset($input->{'params'}) ? $input->{'params'} : null;

        $obj = new self($name, $params);
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

    // When there is at least one additional property, this is not null, otherwise always null (no empty stdClass objects!)
    private ?object $_additionalProperies = null;

    // getter for the whole set
    public function additionalProperties(bool $associative = false): array|object|null
    {
        if ($associative && $this->_additionalProperies) {
            return json_decode(json_encode($this->_additionalProperies), true);
        }
        return $this->_additionalProperies;
    }

    // setter for the whole set
    public function addAdditionalProperties(array|object $additionalProperties, bool $validate = true): self
    {
        $this->_additionalProperies = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;
        if ($validate) {
            $this->validate();
        }
        return $this;
    }

    // unsetter for the whole set
    public function removeAdditionalProperties(): self
    {
        $this->_additionalProperies = null;
        return $this;
    }

    // setter for only one additional property
    public function addAdditionalProperty(string $name, mixed $value, bool $validate = true): self
    {
        $this->_additionalProperies->{$name} = $value;
        if ($validate) {
            $this->validate();
        }
        return $this;
    }

    // unsetter for just one additional property
    public function removeAdditionalProperty(string $name): self
    {
        unset($this->_additionalProperies->{$name});
        if ((array) $this->_additionalProperies === []) {
            $this->_additionalProperies = null;
        }
        return $this;
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
