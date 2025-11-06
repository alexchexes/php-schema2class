<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Spec;

class Specification
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = ['properties' => ['options' => ['$ref' => '#/definitions/SpecificationOptions'], 'files' => ['type' => 'array', 'items' => ['properties' => ['input' => ['type' => ['string', 'object']], 'className' => ['type' => 'string'], 'options' => ['$ref' => '#/definitions/SpecificationOptions']], 'additionalProperties' => false, 'required' => ['input']]]], 'additionalProperties' => false, 'required' => ['files'], 'definitions' => ['SpecificationOptions' => ['additionalProperties' => false, 'properties' => ['targetDirectory' => ['type' => 'string'], 'targetNamespace' => ['type' => 'string'], 'targetPHPVersion' => ['oneOf' => [['type' => 'integer', 'enum' => [5, 7, 8]], ['type' => 'string']]], 'cleanTargetDirectory' => ['type' => 'boolean'], 'disableStrictTypes' => ['type' => 'boolean'], 'inlineAllofReferences' => ['type' => 'boolean'], 'newValidatorExpr' => ['type' => 'string'], 'arrayToObjectExpr' => ['type' => 'string'], 'preservePropertyNames' => ['type' => 'boolean'], 'noGetters' => ['type' => 'boolean'], 'noSetters' => ['type' => 'boolean'], 'mutableSetters' => ['oneOf' => [['type' => 'boolean', 'enum' => [true]], ['type' => 'string', 'enum' => ['chainable']]]], 'noSchemaMetadata' => ['type' => 'boolean'], 'singleLineSchema' => ['type' => 'boolean'], 'noEnums' => ['type' => 'boolean'], 'allowedDefinitions' => ['type' => 'array', 'items' => ['type' => 'string']]]]]];

    private ?SpecificationOptions $options = null;

    /**
     * @var SpecificationFilesItem[]
     */
    private array $files;

    /**
     * @param SpecificationFilesItem[] $files
     */
    public function __construct(array $files, ?SpecificationOptions $options = null)
    {
        $this->files = $files;
        $this->options = $options;
    }

    public function getOptions(): ?SpecificationOptions
    {
        return $this->options ?? null;
    }

    public function withOptions(SpecificationOptions $options): self
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
     * @return SpecificationFilesItem[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param SpecificationFilesItem[] $files
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

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return Specification Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): Specification
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $files = array_map(
            fn (object|array $i): SpecificationFilesItem => SpecificationFilesItem::fromInput($i, $validate),
            $input->{'files'},
        );
        $options = isset($input->{'options'})
            ? SpecificationOptions::fromInput($input->{'options'}, $validate)
            : null;

        $obj = new self($files, $options);

        return $obj;
    }

    /**
     * Converts this object to array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray(): array
    {
        $output = [];
        if (isset($this->options)) {
            $output['options'] = $this->options->toArray();
        }
        $output['files'] = array_map(fn (SpecificationFilesItem $i) => $i->toArray(), $this->files);

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
        if (isset($this->options)) {
            $output->{'options'} = $this->options->toStdClass();
        }
        $output->{'files'} = array_map(fn (SpecificationFilesItem $i) => $i->toStdClass(), $this->files);

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
        if (isset($this->options)) {
            $this->options = clone $this->options;
        }
        $this->files = array_map(fn (SpecificationFilesItem $i) => clone $i, $this->files);
    }
}

