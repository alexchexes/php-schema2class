<?php

declare(strict_types=1);

namespace Ns\NestedSchemaRefs_8_4;

class MyClass
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
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
     * Map of name/value pairs for properties not specified in the schema.
     */
    private object $_additionalProperties;

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
        $this->files = $files;
        $this->options = $options;
    }

    /**
     * Allows adding properties not specified in the schema.
     *
     * @param array|object $additionalProperties Map of property name/value pairs to add.
     */
    public function withAdditionalProperties(array|object $additionalProperties): self
    {
        $clone = clone $this;
        $clone->_additionalProperties = is_array($additionalProperties)
            ? \JsonSchema\Validator::arrayToObjectRecursive($additionalProperties)
            : $additionalProperties;

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
    public static function fromInput(array|object $input, bool $validate = true): MyClass
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $files = isset($input->{'files'}) ? array_map(fn (array|object $i): MyClassFilesItem => MyClassFilesItem::fromInput($i, $validate), $input->{'files'}) : null;
        $options = isset($input->{'options'}) ? OptionsObject::fromInput($input->{'options'}, $validate) : null;

        $obj = new self($files, $options);
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
        $output = new \stdClass();
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
        if (isset($this->files)) {
            $this->files = array_map(fn (MyClassFilesItem $i) => clone $i, $this->files);
        }
    }
}
