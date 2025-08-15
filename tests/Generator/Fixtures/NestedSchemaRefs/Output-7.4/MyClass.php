<?php

declare(strict_types=1);

namespace Ns\NestedSchemaRefs_7_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = [
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
     * Mapping of schema property names to this class's property names.
     */
    private static array $_namesMap = [
        'files' => 'files',
        'options' => 'options',
    ];

    /**
     * Map of name/value pairs for properties not specified in the schema.
     */
    private \stdClass $_additionalProperties;

    /**
     * @var MyClassFilesItem[]|null
     */
    private ?array $files = null;

    private ?OptionsObject $options = null;

    /**
     * @param MyClassFilesItem[]|null $files
     */
    public function __construct(?array $files = null, ?OptionsObject $options = null)
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
    public function getAdditionalProperties(bool $asArray = true)
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
    public function withAdditionalProperties($additionalProperties): self
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

    /**
     * @return MyClassFilesItem[]|null
     */
    public function getFiles(): ?array
    {
        return $this->files ?? null;
    }

    /**
     * @param MyClassFilesItem[] $files
     */
    public function withFiles(array $files, bool $validate = true): self
    {
        $clone = clone $this;
        $clone->files = $files;
        if ($validate) {
            $clone->validate();
        }
        return $clone;
    }

    public function withoutFiles(): self
    {
        $clone = clone $this;
        unset($clone->files);

        return $clone;
    }

    public function getOptions(): ?OptionsObject
    {
        return $this->options ?? null;
    }

    public function withOptions(OptionsObject $options): self
    {
        $clone = clone $this;
        $clone->options = $options;

        return $clone;
    }

    public function withoutOptions(): self
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
    public static function fromInput($input, bool $validate = true): MyClass
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

        $files = isset($input->{'files'})
            ? array_map(
                fn ($i): MyClassFilesItem => MyClassFilesItem::fromInput($i, $validate),
                $input->{'files'},
            )
            : null;
        $options = isset($input->{'options'})
            ? OptionsObject::fromInput($input->{'options'}, $validate)
            : null;

        $obj = new self($files, $options);

        $_additionalProperties = array_diff_key(get_object_vars($input), self::$_namesMap);
        if (!empty($_additionalProperties)) {
            $obj->_additionalProperties = (object) $_additionalProperties;
        }

        return $obj;
    }

    /**
     * Converts this object to array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = json_decode(json_encode($this->_additionalProperties), true);

        if (isset($this->files)) {
            $output['files'] = array_map(fn (MyClassFilesItem $i) => $i->toArray(), $this->files);
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
    public function toStdClass(): \stdClass
    {
        $output = $this->_additionalProperties;

        if (isset($this->files)) {
            $output->{'files'} = array_map(fn (MyClassFilesItem $i) => $i->toStdClass(), $this->files);
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
    public static function validateInput($input, bool $return = false): bool
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$_schema);

        if (!$validator->isValid() && !$return) {
            $errors = array_map(
                fn (array $e): string => ($e["property"] ? $e["property"] . ": " : "") . $e["message"],
                $validator->getErrors(),
            );
            throw new \InvalidArgumentException(join(".\n", $errors));
        }

        return $validator->isValid();
    }

    public function __clone()
    {
        if (isset($this->files)) {
            $this->files = array_map(fn (MyClassFilesItem $i) => clone $i, $this->files);
        }
    }
}
