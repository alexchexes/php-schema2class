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
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\assertFalse;
use Helmich\Schema2Class\Generator\Definition\Definition;
use Helmich\Schema2Class\Generator\ReferenceLookup\DefinitionsReferenceLookup;

class UnionPropertyTest extends TestCase
{

    private UnionProperty $property;

    private GeneratorRequest $generatorRequest;

    protected function setUp(): void
    {
        $this->generatorRequest = new GeneratorRequest([], new ValidatedSpecificationFilesItem("BarNs", "Foo", ""), new SpecificationOptions());
        $this->property = new UnionProperty(
            'myPropertyName',
            ['anyOf' => [['properties' => ['subFoo1' => ['type' => 'string']]], ['properties' => ['subFoo2' => ['type' => 'string']]]]],
            $this->generatorRequest
        );
    }

    public function testCanHandleSchema()
    {
        assertTrue(UnionProperty::canHandleSchema(['anyOf' => []]));
        assertTrue(UnionProperty::canHandleSchema(['oneOf' => []]));
    }

    public function testNeedsValidation()
    {
        assertFalse($this->property->needsValidation());
    }

    public function testConvertInputToType()
    {
        $underTest = new UnionProperty('myPropertyName', ['anyOf' => [['properties' => ['subFoo1' => ['type' => 'string']]], ['properties' => ['subFoo2' => ['type' => 'string']]]]], $this->generatorRequest);

        $result = $underTest->convertInputToType();

        $expected = <<<'EOCODE'
$myPropertyName = match (true) {
    FooMyPropertyNameAlternative1::validateInput($input->{'myPropertyName'}, true) => FooMyPropertyNameAlternative1::fromInput($input->{'myPropertyName'}, $validate),
    FooMyPropertyNameAlternative2::validateInput($input->{'myPropertyName'}, true) => FooMyPropertyNameAlternative2::fromInput($input->{'myPropertyName'}, $validate),
    default => throw new \InvalidArgumentException("could not build property 'myPropertyName' from JSON"),
};
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToArray()
    {
        $underTest = new UnionProperty('myPropertyName', ['anyOf' => [['properties' => ['subFoo1' => ['type' => 'string']]], ['properties' => ['subFoo2' => ['type' => 'string']]]]], $this->generatorRequest);

        $result = $underTest->convertTypeToArray();

        $expected = <<<'EOCODE'
$output['myPropertyName'] = match (true) {
    $this->myPropertyName instanceof FooMyPropertyNameAlternative1,
    $this->myPropertyName instanceof FooMyPropertyNameAlternative2 => $this->myPropertyName->toArray(),
};
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToStdClass()
    {
        $underTest = new UnionProperty('myPropertyName', ['anyOf' => [['properties' => ['subFoo1' => ['type' => 'string']]], ['properties' => ['subFoo2' => ['type' => 'string']]]]], $this->generatorRequest);

        $result = $underTest->convertTypeToStdClass();

        $expected = <<<'EOCODE'
$output->{'myPropertyName'} = match (true) {
    $this->myPropertyName instanceof FooMyPropertyNameAlternative1,
    $this->myPropertyName instanceof FooMyPropertyNameAlternative2 => $this->myPropertyName->toStdClass(),
};
EOCODE;

        assertSame($expected, $result);
    }

    public function testCloneProperty()
    {
        $expected = <<<'EOCODE'
$this->myPropertyName = match (true) {
    $this->myPropertyName instanceof FooMyPropertyNameAlternative1,
    $this->myPropertyName instanceof FooMyPropertyNameAlternative2 => clone $this->myPropertyName,
};
EOCODE;
        assertSame($expected, $this->property->cloneAssignment());
    }

    public function testAllowsNullIfSubPropertyAllowsNull(): void
    {
        $defs = [
            '#/definitions/foo' => new Definition('Ns', '', 'Ns\\Foo', 'Foo', ['type' => ['boolean', 'null']]),
            '#/definitions/bar' => new Definition('Ns', '', 'Ns\\Bar', 'Bar', ['type' => 'string']),
        ];

        $lookup = new DefinitionsReferenceLookup($defs);
        $req    = $this->generatorRequest->withReferenceLookup($lookup);

        $prop = new UnionProperty('myPropertyName', [
            'anyOf' => [
                ['$ref' => '#/definitions/foo'],
                ['$ref' => '#/definitions/bar'],
            ],
        ], $req);

        assertTrue($prop->allowsNull());
    }

    public static function dataForAnnotationAndHintWithSimpleArray(): array
    {
        $php8Ver = GeneratorRequest::DEFAULT_PHP8_VERSION;
        return [
            "php {$php8Ver}" =>
                [$php8Ver, '\BarNs\FooMyPropertyNameAlternative1|\BarNs\FooMyPropertyNameAlternative2'],
            'php 7.2' => ['7.2.0', 'object'],
            'php 7.1' => ['7.1.0', null],
        ];
    }

    #[DataProvider('dataForAnnotationAndHintWithSimpleArray')]
    public function testGetAnnotationAndHintWithSimpleArray(string $phpVersion, mixed $expected)
    {
        $request = $this->generatorRequest->withPHPVersion($phpVersion);
        $underTest = new UnionProperty(
            'myPropertyName',
            ['anyOf' => [['properties' => ['subFoo1' => ['type' => 'string']]], ['properties' => ['subFoo2' => ['type' => 'string']]]]],
            $request
        );

        assertSame('\BarNs\FooMyPropertyNameAlternative1|\BarNs\FooMyPropertyNameAlternative2', $underTest->typeAnnotation());
        assertSame($expected, $underTest->typeHint("n/a"));
    }

    public static function provideTestSchema(): array
    {
        return [
            'oneOf inside' => [
                ['oneOf' => [
                    ['required' => ['foo'], 'properties' => ['foo' => ['type' => 'int']]],
                    ['required' => ['bar', 'foo'], 'properties' => ['bar' => ['type' => 'date-time'], 'foo' => ['type' => 'string']]]
                ]],
            ],
            'anyOf inside' => [
                ['anyOf' => [
                    ['required' => ['foo'], 'properties' => ['foo' => ['type' => 'int']]],
                    ['required' => ['bar'], 'properties' => ['bar' => ['type' => 'date-time']]]
                ]],
            ],
        ];
    }

    #[DataProvider('provideTestSchema')]
    public function testGenerateSubTypes($schema)
    {
        if (isset($schema['oneOf'])) {
            $subschemas = $schema['oneOf'];
        } elseif (isset($schema['anyOf'])) {
            $subschemas = $schema['anyOf'];
        }

        $underTest = new UnionProperty('myPropertyName', $schema, $this->generatorRequest);

        $writer = new DebugWriter(new NullOutput());

        $underTest->generateSubTypes($writer, new NullOutput());

        $this->assertCount(count($subschemas), $writer->getWrittenFiles());
    }

}
