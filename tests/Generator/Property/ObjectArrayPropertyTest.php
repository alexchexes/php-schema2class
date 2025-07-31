<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\GeneratorRequest;
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

    private ObjectArrayProperty $property;

    private GeneratorRequest $generatorRequest;

    protected function setUp(): void
    {
        $this->generatorRequest = new GeneratorRequest([], new ValidatedSpecificationFilesItem("", "Foo", ""), new SpecificationOptions());
        $this->property = new ObjectArrayProperty('myPropertyName', ['type' => 'array', 'items' => ['type' => 'object']], $this->generatorRequest);
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

        assertTrue($underTest->isComplex());

        $result = $underTest->convertInputToType('variable', 'providedOptionals');

        $expected = <<<'EOCODE'
$myPropertyName = array_map(fn (array|object $i): FooMyPropertyNameItem => FooMyPropertyNameItem::buildFromInput($i, $validate), $variable->{'myPropertyName'});
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToArrayWithComplexArray()
    {
        $underTest = new ObjectArrayProperty('myPropertyName', ['type' => 'array', 'items' => ['properties' => ['foo' => ['type' => 'string']]]], $this->generatorRequest);

        $result = $underTest->convertTypeToArray('variable');

        $expected = <<<'EOCODE'
$variable['myPropertyName'] = array_map(fn (FooMyPropertyNameItem $i) => $i->toArray(), $this->myPropertyName);
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToStdClassWithComplexArray()
    {
        $underTest = new ObjectArrayProperty('myPropertyName', ['type' => 'array', 'items' => ['properties' => ['foo' => ['type' => 'string']]]], $this->generatorRequest);

        $result = $underTest->convertTypeToStdClass('variable');

        $expected = <<<'EOCODE'
$variable->{'myPropertyName'} = array_map(fn (FooMyPropertyNameItem $i) => $i->toStdClass(), $this->myPropertyName);
EOCODE;

        assertSame($expected, $result);
    }

    public function testClonePropertyWithComplexArray()
    {
        $underTest = new ObjectArrayProperty('myPropertyName', ['type' => 'array', 'items' => ['properties' => ['foo' => ['type' => 'string']]]], $this->generatorRequest);

        $expected = <<<'EOCODE'
$this->myPropertyName = array_map(fn (FooMyPropertyNameItem $i) => clone $i, $this->myPropertyName);
EOCODE;
        assertSame($expected, $underTest->cloneProperty());
    }

    public function testGetAnnotationAndHintWithComplexArray()
    {
        $underTest = new ObjectArrayProperty('myPropertyName', ['type' => 'array', 'items' => ['properties' => ['foo' => ['type' => 'string']]]], $this->generatorRequest);

        assertSame('FooMyPropertyNameItem[]', $underTest->typeAnnotation());
        assertSame('array', $underTest->typeHint("7.2.0"));
        assertSame('array', $underTest->typeHint("5.6.0"));

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
