<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Composer\Semver\Comparator;
use Helmich\Schema2Class\Generator\Hook\AddInterfaceHook;
use Helmich\Schema2Class\Generator\Hook\AddMethodHook;
use Helmich\Schema2Class\Generator\Hook\AddPropertyHook;
use Helmich\Schema2Class\Generator\Hook\GeneratorHookRunner;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeInterface;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeUnknown;
use Helmich\Schema2Class\Generator\ReferenceLookup\ReferenceLookup;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\OptionsDefaults;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use Helmich\Schema2Class\Generator\SchemaToClassFactory;
use Helmich\Schema2Class\Loader\SchemaLoader;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\PropertyGenerator as LaminasPropertyGenerator;

/** 
 * (Mostly) immutable data object describing what and how to generate.
 * 
 * Every class or enum is generated from a `GeneratorRequest`. It bundles the JSON schema
 * to operate on, generation options and information about the surrounding specification file.
 * 
 * The request also stores runtime information such as registered {@see ReferenceLookup}
 * implementations and is cloned when modifications are needed for nested classes
 * 
 * Mutation is deliberately allowed for `currReqHasDefaults` to simplify passing
 * information to nested contexts. TODO: Rethink this.
 */
class GeneratorRequest
{
    use GeneratorHookRunner;

    const DEFAULT_PHP5_VERSION = '5.6';
    const DEFAULT_PHP7_VERSION = '7.4';
    const DEFAULT_PHP8_VERSION = '8.4';

    private array $schema;
    private object|null $rawSchema = null;
    
    /** @var array<string,mixed>|null Root schema's definitions, if any */
    private ?array $rootDefinitions = null;
    private array $generatedClassNames = [];

    private ValidatedSpecificationFilesItem $spec;
    private SpecificationOptions $opts;

    /** @var array<class-string, ReferenceLookup> */
    private array $referenceLookup = [];
    
    /**
     * Whether the object schema from which the class is currently generated has defaults.
     */
    private bool $currReqHasDefaults = false;

    private SchemaToClassFactory $factory;

    public static function normalizeTargetVersion(int|string $version): string
    {
        $mapped = match ($version) {
            5, '5' => self::DEFAULT_PHP5_VERSION,
            7, '7' => self::DEFAULT_PHP7_VERSION,
            8, '8' => self::DEFAULT_PHP8_VERSION,
            default => $version,
        };

        return self::semversifyVersionNumber($mapped);
    }

    public function __construct(
        array $schema,
        ValidatedSpecificationFilesItem $spec,
        SpecificationOptions $opts,
        ?SchemaToClassFactory $factory = null,
    )
    {
        $opts = OptionsDefaults::applyDefaults($opts);
        $opts = $opts->withTargetPHPVersion(
            self::normalizeTargetVersion($opts->getTargetPHPVersion())
        );

        $this->rawSchema = $schema[SchemaLoader::RAW_KEY] ?? null;
        if ($this->rawSchema !== null) {
            unset($schema[SchemaLoader::RAW_KEY]);
        }

        $this->schema = $schema;
        $this->spec   = $spec;
        $this->opts   = $opts;
        $this->factory = $factory ?? new SchemaToClassFactory();
    }

    /**
     * Attach the root schema's "definitions" block so each class can re‑embed it.
     *
     * @param array<string,mixed> $definitions
     * @return self
     */
    public function withRootDefinitions(array $definitions): self
    {
        $clone = clone $this;
        $clone->rootDefinitions = $definitions;
        return $clone;
    }

    /**
     * @return array<string,mixed>|null
     */
    public function getRootDefinitions(): ?array
    {
        return $this->rootDefinitions;
    }

    public function getRawSchema(): ?object
    {
        return $this->rawSchema;
    }

    public function getSchemaToClassFactory(): SchemaToClassFactory
    {
        return $this->factory;
    }

    private static function semversifyVersionNumber(string|int $versionNumber): string
    {
        if (is_int($versionNumber)) {
            return $versionNumber . ".0.0";
        }

        return match (substr_count($versionNumber, '.')) {
            0 => $versionNumber . ".0.0",
            1 => $versionNumber . ".0",
            default => $versionNumber
        };
    }

    public function withReferenceLookup(ReferenceLookup $referenceLookup): self
    {
        $clone                  = clone $this;
        $clone->referenceLookup = [];
        $clone->referenceLookup[$referenceLookup::class] = $referenceLookup;

        return $clone;
    }

    public function withAdditionalReferenceLookup(ReferenceLookup $referenceLookup): self
    {
        $clone                  = clone $this;
        $clone->referenceLookup[$referenceLookup::class] = $referenceLookup;

        return $clone;
    }

    /**
     * @param class-string $referenceLookup
     */
    public function hasReferenceLookup(string $referenceLookup): bool
    {
        return isset($this->referenceLookup[$referenceLookup]);
    }

    public function withSchema(array $schema): self
    {
        $clone         = clone $this;
        $clone->schema = $schema;

        return $clone;
    }

    public function withClass(?string $targetClass): self
    {
        $clone       = clone $this;
        $clone->spec = $this->spec->withTargetClass($targetClass);

        $clone->clearNonPropagatingHooks();

        return $clone;
    }

    public function withNamespace(string $targetNamespace): self
    {
        $clone       = clone $this;
        $clone->spec = $this->spec->withTargetNamespace($targetNamespace);

        $clone->clearNonPropagatingHooks();

        return $clone;
    }

    public function withDirectory(string $targetDirectory): self
    {
        $clone       = clone $this;
        $clone->spec = $this->spec->withTargetDirectory($targetDirectory);

        $clone->clearNonPropagatingHooks();

        return $clone;
    }

    public function withGeneratedClassNames(array $names): self
    {
        $clone = clone $this;
        $clone->generatedClassNames = $names;
        return $clone;
    }
    
    public function getGeneratedClassNames(): array
    {
        return $this->generatedClassNames;
    }

    public function withPHPVersion(string $targetPHPVersion): self
    {
        $clone       = clone $this;
        $clone->opts = $this->opts->withTargetPHPVersion(
            self::normalizeTargetVersion($targetPHPVersion)
        );

        return $clone;
    }

    /**
     * Adds a property to generated classes.
     *
     * @param LaminasPropertyGenerator $property The property to add to generated classes.
     * @param bool $propagateToSubObjects Controls if the property should be added to sub-objects.
     * @return self
     */
    public function withAdditionalProperty(LaminasPropertyGenerator $property, bool $propagateToSubObjects = false): self
    {
        return $this->withHook(new AddPropertyHook($property), $propagateToSubObjects);
    }

    /**
     * Adds a method to generated classes.
     *
     * @param MethodGenerator $method The method to add to generated classes.
     * @param bool $propagateToSubObjects Controls if the method should be added to sub-objects.
     * @return self
     */
    public function withAdditionalMethod(MethodGenerator $method, bool $propagateToSubObjects = false): self
    {
        return $this->withHook(new AddMethodHook($method), $propagateToSubObjects);
    }

    /**
     * Adds an "implements" clause to generated classes.
     *
     * @param string $interface The interface to add to generated classes.
     * @param bool $propagateToSubObjects Controls if the interface should be added to sub-objects.
     * @return self
     */
    public function withAdditionalInterface(string $interface, bool $propagateToSubObjects = false): self
    {
        return $this->withHook(new AddInterfaceHook($interface), $propagateToSubObjects);
    }

    public function getTargetPHPVersion(): string
    {
        return (string)$this->opts->getTargetPHPVersion();
    }

    public function getNoGetters(): bool
    {
        return $this->opts->getNoGetters() === true;
    }
    
    public function getNoSetters(): bool
    {
        return $this->opts->getNoSetters() === true;
    }

    /** 
     * @return true|'chainable'|null
     */
    public function getMutableSetters(): bool|string|null
    {
        return $this->opts->getMutableSetters();
    }

    public function getNoEnums(): bool
    {
        return $this->opts->getNoEnums() === true;
    }

    public function isAtLeastPHP(string $version): bool
    {
        return Comparator::greaterThanOrEqualTo($this->getTargetPHPVersion(), self::semversifyVersionNumber($version));
    }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->spec->getTargetDirectory();
    }

    /**
     * @return string
     */
    public function getTargetNamespace(): string
    {
        return $this->spec->getTargetNamespace();
    }

    /**
     * @return non-empty-string|null
     */
    public function getTargetClass(): ?string
    {
        return $this->spec->getTargetClass();
    }

    /**
     * @return array
     */
    public function getSchema(): array
    {
        return $this->schema;
    }

    public function getOptions(): SpecificationOptions
    {
        return $this->opts;
    }

    public function lookupReference(string $ref): ReferencedTypeInterface
    {
        if (empty($this->referenceLookup)) {
            throw new GeneratorException("unresolvable reference: {$ref}");
        }

        foreach ($this->referenceLookup as $lookup) {
            $reference = $lookup->lookupReference($ref);
            if (!$reference instanceof ReferencedTypeUnknown) {
                return $reference;
            }
        }

        throw new GeneratorException("unresolvable reference: {$ref}");
    }

    public function lookupSchema(string $ref): array
    {
        if (empty($this->referenceLookup)) {
            throw new GeneratorException("unresolvable reference: {$ref}");
        }

        foreach ($this->referenceLookup as $lookup) {
            $schema = $lookup->lookupSchema($ref);
            if (!empty($schema)) {
                return $schema;
            }
        }

        throw new GeneratorException("unresolvable reference: {$ref}");
    }

    /**
     * This method is deliberately mutating (not `with...`) so that the information about
     * the presence of defaults is available to all property generators in nested contexts.
     */
    public function setCurrReqHasDefaults(bool $currReqHasDefaults): void
    {
        $this->currReqHasDefaults = $currReqHasDefaults;
    }

    public function getCurrReqHasDefaults(): bool
    {
        return $this->currReqHasDefaults;
    }
}
