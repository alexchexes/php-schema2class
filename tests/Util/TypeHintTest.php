<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use PHPUnit\Framework\TestCase;

class TypeHintTest extends TestCase
{
    public function testNullableType()
    {
        $hint = TypeHint::typeHintForPhpVersion('int|null', '7.1', TypeHint::TYPE_KIND_ARG);
        $this->assertSame('?int', $hint);
    }

    public function testNullableTypeUnsupported()
    {
        $hint = TypeHint::typeHintForPhpVersion('int|null', '7.0', TypeHint::TYPE_KIND_ARG);
        $this->assertNull($hint);
    }

    public function testUnionSorting()
    {
        $hint = TypeHint::typeHintForPhpVersion('string|int|null', '8.0', TypeHint::TYPE_KIND_RETURN);
        $this->assertSame('int|string|null', $hint);
    }

    public function testMixedType()
    {
        $hint = TypeHint::typeHintForPhpVersion('mixed|null', '8.0', TypeHint::TYPE_KIND_RETURN);
        $this->assertSame('mixed', $hint);
    }

    public function testMixedWithOtherTypesThrows()
    {
        $this->expectException(\InvalidArgumentException::class);
        TypeHint::typeHintForPhpVersion('mixed|string', '8.0', TypeHint::TYPE_KIND_RETURN);
    }

    public function testTrueAndFalseHandling()
    {
        $hint = TypeHint::typeHintForPhpVersion('true|int', '8.1', TypeHint::TYPE_KIND_RETURN);
        $this->assertSame('bool|int', $hint);

        $hint = TypeHint::typeHintForPhpVersion('false|null', '8.1', TypeHint::TYPE_KIND_RETURN);
        $this->assertSame('?bool', $hint);
    }

    public function testVoidAndNever()
    {
        $hint = TypeHint::typeHintForPhpVersion('void', '7.1', TypeHint::TYPE_KIND_RETURN);
        $this->assertSame('void', $hint);

        $hint = TypeHint::typeHintForPhpVersion('never', '8.1', TypeHint::TYPE_KIND_RETURN);
        $this->assertSame('never', $hint);
    }

    public function testIntersectionAndDnf()
    {
        $hint = TypeHint::typeHintForPhpVersion('A&B', '8.1', TypeHint::TYPE_KIND_RETURN);
        $this->assertSame('A&B', $hint);

        $hint = TypeHint::typeHintForPhpVersion('A&B|null', '8.1', TypeHint::TYPE_KIND_RETURN);
        $this->assertNull($hint);

        $hint = TypeHint::typeHintForPhpVersion('A&B|null', '8.2', TypeHint::TYPE_KIND_RETURN);
        $this->assertSame('A&B|null', $hint);
    }

    public function testStandaloneNullAndFalse()
    {
        $hint = TypeHint::typeHintForPhpVersion('null', '8.2', TypeHint::TYPE_KIND_RETURN);
        $this->assertSame('null', $hint);

        $hint = TypeHint::typeHintForPhpVersion('false', '8.1', TypeHint::TYPE_KIND_ARG);
        $this->assertSame('bool', $hint);
    }
}
