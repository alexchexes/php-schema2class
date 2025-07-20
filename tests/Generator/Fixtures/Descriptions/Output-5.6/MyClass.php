<?php

namespace Ns\Descriptions_5_6;

/**
 * Simple object with two preperties and a description
 */
class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
        'type' => 'object',
        'description' => 'Simple object with two preperties and a description',
        'properties' => [
            'foo' => [
                'type' => 'string',
                'description' => 'Description for the `foo` property',
            ],
            'bar' => [
                '$ref' => '#/definitions/Baz',
            ],
        ],
        'definitions' => [
            'Baz' => [
                'type' => 'object',
                'properties' => [
                    'name' => [
                        'type' => 'string',
                        'description' => 'Description of the `name` property in `Baz` definition',
                    ],
                ],
                'description' => 'Description  of the `Baz` definition',
            ],
        ],
    ];

    /**
     * Description for the `foo` property
     *
     * @var string|null
     */
    private $foo = null;

    /**
     * @var Baz|null
     */
    private $bar = null;

    /**
     * Description for the `foo` property
     *
     * @return string|null
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @return Baz|null
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param string $foo
     * @return self
     * @param bool $validate
     */
    public function withFoo($foo, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($foo, self::$schema['properties']['foo']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->foo = $foo;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutFoo()
    {
        $clone = clone $this;
        unset($clone->foo);

        return $clone;
    }

    /**
     * @param Baz $bar
     * @return self
     */
    public function withBar(Baz $bar)
    {
        $clone = clone $this;
        $clone->bar = $bar;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutBar()
    {
        $clone = clone $this;
        unset($clone->bar);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MyClass Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput($input, bool $validate = true)
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

        $foo = isset($input->{'foo'}) ? $input->{'foo'} : null;
        $bar = isset($input->{'bar'}) ? Baz::buildFromInput($input->{'bar'}, $validate) : null;

        $obj = new self();
        $obj->foo = $foo;
        $obj->bar = $bar;
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
        if (isset($this->foo)) {
            $output['foo'] = $this->foo;
        }
        if (isset($this->bar)) {
            $output['bar'] = $this->bar->toArray();
        }

        return $output;
    }

    /**
     * Converts this object back to a stdClass that can be JSON-serialized
     *
     * @return stdClass Converted object
     */
    public function toObject()
    {
        $output = new \stdClass();
        if (isset($this->foo)) {
            $output->{'foo'} = $this->foo;
        }
        if (isset($this->bar)) {
            $output->{'bar'} = $this->bar->toObject();
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
}
