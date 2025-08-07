<?php

declare(strict_types=1);

namespace Example\Advanced;

class User
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'required' => [
            'firstName',
            'lastName',
        ],
        'properties' => [
            'createdAt' => [
                'type' => 'string',
                'format' => 'date-time',
            ],
            'gender' => [
                'type' => 'string',
                'enum' => [
                    'male',
                    'female',
                ],
            ],
            'firstName' => [
                'type' => 'string',
                'minLength' => 2,
            ],
            'lastName' => [
                'type' => 'string',
            ],
            'email' => [
                'type' => 'string',
                'format' => 'email',
            ],
            'billing' => [
                'allOf' => [
                    [
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
                        ],
                    ],
                    [
                        'oneOf' => [
                            [
                                'required' => [
                                    'foo',
                                ],
                                'properties' => [
                                    'foo' => [
                                        'type' => 'int',
                                    ],
                                ],
                            ],
                            [
                                'required' => [
                                    'bar',
                                ],
                                'properties' => [
                                    'bar' => [
                                        'type' => 'string',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'payment' => [
                'oneOf' => [
                    [
                        'required' => [
                            'type',
                        ],
                        'properties' => [
                            'type' => [
                                'type' => 'string',
                                'enum' => [
                                    'invoice',
                                ],
                            ],
                        ],
                    ],
                    [
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
                    ],
                    [
                        'type' => 'string',
                    ],
                ],
            ],
            'address' => [
                'required' => [
                    'city',
                    'street',
                ],
                'properties' => [
                    'city' => [
                        'type' => 'string',
                        'maxLength' => 32,
                    ],
                    'street' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'tags' => [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                    'minLength' => 1,
                ],
            ],
            'hobbies' => [
                'type' => 'array',
                'items' => [
                    'properties' => [
                        'name' => [
                            'type' => 'string',
                        ],
                    ],
                ],
            ],
        ],
    ];

    private ?\DateTime $createdAt = null;

    /**
     * @var UserGender|null
     */
    private ?UserGender $gender = null;

    private string $firstName;

    private string $lastName;

    private ?string $email = null;

    /**
     * @var UserBilling|null
     */
    private ?UserBilling $billing = null;

    /**
     * @var UserPaymentAlternative1|UserPaymentAlternative2|string|null
     */
    private UserPaymentAlternative1|UserPaymentAlternative2|string|null $payment = null;

    /**
     * @var UserAddress|null
     */
    private ?UserAddress $address = null;

    /**
     * @var string[]|null
     */
    private ?array $tags = null;

    /**
     * @var UserHobbiesItem[]|null
     */
    private ?array $hobbies = null;

    /**
     * @param UserGender|null $gender
     * @param UserBilling|null $billing
     * @param UserPaymentAlternative1|UserPaymentAlternative2|string|null $payment
     * @param UserAddress|null $address
     * @param string[]|null $tags
     * @param UserHobbiesItem[]|null $hobbies
     */
    public function __construct(string $firstName, string $lastName, ?\DateTime $createdAt = null, ?UserGender $gender = null, ?string $email = null, ?UserBilling $billing = null, UserPaymentAlternative1|UserPaymentAlternative2|string|null $payment = null, ?UserAddress $address = null, ?array $tags = null, ?array $hobbies = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->createdAt = $createdAt;
        $this->gender = $gender;
        $this->email = $email;
        $this->billing = $billing;
        $this->payment = $payment;
        $this->address = $address;
        $this->tags = $tags;
        $this->hobbies = $hobbies;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function withCreatedAt(\DateTime $createdAt): self
    {
        $clone = clone $this;
        $clone->createdAt = $createdAt;

        return $clone;
    }

    public function withoutCreatedAt(): self
    {
        $clone = clone $this;
        unset($clone->createdAt);

        return $clone;
    }

    /**
     * @return UserGender|null
     */
    public function getGender(): ?UserGender
    {
        return $this->gender;
    }

    /**
     * @param UserGender $gender
     */
    public function withGender(UserGender $gender): self
    {
        $clone = clone $this;
        $clone->gender = $gender;

        return $clone;
    }

    public function withoutGender(): self
    {
        $clone = clone $this;
        unset($clone->gender);

        return $clone;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function withFirstName(string $firstName, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($firstName, self::$_schema['properties']['firstName']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->firstName = $firstName;

        return $clone;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function withLastName(string $lastName): self
    {
        $clone = clone $this;
        $clone->lastName = $lastName;

        return $clone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function withEmail(string $email): self
    {
        $clone = clone $this;
        $clone->email = $email;

        return $clone;
    }

    public function withoutEmail(): self
    {
        $clone = clone $this;
        unset($clone->email);

        return $clone;
    }

    /**
     * @return UserBilling|null
     */
    public function getBilling(): ?UserBilling
    {
        return $this->billing;
    }

    /**
     * @param UserBilling $billing
     */
    public function withBilling(UserBilling $billing): self
    {
        $clone = clone $this;
        $clone->billing = $billing;

        return $clone;
    }

    public function withoutBilling(): self
    {
        $clone = clone $this;
        unset($clone->billing);

        return $clone;
    }

    /**
     * @return UserPaymentAlternative1|UserPaymentAlternative2|string|null
     */
    public function getPayment(): UserPaymentAlternative1|UserPaymentAlternative2|string|null
    {
        return $this->payment;
    }

    /**
     * @param UserPaymentAlternative1|UserPaymentAlternative2|string $payment
     */
    public function withPayment(UserPaymentAlternative1|UserPaymentAlternative2|string $payment): self
    {
        $clone = clone $this;
        $clone->payment = $payment;

        return $clone;
    }

    public function withoutPayment(): self
    {
        $clone = clone $this;
        unset($clone->payment);

        return $clone;
    }

    /**
     * @return UserAddress|null
     */
    public function getAddress(): ?UserAddress
    {
        return $this->address;
    }

    /**
     * @param UserAddress $address
     */
    public function withAddress(UserAddress $address): self
    {
        $clone = clone $this;
        $clone->address = $address;

        return $clone;
    }

    public function withoutAddress(): self
    {
        $clone = clone $this;
        unset($clone->address);

        return $clone;
    }

    /**
     * @return string[]|null
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }

    /**
     * @param string[] $tags
     */
    public function withTags(array $tags, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($tags, self::$_schema['properties']['tags']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->tags = $tags;

        return $clone;
    }

    public function withoutTags(): self
    {
        $clone = clone $this;
        unset($clone->tags);

        return $clone;
    }

    /**
     * @return UserHobbiesItem[]|null
     */
    public function getHobbies(): ?array
    {
        return $this->hobbies;
    }

    /**
     * @param UserHobbiesItem[] $hobbies
     */
    public function withHobbies(array $hobbies, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($hobbies, self::$_schema['properties']['hobbies']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->hobbies = $hobbies;

        return $clone;
    }

    public function withoutHobbies(): self
    {
        $clone = clone $this;
        unset($clone->hobbies);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return User Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): User
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $firstName = $input->{'firstName'};
        $lastName = $input->{'lastName'};
        $createdAt = isset($input->{'createdAt'}) ? $input->{'createdAt'} : null;
        $gender = isset($input->{'gender'}) ? UserGender::from($input->{'gender'}) : null;
        $email = isset($input->{'email'}) ? $input->{'email'} : null;
        $billing = isset($input->{'billing'}) ? UserBilling::fromInput($input->{'billing'}, $validate) : null;
        $payment = isset($input->{'payment'}) ? match (true) {
            UserPaymentAlternative1::validateInput($input->{'payment'}, true) => UserPaymentAlternative1::fromInput($input->{'payment'}, $validate),
            UserPaymentAlternative2::validateInput($input->{'payment'}, true) => UserPaymentAlternative2::fromInput($input->{'payment'}, $validate),
            is_string($input->{'payment'}) => $input->{'payment'},
            default => null,
        } : null;
        $address = isset($input->{'address'}) ? UserAddress::fromInput($input->{'address'}, $validate) : null;
        $tags = isset($input->{'tags'}) ? $input->{'tags'} : null;
        $hobbies = isset($input->{'hobbies'}) ? array_map(fn (array|object $i): UserHobbiesItem => UserHobbiesItem::fromInput($i, $validate), $input->{'hobbies'}) : null;

        $obj = new self(
            $firstName,
            $lastName,
            $createdAt,
            $gender,
            $email,
            $billing,
            $payment,
            $address,
            $tags,
            $hobbies
        );
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
        if (isset($this->createdAt)) {
            $output['createdAt'] = ($this->createdAt)->format(\DateTime::ATOM);
        }
        if (isset($this->gender)) {
            $output['gender'] = ($this->gender)->value;
        }
        $output['firstName'] = $this->firstName;
        $output['lastName'] = $this->lastName;
        if (isset($this->email)) {
            $output['email'] = $this->email;
        }
        if (isset($this->billing)) {
            $output['billing'] = ($this->billing)->toArray();
        }
        if (isset($this->payment)) {
            $output['payment'] = match (true) {
                $this->payment instanceof UserPaymentAlternative1,
                $this->payment instanceof UserPaymentAlternative2 => ($this->payment)->toArray(),
                is_string($this->payment) => $this->payment,
            };
        }
        if (isset($this->address)) {
            $output['address'] = ($this->address)->toArray();
        }
        if (isset($this->tags)) {
            $output['tags'] = $this->tags;
        }
        if (isset($this->hobbies)) {
            $output['hobbies'] = array_map(fn (UserHobbiesItem $i) => $i->toArray(), $this->hobbies);
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
        if (isset($this->createdAt)) {
            $output->{'createdAt'} = $this->createdAt;
        }
        if (isset($this->gender)) {
            $output->{'gender'} = ($this->gender)->value;
        }
        $output->{'firstName'} = $this->firstName;
        $output->{'lastName'} = $this->lastName;
        if (isset($this->email)) {
            $output->{'email'} = $this->email;
        }
        if (isset($this->billing)) {
            $output->{'billing'} = ($this->billing)->toStdClass();
        }
        if (isset($this->payment)) {
            $output->{'payment'} = match (true) {
                $this->payment instanceof UserPaymentAlternative1,
                $this->payment instanceof UserPaymentAlternative2 => ($this->payment)->toStdClass(),
                is_string($this->payment) => $this->payment,
            };
        }
        if (isset($this->address)) {
            $output->{'address'} = ($this->address)->toStdClass();
        }
        if (isset($this->tags)) {
            $output->{'tags'} = $this->tags;
        }
        if (isset($this->hobbies)) {
            $output->{'hobbies'} = array_map(fn (UserHobbiesItem $i) => $i->toStdClass(), $this->hobbies);
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

    public function __clone()
    {
        if (isset($this->createdAt)) {
            $this->createdAt = clone $this->createdAt;
        }
        if (isset($this->billing)) {
            $this->billing = clone $this->billing;
        }
        if (isset($this->payment)) {
            $this->payment = match (true) {
                $this->payment instanceof UserPaymentAlternative1,
                $this->payment instanceof UserPaymentAlternative2 => clone $this->payment,
                is_string($this->payment) => $this->payment,
            };
        }
        if (isset($this->address)) {
            $this->address = clone $this->address;
        }
        if (isset($this->hobbies)) {
            $this->hobbies = array_map(fn (UserHobbiesItem $i) => clone $i, $this->hobbies);
        }
    }
}

