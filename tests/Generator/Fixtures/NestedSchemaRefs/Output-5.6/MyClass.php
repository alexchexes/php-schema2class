<?php

namespace Ns\NestedSchemaRefs_5_6;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static $_schema = [
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
     * Map of name/value pairs for properties not specified in the schema.
     *
     * @var \stdClass
     */
    private $_additionalProperties;

    /**
     * @var MyClassFilesItem[]|null
     */
    private $files = null;

    /**
     * @var OptionsObject|null
     */
    private $options = null;

    /**
     * @param MyClassFilesItem[]|null $files
     * @param OptionsObject|null $options
     */
    public function __construct(array $files = null, OptionsObject $options = null)
    {
        $this->_additionalProperties = new \stdClass();

        $this->files = $files;
        $this->options = $options;
    }

    /**
     * Object (`stdClass`) or array with name/value pairs for properties not specified in the schema.
     *
     * @param bool $asArray Whether return an associative array instead of `stdClass` object.
     * @return array|\stdClass
     */
    public function getAdditionalProperties($asArray = true)
    {
        return $asArray
            ? json_decode(json_encode($this->_additionalProperties), true)
            : $this->_additionalProperties;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param \stdClass|array $additionalProperties Map of property name/value pairs to add.
     * @return self
     */
    public function withAdditionalProperties($additionalProperties)
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

        return $clone;
    }

    /**
     * Removes all extra properties not specified in the schema.
     *
     * @return self
     */
    public function withoutAdditionalProperties()
    {
        $clone = clone $this;
        $clone->_additionalProperties = new \stdClass();
        return $clone;
    }

    /**
     * @return MyClassFilesItem[]|null
     */
    public function getFiles()
    {
        return isset($this->files) ? $this->files : null;
    }

    /**
     * @param MyClassFilesItem[] $files
     * @param bool $validate
     * @return self
     */
    public function withFiles(array $files, $validate = true)
    {
        $clone = clone $this;
        $clone->files = $files;
        if ($validate) {
            $clone->validate();
        }
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
     * @return OptionsObject|null
     */
    public function getOptions()
    {
        return isset($this->options) ? $this->options : null;
    }

    /**
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
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
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

        $files = isset($input->{'files'}) ? array_map(function($i) use ($validate) { return MyClassFilesItem::fromInput($i, $validate); }, $input->{'files'}) : null;
        $options = isset($input->{'options'}) ? OptionsObject::fromInput($input->{'options'}, $validate) : null;

        $obj = new self($files, $options);
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
            $output['files'] = array_map(
                function(MyClassFilesItem $i) {
                    return $i->toArray();
                },
                $this->files
            );
        }
        if (isset($this->options)) {
            $output['options'] = $this->options->toArray();
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
        if (isset($this->files)) {
            $output->{'files'} = array_map(
                function(MyClassFilesItem $i) {
                    return $i->toStdClass();
                },
                $this->files
            );
        }
        if (isset($this->options)) {
            $output->{'options'} = $this->options->toStdClass();
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
