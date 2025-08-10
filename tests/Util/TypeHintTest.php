<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Tests\Util;

use Helmich\Schema2Class\Util\TypeHint;
use PHPUnit\Framework\TestCase;

final class TypeHintTest extends TestCase
{
    public function testNullableTypeBeforePhp80(): void
    {
        self::assertSame('?string', TypeHint::hintForPhpVersion('string|null', '7.4.0', TypeHint::TYPE_KIND_RETURN));
    }

    public function testUnionRequiresPhp80(): void
    {
        self::assertNull(TypeHint::hintForPhpVersion('int|string', '7.4.0', TypeHint::TYPE_KIND_ARG));
        self::assertSame('int|string', TypeHint::hintForPhpVersion('int|string', '8.0.0', TypeHint::TYPE_KIND_ARG));
    }

    public function testMixedHandling(): void
    {
        self::assertNull(TypeHint::hintForPhpVersion('mixed', '7.4.0', TypeHint::TYPE_KIND_ARG));
        self::assertSame('mixed', TypeHint::hintForPhpVersion('mixed|null', '8.0.0', TypeHint::TYPE_KIND_RETURN));
    }

    public function testVoidReturnOnly(): void
    {
        self::assertSame('void', TypeHint::hintForPhpVersion('void', '7.4.0', TypeHint::TYPE_KIND_RETURN));
        $this->expectException(\InvalidArgumentException::class);
        TypeHint::hintForPhpVersion('void', '7.4.0', TypeHint::TYPE_KIND_ARG);
    }

    public function testLiteralTypes(): void
    {
        self::assertSame('bool', TypeHint::hintForPhpVersion('true', '8.1.0', TypeHint::TYPE_KIND_ARG));
        self::assertSame('bool|string', TypeHint::hintForPhpVersion('false|string', '8.1.0', TypeHint::TYPE_KIND_RETURN));
        self::assertSame('null', TypeHint::hintForPhpVersion('null', '8.2.0', TypeHint::TYPE_KIND_RETURN));
    }

    public function testNeverType(): void
    {
        self::assertSame('never', TypeHint::hintForPhpVersion('never', '8.1.0', TypeHint::TYPE_KIND_RETURN));
    }
}
