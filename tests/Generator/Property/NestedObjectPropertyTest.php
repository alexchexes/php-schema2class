<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Writer\DebugWriter;
use Symfony\Component\Console\Output\NullOutput;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class NestedObjectPropertyTest extends TestCase
{

    private NestedObjectProperty $property;

    private GeneratorRequest $generatorRequest;

    protected function setUp(): void
    {
        $this->generatorRequest = new GeneratorRequest([], new ValidatedSpecificationFilesItem("BarNs", "Foo", ""), new SpecificationOptions());
        $this->property = new NestedObjectProperty('myPropertyName', ['allOf' => []], $this->generatorRequest);
    }

    public function testCanHandleSchema()
    {
        assertFalse(NestedObjectProperty::canHandleSchema(['type' => 'object']));
        assertTrue(NestedObjectProperty::canHandleSchema(['properties' => ['x' => ['type' => 'string']]]));
        assertFalse(NestedObjectProperty::canHandleSchema(['properties' => []]));
        assertFalse(NestedObjectProperty::canHandleSchema(['type' => 'foo']));
        assertFalse(NestedObjectProperty::canHandleSchema([]));
    }

    public function testIsComplex()
    {
        assertTrue($this->property->isComplex());
    }

    public function testConvertInputToType()
    {
        $underTest = new NestedObjectProperty('myPropertyName', ['allOf' => []], $this->generatorRequest);

        $result = $underTest->convertInputToType('variable');

        $expected = <<<'EOCODE'
$myPropertyName = FooMyPropertyName::buildFromInput($variable->{'myPropertyName'}, $validate);
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToArray()
    {
        $result = $this->property->convertTypeToArray('variable');

        $expected = <<<'EOCODE'
$variable['myPropertyName'] = ($this->myPropertyName)->toArray();
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToStdClass()
    {
        $result = $this->property->convertTypeToStdClass('variable');

        $expected = <<<'EOCODE'
$variable->{'myPropertyName'} = ($this->myPropertyName)->toStdClass();
EOCODE;

        assertSame($expected, $result);
    }

    public function testCloneProperty()
    {
        $expected = <<<'EOCODE'
$this->myPropertyName = clone $this->myPropertyName;
EOCODE;
        assertSame($expected, $this->property->cloneProperty());
    }

    public function testGetAnnotationAndHintWithSimpleArray()
    {
        $underTest = new NestedObjectProperty('myPropertyName',  ['allOf' => []], $this->generatorRequest);

        assertSame('FooMyPropertyName', $underTest->typeAnnotation());
        assertSame('\\BarNs\\FooMyPropertyName', $underTest->typeHint("7.2.0"));
        assertSame('\\BarNs\\FooMyPropertyName', $underTest->typeHint("5.6.0"));
    }

    public function testGenerateSubTypesWithSimpleArray()
    {
        $writer = new DebugWriter(new NullOutput());

        $this->property->generateSubTypes($writer, new NullOutput());

        $this->assertCount(1, $writer->getWrittenFiles());
    }

}
