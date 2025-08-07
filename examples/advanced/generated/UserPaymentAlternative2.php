<?php

declare(strict_types=1);

namespace Example\Advanced;

class UserPaymentAlternative2
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'required' => [
            'type',
            'accountNumber',
        ],
        'properties' => [
            'type' => [
                'type' => 'string',
                'enum' => [
                    'debit',
                ],
            ],
            'accountNumber' => [
                'type' => 'string',
            ],
        ],
    ];

    /**
     * @var UserPaymentAlternative2Type
     */
    private UserPaymentAlternative2Type $type;

    private string $accountNumber;

    /**
     * @param UserPaymentAlternative2Type $type
     */
    public function __construct(UserPaymentAlternative2Type $type, string $accountNumber)
    {
        $this->type = $type;
        $this->accountNumber = $accountNumber;
    }

    /**
     * @return UserPaymentAlternative2Type
     */
    public function getType(): UserPaymentAlternative2Type
    {
        return $this->type;
    }

    /**
     * @param UserPaymentAlternative2Type $type
     */
    public function withType(UserPaymentAlternative2Type $type): self
    {
        $clone = clone $this;
        $clone->type = $type;

        return $clone;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function withAccountNumber(string $accountNumber): self
    {
        $clone = clone $this;
        $clone->accountNumber = $accountNumber;

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return UserPaymentAlternative2 Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): UserPaymentAlternative2
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $type = UserPaymentAlternative2Type::from($input->{'type'});
        $accountNumber = $input->{'accountNumber'};


        $obj = new self($type, $accountNumber);
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
        $output['type'] = ($this->type)->value;
        $output['accountNumber'] = $this->accountNumber;

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
        $output->{'type'} = ($this->type)->value;
        $output->{'accountNumber'} = $this->accountNumber;

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

