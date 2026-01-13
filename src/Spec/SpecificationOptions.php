<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Spec;

class SpecificationOptions
{
    /**
     * Schema used to validate input for creating instances of this class
     */
    private static array $_schema = ['additionalProperties' => false, 'properties' => ['targetDirectory' => ['type' => 'string'], 'targetNamespace' => ['type' => 'string'], 'targetPHPVersion' => ['oneOf' => [['type' => 'integer', 'enum' => [5, 7, 8]], ['type' => 'string']]], 'cleanTargetDirectory' => ['type' => 'boolean'], 'disableStrictTypes' => ['type' => 'boolean'], 'inlineAllofReferences' => ['type' => 'boolean'], 'newValidatorExpr' => ['type' => 'string'], 'arrayToObjectExpr' => ['type' => 'string'], 'preservePropertyNames' => ['type' => 'boolean'], 'noGetters' => ['type' => 'boolean'], 'noSetters' => ['type' => 'boolean'], 'mutableSetters' => ['oneOf' => [['type' => 'boolean', 'enum' => [true]], ['type' => 'string', 'enum' => ['chainable']]]], 'noSchemaMetadata' => ['type' => 'boolean'], 'singleLineSchema' => ['type' => 'boolean'], 'noEnums' => ['type' => 'boolean']]];

    private ?string $targetDirectory = null;

    private ?string $targetNamespace = null;

    /**
     * @var 5|7|8|string|null
     */
    private int|string|null $targetPHPVersion = null;

    private ?bool $cleanTargetDirectory = null;

    private ?bool $disableStrictTypes = null;

    private ?bool $inlineAllofReferences = null;

    private ?string $newValidatorExpr = null;

    private ?string $arrayToObjectExpr = null;

    private ?bool $preservePropertyNames = null;

    private ?bool $noGetters = null;

    private ?bool $noSetters = null;

    /**
     * @var true|'chainable'|null
     */
    private bool|string|null $mutableSetters = null;

    private ?bool $noSchemaMetadata = null;

    private ?bool $singleLineSchema = null;

    private ?bool $noEnums = null;

    /**
     * @param 5|7|8|string|null $targetPHPVersion
     * @param true|'chainable'|null $mutableSetters
     */
    public function __construct(?string $targetDirectory = null, ?string $targetNamespace = null, int|string|null $targetPHPVersion = null, ?bool $cleanTargetDirectory = null, ?bool $disableStrictTypes = null, ?bool $inlineAllofReferences = null, ?string $newValidatorExpr = null, ?string $arrayToObjectExpr = null, ?bool $preservePropertyNames = null, ?bool $noGetters = null, ?bool $noSetters = null, bool|string|null $mutableSetters = null, ?bool $noSchemaMetadata = null, ?bool $singleLineSchema = null, ?bool $noEnums = null)
    {
        $this->targetDirectory = $targetDirectory;
        $this->targetNamespace = $targetNamespace;
        $this->targetPHPVersion = $targetPHPVersion;
        $this->cleanTargetDirectory = $cleanTargetDirectory;
        $this->disableStrictTypes = $disableStrictTypes;
        $this->inlineAllofReferences = $inlineAllofReferences;
        $this->newValidatorExpr = $newValidatorExpr;
        $this->arrayToObjectExpr = $arrayToObjectExpr;
        $this->preservePropertyNames = $preservePropertyNames;
        $this->noGetters = $noGetters;
        $this->noSetters = $noSetters;
        $this->mutableSetters = $mutableSetters;
        $this->noSchemaMetadata = $noSchemaMetadata;
        $this->singleLineSchema = $singleLineSchema;
        $this->noEnums = $noEnums;
    }

    public function getTargetDirectory(): ?string
    {
        return $this->targetDirectory ?? null;
    }

    public function withTargetDirectory(string $targetDirectory): self
    {
        $clone = clone $this;
        $clone->targetDirectory = $targetDirectory;

        return $clone;
    }

    public function withoutTargetDirectory(): self
    {
        $clone = clone $this;
        unset($clone->targetDirectory);

        return $clone;
    }

    public function getTargetNamespace(): ?string
    {
        return $this->targetNamespace ?? null;
    }

    public function withTargetNamespace(string $targetNamespace): self
    {
        $clone = clone $this;
        $clone->targetNamespace = $targetNamespace;

        return $clone;
    }

    public function withoutTargetNamespace(): self
    {
        $clone = clone $this;
        unset($clone->targetNamespace);

        return $clone;
    }

    /**
     * @return 5|7|8|string|null
     */
    public function getTargetPHPVersion(): int|string|null
    {
        return $this->targetPHPVersion ?? null;
    }

    /**
     * @param 5|7|8|string $targetPHPVersion
     */
    public function withTargetPHPVersion(int|string $targetPHPVersion, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($targetPHPVersion, self::$_schema['properties']['targetPHPVersion']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->targetPHPVersion = $targetPHPVersion;

        return $clone;
    }

    public function withoutTargetPHPVersion(): self
    {
        $clone = clone $this;
        unset($clone->targetPHPVersion);

        return $clone;
    }

    /**
     * When true, the generator removes all files from the target directory
     * before writing new ones.
     */
    public function getCleanTargetDirectory(): ?bool
    {
        return $this->cleanTargetDirectory ?? null;
    }

    /**
     * When true, the generator removes all files from the target directory
     * before writing new ones.
     */
    public function withCleanTargetDirectory(bool $cleanTargetDirectory): self
    {
        $clone = clone $this;
        $clone->cleanTargetDirectory = $cleanTargetDirectory;

        return $clone;
    }

    public function withoutCleanTargetDirectory(): self
    {
        $clone = clone $this;
        unset($clone->cleanTargetDirectory);

        return $clone;
    }

    public function getDisableStrictTypes(): ?bool
    {
        return $this->disableStrictTypes ?? null;
    }

    public function withDisableStrictTypes(bool $disableStrictTypes): self
    {
        $clone = clone $this;
        $clone->disableStrictTypes = $disableStrictTypes;

        return $clone;
    }

    public function withoutDisableStrictTypes(): self
    {
        $clone = clone $this;
        unset($clone->disableStrictTypes);

        return $clone;
    }

    public function getInlineAllofReferences(): ?bool
    {
        return $this->inlineAllofReferences ?? null;
    }

    public function withInlineAllofReferences(bool $inlineAllofReferences): self
    {
        $clone = clone $this;
        $clone->inlineAllofReferences = $inlineAllofReferences;

        return $clone;
    }

    public function withoutInlineAllofReferences(): self
    {
        $clone = clone $this;
        unset($clone->inlineAllofReferences);

        return $clone;
    }

    /**
     * The expression to use to create a new instance of the validator class.
     * This is useful if you want to use a custom validator class.
     */
    public function getNewValidatorExpr(): ?string
    {
        return $this->newValidatorExpr ?? null;
    }

    /**
     * The expression to use to create a new instance of the validator class.
     * This is useful if you want to use a custom validator class.
     */
    public function withNewValidatorExpr(string $newValidatorExpr): self
    {
        $clone = clone $this;
        $clone->newValidatorExpr = $newValidatorExpr;

        return $clone;
    }

    public function withoutNewValidatorExpr(): self
    {
        $clone = clone $this;
        unset($clone->newValidatorExpr);

        return $clone;
    }

    /**
     * Expression to use to recursively convert arrays to objects (e.g. `Utils::arrayToObjectRecursive` - no call parens!)
     */
    public function getArrayToObjectExpr(): ?string
    {
        return $this->arrayToObjectExpr ?? null;
    }

    /**
     * Expression to use to recursively convert arrays to objects (e.g. `Utils::arrayToObjectRecursive` - no call parens!)
     */
    public function withArrayToObjectExpr(string $arrayToObjectExpr): self
    {
        $clone = clone $this;
        $clone->arrayToObjectExpr = $arrayToObjectExpr;

        return $clone;
    }

    public function withoutArrayToObjectExpr(): self
    {
        $clone = clone $this;
        unset($clone->arrayToObjectExpr);

        return $clone;
    }

    /**
     * When true, properties names are not converted to camelCase.
     */
    public function getPreservePropertyNames(): ?bool
    {
        return $this->preservePropertyNames ?? null;
    }

    /**
     * When true, properties names are not converted to camelCase.
     */
    public function withPreservePropertyNames(bool $preservePropertyNames): self
    {
        $clone = clone $this;
        $clone->preservePropertyNames = $preservePropertyNames;

        return $clone;
    }

    public function withoutPreservePropertyNames(): self
    {
        $clone = clone $this;
        unset($clone->preservePropertyNames);

        return $clone;
    }

    /**
     * When true, no getters are created and all properties are 'public'.
     */
    public function getNoGetters(): ?bool
    {
        return $this->noGetters ?? null;
    }

    /**
     * When true, no getters are created and all properties are 'public'.
     */
    public function withNoGetters(bool $noGetters): self
    {
        $clone = clone $this;
        $clone->noGetters = $noGetters;

        return $clone;
    }

    public function withoutNoGetters(): self
    {
        $clone = clone $this;
        unset($clone->noGetters);

        return $clone;
    }

    /**
     * When true, no withX() / withoutX() setters/unsetters are created.
     */
    public function getNoSetters(): ?bool
    {
        return $this->noSetters ?? null;
    }

    /**
     * When true, no withX() / withoutX() setters/unsetters are created.
     */
    public function withNoSetters(bool $noSetters): self
    {
        $clone = clone $this;
        $clone->noSetters = $noSetters;

        return $clone;
    }

    public function withoutNoSetters(): self
    {
        $clone = clone $this;
        unset($clone->noSetters);

        return $clone;
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
        return $this->mutableSetters ?? null;
    }

    /**
     * If set, generate classic setX() methods instead of immutable
     * withX()/withoutX(). When the value is "chainable", the setter
     * returns $this.
     *
     *
     * @param true|'chainable' $mutableSetters
     */
    public function withMutableSetters(bool|string $mutableSetters, bool $validate = true): self
    {
        if ($validate) {
            $validator = new \JsonSchema\Validator();
            $validator->validate($mutableSetters, self::$_schema['properties']['mutableSetters']);
            if (!$validator->isValid()) {
                throw new \InvalidArgumentException($validator->getErrors()[0]['message']);
            }
        }

        $clone = clone $this;
        $clone->mutableSetters = $mutableSetters;

        return $clone;
    }

    public function withoutMutableSetters(): self
    {
        $clone = clone $this;
        unset($clone->mutableSetters);

        return $clone;
    }

    /**
     * When true, the schema used for validation will not include
     * description, title and other non-validation metadata fields
     */
    public function getNoSchemaMetadata(): ?bool
    {
        return $this->noSchemaMetadata ?? null;
    }

    /**
     * When true, the schema used for validation will not include
     * description, title and other non-validation metadata fields
     */
    public function withNoSchemaMetadata(bool $noSchemaMetadata): self
    {
        $clone = clone $this;
        $clone->noSchemaMetadata = $noSchemaMetadata;

        return $clone;
    }

    public function withoutNoSchemaMetadata(): self
    {
        $clone = clone $this;
        unset($clone->noSchemaMetadata);

        return $clone;
    }

    /**
     * When true, the whole schema used for validation will on a single line in the class property
     */
    public function getSingleLineSchema(): ?bool
    {
        return $this->singleLineSchema ?? null;
    }

    /**
     * When true, the whole schema used for validation will on a single line in the class property
     */
    public function withSingleLineSchema(bool $singleLineSchema): self
    {
        $clone = clone $this;
        $clone->singleLineSchema = $singleLineSchema;

        return $clone;
    }

    public function withoutSingleLineSchema(): self
    {
        $clone = clone $this;
        unset($clone->singleLineSchema);

        return $clone;
    }

    /**
     * Disable generating PHP enum classes even on PHP ≥ 8.1. Enum values will be
     * handled like in earlier PHP versions.
     */
    public function getNoEnums(): ?bool
    {
        return $this->noEnums ?? null;
    }

    /**
     * Disable generating PHP enum classes even on PHP ≥ 8.1. Enum values will be
     * handled like in earlier PHP versions.
     */
    public function withNoEnums(bool $noEnums): self
    {
        $clone = clone $this;
        $clone->noEnums = $noEnums;

        return $clone;
    }

    public function withoutNoEnums(): self
    {
        $clone = clone $this;
        unset($clone->noEnums);

        return $clone;
    }

    /**
     * Builds a new instance from an input array or object
     *
     * @param array|object $input Input data
     * @param bool $validate If `false`, validation against the schema will be skipped.
     * @return SpecificationOptions Created instance
     * @throws \InvalidArgumentException
     */
    public static function fromInput(array|object $input, bool $validate = true): SpecificationOptions
    {
        $input = is_array($input) ? \JsonSchema\Validator::arrayToObjectRecursive($input) : $input;
        if ($validate) {
            static::validateInput($input);
        }

        $targetDirectory = isset($input->{'targetDirectory'}) ? $input->{'targetDirectory'} : null;
        $targetNamespace = isset($input->{'targetNamespace'}) ? $input->{'targetNamespace'} : null;
        $targetPHPVersion = isset($input->{'targetPHPVersion'})
            ? match (true) {
                is_string($input->{'targetPHPVersion'}) => ($input->{'targetPHPVersion'}),
                in_array($input->{'targetPHPVersion'}, [5, 7, 8], true) => (int)$input->{'targetPHPVersion'},
                default => $input->{'targetPHPVersion'},
            }
            : null;
        $cleanTargetDirectory = isset($input->{'cleanTargetDirectory'}) ? $input->{'cleanTargetDirectory'} : null;
        $disableStrictTypes = isset($input->{'disableStrictTypes'}) ? $input->{'disableStrictTypes'} : null;
        $inlineAllofReferences = isset($input->{'inlineAllofReferences'}) ? $input->{'inlineAllofReferences'} : null;
        $newValidatorExpr = isset($input->{'newValidatorExpr'}) ? $input->{'newValidatorExpr'} : null;
        $arrayToObjectExpr = isset($input->{'arrayToObjectExpr'}) ? $input->{'arrayToObjectExpr'} : null;
        $preservePropertyNames = isset($input->{'preservePropertyNames'}) ? $input->{'preservePropertyNames'} : null;
        $noGetters = isset($input->{'noGetters'}) ? $input->{'noGetters'} : null;
        $noSetters = isset($input->{'noSetters'}) ? $input->{'noSetters'} : null;
        $mutableSetters = isset($input->{'mutableSetters'})
            ? match (true) {
                in_array($input->{'mutableSetters'}, ['chainable'], true) => ($input->{'mutableSetters'}),
                in_array($input->{'mutableSetters'}, [true], true) => (bool)$input->{'mutableSetters'},
                default => $input->{'mutableSetters'},
            }
            : null;
        $noSchemaMetadata = isset($input->{'noSchemaMetadata'}) ? $input->{'noSchemaMetadata'} : null;
        $singleLineSchema = isset($input->{'singleLineSchema'}) ? $input->{'singleLineSchema'} : null;
        $noEnums = isset($input->{'noEnums'}) ? $input->{'noEnums'} : null;

        $obj = new self(
            $targetDirectory,
            $targetNamespace,
            $targetPHPVersion,
            $cleanTargetDirectory,
            $disableStrictTypes,
            $inlineAllofReferences,
            $newValidatorExpr,
            $arrayToObjectExpr,
            $preservePropertyNames,
            $noGetters,
            $noSetters,
            $mutableSetters,
            $noSchemaMetadata,
            $singleLineSchema,
            $noEnums
        );

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
        if (isset($this->targetDirectory)) {
            $output['targetDirectory'] = $this->targetDirectory;
        }
        if (isset($this->targetNamespace)) {
            $output['targetNamespace'] = $this->targetNamespace;
        }
        if (isset($this->targetPHPVersion)) {
            $output['targetPHPVersion'] = match (true) {
                default => $this->targetPHPVersion,
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
        if (isset($this->newValidatorExpr)) {
            $output['newValidatorExpr'] = $this->newValidatorExpr;
        }
        if (isset($this->arrayToObjectExpr)) {
            $output['arrayToObjectExpr'] = $this->arrayToObjectExpr;
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
                default => $this->mutableSetters,
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
                default => $this->targetPHPVersion,
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
        if (isset($this->newValidatorExpr)) {
            $output->{'newValidatorExpr'} = $this->newValidatorExpr;
        }
        if (isset($this->arrayToObjectExpr)) {
            $output->{'arrayToObjectExpr'} = $this->arrayToObjectExpr;
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
                default => $this->mutableSetters,
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
}

