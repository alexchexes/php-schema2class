<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use PHPUnit\Framework\TestCase;

class PropertyIdentifierResolverTest extends TestCase
{
    public function testStableIdentifiersRegardlessOfPropertyOrder(): void
    {
        $schemaA = [
            'properties' => [
                'foo-bar' => ['type' => 'string'],
                'foo_bar' => ['type' => 'string'],
                'bound' => ['type' => 'string'],
                'outbound' => ['type' => 'string'],
        'input' => ['type' => 'string'],
                'GLOBALS' => ['type' => 'string'],
            ],
        ];

        $schemaB = ['properties' => array_reverse($schemaA['properties'], true)];

        $expected = [
            'foo-bar' => ['fooBar', 'FooBar', 'fooBar'],
            'foo_bar' => ['_fooBar', '_FooBar', '_fooBar'],
            'bound' => ['bound', 'Bound', 'bound'],
            'outbound' => ['outbound', '_Outbound', 'outbound'],
            'input' => ['input', 'Input', '_input'],
            'GLOBALS' => ['GLOBALS', 'GLOBALS', '_GLOBALS_1'],
        ];
        ksort($expected);

        $mapA = $this->resolveForSchema($schemaA);
        $mapB = $this->resolveForSchema($schemaB);

        self::assertSame($expected, $mapA);
        self::assertSame($expected, $mapB);
    }

    public function testCaseVariantCollisionsYieldDeterministicNames(): void
    {
        $schemaA = [
            'properties' => [
                'foo' => ['type' => 'string'],
                'Foo' => ['type' => 'string'],
            ],
        ];
        $schemaB = ['properties' => array_reverse($schemaA['properties'], true)];

        $expected = [
            'foo' => ['foo', '_Foo', 'foo'],
            'Foo' => ['Foo', 'Foo', 'Foo'],
        ];
        ksort($expected);

        $mapA = $this->resolveForSchema($schemaA);
        $mapB = $this->resolveForSchema($schemaB);

        self::assertSame($expected, $mapA);
        self::assertSame($expected, $mapB);
    }

    public function testTemporaryVarNamesAvoidReservedNames(): void
    {
        $schema = [
            'properties' => [
                'input' => ['type' => 'string'],
                'GLOBALS' => ['type' => 'string'],
            ],
        ];

        $map = $this->resolveForSchema($schema);

        $expected = [
            'GLOBALS' => ['GLOBALS', 'GLOBALS', '_GLOBALS_1'],
            'input' => ['input', 'Input', '_input'],
        ];
        ksort($expected);
        self::assertSame($expected, $map);
    }

    /**
     * @return array<string, array{0:string,1:string,2:string}>
     */
    private function resolveForSchema(array $schema): array
    {
        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns', 'Foo', ''),
            new SpecificationOptions(),
        );

        $collector = new SchemaPropertyCollector();
        $props = $collector->collectPropertiesFromSchema($schema, $req);
        (new PropertyIdentifierResolver($req))->resolve($props);

        $map = [];
        foreach ($props as $p) {
            $map[$p->key()] = [$p->name(), $p->methodName(), $p->varName()];
        }

        ksort($map);

        return $map;
    }
}

