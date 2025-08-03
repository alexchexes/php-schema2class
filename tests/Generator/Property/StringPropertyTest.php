<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Writer\DebugWriter;
use Symfony\Component\Console\Output\NullOutput;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class StringPropertyTest extends TestCase
{

    private StringProperty $property;

    private GeneratorRequest $generatorRequest;

    protected function setUp(): void
    {
        $this->generatorRequest = new GeneratorRequest([], new ValidatedSpecificationFilesItem("BarNs", "Foo", ""), new SpecificationOptions());
        $this->property = new StringProperty('myString', ['type' => 'string'], $this->generatorRequest);
    }

    public function testCanHandleSchema()
    {
        $stringSchema = ['type' => 'string'];
        assertTrue(StringProperty::canHandleSchema($stringSchema));
        assertFalse(StringProperty::canHandleSchema([]));
    }

    public function testIsComplex()
    {
        assertFalse($this->property->isComplex());
    }

    public function testConvertInputToType()
    {
        $result = $this->property->convertInputToType('variable', 'providedOptionals');

        $expected = <<<'EOCODE'
$myString = $variable->{'myString'};
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToArray()
    {
        $result = $this->property->convertTypeToArray();

        $expected = <<<'EOCODE'
$output['myString'] = $this->myString;
EOCODE;

        assertSame($expected, $result);
    }

    public function testCloneProperty()
    {
        assertNull($this->property->cloneAssignment());
    }

    public function testGetAnnotationAndHint()
    {
        assertSame('string', $this->property->typeAnnotation());

        assertSame('string', $this->property->typeHint());

        $property = new StringProperty('myString', ['type' => 'string'], $this->generatorRequest->withPHPVersion('5.6.0'));
        assertSame(null, $property->typeHint());
    }

    public function testGenerateSubTypesWithSimpleArray()
    {
        $writer = new DebugWriter(new NullOutput());

        $this->property->generateSubTypes($writer, new NullOutput());

        $this->assertCount(0, $writer->getWrittenFiles());
    }

}
