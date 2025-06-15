<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\Definitions\Definition;
use Helmich\Schema2Class\Generator\Definitions\DefinitionsCollector;
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

        $this->assertSame('TargetNamespace\Defs', $definitions['#/$defs/address']->namespace);
        $this->assertSame('TargetNamespace\Definitions', $definitions['#/definitions/address']->namespace);
        $this->assertSame('TargetNamespace\Definitions\Address\Defs', $definitions['#/definitions/address/$defs/name']->namespace);

        $this->assertSame('targetDirectory/Defs', $definitions['#/$defs/address']->directory);
        $this->assertSame('targetDirectory/Definitions', $definitions['#/definitions/address']->directory);
        $this->assertSame('targetDirectory/Definitions/Address/Defs', $definitions['#/definitions/address/$defs/name']->directory);

        $this->assertSame('TargetNamespace\Defs\Address', $definitions['#/$defs/address']->classFQN);
        $this->assertSame('TargetNamespace\Definitions\Address', $definitions['#/definitions/address']->classFQN);
        $this->assertSame('TargetNamespace\Definitions\Address\Defs\Name', $definitions['#/definitions/address/$defs/name']->classFQN);

        $this->assertSame('Address', $definitions['#/$defs/address']->className);
        $this->assertSame('Address', $definitions['#/definitions/address']->className);
        $this->assertSame('Name', $definitions['#/definitions/address/$defs/name']->className);
    }
}
