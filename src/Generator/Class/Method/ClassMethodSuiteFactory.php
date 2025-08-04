<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Laminas\Code\Generator\MethodGenerator;

/**
 * Factory for creating all methods of a generated class.
 */
class ClassMethodSuiteFactory
{
    public function __construct(
        private GeneratorRequest $request,
        private PropertyCollection $schemaProperties,
        private array $defaults,
        private bool $hasOptionalNullable,
    ) {}

    /**
     * Generate all methods for a class and ensure unique names.
     *
     * @return MethodGenerator[]
     */
    public function generateMethods(): array
    {
        $constructorFactory = new ConstructorFactory($this->schemaProperties);
        $accessorsFactory = new PropertyAccessorsFactory($this->request, $this->schemaProperties);
        $buildMethodFactory = new FromInputMethodFactory($this->request, $this->schemaProperties, $this->defaults, $this->hasOptionalNullable);
        $serializeMethodFactory = new SerializeMethodFactory($this->request, $this->schemaProperties, $this->defaults);
        $validateMethodFactory = new ValidateMethodFactory($this->request);
        $cloneMethodFactory = new CloneMethodFactory($this->schemaProperties);
        $isProvidedMethodFactory = new IsProvidedMethodFactory($this->request, $this->hasOptionalNullable);

        $methodGenerators = [
            $constructorFactory->generateConstructor(),
            ...$accessorsFactory->generatePropertyAccessors(),
            $buildMethodFactory->generateFromInputMethod(),
            ...$serializeMethodFactory->generateSerializeMethods(),
            $validateMethodFactory->generateValidateMethod(),
            $cloneMethodFactory->generateCloneMethod(),
            $isProvidedMethodFactory->generateIsProvidedMethod(),
        ];

        $methodGenerators = array_values(array_filter($methodGenerators));

        return $methodGenerators;
    }
}
