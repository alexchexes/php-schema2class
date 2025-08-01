<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\Class\Method\FromInputMethodFactory;
use Helmich\Schema2Class\Generator\Class\Method\CloneMethodFactory;
use Helmich\Schema2Class\Generator\Class\Method\ConstructorFactory;
use Helmich\Schema2Class\Generator\Class\Method\GetterFactory;
use Helmich\Schema2Class\Generator\Class\Method\IsProvidedMethodFactory;
use Helmich\Schema2Class\Generator\Class\Method\SerializeMethodFactory;
use Helmich\Schema2Class\Generator\Class\Method\SetterFactory;
use Helmich\Schema2Class\Generator\Class\Method\ValidateMethodFactory;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Laminas\Code\Generator\MethodGenerator;

/**
 * Factory for creating all methods of a generated class.
 */
class ClassMethodFactory
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
        $constructorFactory = new ConstructorFactory($this->request, $this->schemaProperties);
        $getterMethodFactory = new GetterFactory($this->request, $this->schemaProperties);
        $setterMethodFactory = new SetterFactory($this->request, $this->schemaProperties);
        $buildMethodFactory = 
            new FromInputMethodFactory($this->request, $this->schemaProperties, $this->defaults, $this->hasOptionalNullable);
        $serializeMethodFactory = new SerializeMethodFactory($this->request, $this->schemaProperties, $this->defaults);
        $validateMethodFactory = new ValidateMethodFactory($this->request);
        $cloneMethodFactory = new CloneMethodFactory($this->schemaProperties);
        $isProvidedMethodFactory = new IsProvidedMethodFactory($this->request);

        $methodGenerators = [
            $constructorFactory->generateConstructor(),
            ...$getterMethodFactory->generateGetters(),
            ...$setterMethodFactory->generateSetters(),
            $buildMethodFactory->generateFromInputMethod(),
            ...$serializeMethodFactory->generateSerializeMethods(),
            $validateMethodFactory->generateValidateMethod(),
            $cloneMethodFactory->generateCloneMethod(),
            $this->hasOptionalNullable
                ? $isProvidedMethodFactory->generateIsProvidedMethod()
                : null,
        ];

        $methodGenerators = array_values(array_filter($methodGenerators));

        // check whether each name is unique and rename if necessary
        $this->ensureUniqueMethodNames($methodGenerators);

        return $methodGenerators;
    }

    /**
     * Iterates provided `MethodGenerator` objects and changes their names if needed
     * @param MethodGenerator[] $methodGenerators 
     */
    private function ensureUniqueMethodNames(array $methodGenerators): void
    {
        $reservedMethodNames = [
            MethodNames::FROM_INPUT,
            MethodNames::TO_ARRAY,
            MethodNames::TO_STD_CLASS,
            MethodNames::VALIDATE_INPUT,
            'clone',
            '__construct',
            '__destruct',
            '__get',
            '__set',
            '__call',
            '__isset',
            '__unset',
            '__sleep',
            '__wakeup',
            '__toString',
            '__invoke',
            '__debugInfo',
            '__clone',
        ];

        $reserved = array_map('strtolower', $reservedMethodNames);
        $used = [];

        foreach ($methodGenerators as $method) {
            $name   = $method->getName();
            $lcName = strtolower($name);

            if (!in_array($lcName, $used, true) && in_array($lcName, $reserved, true)) {
                $used[] = $lcName;
                continue;
            }

            $candidate = $name;
            $prefix    = '';
            $base      = $name;

            if (preg_match('/^(set|get|without|with)(.+)$/', $name, $m)) {
                $prefix = $m[1];
                $base   = $m[2];
            }

            $i = 1;
            while (in_array(strtolower($candidate), $used, true) || in_array(strtolower($candidate), $reserved, true)) {
                if ($prefix !== '') {
                    $suffix   = $i > 1 ? $base . '_' . ($i - 1) : $base;
                    $candidate = $prefix . '_' . $suffix;
                } else {
                    $candidate = $name . '_' . $i;
                }
                $i++;
            }

            if ($candidate !== $name) {
                $method->setName($candidate);
            }

            $used[] = strtolower($candidate);
        }
    }
}
