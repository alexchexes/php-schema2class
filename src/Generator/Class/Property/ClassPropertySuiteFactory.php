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
        $propertyGenerators = [
            (new ValidationSchemaPropertyFactory($this->request, $this->schema))->generate(),
            (new NamesMapPropertyFactory($this->request, $this->schemaProperties, $this->additionalsAllowed))->generate(),
            (new DefaultsPropertyFactory($this->request, $this->defaults))->generate(),
            (new ProvidedOptionalsPropertyFactory($this->request, $this->schemaProperties))->generate(),
            (new AdditionalPropertiesPropertyFactory($this->request, $this->additionalsAllowed))->generate(),
            ...(new SchemaPropertyFactory($this->request, $this->schemaProperties))->generateAll(),
        ];
        
        return array_values(array_filter($propertyGenerators));
    }
}