<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\Class\Method\AdditionalProperties\AdditionalPropsMethodsFactory;
use Helmich\Schema2Class\Generator\Class\Method\SchemaPropertyAccessor\PropertyAccessorsFactory;
use Helmich\Schema2Class\Generator\Class\Method\Serialize\SerializeMethodFactory;
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
        private array $schema,
        private PropertyCollection $schemaProperties,
        private array $defaults,
        private bool $additionalsAllowed,
    ) {}

    /**
     * Generate all methods for a class and ensure unique names.
     *
     * @return MethodGenerator[]
     */
    public function generateAll(): array
    {
        $methodGenerators = [
            (new ConstructorFactory($this->schemaProperties, $this->additionalsAllowed))->generate(),
            ...(new AdditionalPropsMethodsFactory($this->request, $this->schema, $this->additionalsAllowed))->generateAll(),
            ...(new PropertyAccessorsFactory($this->request, $this->schema, $this->schemaProperties))->generateAll(),
            (new FromInputMethodFactory($this->request, $this->schemaProperties, $this->defaults, $this->additionalsAllowed))->generate(),
            ...(new SerializeMethodFactory($this->request, $this->schemaProperties, $this->defaults, $this->additionalsAllowed))->generateAll(),
            (new ValidateMethodFactory($this->request))->generate(),
            (new ValidateInputMethodFactory($this->request))->generate(),
            (new CloneMethodFactory($this->schemaProperties))->generate(),
            (new IsOptionalProvidedMethodFactory($this->request, $this->schemaProperties->hasOptionalNullable()))->generate(),
        ];

        return array_values(array_filter($methodGenerators));
    }
}
