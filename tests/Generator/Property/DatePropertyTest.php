<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Type\Primitive\DateProperty;
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

    private array $schema;

    protected function setUp(): void
    {
        $this->generatorRequest = new GeneratorRequest(
            [],
            new ValidatedSpecificationFilesItem("", "Foo", ""),
            new SpecificationOptions()
        );

        $this->schema = ['type' => 'string', 'format' => 'date-time'];

        $this->property = new DateProperty('myPropertyName', $this->schema, $this->generatorRequest);
    }

    public function testCanHandleSchema()
    {
        assertTrue(DateProperty::canHandleSchema(['type' => 'string', 'format' => 'date-time']));

        assertFalse(DateProperty::canHandleSchema(['type' => 'string']));
        assertFalse(DateProperty::canHandleSchema(['type' => 'string', 'format' => 'foo']));

    }

    public function testNeedsValidation()
    {
        assertFalse($this->property->needsValidation());
    }

    public function testConvertInputToType()
    {
        $result = $this->property->convertInputToType();

        $expected = <<<'EOCODE'
$myPropertyName = new \DateTime($input->{'myPropertyName'});
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToArray()
    {
        $result = $this->property->convertTypeToArray();

        $expected = <<<'EOCODE'
$output['myPropertyName'] = $this->myPropertyName->format(\DateTime::ATOM);
EOCODE;

        assertSame($expected, $result);
    }

    public function testCloneProperty()
    {
        $expected = <<<'EOCODE'
$this->myPropertyName = clone $this->myPropertyName;
EOCODE;
        assertSame($expected, $this->property->cloneAssignment());
    }

    public function testGetAnnotationAndHintWithSimpleArray()
    {
        assertSame('\\DateTime', $this->property->typeAnnotation());
        assertSame('\\DateTime', $this->property->typeHint());

        $property = new DateProperty('myPropertyName', $this->schema, $this->generatorRequest->withPHPVersion('5.6.0'));

        assertSame('\\DateTime', $property->typeHint());
    }

    public function testGenerateSubTypesWithSimpleArray()
    {
        $writer = new DebugWriter(new NullOutput());

        $this->property->generateSubTypes($writer, new NullOutput());

        $this->assertCount(0, $writer->getWrittenFiles());
    }

}
