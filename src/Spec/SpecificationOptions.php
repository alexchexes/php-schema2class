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
    private static array $schema = ['additionalProperties' => false, 'properties' => ['targetDirectory' => ['type' => 'string'], 'targetNamespace' => ['type' => 'string'], 'targetPHPVersion' => ['oneOf' => [['type' => 'integer', 'enum' => [5, 7, 8]], ['type' => 'string']]], 'cleanTargetDirectory' => ['type' => 'boolean'], 'disableStrictTypes' => ['type' => 'boolean'], 'inlineAllofReferences' => ['type' => 'boolean'], 'newValidatorClassExpr' => ['type' => 'string'], 'preservePropertyNames' => ['type' => 'boolean'], 'noGetters' => ['type' => 'boolean'], 'noSetters' => ['type' => 'boolean'], 'mutableSetters' => ['oneOf' => [['type' => 'boolean', 'enum' => [true]], ['type' => 'string', 'enum' => ['chainable']]]], 'noSchemaMetadata' => ['type' => 'boolean'], 'singleLineSchema' => ['type' => 'boolean'], 'noEnums' => ['type' => 'boolean']]];

    /**
     * @var string|null
     */
    private ?string $targetDirectory = null;

    /**
     * @var string|null
     */
    private ?string $targetNamespace = null;

    /**
     * @var int|string|null
     */
    private int|string|null $targetPHPVersion = null;

    /**
     * When true, the generator removes all files from the target directory
     * before writing new ones.
     *
     *
     * @var bool|null
     */
    private ?bool $cleanTargetDirectory = null;

    /**
     * @var bool|null
     */
    private ?bool $disableStrictTypes = null;

    /**
     * @var bool|null
     */
    private ?bool $inlineAllofReferences = null;

    /**
     * The expression to use to create a new instance of the validator class.
     * This is useful if you want to use a custom validator class.
     *
     *
     * @var string|null
     */
    private ?string $newValidatorClassExpr = null;

    /**
     * When true, properties names are not converted to camelCase.
     *
     *
     * @var bool|null
     */
    private ?bool $preservePropertyNames = null;

    /**
     * When true, no getters are created and all properties are 'public'.
     *
     *
     * @var bool|null
     */
    private ?bool $noGetters = null;

    /**
     * When true, no withX() / withoutX() setters/unsetters are created.
     *
     *
     * @var bool|null
     */
    private ?bool $noSetters = null;

    /**
     * If set, generate classic setX() methods instead of immutable
     * withX()/withoutX(). When the value is "chainable", the setter
     * returns $this.
     *
     *
     * @var true|'chainable'|null
     */
    private bool|string|null $mutableSetters = null;

    /**
     * When true, the schema used for validation will not include
     * description, title and other non-validation metadata fields
     *
     *
     * @var bool|null
     */
    private ?bool $noSchemaMetadata = null;

    /**
     * When true, the whole schema used for validation will on a single line in the class property
     *
     *
     * @var bool|null
     */
    private ?bool $singleLineSchema = null;

    /**
     * Disable generating PHP enum classes even on PHP ≥ 8.1. Enum values will be
     * handled like in earlier PHP versions.
     *
     *
     * @var bool|null
     */
    private ?bool $noEnums = null;

    /**
     * @return string|null
     */
    public function getTargetDirectory(): ?string
    {
        return $this->targetDirectory ?? null;
    }

    /**
     * @return string|null
     */
    public function getTargetNamespace(): ?string
    {
        return $this->targetNamespace ?? null;
    }

    /**
     * @return int|string|null
     */
    public function getTargetPHPVersion(): int|string|null
    {
        return $this->targetPHPVersion;
    }

    /**
     * When true, the generator removes all files from the target directory
     * before writing new ones.
     *
     *
     * @return bool|null
     */
    public function getCleanTargetDirectory(): ?bool
    {
        return $this->cleanTargetDirectory ?? null;
    }

    /**
     * @return bool|null
     */
    public function getDisableStrictTypes(): ?bool
    {
        return $this->disableStrictTypes ?? null;
    }

    /**
     * @return bool|null
     */
    public function getInlineAllofReferences(): ?bool
    {
        return $this->inlineAllofReferences ?? null;
    }

    /**
     * The expression to use to create a new instance of the validator class.
     * This is useful if you want to use a custom validator class.
     *
     *
     * @return string|null
     */
    public function getNewValidatorClassExpr(): ?string
    {
        return $this->newValidatorClassExpr ?? null;
    }

    /**
     * When true, properties names are not converted to camelCase.
     *
     *
     * @return bool|null
     */
    public function getPreservePropertyNames(): ?bool
    {
        return $this->preservePropertyNames ?? null;
    }

    /**
     * When true, no getters are created and all properties are 'public'.
     *
     *
     * @return bool|null
     */
    public function getNoGetters(): ?bool
    {
        return $this->noGetters ?? null;
    }

    /**
     * When true, no withX() / withoutX() setters/unsetters are created.
     *
     *
     * @return bool|null
     */
    public function getNoSetters(): ?bool
    {
        return $this->noSetters ?? null;
    }

    /**
     * If set, generate classic setX() methods instead of immutable
     * withX()/withoutX(). When the value is "chainable", the setter
     * returns $this.
     *
     *
     * @return true|'chainable'|null
     */
    public function getMutableSetters(): bool|string|null
    {
        return $this->mutableSetters;
    }

    /**
     * When true, the schema used for validation will not include
     * description, title and other non-validation metadata fields
     *
     *
     * @return bool|null
     */
    public function getNoSchemaMetadata(): ?bool
    {
        return $this->noSchemaMetadata ?? null;
    }

    /**
     * When true, the whole schema used for validation will on a single line in the class property
     *
     *
     * @return bool|null
     */
    public function getSingleLineSchema(): ?bool
    {
        return $this->singleLineSchema ?? null;
    }

    /**
     * Disable generating PHP enum classes even on PHP ≥ 8.1. Enum values will be
     * handled like in earlier PHP versions.
     *
     *
     * @return bool|null
     */
    public function getNoEnums(): ?bool
    {
        return $this->noEnums ?? null;
    }

    /**
     * @param string $targetDirectory
     * @return self
     * @param bool $validate
     */
    public function withTargetDirectory(string $targetDirectory, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($targetDirectory, self::$schema['properties']['targetDirectory']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->targetDirectory = $targetDirectory;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutTargetDirectory(): self
    {
        $clone = clone $this;
        unset($clone->targetDirectory);

        return $clone;
    }

    /**
     * @param string $targetNamespace
     * @return self
     * @param bool $validate
     */
    public function withTargetNamespace(string $targetNamespace, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($targetNamespace, self::$schema['properties']['targetNamespace']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->targetNamespace = $targetNamespace;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutTargetNamespace(): self
    {
        $clone = clone $this;
        unset($clone->targetNamespace);

        return $clone;
    }

    /**
     * @param int|string $targetPHPVersion
     * @return self
     */
    public function withTargetPHPVersion(int|string $targetPHPVersion): self
    {
        $clone = clone $this;
        $clone->targetPHPVersion = $targetPHPVersion;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutTargetPHPVersion(): self
    {
        $clone = clone $this;
        unset($clone->targetPHPVersion);

        return $clone;
    }

    /**
     * @param bool $cleanTargetDirectory
     * @return self
     * @param bool $validate
     */
    public function withCleanTargetDirectory(bool $cleanTargetDirectory, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($cleanTargetDirectory, self::$schema['properties']['cleanTargetDirectory']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->cleanTargetDirectory = $cleanTargetDirectory;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutCleanTargetDirectory(): self
    {
        $clone = clone $this;
        unset($clone->cleanTargetDirectory);

        return $clone;
    }

    /**
     * @param bool $disableStrictTypes
     * @return self
     * @param bool $validate
     */
    public function withDisableStrictTypes(bool $disableStrictTypes, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($disableStrictTypes, self::$schema['properties']['disableStrictTypes']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->disableStrictTypes = $disableStrictTypes;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutDisableStrictTypes(): self
    {
        $clone = clone $this;
        unset($clone->disableStrictTypes);

        return $clone;
    }

    /**
     * @param bool $inlineAllofReferences
     * @return self
     * @param bool $validate
     */
    public function withInlineAllofReferences(bool $inlineAllofReferences, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($inlineAllofReferences, self::$schema['properties']['inlineAllofReferences']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->inlineAllofReferences = $inlineAllofReferences;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutInlineAllofReferences(): self
    {
        $clone = clone $this;
        unset($clone->inlineAllofReferences);

        return $clone;
    }

    /**
     * @param string $newValidatorClassExpr
     * @return self
     * @param bool $validate
     */
    public function withNewValidatorClassExpr(string $newValidatorClassExpr, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($newValidatorClassExpr, self::$schema['properties']['newValidatorClassExpr']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->newValidatorClassExpr = $newValidatorClassExpr;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutNewValidatorClassExpr(): self
    {
        $clone = clone $this;
        unset($clone->newValidatorClassExpr);

        return $clone;
    }

    /**
     * @param bool $preservePropertyNames
     * @return self
     * @param bool $validate
     */
    public function withPreservePropertyNames(bool $preservePropertyNames, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($preservePropertyNames, self::$schema['properties']['preservePropertyNames']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->preservePropertyNames = $preservePropertyNames;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutPreservePropertyNames(): self
    {
        $clone = clone $this;
        unset($clone->preservePropertyNames);

        return $clone;
    }

    /**
     * @param bool $noGetters
     * @return self
     * @param bool $validate
     */
    public function withNoGetters(bool $noGetters, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($noGetters, self::$schema['properties']['noGetters']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->noGetters = $noGetters;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutNoGetters(): self
    {
        $clone = clone $this;
        unset($clone->noGetters);

        return $clone;
    }

    /**
     * @param bool $noSetters
     * @return self
     * @param bool $validate
     */
    public function withNoSetters(bool $noSetters, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($noSetters, self::$schema['properties']['noSetters']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->noSetters = $noSetters;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutNoSetters(): self
    {
        $clone = clone $this;
        unset($clone->noSetters);

        return $clone;
    }

    /**
     * @param true|'chainable' $mutableSetters
     * @return self
     */
    public function withMutableSetters(bool|string $mutableSetters): self
    {
        $clone = clone $this;
        $clone->mutableSetters = $mutableSetters;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutMutableSetters(): self
    {
        $clone = clone $this;
        unset($clone->mutableSetters);

        return $clone;
    }

    /**
     * @param bool $noSchemaMetadata
     * @return self
     * @param bool $validate
     */
    public function withNoSchemaMetadata(bool $noSchemaMetadata, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($noSchemaMetadata, self::$schema['properties']['noSchemaMetadata']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->noSchemaMetadata = $noSchemaMetadata;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutNoSchemaMetadata(): self
    {
        $clone = clone $this;
        unset($clone->noSchemaMetadata);

        return $clone;
    }

    /**
     * @param bool $singleLineSchema
     * @return self
     * @param bool $validate
     */
    public function withSingleLineSchema(bool $singleLineSchema, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($singleLineSchema, self::$schema['properties']['singleLineSchema']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->singleLineSchema = $singleLineSchema;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutSingleLineSchema(): self
    {
        $clone = clone $this;
        unset($clone->singleLineSchema);

        return $clone;
    }

    /**
     * @param bool $noEnums
     * @return self
     * @param bool $validate
     */
    public function withNoEnums(bool $noEnums, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($noEnums, self::$schema['properties']['noEnums']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->noEnums = $noEnums;

        return $clone;
    }

    /**
     * @return self
     */
    public function withoutNoEnums(): self
    {
        $clone = clone $this;
        unset($clone->noEnums);

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
    public static function buildFromInput(array|object $input, bool $validate = true): SpecificationOptions
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $targetDirectory = isset($input->{'targetDirectory'}) ? $input->{'targetDirectory'} : null;
        $targetNamespace = isset($input->{'targetNamespace'}) ? $input->{'targetNamespace'} : null;
        $targetPHPVersion = isset($input->{'targetPHPVersion'}) ? match (true) {
            is_int($input->{'targetPHPVersion'}) => (int)($input->{'targetPHPVersion'}),
            is_string($input->{'targetPHPVersion'}) => $input->{'targetPHPVersion'},
            default => null,
        } : null;
        $cleanTargetDirectory = isset($input->{'cleanTargetDirectory'}) ? $input->{'cleanTargetDirectory'} : null;
        $disableStrictTypes = isset($input->{'disableStrictTypes'}) ? $input->{'disableStrictTypes'} : null;
        $inlineAllofReferences = isset($input->{'inlineAllofReferences'}) ? $input->{'inlineAllofReferences'} : null;
        $newValidatorClassExpr = isset($input->{'newValidatorClassExpr'}) ? $input->{'newValidatorClassExpr'} : null;
        $preservePropertyNames = isset($input->{'preservePropertyNames'}) ? $input->{'preservePropertyNames'} : null;
        $noGetters = isset($input->{'noGetters'}) ? $input->{'noGetters'} : null;
        $noSetters = isset($input->{'noSetters'}) ? $input->{'noSetters'} : null;
        $mutableSetters = isset($input->{'mutableSetters'}) ? match (true) {
            is_bool($input->{'mutableSetters'}) => (bool)($input->{'mutableSetters'}),
            in_array($input->{'mutableSetters'}, array (
          0 => 'chainable',
        ), true) => $input->{'mutableSetters'},
            default => null,
        } : null;
        $noSchemaMetadata = isset($input->{'noSchemaMetadata'}) ? $input->{'noSchemaMetadata'} : null;
        $singleLineSchema = isset($input->{'singleLineSchema'}) ? $input->{'singleLineSchema'} : null;
        $noEnums = isset($input->{'noEnums'}) ? $input->{'noEnums'} : null;

        $obj = new self();
        $obj->targetDirectory = $targetDirectory;
        $obj->targetNamespace = $targetNamespace;
        $obj->targetPHPVersion = $targetPHPVersion;
        $obj->cleanTargetDirectory = $cleanTargetDirectory;
        $obj->disableStrictTypes = $disableStrictTypes;
        $obj->inlineAllofReferences = $inlineAllofReferences;
        $obj->newValidatorClassExpr = $newValidatorClassExpr;
        $obj->preservePropertyNames = $preservePropertyNames;
        $obj->noGetters = $noGetters;
        $obj->noSetters = $noSetters;
        $obj->mutableSetters = $mutableSetters;
        $obj->noSchemaMetadata = $noSchemaMetadata;
        $obj->singleLineSchema = $singleLineSchema;
        $obj->noEnums = $noEnums;
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
        if (isset($this->targetDirectory)) {
            $output['targetDirectory'] = $this->targetDirectory;
        }
        if (isset($this->targetNamespace)) {
            $output['targetNamespace'] = $this->targetNamespace;
        }
        if (isset($this->targetPHPVersion)) {
            $output['targetPHPVersion'] = match (true) {
                is_int($this->targetPHPVersion),
                is_string($this->targetPHPVersion) => $this->targetPHPVersion,
            };
        }
        if (isset($this->cleanTargetDirectory)) {
            $output['cleanTargetDirectory'] = $this->cleanTargetDirectory;
        }
        if (isset($this->disableStrictTypes)) {
            $output['disableStrictTypes'] = $this->disableStrictTypes;
        }
        if (isset($this->inlineAllofReferences)) {
            $output['inlineAllofReferences'] = $this->inlineAllofReferences;
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
        if (isset($this->mutableSetters)) {
            $output['mutableSetters'] = match (true) {
                is_bool($this->mutableSetters),
                is_string($this->mutableSetters) && in_array($this->mutableSetters, array (
              0 => 'chainable',
            ), true) => $this->mutableSetters,
            };
        }
        if (isset($this->noSchemaMetadata)) {
            $output['noSchemaMetadata'] = $this->noSchemaMetadata;
        }
        if (isset($this->singleLineSchema)) {
            $output['singleLineSchema'] = $this->singleLineSchema;
        }
        if (isset($this->noEnums)) {
            $output['noEnums'] = $this->noEnums;
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
        if (isset($this->targetDirectory)) {
            $output->{'targetDirectory'} = $this->targetDirectory;
        }
        if (isset($this->targetNamespace)) {
            $output->{'targetNamespace'} = $this->targetNamespace;
        }
        if (isset($this->targetPHPVersion)) {
            $output->{'targetPHPVersion'} = match (true) {
                is_int($this->targetPHPVersion),
                is_string($this->targetPHPVersion) => $this->targetPHPVersion,
            };
        }
        if (isset($this->cleanTargetDirectory)) {
            $output->{'cleanTargetDirectory'} = $this->cleanTargetDirectory;
        }
        if (isset($this->disableStrictTypes)) {
            $output->{'disableStrictTypes'} = $this->disableStrictTypes;
        }
        if (isset($this->inlineAllofReferences)) {
            $output->{'inlineAllofReferences'} = $this->inlineAllofReferences;
        }
        if (isset($this->newValidatorClassExpr)) {
            $output->{'newValidatorClassExpr'} = $this->newValidatorClassExpr;
        }
        if (isset($this->preservePropertyNames)) {
            $output->{'preservePropertyNames'} = $this->preservePropertyNames;
        }
        if (isset($this->noGetters)) {
            $output->{'noGetters'} = $this->noGetters;
        }
        if (isset($this->noSetters)) {
            $output->{'noSetters'} = $this->noSetters;
        }
        if (isset($this->mutableSetters)) {
            $output->{'mutableSetters'} = match (true) {
                is_bool($this->mutableSetters),
                is_string($this->mutableSetters) && in_array($this->mutableSetters, array (
              0 => 'chainable',
            ), true) => $this->mutableSetters,
            };
        }
        if (isset($this->noSchemaMetadata)) {
            $output->{'noSchemaMetadata'} = $this->noSchemaMetadata;
        }
        if (isset($this->singleLineSchema)) {
            $output->{'singleLineSchema'} = $this->singleLineSchema;
        }
        if (isset($this->noEnums)) {
            $output->{'noEnums'} = $this->noEnums;
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
    public static function validateInput(array|object $input, bool $return = false): bool
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
                is_int($this->targetPHPVersion),
                is_string($this->targetPHPVersion) => $this->targetPHPVersion,
            };
        }
        if (isset($this->mutableSetters)) {
            $this->mutableSetters = match (true) {
                is_bool($this->mutableSetters),
                is_string($this->mutableSetters) && in_array($this->mutableSetters, array (
              0 => 'chainable',
            ), true) => $this->mutableSetters,
            };
        }
    }
}

