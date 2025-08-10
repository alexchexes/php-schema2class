<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TypeHintTest extends TestCase
{
    public function testPropertyTypingRequiresPhp74(): void
    {
        $this->assertNull(TypeHint::forPhpVersion('string', '7.3.0', TypeHint::TYPE_KIND_PROP));
        $this->assertSame('string', TypeHint::forPhpVersion('string', '7.4.0', TypeHint::TYPE_KIND_PROP));
    }

    public function testNullableTypesRequirePhp71(): void
    {
        $this->assertNull(TypeHint::forPhpVersion('string|null', '7.0.0', TypeHint::TYPE_KIND_RETURN));
        $this->assertSame('?string', TypeHint::forPhpVersion('string|null', '7.1.0', TypeHint::TYPE_KIND_RETURN));
    }

    public function testUnionTypesRequirePhp8(): void
    {
        $this->assertNull(TypeHint::forPhpVersion(['int', 'string'], '7.4.0', TypeHint::TYPE_KIND_ARG));
        $this->assertSame('int|string', TypeHint::forPhpVersion(['int', 'string'], '8.0.0', TypeHint::TYPE_KIND_ARG));
    }

    public function testUnionTypesAreSortedAndNullLast(): void
    {
        $this->assertSame(
            'int|string|null',
            TypeHint::forPhpVersion(['string', 'null', 'int'], '8.1.0', TypeHint::TYPE_KIND_RETURN)
        );
    }

    public function testMixedRequiresPhp80(): void
    {
        $this->assertNull(TypeHint::forPhpVersion('mixed', '7.4.0', TypeHint::TYPE_KIND_ARG));
        $this->assertSame('mixed', TypeHint::forPhpVersion('mixed', '8.0.0', TypeHint::TYPE_KIND_ARG));
    }

    public function testVoidCanOnlyBeUsedForReturnType(): void
    {
        $this->assertSame('void', TypeHint::forPhpVersion('void', '7.1.0', TypeHint::TYPE_KIND_RETURN));
        $this->assertNull(TypeHint::forPhpVersion('void', '7.0.0', TypeHint::TYPE_KIND_RETURN));

        $this->expectException(InvalidArgumentException::class);
        TypeHint::forPhpVersion('void', '7.4.0', TypeHint::TYPE_KIND_ARG);
    }

    public function testLiteralTrueFalseHandling(): void
    {
        $this->assertSame('false|true', TypeHint::forPhpVersion(['true', 'false'], '8.2.0', TypeHint::TYPE_KIND_RETURN));
        $this->assertSame('bool', TypeHint::forPhpVersion(['true', 'false'], '8.1.0', TypeHint::TYPE_KIND_RETURN));
        $this->assertSame('bool|string', TypeHint::forPhpVersion(['false', 'string'], '8.1.0', TypeHint::TYPE_KIND_RETURN));
    }

    public function testNeverType(): void
    {
        $this->assertSame('never', TypeHint::forPhpVersion('never', '8.1.0', TypeHint::TYPE_KIND_RETURN));
        $this->assertSame('void', TypeHint::forPhpVersion('never', '7.4.0', TypeHint::TYPE_KIND_RETURN));
    }

    public function testObjectTypeRequiresPhp72(): void
    {
        $this->assertNull(TypeHint::forPhpVersion('object', '7.1.0', TypeHint::TYPE_KIND_ARG));
        $this->assertSame('object', TypeHint::forPhpVersion('object', '7.2.0', TypeHint::TYPE_KIND_ARG));
    }
}
