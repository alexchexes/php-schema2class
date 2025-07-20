<?php

namespace Ns\NestedSchemaRefs_5_6;

class MyClassFilesItem
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
        'properties' => [
            'input' => [
                'type' => 'string',
            ],
            'options' => [
                '$ref' => '#/definitions/OptionsObject',
            ],
        ],
        'definitions' => [
            'OptionsObject' => [
                'properties' => [
                    'output' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var string|null
     */
    private $input = null;

    /**
     * @var OptionsObject|null
     */
    private $options = null;

    /**
     * @return string|null
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return OptionsObject|null
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $input
     * @return self
     * @param bool $validate
     */
    public function withInput($input, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($input, self::$schema['properties']['input']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->input = $input;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutInput()
    {
        $clone = clone $this;
        unset($clone->input);

        return $clone;
    }

    /**
     * @param OptionsObject $options
     * @return self
     */
    public function withOptions(OptionsObject $options)
    {
        $clone = clone $this;
        $clone->options = $options;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptions()
    {
        $clone = clone $this;
        unset($clone->options);

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return MyClassFilesItem Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput($input, bool $validate = true)
    {
        $_input = $input;
        unset($input);

        if (!is_array($_input) && !is_object($_input)) {
            throw new \InvalidArgumentException(
                'Input to buildFromInput must be array or object, got ' . gettype($_input)
            );
        }

        $_input = is_array($_input) ? \JsonSchema\Validator::arrayToObjectRecursive($_input) : $_input;
        if ($validate) {
            static::validateInput($_input);
        }

        $input = isset($_input->{'input'}) ? $_input->{'input'} : null;
        $options = isset($_input->{'options'}) ? OptionsObject::buildFromInput($_input->{'options'}, $validate) : null;

        $obj = new self();
        $obj->input = $input;
        $obj->options = $options;
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
        if (isset($this->input)) {
            $output['input'] = $this->input;
        }
        if (isset($this->options)) {
            $output['options'] = $this->options->toArray();
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
        if (isset($this->input)) {
            $output->{'input'} = $this->input;
        }
        if (isset($this->options)) {
            $output->{'options'} = $this->options->toObject();
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
