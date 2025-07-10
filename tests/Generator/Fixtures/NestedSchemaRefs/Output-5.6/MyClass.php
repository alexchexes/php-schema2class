<?php

namespace Ns\NestedSchemaRefs_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $schema = [
        'properties' => [
            'files' => [
                'type' => 'array',
                'items' => [
                    'properties' => [
                        'input' => [
                            'type' => 'string',
                        ],
                        'options' => [
                            '$ref' => '#/definitions/OptionsObject',
                        ],
                    ],
                ],
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
     * @var MyClassFilesItem[]|null
     */
    private $files = null;

    /**
     * @var OptionsObject|null
     */
    private $options = null;

    /**
     * @return MyClassFilesItem[]|null
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return OptionsObject|null
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param MyClassFilesItem[] $files
     * @return self
     * @param bool $validate
     */
    public function withFiles(array $files, bool $validate = true)
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($files, self::$schema['properties']['files']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->files = $files;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutFiles()
    {
        $clone = clone $this;
        unset($clone->files);

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

        $files = isset($input->{'files'}) ? array_map(function($i) use ($validate) { return MyClassFilesItem::buildFromInput($i, $validate); }, $input->{'files'}) : null;
        $options = isset($input->{'options'}) ? OptionsObject::buildFromInput($input->{'options'}, $validate) : null;

        $obj = new self();
        $obj->files = $files;
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
        if (isset($this->files)) {
            $output['files'] = array_map(function(MyClassFilesItem $i) { return $i->toArray(); }, $this->files);
        }
        if (isset($this->options)) {
            $output['options'] = $this->options->toArray();
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
        if (isset($this->files)) {
            $this->files = array_map(function(MyClassFilesItem $i) { return clone $i; }, $this->files);
        }
    }
}
