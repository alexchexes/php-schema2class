<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use PHPUnit\Framework\TestCase;

class MethodFactoryTest extends TestCase
{
    public function testGenerateConstructorReturnsNullWithoutRequiredProps(): void
    {
        $req = new GeneratorRequest([], new ValidatedSpecificationFilesItem('Ns','Foo',''), new SpecificationOptions());
        $factory = new MethodFactory($req);
        $collection = new PropertyCollection();
        $this->assertNull($factory->generateConstructor($collection));
    }
}
