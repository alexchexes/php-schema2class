<?php

namespace Ns\EscapeDefinitionName_5_6;

class BarTest
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
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
     * @var FooTest|MoiKlass|FooTest_1|null
     */
    private $exampleProp = null;

    /**
     * @return FooTest|MoiKlass|FooTest_1|null
     */
    public function getExampleProp()
    {
        return $this->exampleProp;
    }

    /**
     * @param FooTest|MoiKlass|FooTest_1 $exampleProp
     * @return self
     */
    public function withExampleProp($exampleProp)
    {
        $clone = clone $this;
        $clone->exampleProp = $exampleProp;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutExampleProp()
    {
        $clone = clone $this;
        unset($clone->exampleProp);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return BarTest Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput($input, bool $validate = true)
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

        $exampleProp = isset($input->{'exampleProp'}) ? ((FooTest_1::validateInput($input->{'exampleProp'}, true)) ? FooTest_1::fromInput($input->{'exampleProp'}, $validate) : (((MoiKlass::validateInput($input->{'exampleProp'}, true)) ? MoiKlass::fromInput($input->{'exampleProp'}, $validate) : (((FooTest::validateInput($input->{'exampleProp'}, true)) ? FooTest::fromInput($input->{'exampleProp'}, $validate) : (null)))))) : null;

        $obj = new self();
        $obj->exampleProp = $exampleProp;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray()
    {
        $output = [];
        if (isset($this->exampleProp)) {
            if ((($this->exampleProp) instanceof FooTest) || (($this->exampleProp) instanceof MoiKlass) || (($this->exampleProp) instanceof FooTest_1)) {
                $output['exampleProp'] = $this->exampleProp->toArray();
            }
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
        if (isset($this->exampleProp)) {
            if ((($this->exampleProp) instanceof FooTest) || (($this->exampleProp) instanceof MoiKlass) || (($this->exampleProp) instanceof FooTest_1)) {
            $output->{'exampleProp'} = $this->exampleProp->toStdClass();
            }
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
    public static function validateInput($input, $return = false)
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function($e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }

    public function __clone()
    {
        if (isset($this->exampleProp)) {
            $this->exampleProp = (($this->exampleProp) instanceof FooTest_1) ? ($this->exampleProp) : ((($this->exampleProp) instanceof MoiKlass) ? ($this->exampleProp) : ((($this->exampleProp) instanceof FooTest) ? ($this->exampleProp) : ($this->exampleProp)));
        }
    }
}
