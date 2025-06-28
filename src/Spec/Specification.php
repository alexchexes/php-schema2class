<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Spec;

class Specification
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = ['properties' => ['options' => ['$ref' => '#/definitions/SpecificationOptions'], 'files' => ['type' => 'array', 'items' => ['properties' => ['input' => ['oneOf' => [['type' => 'string'], ['type' => 'object']]], 'className' => ['type' => 'string'], 'options' => ['$ref' => '#/definitions/SpecificationOptions']], 'additionalProperties' => false, 'required' => ['input']]]], 'additionalProperties' => false, 'required' => ['files'], 'definitions' => ['SpecificationOptions' => ['additionalProperties' => false, 'properties' => ['targetDirectory' => ['type' => 'string'], 'targetNamespace' => ['type' => 'string'], 'targetPHPVersion' => ['oneOf' => [['type' => 'integer', 'enum' => [5, 7, 8]], ['type' => 'string']]], 'cleanTargetDirectory' => ['type' => 'boolean'], 'disableStrictTypes' => ['type' => 'boolean'], 'treatValuesWithDefaultAsOptional' => ['type' => 'boolean'], 'inlineAllofReferences' => ['type' => 'boolean'], 'newValidatorClassExpr' => ['type' => 'string'], 'preservePropertyNames' => ['type' => 'boolean'], 'noGetters' => ['type' => 'boolean'], 'noSetters' => ['type' => 'boolean'], 'noDescriptionsInSchema' => ['type' => 'boolean'], 'singleLineSchema' => ['type' => 'boolean'], 'noEnums' => ['type' => 'boolean']]]]];

    /**
     * @var SpecificationOptions|null
     */
    private ?SpecificationOptions $options = null;

    /**
     * @var SpecificationFilesItem[]
     */
    private array $files;

    /**
     * @param SpecificationFilesItem[] $files
     */
    public function __construct(array $files)
    {
        $this->files = $files;
    }

    /**
     * @return SpecificationOptions|null
     */
    public function getOptions() : ?SpecificationOptions
    {
        return $this->options ?? null;
    }

    /**
     * @return SpecificationFilesItem[]
     */
    public function getFiles() : array
    {
        return $this->files;
    }

    /**
     * @param SpecificationOptions $options
     * @return self
     */
    public function withOptions(SpecificationOptions $options) : self
    {
        $clone = clone $this;
        $clone->options = $options;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutOptions() : self
    {
        $clone = clone $this;
        unset($clone->options);

        return $clone;
    }

    /**
     * @param SpecificationFilesItem[] $files
     * @return self
     */
    public function withFiles(array $files) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($files, self::$schema['properties']['files']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->files = $files;

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return Specification Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : Specification
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $options = isset($input->{'options'}) ? SpecificationOptions::buildFromInput($input->{'options'}, $validate) : null;
        $files = array_map(fn (array|object $i): SpecificationFilesItem => SpecificationFilesItem::buildFromInput($i, validate: $validate), $input->{'files'});

        $obj = new self($files);
        $obj->options = $options;
        return $obj;
    }

    /**
     * Converts this object back to a simple array that can be JSON-serialized
     *
     * @return array Converted array
     */
    public function toArray() : array
    {
        $output = [];
        if (isset($this->options)) {
            $output['options'] = $this->options->toArray();
        }
        $output['files'] = array_map(fn (SpecificationFilesItem $i) => $i->toArray(), $this->files);

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
    public static function validateInput(array|object $input, bool $return = false) : bool
    {
        $validator = new \JsonSchema\Validator();
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        $validator->validate($input, self::$schema);

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
        $this->files = array_map(fn (SpecificationFilesItem $i) => clone $i, $this->files);
    }
}

