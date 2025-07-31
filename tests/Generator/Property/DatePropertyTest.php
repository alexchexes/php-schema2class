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
use function PHPUnit\Framework\assertTrue;

class DatePropertyTest extends TestCase
{

    private DateProperty $property;

    private GeneratorRequest $generatorRequest;

    protected function setUp(): void
    {
        $this->generatorRequest = new GeneratorRequest([], new ValidatedSpecificationFilesItem("", "Foo", ""), new SpecificationOptions());
        $this->property         = new DateProperty('myPropertyName', ['type' => 'string', 'format' => 'date-time'], $this->generatorRequest);
    }

    public function testCanHandleSchema()
    {
        assertTrue(DateProperty::canHandleSchema(['type' => 'string', 'format' => 'date-time']));

        assertFalse(DateProperty::canHandleSchema(['type' => 'string']));
        assertFalse(DateProperty::canHandleSchema(['type' => 'string', 'format' => 'foo']));

    }

    public function testIsComplex()
    {
        assertTrue($this->property->isComplex());
    }

    public function testConvertInputToType()
    {
        $result = $this->property->convertInputToType('variable');

        $expected = <<<'EOCODE'
$myPropertyName = new \DateTime($variable->{'myPropertyName'});
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToArray()
    {
        $result = $this->property->convertTypeToArray('variable');

        $expected = <<<'EOCODE'
$variable['myPropertyName'] = ($this->myPropertyName)->format(\DateTime::ATOM);
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
        assertSame('\\DateTime', $this->property->typeAnnotation());
        assertSame('\\DateTime', $this->property->typeHint("7.2.0"));
        assertSame('\\DateTime', $this->property->typeHint("5.6.0"));
    }

    public function testGenerateSubTypesWithSimpleArray()
    {
        $writer = new DebugWriter(new NullOutput());

        $this->property->generateSubTypes($writer, new NullOutput());

        $this->assertCount(0, $writer->getWrittenFiles());
    }

}
