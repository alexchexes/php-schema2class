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

class IntegerPropertyTest extends TestCase
{

    private IntegerProperty $property;

    private GeneratorRequest $generatorRequest;

    protected function setUp(): void
    {
        $this->generatorRequest = new GeneratorRequest(
            [],
            new ValidatedSpecificationFilesItem("", "Foo", ""),
            new SpecificationOptions(),
        );
        $this->property = new IntegerProperty('myPropertyName', ['type' => 'integer'], $this->generatorRequest);
    }

    public function testCanHandleSchema()
    {
        assertTrue(IntegerProperty::canHandleSchema(['type' => 'int']));
        assertTrue(IntegerProperty::canHandleSchema(['type' => 'integer']));
        assertTrue(IntegerProperty::canHandleSchema(['type' => 'number', 'format' => 'int']));
        assertTrue(IntegerProperty::canHandleSchema(['type' => 'number', 'format' => 'integer']));

        assertFalse(IntegerProperty::canHandleSchema([]));
        assertFalse(IntegerProperty::canHandleSchema(['type' => 'foo']));
        assertFalse(IntegerProperty::canHandleSchema(['type' => 'number', 'format' => 'foo']));
    }

    public function testIsComplex()
    {
        assertFalse($this->property->isComplex());
    }

    public function testConvertInputToType()
    {
        $result = $this->property->convertInputToType('variable', 'providedOptionals');

        $expected = <<<'EOCODE'
$myPropertyName = (int)$variable->{'myPropertyName'};
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToArray()
    {
        $result = $this->property->convertTypeToArray();

        $expected = <<<'EOCODE'
$output['myPropertyName'] = $this->myPropertyName;
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToStdClass()
    {
        $result = $this->property->convertTypeToStdClass();

        $expected = <<<'EOCODE'
$output->{'myPropertyName'} = $this->myPropertyName;
EOCODE;

        assertSame($expected, $result);
    }

    public function testCloneProperty()
    {
        assertNull($this->property->cloneProperty());
    }

    public function testGetAnnotationAndHintWithSimpleArray()
    {
        assertSame('int', $this->property->typeAnnotation());
        assertSame('int', $this->property->typeHint("7.2.0"));
        assertSame(null, $this->property->typeHint("5.6.0"));
    }

    public function testGenerateSubTypesWithSimpleArray()
    {
        $writer = new DebugWriter(new NullOutput());

        $this->property->generateSubTypes($writer, new NullOutput());

        $this->assertCount(0, $writer->getWrittenFiles());
    }

}
