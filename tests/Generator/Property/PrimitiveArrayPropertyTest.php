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

        assertFalse($underTest->needsValidation());

        $result = $underTest->convertInputToType();

        $expected = <<<'EOCODE'
$myPropertyName = $input->{'myPropertyName'};
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToArrayWithSimpleArray()
    {
        $underTest = new PrimitiveArrayProperty('myPropertyName', ['type' => 'array'], $this->generatorRequest);

        $result = $underTest->convertTypeToArray();

        $expected = <<<'EOCODE'
$output['myPropertyName'] = $this->myPropertyName;
EOCODE;

        assertSame($expected, $result);
    }

    public function testClonePropertyWithSimpleArray()
    {
        $underTest = new PrimitiveArrayProperty('myPropertyName', ['type' => 'array'], $this->generatorRequest);

        assertThat($underTest->cloneAssignment(), isNull());
    }

    public function testGetAnnotationAndHintWithSimpleArray()
    {
        assertSame('array', $this->property->typeAnnotation());
        assertSame('array', $this->property->typeHint());

        $property = new PrimitiveArrayProperty(
            'myPropertyName', ['type' => 'integer'], $this->generatorRequest->withPHPVersion('5.6.0')
        );
        assertSame('array', $property->typeHint());
    }

    public function testGetAnnotationWithSimpleItemsArray()
    {
        $underTest = new PrimitiveArrayProperty('myPropertyName', ['type' => 'array', 'items' => ['type' => 'string']], $this->generatorRequest);

        assertSame('string[]', $underTest->typeAnnotation());
        assertSame('array', $underTest->typeHint());

        $underTest = new PrimitiveArrayProperty(
            'myPropertyName', ['type' => 'integer'], $this->generatorRequest->withPHPVersion('5.6.0')
        );
        assertSame('array', $underTest->typeHint());
    }

    public function testGenerateSubTypesWithSimpleArray()
    {
        $writer = new DebugWriter(new NullOutput());

        $this->property->generateSubTypes($writer, new NullOutput());

        $this->assertCount(0, $writer->getWrittenFiles());
    }

}
