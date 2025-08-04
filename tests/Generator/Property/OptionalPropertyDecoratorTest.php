<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
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

    private GeneratorRequest $request;

    protected function setUp(): void
    {
        $this->innerProperty = $this->prophesize(PropertyInterface::class);
        $this->innerProperty->schema()->willReturn([]);
        $this->innerProperty->allowsNull()->willReturn(false);
        $this->innerProperty->formatValue(Argument::any())->willReturn(new PropertyValueGenerator(null));
        $this->innerProperty->varName()->willReturn('myPropertyName');
        $this->innerProperty->keyStr()->willReturn('\'myPropertyName\'');
        
        $this->request = new GeneratorRequest(
                [],
                new ValidatedSpecificationFilesItem('', null, ''),
                new SpecificationOptions,
        );

        $this->decorator = new OptionalPropertyDecorator(
            'myPropertyName',
            $this->innerProperty->reveal(),
            $this->request,
        );
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
        $this->innerProperty->varName()->shouldBeCalled()->willReturn('myPropertyName');
        $this->innerProperty
            ->inputMappingExpr('$input->{\'myPropertyName\'}', true)
            ->shouldBeCalled()
            ->willReturn('INNER_EXPR');

        $result = $this->decorator->convertInputToType();

        $expected = '$myPropertyName = isset($input->{\'myPropertyName\'}) ? INNER_EXPR : null;';

        assertSame($expected, $result);
    }

    public function testConvertInputToTypeWithNullableDefault()
    {
        $prophecy = $this->prophesize(PropertyInterface::class);
        $prophecy->schema()->willReturn(['default' => false]);
        $prophecy->allowsNull()->willReturn(true);
        $prophecy->varName()->willReturn('myPropertyName');
        $prophecy
            ->inputMappingExpr('$input->{\'myPropertyName\'}', true)
            ->willReturn('INNER_EXPR');
        $prophecy->formatValue(false)->willReturn(new PropertyValueGenerator(false));
        $prophecy->keyStr()->willReturn('\'myPropertyName\'');

        $decorator = new OptionalPropertyDecorator(
            'myPropertyName',
            $prophecy->reveal(),
            new GeneratorRequest(
                [],
                new ValidatedSpecificationFilesItem('', null, ''),
                new SpecificationOptions()
            )
        );

        $result = $decorator->convertInputToType();

        $expected = '$myPropertyName = property_exists($input, \'myPropertyName\') ? INNER_EXPR : null;';
        assertSame($expected, $result);
    }

    public function testConvertTypeToArray()
    {
        $this->innerProperty->propName()->shouldBeCalled()->willReturn('myPropertyName');
        $this->innerProperty->allowsNull()->shouldBeCalled()->willReturn(false);
        $this->innerProperty->convertTypeToArray()->shouldBeCalled()->willReturn('echo "InnerCode";');

        $result = $this->decorator->convertTypeToArray();

        $expected = <<<'EOCODE'
if (isset($this->myPropertyName)) {
    echo "InnerCode";
}
EOCODE;

        assertSame($expected, $result);
    }

    public function testConvertTypeToStdClass()
    {
        $this->innerProperty->propName()->shouldBeCalled()->willReturn('myPropertyName');
        $this->innerProperty->allowsNull()->shouldBeCalled()->willReturn(false);
        $this->innerProperty->convertTypeToStdClass()->shouldBeCalled()->willReturn('echo "InnerCode";');

        $result = $this->decorator->convertTypeToStdClass();

        $expected = <<<'EOCODE'
if (isset($this->myPropertyName)) {
    echo "InnerCode";
}
EOCODE;

        assertSame($expected, $result);
    }

    public function testClonePropertyWithoutInnerCode()
    {
        $this->innerProperty->propName()->shouldBeCalled()->willReturn('innerPropertyName');
        $this->innerProperty->cloneAssignment()->shouldBeCalled()->willReturn(null);

        assertNull($this->decorator->cloneAssignment());
    }

    public function testClonePropertyWithInnerCode()
    {
        $this->innerProperty->propName()->shouldBeCalled()->willReturn('innerPropertyName');
        $this->innerProperty->cloneAssignment()->shouldBeCalled()->willReturn('echo "InnerCode";');
        $expected = <<<'EOCODE'
if (isset($this->innerPropertyName)) {
    echo "InnerCode";
}
EOCODE;
        assertSame($expected, $this->decorator->cloneAssignment());
    }

    public function testGetAnnotationAndHintWithSimpleArray()
    {
        $this->innerProperty->typeAnnotation()->shouldBeCalled()->willReturn('Foo');
        assertSame('Foo|null', $this->decorator->typeAnnotation());

        $decorator = new OptionalPropertyDecorator(
            'myPropertyName',
            $this->innerProperty->reveal(),
            $this->request->withPHPVersion('7.2.0'),
        );
        $this->innerProperty->typeHint()->shouldBeCalled()->willReturn('Foo');
        assertSame('?Foo', $decorator->typeHint());

        $decorator = new OptionalPropertyDecorator(
            'myPropertyName',
            $this->innerProperty->reveal(),
            $this->request->withPHPVersion('5.6.0'),
        );
        $this->innerProperty->typeHint()->shouldBeCalled()->willReturn('Foo');
        assertSame('Foo', $decorator->typeHint());

        $decorator = new OptionalPropertyDecorator(
            'myPropertyName',
            $this->innerProperty->reveal(),
            $this->request->withPHPVersion('7.2.0'),
        );
        $this->innerProperty->typeHint()->shouldBeCalled()->willReturn(null);
        assertSame(null, $decorator->typeHint());

    }

}
