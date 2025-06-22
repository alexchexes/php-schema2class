<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Spec;

class SpecificationFilesItem
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = ['properties' => ['input' => ['type' => 'string'], 'className' => ['type' => 'string'], 'options' => ['$ref' => '#/definitions/SpecificationOptions']], 'additionalProperties' => false, 'required' => ['input'], 'definitions' => ['SpecificationOptions' => ['additionalProperties' => false, 'properties' => ['targetDirectory' => ['type' => 'string'], 'targetNamespace' => ['type' => 'string'], 'cleanTargetDirectory' => ['type' => 'boolean'], 'disableStrictTypes' => ['type' => 'boolean'], 'treatValuesWithDefaultAsOptional' => ['type' => 'boolean'], 'inlineAllofReferences' => ['type' => 'boolean'], 'targetPHPVersion' => ['oneOf' => [['type' => 'integer', 'enum' => [5, 7, 8]], ['type' => 'string']]], 'newValidatorClassExpr' => ['type' => 'string'], 'preservePropertyNames' => ['type' => 'boolean'], 'noGetters' => ['type' => 'boolean'], 'noSetters' => ['type' => 'boolean'], 'noDescriptionsInSchema' => ['type' => 'boolean'], 'singleLineSchema' => ['type' => 'boolean'], 'noEnums' => ['type' => 'boolean']]]]];

    /**
     * @var string
     */
    private string $input;

    /**
     * @var string|null
     */
    private ?string $className = null;

    /**
     * @var SpecificationOptions|null
     */
    private ?SpecificationOptions $options = null;

    /**
     * @param string $input
     */
    public function __construct(string $input)
    {
        $this->input = $input;
    }

    /**
     * @return string
     */
    public function getInput() : string
    {
        return $this->input;
    }

    /**
     * @return string|null
     */
    public function getClassName() : ?string
    {
        return $this->className ?? null;
    }

    /**
     * @return SpecificationOptions|null
     */
    public function getOptions() : ?SpecificationOptions
    {
        return $this->options ?? null;
    }

    /**
     * @param string $input
     * @return self
     */
    public function withInput(string $input) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($input, self::$schema['properties']['input']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->input = $input;

        return $clone;
    }

    /**
     * @param string $className
     * @return self
     */
    public function withClassName(string $className) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($className, self::$schema['properties']['className']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->className = $className;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutClassName() : self
    {
        $clone = clone $this;
        unset($clone->className);

        return $clone;
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
     * Builds a new instance from an input array
     *
     * @param array|object $input2 Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return SpecificationFilesItem Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input2, bool $validate = true) : SpecificationFilesItem
    {
        $input2 = is_array($input2) ? \JsonSchema\Validator::arrayToObjectRecursive($input2) : $input2;
        if ($validate) {
            static::validateInput($input2);
        }

        $input = $input2->{'input'};
        $className = isset($input2->{'className'}) ? $input2->{'className'} : null;
        $options = isset($input2->{'options'}) ? SpecificationOptions::buildFromInput($input2->{'options'}, $validate) : null;

        $obj = new self($input);
        $obj->className = $className;
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
        $output['input'] = $this->input;
        if (isset($this->className)) {
            $output['className'] = $this->className;
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
}

