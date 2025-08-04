<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use PHPUnit\Framework\TestCase;

class PropertyNameResolverTest extends TestCase
{
    private function collectAndResolve(array $schema): array
    {
        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns', 'Foo', ''),
            new SpecificationOptions(),
        );

        $collector = new SchemaPropertyCollector();
        $props = $collector->collectPropertiesFromSchema($schema, $req);

        (new PropertyNameResolver($req))->resolve($props);

        $map = [];
        foreach ($props as $p) {
            $map[$p->key()] = [
                'prop' => $p->name(),
                'var' => $p->varName(),
                'method' => $p->methodName(),
            ];
        }

        return $map;
    }

    public function testStableAndUniqueNames(): void
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'input' => ['type' => 'string'],
                'bound' => ['type' => 'string'],
                'outbound' => ['type' => 'string'],
                'foo' => ['type' => 'string'],
                'Foo' => ['type' => 'string'],
            ],
            'required' => ['input', 'outbound', 'foo', 'Foo'],
        ];

        $schemaReversed = $schema;
        $schemaReversed['properties'] = array_reverse($schema['properties'], true);

        $map1 = $this->collectAndResolve($schema);
        $map2 = $this->collectAndResolve($schemaReversed);

        self::assertEquals($map1, $map2, 'Names must be stable regardless of property order');

        // case-variant collision should produce deterministic method names
        self::assertSame('Foo_1', $map1['foo']['method']);
        self::assertSame('Foo', $map1['Foo']['method']);

        // cross-prefix collision bound/outbound
        self::assertSame('Bound', $map1['bound']['method']);
        self::assertSame('Outbound_1', $map1['outbound']['method']);

        // variable name must avoid reserved/internal names
        self::assertNotSame('input', $map1['input']['var']);
    }
}

