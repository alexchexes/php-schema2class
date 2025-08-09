<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Property;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Laminas\Code\Generator\PropertyGenerator;

/**
 * Factory for creating all properties of a generated class.
 */
class ClassPropertySuiteFactory
{
    public function __construct(
      private GeneratorRequest $request,
      private array $schema,
      private PropertyCollection $schemaProperties,
      private array $defaults,
      private bool $additionalsAllowed,
    ) {}

    /**
     * @return PropertyGenerator[]
     */
    public function generateAll()
    {
        $validationSchemaFactory = new ValidationSchemaPropertyFactory($this->request, $this->schema);
        $addPropsPropertyFactory = new AdditionalPropertiesPropertyFactory($this->request, $this->schema);
        $providedOptionalsFactory = new ProvidedOptionalsPropertyFactory($this->request);
        $defaultsFactory = new DefaultsPropertyFactory($this->request, $this->defaults);
        $schemaPropertyFactory = new SchemaPropertyFactory($this->request);

        $propertyGenerators[] = $validationSchemaFactory->generate();

        if ($this->defaults) {
            $propertyGenerators[] = $defaultsFactory->generate();
        }

        if ($this->schemaProperties->hasOptionalNullable()) {
            $propertyGenerators[] = $providedOptionalsFactory->generate();
        }

        if ($this->additionalsAllowed) {
            $propertyGenerators[] = $addPropsPropertyFactory->generate();
        }
        
        foreach ($this->schemaProperties as $schemaProp) {
            $propertyGenerators[] = $schemaPropertyFactory->generate($schemaProp);
        }

        return array_values(array_filter($propertyGenerators));
    }
}