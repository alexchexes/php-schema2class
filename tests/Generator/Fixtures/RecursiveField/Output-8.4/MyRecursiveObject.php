<?php

declare(strict_types=1);

namespace Ns\RecursiveField_8_4;

class MyRecursiveObject
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $_schema = [
        'additionalProperties' => false,
        'properties' => [
            'MyRecursiveObject' => [
                '$ref' => '#/definitions/MyRecursiveObject',
            ],
        ],
        'type' => 'object',
        'definitions' => [
            'MyRecursiveObject' => [
                'additionalProperties' => false,
                'properties' => [
                    'MyRecursiveObject' => [
                        '$ref' => '#/definitions/MyRecursiveObject',
                    ],
                ],
                'type' => 'object',
            ],
        ],
    ];

    private ?MyRecursiveObject $MyRecursiveObject = null;

    public function __construct(?MyRecursiveObject $MyRecursiveObject = null)
    {
        $this->MyRecursiveObject = $MyRecursiveObject;
    }

    public function getMyRecursiveObject(): ?MyRecursiveObject
    {
        return $this->MyRecursiveObject ?? null;
    }

    public function withMyRecursiveObject(MyRecursiveObject $MyRecursiveObject): self
    {
        $clone = clone $this;
        $clone->MyRecursiveObject = $MyRecursiveObject;

        return $clone;
    }

    public function withoutMyRecursiveObject(): self
    {
        $clone = clone $this;
        unset($clone->MyRecursiveObject);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return MyRecursiveObject Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): MyRecursiveObject
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $MyRecursiveObject = isset($input->{'MyRecursiveObject'}) ? MyRecursiveObject::fromInput($input->{'MyRecursiveObject'}, $validate) : null;

        $obj = new self($MyRecursiveObject);

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
        if (isset($this->MyRecursiveObject)) {
            $output['MyRecursiveObject'] = $this->MyRecursiveObject->toArray();
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
        if (isset($this->MyRecursiveObject)) {
            $output->{'MyRecursiveObject'} = $this->MyRecursiveObject->toStdClass();
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
}
