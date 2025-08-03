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

class NumberPropertyTest extends TestCase
{

    private NumberProperty $property;

    private GeneratorRequest $generatorRequest;

    protected function setUp(): void
    {
        $this->generatorRequest = new GeneratorRequest(
            [],
            new ValidatedSpecificationFilesItem("", "Foo", ""),
            new SpecificationOptions(),
        );
        $this->property = new NumberProperty('myPropertyName', ['type' => 'Number'], $this->generatorRequest);
    }

    public function testCanHandleSchema()
    {
        assertTrue($this->property::canHandleSchema(['type' => 'number']));

        assertFalse($this->property::canHandleSchema([]));
		assertFalse($this->property::canHandleSchema(['type' => 'integer']));
		assertFalse($this->property::canHandleSchema(['type' => 'float']));
        assertFalse($this->property::canHandleSchema(['type' => 'foo']));
    }

    public function testIsComplex()
    {
        assertFalse($this->property->isComplex());
    }

    public function testConvertInputToType()
    {
        $result = $this->property->convertInputToType('variable', 'providedOptionals');

        $expected = <<<'EOCODE'
$myPropertyName = (str_contains((string)$variable->{'myPropertyName'}, '.') ? (float)$variable->{'myPropertyName'} : (int)$variable->{'myPropertyName'});
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

    public function testCloneProperty()
    {
        assertNull($this->property->cloneAssignment());
    }

    public function testGetAnnotationAndHintWithSimpleArray()
    {
        assertSame('int|float', $this->property->typeAnnotation());
        
        $property = new NumberProperty(
            'myPropertyName',
            ['type' => 'Number'],
            $this->generatorRequest->withPHPVersion('7.4')
        );
        assertSame(null, $property->typeHint());
    }

    public function testGenerateSubTypesWithSimpleArray()
    {
        $writer = new DebugWriter(new NullOutput());

        $this->property->generateSubTypes($writer, new NullOutput());

        $this->assertCount(0, $writer->getWrittenFiles());
    }

}
