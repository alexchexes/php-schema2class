<?php

namespace Ns\RecursiveField_5_6;

class MyRecursiveObject
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
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

    /**
     * @var MyRecursiveObject|null
     */
    private $MyRecursiveObject = null;

    /**
     * @param MyRecursiveObject|null $MyRecursiveObject
     */
    public function __construct(MyRecursiveObject $MyRecursiveObject = null)
    {
        $this->MyRecursiveObject = $MyRecursiveObject;
    }

    /**
     * @return MyRecursiveObject|null
     */
    public function getMyRecursiveObject()
    {
        return isset($this->MyRecursiveObject) ? $this->MyRecursiveObject : null;
    }

    /**
     * @return self
     */
    public function withMyRecursiveObject(MyRecursiveObject $MyRecursiveObject)
    {
        $clone = clone $this;
        $clone->MyRecursiveObject = $MyRecursiveObject;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutMyRecursiveObject()
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
    public static function fromInput($input, $validate = true)
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

        $MyRecursiveObject = isset($input->{'MyRecursiveObject'})
            ? MyRecursiveObject::fromInput($input->{'MyRecursiveObject'}, $validate)
            : null;

        $obj = new self($MyRecursiveObject);

        return $obj;
    }

    /**
     * Converts this object to array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray()
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
    public function toStdClass()
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
    public function validate($return = false)
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
    public static function validateInput($input, $return = false)
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function(array $e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
