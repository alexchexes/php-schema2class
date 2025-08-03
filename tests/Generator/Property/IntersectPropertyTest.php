<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Writer\DebugWriter;
use Symfony\Component\Console\Output\NullOutput;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class IntersectPropertyTest extends TestCase
{

    private IntersectProperty $property;

    private GeneratorRequest $generatorRequest;

    protected function setUp(): void
    {
        $this->generatorRequest = new GeneratorRequest(
            [],
            new ValidatedSpecificationFilesItem("BarNs", "Foo", ""),
            new SpecificationOptions(),
        );
        $this->generatorRequest->setCurrValidateArgAlias('validate');
        $this->generatorRequest->setCurrReqHasDefaults(false);
        $this->property = new IntersectProperty('myPropertyName', ['allOf' => []], $this->generatorRequest);
    }

    public function testCanHandleSchema()
    {
        assertTrue(IntersectProperty::canHandleSchema(['allOf' => []]));

        assertFalse(IntersectProperty::canHandleSchema([]));
    }

    public function testIsComplex()
    {
        assertTrue($this->property->isComplex());
    }

    public function testConvertInputToType()
    {
        $underTest = new IntersectProperty('myPropertyName', ['allOf' => []], $this->generatorRequest);

        $result = $underTest->convertInputToType('variable', 'providedOptionals');

        $expected = <<<'EOCODE'
$myPropertyName = FooMyPropertyName::fromInput($variable->{'myPropertyName'}, $validate);
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToArray()
    {
        $result = $this->property->convertTypeToArray();

        $expected = <<<'EOCODE'
$output['myPropertyName'] = ($this->myPropertyName)->toArray();
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToStdClass()
    {
        $result = $this->property->convertTypeToStdClass();

        $expected = <<<'EOCODE'
$output->{'myPropertyName'} = ($this->myPropertyName)->toStdClass();
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
        $underTest = new IntersectProperty('myPropertyName', ['allOf' => []], $this->generatorRequest);

        assertSame('FooMyPropertyName', $underTest->typeAnnotation());
        assertSame('\\BarNs\\FooMyPropertyName', $underTest->typeHint("7.2.0"));
        assertSame('\\BarNs\\FooMyPropertyName', $underTest->typeHint("5.6.0"));
    }


    public static function provideTestSchema()
    {
        return [
            'empty allOf' => [
                ['allOf' => []],
                ['required' => [], 'properties' => []]
            ],
            'required' => [
                ['allOf' => [['required' => ['foo']], ['required' => ['bar']]]],
                ['required' => ['foo', 'bar'], 'properties' => []]
            ],
            'properties' => [
                ['allOf' => [
                    ['properties' => ['foo' => ['type' => 'int']]],
                    ['properties' => ['bar' => ['type' => 'date-time']]]
                ]],
                ['required' => [], 'properties' => ['foo' => ['type' => 'int'], 'bar' => ['type' => 'date-time']]]
            ],
            'oneOf inside' => [
                ['allOf' => [
                    ['oneOf' => [
                        ['required' => ['foo'], 'properties' => ['foo' => ['type' => 'int']]],
                        ['required' => ['bar', 'foo'], 'properties' => ['bar' => ['type' => 'date-time'], 'foo' => ['type' => 'string']]]
                    ]]
                ]],
                ['required' => ['foo'], 'properties' => ['bar' => ['type' => 'date-time'], 'foo' => ['type' => 'string']]]
            ],
            'anyOf inside' => [
                ['allOf' => [
                    ['anyOf' => [
                        ['required' => ['foo'], 'properties' => ['foo' => ['type' => 'int']]],
                        ['required' => ['bar'], 'properties' => ['bar' => ['type' => 'date-time']]]
                    ]]
                ]],
                ['required' => [], 'properties' => ['bar' => ['type' => 'date-time'], 'foo' => ['type' => 'int']]]
            ],
        ];
    }

    #[DataProvider('provideTestSchema')]
    public function testGenerateSubTypes($schema, $subschema)
    {
        $underTest = new IntersectProperty('myPropertyName', $schema, $this->generatorRequest);

        $writer = new DebugWriter(new NullOutput());

        $underTest->generateSubTypes($writer, new NullOutput());

        $expectedFiles = count($subschema['properties']) > 0 ? 1 : 0;
        $this->assertCount($expectedFiles, $writer->getWrittenFiles());
    }
}
