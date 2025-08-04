<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use PHPUnit\Framework\TestCase;

class SchemaPropertyNamingTest extends TestCase
{
    private function map(PropertyCollection $props): array
    {
        $res = [];
        foreach ($props as $p) {
            $res[$p->key()] = [$p->name(), $p->varName(), $p->methodName()];
        }
        ksort($res);
        return $res;
    }

    public function testNamesStableRegardlessOfOrder(): void
    {
        $schema1 = ['properties' => [
            'bound' => ['type' => 'string'],
            'outbound' => ['type' => 'string'],
            'input' => ['type' => 'string'],
        ]];
        $schema2 = ['properties' => [
            'input' => ['type' => 'string'],
            'outbound' => ['type' => 'string'],
            'bound' => ['type' => 'string'],
        ]];

        $req1 = new GeneratorRequest($schema1, new ValidatedSpecificationFilesItem('Ns', 'Foo', ''), new SpecificationOptions());
        $req2 = new GeneratorRequest($schema2, new ValidatedSpecificationFilesItem('Ns', 'Foo', ''), new SpecificationOptions());

        $collector = new SchemaPropertyCollector();
        $props1 = $collector->collectPropertiesFromSchema($schema1, $req1);
        $props2 = $collector->collectPropertiesFromSchema($schema2, $req2);

        (new SchemaPropertyNaming($req1))->resolve($props1);
        (new SchemaPropertyNaming($req2))->resolve($props2);

        $this->assertSame($this->map($props1), $this->map($props2));

        $mapped = $this->map($props1);
        $this->assertSame('_input', $mapped['input'][1]);
        $this->assertSame('Bound', $mapped['bound'][2]);
        $this->assertSame('Outbound_1', $mapped['outbound'][2]);
    }

    public function testCaseVariantMethodNamesDeterministic(): void
    {
        $schemaA = ['properties' => [
            'foo' => ['type' => 'string'],
            'FOO' => ['type' => 'string'],
        ]];
        $schemaB = ['properties' => [
            'FOO' => ['type' => 'string'],
            'foo' => ['type' => 'string'],
        ]];

        $options = (new SpecificationOptions())->withPreservePropertyNames(true);
        $reqA = new GeneratorRequest($schemaA, new ValidatedSpecificationFilesItem('Ns', 'Foo', ''), $options);
        $reqB = new GeneratorRequest($schemaB, new ValidatedSpecificationFilesItem('Ns', 'Foo', ''), $options);

        $collector = new SchemaPropertyCollector();
        $propsA = $collector->collectPropertiesFromSchema($schemaA, $reqA);
        $propsB = $collector->collectPropertiesFromSchema($schemaB, $reqB);

        (new SchemaPropertyNaming($reqA))->resolve($propsA);
        (new SchemaPropertyNaming($reqB))->resolve($propsB);

        $this->assertSame($this->map($propsA), $this->map($propsB));

        $mapped = $this->map($propsA);
        $this->assertSame('foo', $mapped['foo'][0]);
        $this->assertSame('FOO', $mapped['FOO'][0]);
        $this->assertSame('Foo_1', $mapped['foo'][2]);
        $this->assertSame('FOO', $mapped['FOO'][2]);
    }
}
