<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method\SchemaPropertyAccessor;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollectionFilterFactory;
use Laminas\Code\Generator\MethodGenerator;

/** 
 * Factory for creating all accessors (get/set/unset/with/without) of a generated class.
 */
class PropertyAccessorsFactory
{
    public function __construct(
        private GeneratorRequest $request,
        private array $schema,
        private PropertyCollection $schemaProperties,
    )
    {}

    /**
     * @return MethodGenerator[]
     */
    public function generateAll(): array
    {
        $methodsGenerators = [];

        $filteredProperties = $this->schemaProperties->filter(
            PropertyCollectionFilterFactory::excludeDeprecatedCaseVariants($this->schemaProperties)
        );

        $getterFactory = new PropertyGetterFactory($this->request);
        $setterFactory = new PropertySetterFactory($this->request, $this->schema);
        $unsetterFactory = new PropertyUnsetterFactory($this->request, $this->schema);

        foreach ($filteredProperties as $property) {
            $methodsGenerators[] = $getterFactory->generate($property);
            $methodsGenerators[] = $setterFactory->generate($property);
            $methodsGenerators[] = $unsetterFactory->generate($property);
        }

        return array_values(array_filter($methodsGenerators));
    }
}