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
    private static array $schema = [
        'required' => [
            'input',
        ],
        'additionalProperties' => false,
        'properties' => [
            'input' => [
                'type' => 'string',
            ],
            'className' => [
                'type' => 'string',
            ],
            'options' => [
                'additionalProperties' => false,
                'properties' => [
                    'targetDirectory' => [
                        'type' => 'string',
                    ],
                    'targetNamespace' => [
                        'type' => 'string',
                    ],
                    'cleanTargetDirectory' => [
                        'type' => 'boolean',
                        'description' => 'When true, the generator removes all files from the target directory
before writing new ones.
',
                        'default' => false,
                    ],
                    'disableStrictTypes' => [
                        'type' => 'boolean',
                        'default' => false,
                    ],
                    'treatValuesWithDefaultAsOptional' => [
                        'type' => 'boolean',
                        'default' => false,
                    ],
                    'inlineAllofReferences' => [
                        'type' => 'boolean',
                        'default' => false,
                    ],
                    'targetPHPVersion' => [
                        'oneOf' => [
                            [
                                'type' => 'integer',
                                'enum' => [
                                    5,
                                    7,
                                    8,
                                ],
                            ],
                            [
                                'type' => 'string',
                            ],
                        ],
                        'default' => '8.4',
                    ],
                    'newValidatorClassExpr' => [
                        'type' => 'string',
                        'description' => 'The expression to use to create a new instance of the validator class.
This is useful if you want to use a custom validator class.
',
                        'default' => 'new \\JsonSchema\\Validator()',
                    ],
                    'preservePropertyNames' => [
                        'type' => 'boolean',
                        'description' => 'When true, properties names are not converted to camelCase.
',
                        'default' => false,
                    ],
                    'noGetters' => [
                        'type' => 'boolean',
                        'description' => 'When true, no getters are created and all properties are \'public\'.
',
                        'default' => false,
                    ],
                    'noSetters' => [
                        'type' => 'boolean',
                        'description' => 'When true, no withX() / withoutX() setters/unsetters are created.
',
                        'default' => false,
                    ],
                    'noDescriptionsInSchema' => [
                        'type' => 'boolean',
                        'description' => 'When true, the schema used for validation will not include any description fields
',
                        'default' => false,
                    ],
                    'singleLineSchema' => [
                        'type' => 'boolean',
                        'description' => 'When true, the whole schema used for validation will on a single line in the class property
',
                        'default' => false,
                    ],
                    'noEnums' => [
                        'type' => 'boolean',
                        'description' => 'Disable generating PHP enum classes even on PHP ≥ 8.1. Enum values will be
handled like in earlier PHP versions.
',
                        'default' => false,
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var string
     */
    private string $input;

    /**
     * @var string|null
     */
    private ?string $className = null;

    /**
     * @var SpecificationFilesItemOptions|null
     */
    private ?SpecificationFilesItemOptions $options = null;

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
     * @return SpecificationFilesItemOptions|null
     */
    public function getOptions() : ?SpecificationFilesItemOptions
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
     * @param SpecificationFilesItemOptions $options
     * @return self
     */
    public function withOptions(SpecificationFilesItemOptions $options) : self
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
        $options = isset($input2->{'options'}) ? SpecificationFilesItemOptions::buildFromInput($input2->{'options'}, validate: $validate) : null;

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
            $output['options'] = ($this->options)->toArray();
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

    public function __clone()
    {
        if (isset($this->options)) {
            $this->options = clone $this->options;
        }
    }
}

