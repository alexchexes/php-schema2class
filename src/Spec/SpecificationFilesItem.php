<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Spec;

class SpecificationFilesItem
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = ['properties' => ['input' => ['type' => ['string', 'object']], 'className' => ['type' => 'string'], 'options' => ['$ref' => '#/definitions/SpecificationOptions']], 'additionalProperties' => false, 'required' => ['input'], 'definitions' => ['SpecificationOptions' => ['additionalProperties' => false, 'properties' => ['targetDirectory' => ['type' => 'string'], 'targetNamespace' => ['type' => 'string'], 'targetPHPVersion' => ['oneOf' => [['type' => 'integer', 'enum' => [5, 7, 8]], ['type' => 'string']]], 'cleanTargetDirectory' => ['type' => 'boolean'], 'disableStrictTypes' => ['type' => 'boolean'], 'inlineAllofReferences' => ['type' => 'boolean'], 'newValidatorExpr' => ['type' => 'string'], 'arrayToObjectExpr' => ['type' => 'string'], 'preservePropertyNames' => ['type' => 'boolean'], 'noGetters' => ['type' => 'boolean'], 'noSetters' => ['type' => 'boolean'], 'mutableSetters' => ['oneOf' => [['type' => 'boolean', 'enum' => [true]], ['type' => 'string', 'enum' => ['chainable']]]], 'noSchemaMetadata' => ['type' => 'boolean'], 'singleLineSchema' => ['type' => 'boolean'], 'noEnums' => ['type' => 'boolean']]]]];

    private string|array|object $input;

    private ?string $className = null;

    private ?SpecificationOptions $options = null;

    public function __construct(string|array|object $_input, ?string $className = null, ?SpecificationOptions $options = null)
    {
        $this->input = $_input;
        $this->className = $className;
        $this->options = $options;
    }

    public function getInput(): string|array|object
    {
        return $this->input;
    }

    public function withInput(string|array|object $_input): self
    {
        $clone = clone $this;
        $clone->input = $_input;

        return $clone;
    }

    public function getClassName(): ?string
    {
        return $this->className ?? null;
    }

    public function withClassName(string $className): self
    {
        $clone = clone $this;
        $clone->className = $className;

        return $clone;
    }

    public function withoutClassName(): self
    {
        $clone = clone $this;
        unset($clone->className);

        return $clone;
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
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return SpecificationFilesItem Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): SpecificationFilesItem
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $_input = $input->{'input'};
        $className = isset($input->{'className'}) ? $input->{'className'} : null;
        $options = isset($input->{'options'})
            ? SpecificationOptions::fromInput($input->{'options'}, $validate)
            : null;

        $obj = new self($_input, $className, $options);

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
        $output['input'] = match (true) {
            is_array($this->input) || is_object($this->input) => json_decode(json_encode($this->input), true),
            default => $this->input,
        };
        if (isset($this->className)) {
            $output['className'] = $this->className;
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
        $output->{'input'} = match (true) {
            is_array($this->input) || is_object($this->input) => json_decode(json_encode($this->input)),
            default => $this->input,
        };
        if (isset($this->className)) {
            $output->{'className'} = $this->className;
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
        $this->input = match (true) {
            is_string($this->input) => $this->input,
            is_array($this->input) || is_object($this->input) => json_decode(json_encode($this->input), is_array($this->input)),
        };
        if (isset($this->options)) {
            $this->options = clone $this->options;
        }
    }
}

