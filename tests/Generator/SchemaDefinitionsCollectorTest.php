<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\Definition\Definition;
use Helmich\Schema2Class\Generator\Definition\DefinitionsCollector;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use PHPUnit\Framework\TestCase;

final class SchemaDefinitionsCollectorTest extends TestCase
{
    public function testCollectsAllDefinitions(): void
    {
        $schema = [
            '$schema' => 'http://json-schema.org/draft-07/schema#',
            '$id' => 'http://json-schema.org/draft-07/schema#',
            'title' => 'definitions test',
            'type' => 'object',
            'additionalProperties' => false,
            'definitions' => [
                'address' => [
                    'type' => 'object',
                    'properties' => [
                        'city' => [
                            'type' => 'string'
                        ]
                    ],
                    '$defs' => [
                        'name' => [
                            'type' => 'string'
                        ]
                    ]
                ],
            ],
            '$defs' => [
                'address' => [
                    'type' => 'object',
                    'properties' => [
                        'city' => [
                            'type' => 'string'
                        ]
                    ]
                ]
            ]
        ];

        $collector = new DefinitionsCollector(new GeneratorRequest(
            schema: $schema,
            spec: new ValidatedSpecificationFilesItem('TargetNamespace', 'TargetClass', 'targetDirectory'),
            opts: new SpecificationOptions(),
        ));
        $definitions = iterator_to_array($collector->collect($schema));

        $this->assertCount(3, $definitions);

        $this->assertArrayHasKey('#/$defs/address', $definitions);
        $this->assertArrayHasKey('#/definitions/address', $definitions);
        $this->assertArrayHasKey('#/definitions/address/$defs/name', $definitions);

        $this->assertInstanceOf(Definition::class, $definitions['#/$defs/address']);
        $this->assertInstanceOf(Definition::class, $definitions['#/definitions/address']);
        $this->assertInstanceOf(Definition::class, $definitions['#/definitions/address/$defs/name']);

        $this->assertSame('TargetNamespace', $definitions['#/$defs/address']->namespace);
        $this->assertSame('TargetNamespace', $definitions['#/definitions/address']->namespace);
        $this->assertSame('TargetNamespace\Address\Defs', $definitions['#/definitions/address/$defs/name']->namespace);

        $this->assertSame('targetDirectory', $definitions['#/$defs/address']->directory);
        $this->assertSame('targetDirectory', $definitions['#/definitions/address']->directory);
        $this->assertSame('targetDirectory/Address/Defs', $definitions['#/definitions/address/$defs/name']->directory);

        $this->assertSame('TargetNamespace\Address_1', $definitions['#/$defs/address']->classFQN);
        $this->assertSame('TargetNamespace\Address', $definitions['#/definitions/address']->classFQN);
        $this->assertSame('TargetNamespace\Address\Defs\Name', $definitions['#/definitions/address/$defs/name']->classFQN);

        $this->assertSame('Address_1', $definitions['#/$defs/address']->className);
        $this->assertSame('Address', $definitions['#/definitions/address']->className);
        $this->assertSame('Name', $definitions['#/definitions/address/$defs/name']->className);
    }

    public function testReservesRootClassNameForEnum(): void
    {
        $schema = [
            'enum' => ['a', 'b'],
            '$defs' => [
                'TargetClass' => [
                    'type' => 'object',
                    'properties' => [],
                ],
            ],
        ];

        $collector = new DefinitionsCollector(new GeneratorRequest(
            schema: $schema,
            spec: new ValidatedSpecificationFilesItem('Ns', 'TargetClass', 'dir'),
            opts: new SpecificationOptions(),
        ));
        $definitions = iterator_to_array($collector->collect($schema));

        $this->assertArrayHasKey('#/$defs/TargetClass', $definitions);
        $this->assertSame('Ns\\TargetClass_1', $definitions['#/$defs/TargetClass']->classFQN);
    }

    public function testReservesRootClassNameForReferencedSchema(): void
    {
        $schema = [
            '$ref' => '#/$defs/address',
            '$defs' => [
                'address' => [
                    'type' => 'object',
                    'properties' => [
                        'street' => ['type' => 'string'],
                    ],
                ],
                'TargetClass' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                    ],
                ],
            ],
        ];

        $collector = new DefinitionsCollector(new GeneratorRequest(
            schema: $schema,
            spec: new ValidatedSpecificationFilesItem('Ns', 'TargetClass', 'dir'),
            opts: new SpecificationOptions(),
        ));
        $definitions = iterator_to_array($collector->collect($schema));

        $this->assertArrayHasKey('#/$defs/address', $definitions);
        $this->assertArrayHasKey('#/$defs/TargetClass', $definitions);
        $this->assertSame('Ns\\TargetClass_1', $definitions['#/$defs/TargetClass']->classFQN);
    }
}
