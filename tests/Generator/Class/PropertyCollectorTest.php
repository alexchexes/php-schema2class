<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use PHPUnit\Framework\TestCase;

class PropertyCollectorTest extends TestCase
{
    public function testCollectDefaults(): void
    {
        $schema = [
            'properties' => [
                'a' => ['type' => 'string', 'default' => 'x'],
            ],
        ];

        $req = new GeneratorRequest(
            $schema,
            new ValidatedSpecificationFilesItem('Ns', 'Foo', ''),
            new SpecificationOptions(),
        );

        $collector = new PropertyCollector();
        $defaults = $collector->collectDefaults($schema, $req);

        $this->assertSame(['a' => ['default' => 'x']], $defaults);
    }
}
