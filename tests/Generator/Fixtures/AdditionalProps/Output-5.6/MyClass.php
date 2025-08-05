<?php

namespace Ns\AdditionalProps_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
        'type' => 'object',
        'properties' => [
            'name' => [
                'type' => 'string',
            ],
            'params' => [
                'type' => 'object',
                'additionalProperties' => [
                    
                ],
            ],
        ],
    ];

    /**
     * @var string|null
     */
    private $name = null;

    /**
     * @var mixed[]|null
     */
    private $params = null;

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @param bool $validate
     * @return self
     */
    public function withName($name, $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($name, self::$_schema['properties']['name']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->name = $name;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutName()
    {
        $clone = clone $this;
        unset($clone->name);

        return $clone;
    }

    /**
     * @return mixed[]|null
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed[] $params
     * @return self
     */
    public function withParams(array $params)
    {
        $clone = clone $this;
        $clone->params = $params;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutParams()
    {
        $clone = clone $this;
        unset($clone->params);

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

        $name = isset($input->{'name'}) ? $input->{'name'} : null;
        $params = isset($input->{'params'}) ? (array)$input->{'params'} : null;

        $obj = new self();
        $obj->name = $name;
        $obj->params = $params;
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
        if (isset($this->name)) {
            $output['name'] = $this->name;
        }
        if (isset($this->params)) {
            $output['params'] = $this->params;
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
        if (isset($this->name)) {
            $output->{'name'} = $this->name;
        }
        if (isset($this->params)) {
            $output->{'params'} = $this->params;
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
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(function($e) {
                return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];
            }, $validator->getErrors());
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }
}
