<?php

declare(strict_types=1);

namespace Example\Advanced;

class UserPaymentAlternative1
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
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
    ];

    /**
     * @var UserPaymentAlternative1Type
     */
    private UserPaymentAlternative1Type $type;

    /**
     * @param UserPaymentAlternative1Type $type
     */
    public function __construct(UserPaymentAlternative1Type $type)
    {
        $this->type = $type;
    }

    /**
     * @return UserPaymentAlternative1Type
     */
    public function getType() : UserPaymentAlternative1Type
    {
        return $this->type;
    }

    /**
     * @param UserPaymentAlternative1Type $type
     * @return self
     */
    public function withType(UserPaymentAlternative1Type $type) : self
    {
        $clone = clone $this;
        $clone->type = $type;

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return UserPaymentAlternative1 Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : UserPaymentAlternative1
    {
        if (!is_array($input) && !is_object($input)) {
            throw new \InvalidArgumentException(
                'Input to buildFromInput must be array or object, got ' . gettype($input)
            );
        }

        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $type = UserPaymentAlternative1Type::from($input->{'type'});

        $obj = new self($type);

        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toJson() : array
    {
        $output = [];
        $output['type'] = ($this->type)->value;

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
    public static function validateInput(array|object $input, bool $return = false) : bool
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
    }
}

