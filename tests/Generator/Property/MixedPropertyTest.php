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

class MixedPropertyTest extends TestCase
{

    private MixedProperty $underTest;

    private GeneratorRequest $generatorRequest;

    protected function setUp(): void
    {
        $this->generatorRequest = new GeneratorRequest(
            [],
            new ValidatedSpecificationFilesItem("BarNs", "Foo", ""),
            new SpecificationOptions(),
        );
        $this->underTest = new MixedProperty('myPropertyName', [], $this->generatorRequest);
    }

    public function testCanHandleSchema()
    {
        assertTrue(MixedProperty::canHandleSchema([]));
    }

    public function testIsComplex()
    {
        assertFalse($this->underTest->isComplex());
    }

    public function testConvertInputToType()
    {
        $result = $this->underTest->convertInputToType('variable', 'providedOptionals');

        $expected = <<<'EOCODE'
$myPropertyName = $variable->{'myPropertyName'};
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToArray()
    {
        $result = $this->underTest->convertTypeToArray();

        $expected = <<<'EOCODE'
$output['myPropertyName'] = $this->myPropertyName;
EOCODE;

        assertSame($expected, $result);
    }

    public function testCloneProperty()
    {
        assertNull($this->underTest->cloneAssignment());
    }

    public function testGetAnnotationAndHintWithSimpleArray()
    {
        assertSame('mixed', $this->underTest->typeAnnotation());
        
        $underTest = new MixedProperty('myPropertyName', [], $this->generatorRequest->withPHPVersion('7.2.0'));
        assertSame(null, $underTest->typeHint());
        
        $underTest = new MixedProperty('myPropertyName', [], $this->generatorRequest->withPHPVersion('5.6.0'));
        assertSame(null, $underTest->typeHint());
    }

    public function testGenerateSubTypesWithSimpleArray()
    {
        $writer = new DebugWriter(new NullOutput());

        $this->underTest->generateSubTypes($writer, new NullOutput());

        $this->assertCount(0, $writer->getWrittenFiles());
    }


}
