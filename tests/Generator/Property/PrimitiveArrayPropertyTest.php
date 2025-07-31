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
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\isNull;

class PrimitiveArrayPropertyTest extends TestCase
{

    private PrimitiveArrayProperty $property;

    private GeneratorRequest $generatorRequest;

    protected function setUp(): void
    {
        $this->generatorRequest = new GeneratorRequest([], new ValidatedSpecificationFilesItem("", "Foo", ""), new SpecificationOptions());
        $this->property = new PrimitiveArrayProperty('myPropertyName', ['type' => 'integer'], $this->generatorRequest);
    }

    public function testCanHandleSchema()
    {
        assertTrue(PrimitiveArrayProperty::canHandleSchema(['type' => 'array', 'items' => ['type' => 'string']]));
        assertFalse(PrimitiveArrayProperty::canHandleSchema(['type' => 'foo']));
    }

    public function testConvertInputToTypeWithSimpleArray()
    {
        $underTest = new PrimitiveArrayProperty('myPropertyName', ['type' => 'array'], $this->generatorRequest);

        assertFalse($underTest->isComplex());

        $result = $underTest->convertInputToType('variable', 'providedOptionals');

        $expected = <<<'EOCODE'
$myPropertyName = $variable->{'myPropertyName'};
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToArrayWithSimpleArray()
    {
        $underTest = new PrimitiveArrayProperty('myPropertyName', ['type' => 'array'], $this->generatorRequest);

        $result = $underTest->convertTypeToArray('variable');

        $expected = <<<'EOCODE'
$variable['myPropertyName'] = $this->myPropertyName;
EOCODE;

        assertSame($expected, $result);
    }

    public function testClonePropertyWithSimpleArray()
    {
        $underTest = new PrimitiveArrayProperty('myPropertyName', ['type' => 'array'], $this->generatorRequest);

        assertThat($underTest->cloneProperty(), isNull());
    }

    public function testGetAnnotationAndHintWithSimpleArray()
    {
        assertSame('array', $this->property->typeAnnotation());
        assertSame('array', $this->property->typeHint("7.2.0"));
        assertSame('array', $this->property->typeHint("5.6.0"));
    }

    public function testGetAnnotationWithSimpleItemsArray()
    {
        $underTest = new PrimitiveArrayProperty('myPropertyName', ['type' => 'array', 'items' => ['type' => 'string']], $this->generatorRequest);

        assertSame('string[]', $underTest->typeAnnotation());
        assertSame('array', $underTest->typeHint("7.2.0"));
        assertSame('array', $underTest->typeHint("5.6.0"));

    }

    public function testGenerateSubTypesWithSimpleArray()
    {
        $writer = new DebugWriter(new NullOutput());

        $this->property->generateSubTypes($writer, new NullOutput());

        $this->assertCount(0, $writer->getWrittenFiles());
    }

}
