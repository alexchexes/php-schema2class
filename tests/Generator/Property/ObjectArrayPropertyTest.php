<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Type\Array\ObjectArrayProperty;
use Helmich\Schema2Class\Writer\DebugWriter;
use Symfony\Component\Console\Output\NullOutput;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class ObjectArrayPropertyTest extends TestCase
{
    private GeneratorRequest $generatorRequest;

    protected function setUp(): void
    {
        $this->generatorRequest = new GeneratorRequest([], new ValidatedSpecificationFilesItem("", "Foo", ""), new SpecificationOptions());
    }

    public static function allowedSchemas(): array {
        return [
            "object with props" => [['type' => 'array', 'items' => ['properties' => ['x' => ['type' => 'string']]]]]
        ];
    }

    public static function disallowedSchemas(): array {
        return [
            "non-array" => [['type' => 'string']],
            "primitive array" => [['type' => 'array', 'items' => ['type' => 'string']]],
            "plain object" => [['type' => 'array', 'items' => ['type' => 'object']]],
            "empty props" => [['type' => 'array', 'items' => ['properties' => []]]],
        ];
    }

    #[DataProvider('allowedSchemas')]
    public function testCanHandleSchema(array $schema)
    {
        assertTrue(ObjectArrayProperty::canHandleSchema($schema));
    }

    #[DataProvider('disallowedSchemas')]
    public function testCanNotHandleSchema(array $schema)
    {
        assertFalse(ObjectArrayProperty::canHandleSchema($schema));
    }

    public function testConvertInputToTypeWithComplexArray()
    {
        $underTest = new ObjectArrayProperty('myPropertyName', ['type' => 'array', 'items' => ['properties' => ['foo' => ['type' => 'string']]]], $this->generatorRequest);

        assertTrue($underTest->needsValidation());

        $result = $underTest->convertInputToType();

        $expected = <<<'EOCODE'
$myPropertyName = array_map(fn (array|object $i): FooMyPropertyNameItem => FooMyPropertyNameItem::fromInput($i, $validate), $input->{'myPropertyName'});
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToArrayWithComplexArray()
    {
        $underTest = new ObjectArrayProperty('myPropertyName', ['type' => 'array', 'items' => ['properties' => ['foo' => ['type' => 'string']]]], $this->generatorRequest);

        $result = $underTest->convertTypeToArray();

        $expected = <<<'EOCODE'
$output['myPropertyName'] = array_map(fn (FooMyPropertyNameItem $i) => $i->toArray(), $this->myPropertyName);
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToStdClassWithComplexArray()
    {
        $underTest = new ObjectArrayProperty('myPropertyName', ['type' => 'array', 'items' => ['properties' => ['foo' => ['type' => 'string']]]], $this->generatorRequest);

        $result = $underTest->convertTypeToStdClass();

        $expected = <<<'EOCODE'
$output->{'myPropertyName'} = array_map(fn (FooMyPropertyNameItem $i) => $i->toStdClass(), $this->myPropertyName);
EOCODE;

        assertSame($expected, $result);
    }

    public function testClonePropertyWithComplexArray()
    {
        $underTest = new ObjectArrayProperty('myPropertyName', ['type' => 'array', 'items' => ['properties' => ['foo' => ['type' => 'string']]]], $this->generatorRequest);

        $expected = <<<'EOCODE'
$this->myPropertyName = array_map(fn (FooMyPropertyNameItem $i) => clone $i, $this->myPropertyName);
EOCODE;
        assertSame($expected, $underTest->cloneAssignment());
    }

    public function testGetAnnotationAndHintWithComplexArray()
    {
        $schema = ['type' => 'array', 'items' => ['properties' => ['foo' => ['type' => 'string']]]];

        $underTest = new ObjectArrayProperty('myPropertyName', $schema, $this->generatorRequest);

        assertSame('FooMyPropertyNameItem[]', $underTest->typeAnnotation());
        
        assertSame('array', $underTest->typeHint());

        $underTest = new ObjectArrayProperty('myPropertyName', $schema, $this->generatorRequest->withPHPVersion('5.6.0'));
        assertSame('array', $underTest->typeHint());
    }

    public function testGenerateSubTypesWithComplexArray()
    {
        $arrayProperties = ['properties' => []];
        $underTest = new ObjectArrayProperty('myPropertyName', ['type' => 'array', 'items' => $arrayProperties], $this->generatorRequest);

        $writer = new DebugWriter(new NullOutput());

        $underTest->generateSubTypes($writer, new NullOutput());

        $this->assertCount(0, $writer->getWrittenFiles());
    }

}
