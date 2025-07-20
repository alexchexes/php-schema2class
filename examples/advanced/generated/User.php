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
    private static array $schema = [
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

    /**
     * @var \DateTime|null
     */
    private ?\DateTime $createdAt = null;

    /**
     * @var UserGender|null
     */
    private ?UserGender $gender = null;

    /**
     * @var string
     */
    private string $firstName;

    /**
     * @var string
     */
    private string $lastName;

    /**
     * @var string|null
     */
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
     * @param string $firstName
     * @param string $lastName
     */
    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt ?? null;
    }

    /**
     * @return UserGender|null
     */
    public function getGender(): ?UserGender
    {
        return $this->gender ?? null;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email ?? null;
    }

    /**
     * @return UserBilling|null
     */
    public function getBilling(): ?UserBilling
    {
        return $this->billing ?? null;
    }

    /**
     * @return UserPaymentAlternative1|UserPaymentAlternative2|string|null
     */
    public function getPayment(): UserPaymentAlternative1|UserPaymentAlternative2|string|null
    {
        return $this->payment;
    }

    /**
     * @return UserAddress|null
     */
    public function getAddress(): ?UserAddress
    {
        return $this->address ?? null;
    }

    /**
     * @return string[]|null
     */
    public function getTags(): ?array
    {
        return $this->tags ?? null;
    }

    /**
     * @return UserHobbiesItem[]|null
     */
    public function getHobbies(): ?array
    {
        return $this->hobbies ?? null;
    }

    /**
     * @param \DateTime $createdAt
     * @return self
     */
    public function withCreatedAt(\DateTime $createdAt) : self
    {
        $clone = clone $this;
        $clone->createdAt = $createdAt;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutCreatedAt() : self
    {
        $clone = clone $this;
        unset($clone->createdAt);

        return $clone;
    }

    /**
     * @param UserGender $gender
     * @return self
     */
    public function withGender(UserGender $gender) : self
    {
        $clone = clone $this;
        $clone->gender = $gender;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutGender() : self
    {
        $clone = clone $this;
        unset($clone->gender);

        return $clone;
    }

    /**
     * @param string $firstName
     * @return self
     * @param bool $validate
     */
    public function withFirstName(string $firstName, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($firstName, self::$schema['properties']['firstName']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->firstName = $firstName;

        return $clone;
    }

    /**
     * @param string $lastName
     * @return self
     * @param bool $validate
     */
    public function withLastName(string $lastName, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($lastName, self::$schema['properties']['lastName']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->lastName = $lastName;

        return $clone;
    }

    /**
     * @param string $email
     * @return self
     * @param bool $validate
     */
    public function withEmail(string $email, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($email, self::$schema['properties']['email']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->email = $email;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutEmail() : self
    {
        $clone = clone $this;
        unset($clone->email);

        return $clone;
    }

    /**
     * @param UserBilling $billing
     * @return self
     */
    public function withBilling(UserBilling $billing) : self
    {
        $clone = clone $this;
        $clone->billing = $billing;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBilling() : self
    {
        $clone = clone $this;
        unset($clone->billing);

        return $clone;
    }

    /**
     * @param UserPaymentAlternative1|UserPaymentAlternative2|string $payment
     * @return self
     */
    public function withPayment(UserPaymentAlternative1|UserPaymentAlternative2|string $payment) : self
    {
        $clone = clone $this;
        $clone->payment = $payment;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutPayment() : self
    {
        $clone = clone $this;
        unset($clone->payment);

        return $clone;
    }

    /**
     * @param UserAddress $address
     * @return self
     */
    public function withAddress(UserAddress $address) : self
    {
        $clone = clone $this;
        $clone->address = $address;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutAddress() : self
    {
        $clone = clone $this;
        unset($clone->address);

        return $clone;
    }

    /**
     * @param string[] $tags
     * @return self
     * @param bool $validate
     */
    public function withTags(array $tags, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($tags, self::$schema['properties']['tags']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->tags = $tags;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutTags() : self
    {
        $clone = clone $this;
        unset($clone->tags);

        return $clone;
    }

    /**
     * @param UserHobbiesItem[] $hobbies
     * @return self
     * @param bool $validate
     */
    public function withHobbies(array $hobbies, bool $validate = true) : self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($hobbies, self::$schema['properties']['hobbies']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->hobbies = $hobbies;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutHobbies() : self
    {
        $clone = clone $this;
        unset($clone->hobbies);

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
    public static function buildFromInput(array|object $input, bool $validate = true): User
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $createdAt = isset($input->{'createdAt'}) ? $input->{'createdAt'} : null;
        $gender = isset($input->{'gender'}) ? UserGender::from($input->{'gender'}) : null;
        $firstName = $input->{'firstName'};
        $lastName = $input->{'lastName'};
        $email = isset($input->{'email'}) ? $input->{'email'} : null;
        $billing = isset($input->{'billing'}) ? UserBilling::buildFromInput($input->{'billing'}, $validate) : null;
        $payment = isset($input->{'payment'}) ? match (true) {
            UserPaymentAlternative1::validateInput($input->{'payment'}, true) => UserPaymentAlternative1::buildFromInput($input->{'payment'}, $validate),
            UserPaymentAlternative2::validateInput($input->{'payment'}, true) => UserPaymentAlternative2::buildFromInput($input->{'payment'}, $validate),
            is_string($input->{'payment'}) => $input->{'payment'},
            default => null,
        } : null;
        $address = isset($input->{'address'}) ? UserAddress::buildFromInput($input->{'address'}, $validate) : null;
        $tags = isset($input->{'tags'}) ? $input->{'tags'} : null;
        $hobbies = isset($input->{'hobbies'}) ? array_map(fn (array|object $i): UserHobbiesItem => UserHobbiesItem::buildFromInput($i, $validate), $input->{'hobbies'}) : null;

        $obj = new self($firstName, $lastName);
        $obj->createdAt = $createdAt;
        $obj->gender = $gender;
        $obj->email = $email;
        $obj->billing = $billing;
        $obj->payment = $payment;
        $obj->address = $address;
        $obj->tags = $tags;
        $obj->hobbies = $hobbies;
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

