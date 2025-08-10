<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

class TypeHintUtilTest extends TestCase
{
    public function testPropertyTypingRequiresPhp74()
    {
        self::assertNull(TypeHint::forPhpVer('string', '7.3', TypeHint::TYPE_KIND_PROP));
        self::assertSame('string', TypeHint::forPhpVer('string', '7.4', TypeHint::TYPE_KIND_PROP));
    }

    public function testNullableType()
    {
        assertThat(TypeHint::forPhpVer('int|null', '7.0', TypeHint::TYPE_KIND_ARG), equalTo(null));
        assertThat(TypeHint::forPhpVer('int|null', '7.1', TypeHint::TYPE_KIND_ARG), equalTo('?int'));
        self::assertNull(TypeHint::forPhpVer('int|null', '7.0', TypeHint::TYPE_KIND_ARG));
        self::assertNull(TypeHint::forPhpVer('string|null', '7.0', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('?int', TypeHint::forPhpVer('int|null', '7.1', TypeHint::TYPE_KIND_ARG));
        self::assertSame('?string', TypeHint::forPhpVer('string|null', '7.1', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('?string', TypeHint::forPhpVer('string|null', '7.4', TypeHint::TYPE_KIND_RETURN));
    }

    public function testUnionTypesRequirePhp8()
    {
        assertThat(TypeHint::forPhpVer(['int', 'string'], '7.4', TypeHint::TYPE_KIND_ARG), equalTo(null));
        assertThat(TypeHint::forPhpVer(['int', 'string'], '8.0', TypeHint::TYPE_KIND_ARG), equalTo('int|string'));
        self::assertNull(TypeHint::forPhpVer('int|string', '7.4', TypeHint::TYPE_KIND_ARG));
        self::assertNull(TypeHint::forPhpVer(['int', 'string'], '7.4', TypeHint::TYPE_KIND_ARG));
        self::assertSame('int|string', TypeHint::forPhpVer('int|string', '8.0', TypeHint::TYPE_KIND_ARG));
        self::assertSame('int|string', TypeHint::forPhpVer(['int', 'string'], '8.0', TypeHint::TYPE_KIND_ARG));
    }

    public function testUnionTypesAreSortedAndNullLast()
    {
        assertThat(TypeHint::forPhpVer(['int', 'MyClass'], '8.0', TypeHint::TYPE_KIND_ARG), equalTo('MyClass|int'));
        self::assertSame('int|string|null', TypeHint::forPhpVer('string|int|null', '8.0', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('int|string|null', TypeHint::forPhpVer(['string', 'null', 'int'], '8.1', TypeHint::TYPE_KIND_RETURN));
    }

    public function testMixedType()
    {
        assertThat(TypeHint::forPhpVer('mixed', '7.4', TypeHint::TYPE_KIND_ARG), equalTo(null));
        assertThat(TypeHint::forPhpVer('mixed|null', '8.1', TypeHint::TYPE_KIND_ARG), equalTo('mixed'));
        self::assertNull(TypeHint::forPhpVer('mixed', '7.4', TypeHint::TYPE_KIND_ARG));
        self::assertNull(TypeHint::forPhpVer('mixed', '7.4', TypeHint::TYPE_KIND_ARG));
        self::assertSame('mixed', TypeHint::forPhpVer('mixed', '8.0', TypeHint::TYPE_KIND_ARG));
        self::assertSame('mixed', TypeHint::forPhpVer('mixed|null', '8.0', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('mixed', TypeHint::forPhpVer('mixed|null', '8.0', TypeHint::TYPE_KIND_RETURN));
    }
    public function testMixedWithOtherTypesThrows()
    {
        $this->expectException(\InvalidArgumentException::class);
        TypeHint::forPhpVer('mixed|string', '8.0', TypeHint::TYPE_KIND_RETURN);
    }

    public function testLiteralTrueFalseHandling()
    {
        // need to revise this. PHP docs say:
        // 8.2.0	Support for the literal type true has been added.
        // 8.2.0	The types null and false can now be used standalone.
        // so in before 8.2 we could use false and null as part of union but not standalone, right?

        assertThat(TypeHint::forPhpVer('false', '8.1', TypeHint::TYPE_KIND_RETURN), equalTo('bool'));
        assertThat(TypeHint::forPhpVer('false', '8.2', TypeHint::TYPE_KIND_RETURN), equalTo('false'));
        self::assertSame('?bool', TypeHint::forPhpVer('false|null', '8.1', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('bool', TypeHint::forPhpVer('false', '8.1', TypeHint::TYPE_KIND_ARG));
        self::assertSame('bool', TypeHint::forPhpVer('true', '8.1', TypeHint::TYPE_KIND_ARG));
        self::assertSame('bool', TypeHint::forPhpVer(['true', 'false'], '8.1', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('bool', TypeHint::forPhpVer(['true', 'false'], '8.2', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('bool|int', TypeHint::forPhpVer('true|int', '8.1', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('bool|string', TypeHint::forPhpVer('false|string', '8.1', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('bool|string', TypeHint::forPhpVer(['false', 'string'], '8.1', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('null', TypeHint::forPhpVer('null', '8.2', TypeHint::TYPE_KIND_RETURN));
    }

    public function testNeverType()
    {
        self::assertSame('never', TypeHint::forPhpVer('never', '8.1', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('void', TypeHint::forPhpVer('never', '8.0', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('void', TypeHint::forPhpVer('never', '7.4', TypeHint::TYPE_KIND_RETURN));
    }

    public function testVoidType()
    {
        self::assertSame('void', TypeHint::forPhpVer('void', '7.1', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('void', TypeHint::forPhpVer('void', '7.1', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('void', TypeHint::forPhpVer('void', '7.4', TypeHint::TYPE_KIND_RETURN));
        self::assertNull(TypeHint::forPhpVer('void', '7.0', TypeHint::TYPE_KIND_RETURN));
    }

    public function testVoidUsedNotForReturnTypeThrows($arg): void
    {
        $this->expectException(InvalidArgumentException::class);
        TypeHint::forPhpVer('void', '7.4', TypeHint::TYPE_KIND_ARG);        
    }

    public function testObjectType()
    {
        self::assertNull(TypeHint::forPhpVer('object', '7.1', TypeHint::TYPE_KIND_ARG));
        self::assertSame('object', TypeHint::forPhpVer('object', '7.2', TypeHint::TYPE_KIND_ARG));
    }

    public function testStandaloneNull()
    {
        self::assertSame('null', TypeHint::forPhpVer('null', '8.2', TypeHint::TYPE_KIND_RETURN));
        assertThat(TypeHint::forPhpVer('null', '8.1', TypeHint::TYPE_KIND_RETURN), equalTo(null));
        assertThat(TypeHint::forPhpVer('null', '8.2', TypeHint::TYPE_KIND_RETURN), equalTo('null'));
    }

    public function testIntersectionAndDnf()
    {
        // shouldn't all dnf be like `T|(X&Y)` (as in php docs)?
        
        assertThat(TypeHint::forPhpVer('A&B', '8.0', TypeHint::TYPE_KIND_ARG), equalTo(null));
        assertThat(TypeHint::forPhpVer('A&B', '8.1', TypeHint::TYPE_KIND_ARG), equalTo('A&B'));
        assertThat(TypeHint::forPhpVer('A&B|C', '8.1', TypeHint::TYPE_KIND_ARG), equalTo(null));
        assertThat(TypeHint::forPhpVer('A&B|C', '8.2', TypeHint::TYPE_KIND_ARG), equalTo('A&B|C'));

        self::assertNull(TypeHint::forPhpVer('A&B|null', '8.1', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('A&B', TypeHint::forPhpVer('A&B', '8.1', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('A&B|null', TypeHint::forPhpVer('A&B|null', '8.2', TypeHint::TYPE_KIND_RETURN));

        assertThat(TypeHint::forPhpVer(['T', 'X&Y'], '8.2', TypeHint::TYPE_KIND_ARG), equalTo('T|(X&Y)'));
    }


    public function testPropertyTypingRequires7_4()
    {
        assertThat(TypeHint::forPhpVer('int', '7.3', TypeHint::TYPE_KIND_PROP), equalTo(null));
        assertThat(TypeHint::forPhpVer('int', '7.4', TypeHint::TYPE_KIND_PROP), equalTo('int'));
    }

    public function testKindsSupportedOnlyIn8_3()
    {
        // 8.3.0	Support for class, interface, trait, and enum constant typing has been added
        assertThat(TypeHint::forPhpVer('int', '8.2', TypeHint::TYPE_KIND_CONST), equalTo(null));
        assertThat(TypeHint::forPhpVer('int', '8.3', TypeHint::TYPE_KIND_CONST), equalTo('int'));

        assertThat(TypeHint::forPhpVer('int', '8.2', TypeHint::TYPE_KIND_CLASS), equalTo(null));
        assertThat(TypeHint::forPhpVer('int', '8.3', TypeHint::TYPE_KIND_CLASS), equalTo('int'));
        // ...
    }

    public function testPhp5_6SupportedTypes()
    {
        // Any class/interface name
        // 'array'
        // 'callable'
        // no nullable types at all
        // no return types at all

        // we must make sure all are supported in 5.6,
    }
    
    // WE NEED TO FIGURE OUT what do we do with the face that nullables before 7.1 were achieved with syntax like
    // myfunc(MyClass $obj = null) - i.e. null type should be just removed and we add "= null" separately.
    // Should our `TypeHint::forPhpVer` remove null type?
    // Or should we introduce a parameter to control this, and throw if that parameter not added when php ver is <7.1,
    // type is supported but nullable?
    // Or should we add separate function that will handle it?

    public function testSyntaxNotSupportedInAnyVersionThrows()
    {
        // If someone accidentally passes php-stan type, array shape, or anything else that we don't handle and cannot normalize.
        // Later we will introduce Annotation-to-hint normalizer and the TypeHint util will be part of it, but here we only test TypeHint which must throw when nonsense is passed.
    }

    public function testRepeatedTypesCollapsed()
    {
        // ... bool|bool should be 'bool', null|NULL -> 'null', etc
    }

    public function testMoreBroadTypesCollapsed()
    {
        // ... bool|false should become bool, etc.
        // for php7, 'bool|false|null' should be '?bool'
    }
}
