<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Spec;

class SpecificationOptions
{
    /**
     * Schema used to validate input for creating instances of this class
     *
     * @var array
     */
    private static array $schema = [
        'properties' => [
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
                'default' => '8.2.0',
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
        ],
    ];

    /**
     * @var bool
     */
    private bool $disableStrictTypes = false;

    /**
     * @var bool
     */
    private bool $treatValuesWithDefaultAsOptional = false;

    /**
     * @var bool
     */
    private bool $inlineAllofReferences = false;

    /**
     * @var int|string
     */
    private int|string $targetPHPVersion = '8.2.0';

    /**
     * The expression to use to create a new instance of the validator class.
     * This is useful if you want to use a custom validator class.
     *
     *
     * @var string
     */
    private string $newValidatorClassExpr = 'new \\JsonSchema\\Validator()';

    /**
     * When true, properties names are not converted to camelCase.
     *
     *
     * @var bool
     */
    private bool $preservePropertyNames = false;

    /**
     * When true, no getters are created and all properties are 'public'.
     *
     *
     * @var bool
     */
    private bool $noGetters = false;

    /**
     * When true, no withX() / withoutX() setters/unsetters are created.
     *
     *
     * @var bool
     */
    private bool $noSetters = false;

    /**
     * When true, the schema used for validation will not include any description fields
     *
     *
     * @var bool
     */
    private bool $noDescriptionsInSchema = false;

    /**
     * When true, the whole schema used for validation will on a single line in the class property
     *
     *
     * @var bool
     */
    private bool $singleLineSchema = false;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return bool
     */
    public function getDisableStrictTypes() : bool
    {
        return $this->disableStrictTypes;
    }

    /**
     * @return bool
     */
    public function getTreatValuesWithDefaultAsOptional() : bool
    {
        return $this->treatValuesWithDefaultAsOptional;
    }

    /**
     * @return bool
     */
    public function getInlineAllofReferences() : bool
    {
        return $this->inlineAllofReferences;
    }

    /**
     * @return int|string
     */
    public function getTargetPHPVersion() : int|string
    {
        return $this->targetPHPVersion;
    }

    /**
     * The expression to use to create a new instance of the validator class.
     * This is useful if you want to use a custom validator class.
     *
     *
     * @return string
     */
    public function getNewValidatorClassExpr() : string
    {
        return $this->newValidatorClassExpr;
    }

    /**
     * When true, properties names are not converted to camelCase.
     *
     *
     * @return bool
     */
    public function getPreservePropertyNames() : bool
    {
        return $this->preservePropertyNames;
    }

    /**
     * When true, no getters are created and all properties are 'public'.
     *
     *
     * @return bool
     */
    public function getNoGetters() : bool
    {
        return $this->noGetters;
    }

    /**
     * When true, no withX() / withoutX() setters/unsetters are created.
     *
     *
     * @return bool
     */
    public function getNoSetters() : bool
    {
        return $this->noSetters;
    }

    /**
     * When true, the schema used for validation will not include any description fields
     *
     *
     * @return bool
     */
    public function getNoDescriptionsInSchema() : bool
    {
        return $this->noDescriptionsInSchema;
    }

    /**
     * When true, the whole schema used for validation will on a single line in the class property
     *
     *
     * @return bool
     */
    public function getSingleLineSchema() : bool
    {
        return $this->singleLineSchema;
    }

    /**
     * @param bool $disableStrictTypes
     * @return self
     */
    public function withDisableStrictTypes(bool $disableStrictTypes) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($disableStrictTypes, self::$schema['properties']['disableStrictTypes']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->disableStrictTypes = $disableStrictTypes;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutDisableStrictTypes() : self
    {
        $clone = clone $this;
        $clone->disableStrictTypes = false;

        return $clone;
    }

    /**
     * @param bool $treatValuesWithDefaultAsOptional
     * @return self
     */
    public function withTreatValuesWithDefaultAsOptional(bool $treatValuesWithDefaultAsOptional) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($treatValuesWithDefaultAsOptional, self::$schema['properties']['treatValuesWithDefaultAsOptional']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->treatValuesWithDefaultAsOptional = $treatValuesWithDefaultAsOptional;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutTreatValuesWithDefaultAsOptional() : self
    {
        $clone = clone $this;
        $clone->treatValuesWithDefaultAsOptional = false;

        return $clone;
    }

    /**
     * @param bool $inlineAllofReferences
     * @return self
     */
    public function withInlineAllofReferences(bool $inlineAllofReferences) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($inlineAllofReferences, self::$schema['properties']['inlineAllofReferences']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->inlineAllofReferences = $inlineAllofReferences;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutInlineAllofReferences() : self
    {
        $clone = clone $this;
        $clone->inlineAllofReferences = false;

        return $clone;
    }

    /**
     * @param int|string $targetPHPVersion
     * @return self
     */
    public function withTargetPHPVersion(int|string $targetPHPVersion) : self
    {
        $clone = clone $this;
        $clone->targetPHPVersion = $targetPHPVersion;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutTargetPHPVersion() : self
    {
        $clone = clone $this;
        $clone->targetPHPVersion = '8.2.0';

        return $clone;
    }

    /**
     * @param string $newValidatorClassExpr
     * @return self
     */
    public function withNewValidatorClassExpr(string $newValidatorClassExpr) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($newValidatorClassExpr, self::$schema['properties']['newValidatorClassExpr']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->newValidatorClassExpr = $newValidatorClassExpr;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutNewValidatorClassExpr() : self
    {
        $clone = clone $this;
        $clone->newValidatorClassExpr = 'new \\JsonSchema\\Validator()';

        return $clone;
    }

    /**
     * @param bool $preservePropertyNames
     * @return self
     */
    public function withPreservePropertyNames(bool $preservePropertyNames) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($preservePropertyNames, self::$schema['properties']['preservePropertyNames']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->preservePropertyNames = $preservePropertyNames;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutPreservePropertyNames() : self
    {
        $clone = clone $this;
        $clone->preservePropertyNames = false;

        return $clone;
    }

    /**
     * @param bool $noGetters
     * @return self
     */
    public function withNoGetters(bool $noGetters) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($noGetters, self::$schema['properties']['noGetters']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->noGetters = $noGetters;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutNoGetters() : self
    {
        $clone = clone $this;
        $clone->noGetters = false;

        return $clone;
    }

    /**
     * @param bool $noSetters
     * @return self
     */
    public function withNoSetters(bool $noSetters) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($noSetters, self::$schema['properties']['noSetters']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->noSetters = $noSetters;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutNoSetters() : self
    {
        $clone = clone $this;
        $clone->noSetters = false;

        return $clone;
    }

    /**
     * @param bool $noDescriptionsInSchema
     * @return self
     */
    public function withNoDescriptionsInSchema(bool $noDescriptionsInSchema) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($noDescriptionsInSchema, self::$schema['properties']['noDescriptionsInSchema']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->noDescriptionsInSchema = $noDescriptionsInSchema;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutNoDescriptionsInSchema() : self
    {
        $clone = clone $this;
        $clone->noDescriptionsInSchema = false;

        return $clone;
    }

    /**
     * @param bool $singleLineSchema
     * @return self
     */
    public function withSingleLineSchema(bool $singleLineSchema) : self
    {
        $validator = new \JsonSchema\Validator();
        $validator->validate($singleLineSchema, self::$schema['properties']['singleLineSchema']);
        if (!$validator->isValid()) {
            throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
        }

        $clone = clone $this;
        $clone->singleLineSchema = $singleLineSchema;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutSingleLineSchema() : self
    {
        $clone = clone $this;
        $clone->singleLineSchema = false;

        return $clone;
    }

    /**
     * Builds a new instance from an input array
     *
     * @param array|object $input Input data
     * @param bool $validate Set this to false to skip validation; use at own risk
     * @return SpecificationOptions Created instance
     * @throws \InvalidArgumentException
     */
    public static function buildFromInput(array|object $input, bool $validate = true) : SpecificationOptions
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

        $disableStrictTypes = isset($input->{'disableStrictTypes'}) ? $input->{'disableStrictTypes'} : false;
        $treatValuesWithDefaultAsOptional = isset($input->{'treatValuesWithDefaultAsOptional'}) ? $input->{'treatValuesWithDefaultAsOptional'} : false;
        $inlineAllofReferences = isset($input->{'inlineAllofReferences'}) ? $input->{'inlineAllofReferences'} : false;
        $targetPHPVersion = isset($input->{'targetPHPVersion'}) ? match (true) {
            default => null,
            is_int($input->{'targetPHPVersion'}) => (int)($input->{'targetPHPVersion'}),
            is_string($input->{'targetPHPVersion'}) => $input->{'targetPHPVersion'},
        } : '8.2.0';
        $newValidatorClassExpr = isset($input->{'newValidatorClassExpr'}) ? $input->{'newValidatorClassExpr'} : 'new \\JsonSchema\\Validator()';
        $preservePropertyNames = isset($input->{'preservePropertyNames'}) ? $input->{'preservePropertyNames'} : false;
        $noGetters = isset($input->{'noGetters'}) ? $input->{'noGetters'} : false;
        $noSetters = isset($input->{'noSetters'}) ? $input->{'noSetters'} : false;
        $noDescriptionsInSchema = isset($input->{'noDescriptionsInSchema'}) ? $input->{'noDescriptionsInSchema'} : false;
        $singleLineSchema = isset($input->{'singleLineSchema'}) ? $input->{'singleLineSchema'} : false;

        $obj = new self();
        $obj->disableStrictTypes = $disableStrictTypes;
        $obj->treatValuesWithDefaultAsOptional = $treatValuesWithDefaultAsOptional;
        $obj->inlineAllofReferences = $inlineAllofReferences;
        $obj->targetPHPVersion = $targetPHPVersion;
        $obj->newValidatorClassExpr = $newValidatorClassExpr;
        $obj->preservePropertyNames = $preservePropertyNames;
        $obj->noGetters = $noGetters;
        $obj->noSetters = $noSetters;
        $obj->noDescriptionsInSchema = $noDescriptionsInSchema;
        $obj->singleLineSchema = $singleLineSchema;
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
        if (isset($this->disableStrictTypes)) {
            $output['disableStrictTypes'] = $this->disableStrictTypes;
        }
        if (isset($this->treatValuesWithDefaultAsOptional)) {
            $output['treatValuesWithDefaultAsOptional'] = $this->treatValuesWithDefaultAsOptional;
        }
        if (isset($this->inlineAllofReferences)) {
            $output['inlineAllofReferences'] = $this->inlineAllofReferences;
        }
        if (isset($this->targetPHPVersion)) {
            $output['targetPHPVersion'] = match (true) {
                is_int($this->targetPHPVersion), is_string($this->targetPHPVersion) => $this->targetPHPVersion,
            };
        }
        if (isset($this->newValidatorClassExpr)) {
            $output['newValidatorClassExpr'] = $this->newValidatorClassExpr;
        }
        if (isset($this->preservePropertyNames)) {
            $output['preservePropertyNames'] = $this->preservePropertyNames;
        }
        if (isset($this->noGetters)) {
            $output['noGetters'] = $this->noGetters;
        }
        if (isset($this->noSetters)) {
            $output['noSetters'] = $this->noSetters;
        }
        if (isset($this->noDescriptionsInSchema)) {
            $output['noDescriptionsInSchema'] = $this->noDescriptionsInSchema;
        }
        if (isset($this->singleLineSchema)) {
            $output['singleLineSchema'] = $this->singleLineSchema;
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
        if (isset($this->targetPHPVersion)) {
            $this->targetPHPVersion = match (true) {
                is_int($this->targetPHPVersion), is_string($this->targetPHPVersion) => $this->targetPHPVersion,
            };
        }
    }
}

