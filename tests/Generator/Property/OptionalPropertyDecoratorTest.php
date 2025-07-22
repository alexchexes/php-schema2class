<?php
declare(strict_types = 1);

namespace Helmich\Schema2Class\Generator\Property;

use Helmich\Schema2Class\Generator\PropertyDecorator\OptionalPropertyDecorator;
use Laminas\Code\Generator\PropertyValueGenerator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;

class OptionalPropertyDecoratorTest extends TestCase
{
    use ProphecyTrait;

    private OptionalPropertyDecorator $decorator;

    private ObjectProphecy $innerProperty;

    protected function setUp(): void
    {
        $this->innerProperty = $this->prophesize(PropertyInterface::class);
        $this->innerProperty->schema()->willReturn([]);
        $this->innerProperty->allowsNull()->willReturn(false);
        $this->innerProperty->formatValue(Argument::any())->willReturn(new PropertyValueGenerator(null));
        $this->decorator     = new OptionalPropertyDecorator('myPropertyName', $this->innerProperty->reveal());
    }

    public function testCanHandleSchema()
    {
        assertFalse(OptionalPropertyDecorator::canHandleSchema([]));
    }

    public function testIsComplex()
    {
        $this->innerProperty->isComplex()->shouldBeCalled()->willReturn(false);
        assertFalse($this->decorator->isComplex());
    }

    public function testConvertInputToType()
    {
        $this->innerProperty->name()->shouldBeCalled()->willReturn('myPropertyName');
        $this->innerProperty
            ->generateInputMappingExpr('$variable[\'myPropertyName\']', true)
            ->shouldBeCalled()
            ->willReturn('INNER_EXPR');

        $result = $this->decorator->convertInputToType('variable');

        $expected = '$myPropertyName = isset($variable[\'myPropertyName\']) ? INNER_EXPR : null;';

        assertSame($expected, $result);
    }

    public function testConvertInputToTypeWithNullableDefault()
    {
        $prophecy = $this->prophesize(PropertyInterface::class);
        $prophecy->schema()->willReturn(['default' => false]);
        $prophecy->allowsNull()->willReturn(true);
        $prophecy->name()->willReturn('myPropertyName');
        $prophecy
            ->generateInputMappingExpr('$variable[\'myPropertyName\']', true)
            ->willReturn('INNER_EXPR');
        $prophecy->formatValue(false)->willReturn(new PropertyValueGenerator(false));

        $decorator = new OptionalPropertyDecorator('myPropertyName', $prophecy->reveal());

        $result = $decorator->convertInputToType('variable');

        $expected = '$myPropertyName = array_key_exists(\'myPropertyName\', $variable) ? INNER_EXPR : null;';
        assertSame($expected, $result);
    }

    public function testConvertTypeToArray()
    {
        $this->innerProperty->name()->shouldBeCalled()->willReturn('myPropertyName');
        $this->innerProperty->allowsNull()->shouldBeCalled()->willReturn(false);
        $this->innerProperty->convertTypeToArray('variable')->shouldBeCalled()->willReturn('echo "InnerCode";');

        $result = $this->decorator->convertTypeToArray('variable');

        $expected = <<<'EOCODE'
if (isset($this->myPropertyName)) {
    echo "InnerCode";
}
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToStdClass()
    {
        $this->innerProperty->name()->shouldBeCalled()->willReturn('myPropertyName');
        $this->innerProperty->allowsNull()->shouldBeCalled()->willReturn(false);
        $this->innerProperty->convertTypeToStdClass('variable')->shouldBeCalled()->willReturn('echo "InnerCode";');

        $result = $this->decorator->convertTypeToStdClass('variable');

        $expected = <<<'EOCODE'
if (isset($this->myPropertyName)) {
    echo "InnerCode";
}
EOCODE;

        assertSame($expected, $result);
    }

    public function testClonePropertyWithoutInnerCode()
    {
        $this->innerProperty->name()->shouldBeCalled()->willReturn('innerPropertyName');
        $this->innerProperty->cloneProperty()->shouldBeCalled()->willReturn(null);

        assertNull($this->decorator->cloneProperty());
    }

    public function testClonePropertyWithInnerCode()
    {
        $this->innerProperty->name()->shouldBeCalled()->willReturn('innerPropertyName');
        $this->innerProperty->cloneProperty()->shouldBeCalled()->willReturn('echo "InnerCode";');
        $expected = <<<'EOCODE'
if (isset($this->innerPropertyName)) {
    echo "InnerCode";
}
EOCODE;
        assertSame($expected, $this->decorator->cloneProperty());
    }

    public function testGetAnnotationAndHintWithSimpleArray()
    {
        $this->innerProperty->typeAnnotation()->shouldBeCalled()->willReturn('Foo');
        assertSame('Foo|null', $this->decorator->typeAnnotation());

        $this->innerProperty->typeHint("7.2.0")->shouldBeCalled()->willReturn('Foo');
        assertSame('?Foo', $this->decorator->typeHint("7.2.0"));

        $this->innerProperty->typeHint("5.6.0")->shouldBeCalled()->willReturn('Foo');
        assertSame('Foo', $this->decorator->typeHint("5.6.0"));

        $this->innerProperty->typeHint("7.2.0")->shouldBeCalled()->willReturn(null);
        assertSame(null, $this->decorator->typeHint("7.2.0"));

    }

}
