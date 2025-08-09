<?php

declare(strict_types=1);

namespace Ns\EscapeDefinitionName_8_4;

class BarTest
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'type' => 'object',
        'properties' => [
            'exampleProp' => [
                'anyOf' => [
                    [
                        '$ref' => '#/definitions/Foo<Test>',
                    ],
                    [
                        '$ref' => '#/definitions/МойКласс',
                    ],
                    [
                        '$ref' => '#/definitions/FooTest',
                    ],
                ],
            ],
        ],
        'definitions' => [
            'Foo<Test>' => [
                'type' => 'object',
                'properties' => [
                    'a' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'FooTest' => [
                'type' => 'object',
                'properties' => [
                    'b' => [
                        'type' => 'string',
                    ],
                ],
            ],
            'МойКласс' => [
                'type' => 'object',
                'properties' => [
                    'c' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    private FooTest|FooTest_1|MoiKlass|null $exampleProp = null;

    public function __construct(FooTest|FooTest_1|MoiKlass|null $exampleProp = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->exampleProp = $exampleProp;
    }

    /**
     * Object (`stdClass`) or array with name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return an associative array instead of `stdClass` object.
     */
    public function getAdditionalProperties(bool $asArray = true): \stdClass|array
    {
        return $asArray
            ? json_decode(json_encode($this->_additionalProperties), true)
            : $this->_additionalProperties;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param \stdClass|array $additionalProperties Map of property name/value pairs to add.
     */
    public function withAdditionalProperties(\stdClass|array $additionalProperties): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        return $clone;
    }

    /**
     * Removes all extra properties not specified in the schema.
     */
    public function withoutAdditionalProperties(): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = new \stdClass();
        return $clone;
    }

    public function getExampleProp(): FooTest|FooTest_1|MoiKlass|null
    {
        return $this->exampleProp ?? null;
    }

    public function withExampleProp(FooTest|FooTest_1|MoiKlass $exampleProp): self
    {
        $clone = clone $this;
        $clone->exampleProp = $exampleProp;

        return $clone;
    }

    public function withoutExampleProp(): self
    {
        $clone = clone $this;
        unset($clone->exampleProp);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return BarTest Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): BarTest
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $exampleProp = isset($input->{'exampleProp'}) ? match (true) {
            FooTest::validateInput($input->{'exampleProp'}, true) => FooTest::fromInput($input->{'exampleProp'}, $validate),
            MoiKlass::validateInput($input->{'exampleProp'}, true) => MoiKlass::fromInput($input->{'exampleProp'}, $validate),
            FooTest_1::validateInput($input->{'exampleProp'}, true) => FooTest_1::fromInput($input->{'exampleProp'}, $validate),
            default => null,
        } : null;

        $obj = new self($exampleProp);

        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

        if (isset($this->exampleProp)) {
            $output['exampleProp'] = match (true) {
                $this->exampleProp instanceof FooTest,
                $this->exampleProp instanceof MoiKlass,
                $this->exampleProp instanceof FooTest_1 => $this->exampleProp->toArray(),
            };
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
        $output = $this->_additionalProperties;

        if (isset($this->exampleProp)) {
            $output->{'exampleProp'} = match (true) {
                $this->exampleProp instanceof FooTest,
                $this->exampleProp instanceof MoiKlass,
                $this->exampleProp instanceof FooTest_1 => $this->exampleProp->toStdClass(),
            };
        }

        return $output;
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

    public function __clone()
    {
        if (isset($this->exampleProp)) {
            $this->exampleProp = match (true) {
                $this->exampleProp instanceof FooTest,
                $this->exampleProp instanceof MoiKlass,
                $this->exampleProp instanceof FooTest_1 => $this->exampleProp,
            };
        }
    }
}
