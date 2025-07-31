<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use PHPUnit\Framework\TestCase;

class MethodFactoryTest extends TestCase
{
    public function test_NoConstructorWhenNoRequiredProps(): void
    {
        $schema = [
            'type' => 'object',
            'properties' => ['foo' => ['type' => 'string']],
        ];

        $req = new GeneratorRequest($schema, new ValidatedSpecificationFilesItem('Ns','Foo',''), new SpecificationOptions());
        $factory = new ClassMethodFactory($req);
        $schemaProperties = (new SchemaPropertyCollector())->collectPropertiesFromSchema($schema, $req);
        $classMethods = $factory->generateMethods($schemaProperties, [], false);

        $names = array_map(static fn ($m) => $m->getName(), $classMethods);
        self::assertNotContains('__construct', $names);
    }

    // TODO: Add test case to verify that `__clone` is also not generated when not needed
}
