<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\Class\Method\SetterFactory;
use Helmich\Schema2Class\Generator\SchemaPropertyCollector;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use Laminas\Code\Generator\MethodGenerator;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use PHPUnit\Framework\TestCase;

class SetterFactoryTest extends TestCase
{
    /**
     * @return array{MethodGenerator, PropertyInterface}
     */
    private function generateSetter(array $schema): array
    {
        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns', 'Foo', ''),
            new SpecificationOptions(),
        );

        $props = SchemaPropertyCollector::collectPropertiesFromSchema($schema, $req)->toArray();
        $prop = $props[0];

        $factory = new SetterFactory($req);
        $setter = $factory->generate($prop);
        self::assertNotNull($setter);

        return [$setter, $prop];
    }

    public function testSetterAllowsNullForOptionalNullableProperty(): void
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'foo' => ['type' => ['string', 'null']],
            ],
        ];

        [$setter, $prop] = $this->generateSetter($schema);
        $varName = $prop->varName();

        $param = $setter->getParameters()[$varName];
        self::assertSame('?string $' . $varName, $param->generate());

        self::assertNull($setter->getDocBlock());
    }

    public function testSetterDisallowsNullForOptionalNonNullableProperty(): void
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'foo' => ['type' => 'string'],
            ],
        ];

        [$setter, $prop] = $this->generateSetter($schema);
        $varName = $prop->varName();

        $param = $setter->getParameters()[$varName];
        self::assertSame('string $' . $varName, $param->generate());

        self::assertNull($setter->getDocBlock());
    }

    public function testSetterAllowsNullForRequiredNullableProperty(): void
    {
        $schema = [
            'type' => 'object',
            'required' => ['foo'],
            'properties' => [
                'foo' => ['type' => ['string', 'null']],
            ],
        ];

        [$setter, $prop] = $this->generateSetter($schema);
        $varName = $prop->varName();

        $param = $setter->getParameters()[$varName];
        self::assertSame('?string $' . $varName, $param->generate());

        self::assertNull($setter->getDocBlock());
    }
}
