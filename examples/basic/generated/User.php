<?php
declare(strict_types=1);

namespace Example\Basic;

class User
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = ['type' => 'object', 'required' => ['name', 'status'], 'properties' => ['name' => ['type' => 'string'], 'address' => ['$ref' => '#/definitions/Address'], 'status' => ['anyOf' => [['enum' => ['customer', 'manager'], 'type' => 'string'], ['type' => 'null']]]], 'definitions' => ['Address' => ['type' => 'object', 'properties' => ['street' => ['type' => 'string'], 'house' => ['type' => 'integer']]]]];

    /**
     * Name of the user - required field.
     *
     * @var string
     */
    private string $name;

    /**
     * Object representing address of the user, field is optional.
     *
     * @var Address|null
     */
    private ?Address $address = null;

    /**
     * User status. Field is obligatory, but nullable.
     *
     * If target PHP is 8.1+ the type will be an `enum` with cases `CUSTOMER = 'customer'` and `MANAGER = 'manager'`
     *
     * @var 'customer'|'manager'|null
     */
    private ?string $status;

    /**
     * @param string $name
     * @param 'customer'|'manager'|null $status
     */
    public function __construct(string $name, ?string $status)
    {
        $this->name = $name;
        $this->status = $status;
    }

    /**
     * Name of the user - required field.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Object representing address of the user, field is optional.
     *
     * @return Address|null
     */
    public function getAddress(): ?Address
    {
        return $this->address ?? null;
    }

    /**
     * User status. Field is obligatory, but nullable.
     *
     * If target PHP is 8.1+ the type will be an `enum` with cases `CUSTOMER = 'customer'` and `MANAGER = 'manager'`
     *
     * @return 'customer'|'manager'|null
     */
    public function getStatus(): ?string
    {
        return $this->status ?? null;
    }

    /**
     * @param string $name
     * @return self
     * @param bool $validate
     */
    public function withName(string $name, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($name, self::$schema['properties']['name']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->name = $name;

        return $clone;
    }

    /**
     * @param Address $address
     * @return self
     */
    public function withAddress(Address $address): self
    {
        $clone = clone $this;
        $clone->address = $address;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutAddress(): self
    {
        $clone = clone $this;
        unset($clone->address);

        return $clone;
    }

    /**
     * @param 'customer'|'manager' $status
     * @return self
     * @param bool $validate
     */
    public function withStatus(?string $status, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($status, self::$schema['properties']['status']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->status = $status;

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return User Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput($input, bool $validate = true): User
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

        $name = $input->{'name'};
        $address = isset($input->{'address'}) ? Address::fromInput($input->{'address'}, $validate) : null;
        $status = ($input->{'status'} !== null) ? ($input->{'status'}) : null;

        $obj = new self($name, $status);
        $obj->address = $address;
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
        $output['name'] = $this->name;
        if (isset($this->address)) {
            $output['address'] = $this->address->toArray();
        }
        $output['status'] = $this->status;

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
        $output->{'name'} = $this->name;
        if (isset($this->address)) {
            $output->{'address'} = $this->address->toStdClass();
        }
        $output->{'status'} = $this->status;

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

