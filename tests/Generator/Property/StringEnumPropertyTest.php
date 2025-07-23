<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use function PHPUnit\Framework\assertSame;

class StringEnumPropertyTest extends TestCase
{
    use ProphecyTrait;

    private StringEnumProperty $property;
    private GeneratorRequest $generatorRequest;

    protected function setUp(): void
    {
        $options = (new SpecificationOptions())->withNoEnums(true);
        $this->generatorRequest = new GeneratorRequest([], new ValidatedSpecificationFilesItem('Ns', 'Foo', ''), $options);
        $this->property = new StringEnumProperty('color', ['type' => 'string', 'enum' => ['foo', "bar's"]], $this->generatorRequest);
    }

    public function testTypeAnnotationEscapesQuotes(): void
    {
        assertSame("'foo'|'bar\\'s'", $this->property->typeAnnotation());
    }
}
