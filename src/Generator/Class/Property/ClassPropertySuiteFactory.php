<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Property;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;

/**
 * Factory for creating all methods of a generated class.
 */
class ClassPropertySuiteFactory
{
    public function __construct(
      private GeneratorRequest $request,
      private array $schema,
      private PropertyCollection $schemaProperties,
      private array $defaults,
      private bool $hasOptionalNullable,
    ) {}

    /**
     * @return PropertyGenerator[]
     */
    public function generateProperties()
    {
        $validationSchemaFactory = new ValidationSchemaPropertyFactory($this->request, $this->schema);
        $providedOptionalsFactory = new ProvidedOptionalsPropertyFactory($this->request);
        $defaultsFactory = new DefaultsPropertyFactory($this->request, $this->defaults);
        $schemaPropertyFactory = new SchemaPropertyFactory($this->request);

        $schemaProperty = $validationSchemaFactory->generateValidationSchemaProperty();
        $propertyGenerators[] = $schemaProperty;

        if ($this->defaults) {
            $propertyGenerators[] = $defaultsFactory->generateDefaultsProperty();
        }

        if ($this->hasOptionalNullable) {
            $propertyGenerators[] = $providedOptionalsFactory->generateProvidedOptionalsProperty();
        }
        
        foreach ($this->schemaProperties as $schemaProp) {
            $propertyGenerators[] = $schemaPropertyFactory->generateClassSchemaProperty($schemaProp);
        }

        return $propertyGenerators;
    }
}