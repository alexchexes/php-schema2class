<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollectionFilterFactory;
use Laminas\Code\Generator\MethodGenerator;

/** 
 * Factory for creating all accessors (get/set/unset/with/without) of a generated class.
 */
class SchemaPropertyAccessorsFactory
{
    public function __construct(
        private GeneratorRequest $request,
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

        $getterFactory = new GetterFactory($this->request);
        $setterFactory = new SetterFactory($this->request);
        $unsetterFactory = new UnsetterFactory($this->request);

        foreach ($filteredProperties as $property) {
            $methodsGenerators[] = $getterFactory->generate($property);
            $methodsGenerators[] = $setterFactory->generate($property);
            $methodsGenerators[] = $unsetterFactory->generate($property);
        }

        return array_values(array_filter($methodsGenerators));
    }
}