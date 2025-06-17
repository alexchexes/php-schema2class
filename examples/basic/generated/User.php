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
    private static array $schema = [
        'required' => [
            'givenName',
            'familyName',
        ],
        'properties' => [
            'givenName' => [
                'type' => 'string',
            ],
            'familyName' => [
                'type' => 'string',
            ],
            'hobbies' => [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                ],
            ],
            'location' => [
                'properties' => [
                    'country' => [
                        'type' => 'string',
                    ],
                    'city' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var string
     */
    private string $givenName;

    /**
     * @var string
     */
    private string $familyName;

    /**
     * @var string[]|null
     */
    private ?array $hobbies = null;

    /**
     * @var UserLocation|null
     */
    private ?UserLocation $location = null;

    /**
     * @param string $givenName
     * @param string $familyName
     */
    public function __construct(string $givenName, string $familyName)
    {
        $this->givenName = $givenName;
        $this->familyName = $familyName;
    }

    /**
     * @return string
     */
    public function getGivenName() : string
    {
        return $this->givenName;
    }

    /**
     * @return string
     */
    public function getFamilyName() : string
    {
        return $this->familyName;
    }

    /**
     * @return string[]|null
     */
    public function getHobbies() : ?array
    {
        return $this->hobbies ?? null;
    }

    /**
     * @return UserLocation|null
     */
    public function getLocation() : ?UserLocation
    {
        return $this->location ?? null;
    }

    /**
     * @param string $givenName
     * @return self
     */
    public function withGivenName(string $givenName) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($givenName, self::$schema['properties']['givenName']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->givenName = $givenName;

        return $clone;
    }

    /**
     * @param string $familyName
     * @return self
     */
    public function withFamilyName(string $familyName) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($familyName, self::$schema['properties']['familyName']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->familyName = $familyName;

        return $clone;
    }

    /**
     * @param string[] $hobbies
     * @return self
     */
    public function withHobbies(array $hobbies) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($hobbies, self::$schema['properties']['hobbies']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
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
     * @param UserLocation $location
     * @return self
     */
    public function withLocation(UserLocation $location) : self
    {
        $clone = clone $this;
        $clone->location = $location;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutLocation() : self
    {
        $clone = clone $this;
        unset($clone->location);

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
    public static function buildFromInput(array|object $input, bool $validate = true) : User
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

        $givenName = $input->{'givenName'};
        $familyName = $input->{'familyName'};
        $hobbies = isset($input->{'hobbies'}) ? $input->{'hobbies'} : null;
        $location = isset($input->{'location'}) ? UserLocation::buildFromInput($input->{'location'}, validate: $validate) : null;

        $obj = new self($givenName, $familyName);
        $obj->hobbies = $hobbies;
        $obj->location = $location;
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
        $output['givenName'] = $this->givenName;
        $output['familyName'] = $this->familyName;
        if (isset($this->hobbies)) {
            $output['hobbies'] = $this->hobbies;
        }
        if (isset($this->location)) {
            $output['location'] = ($this->location)->toJson();
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
        if (isset($this->location)) {
            $this->location = clone $this->location;
        }
    }
}

