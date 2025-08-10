<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

class TypeHintTest extends TestCase
{
    public function testPropertyTypesUnsupportedBefore74(): void
    {
        assertThat(TypeHint::typeHintForPhpVer('int', '7.3', TypeHint::TYPE_KIND_PROP), equalTo(null));
        assertThat(TypeHint::typeHintForPhpVer('int', '7.4', TypeHint::TYPE_KIND_PROP), equalTo('int'));
    }

    public function testNullableTypeRequiresPhp71(): void
    {
        assertThat(TypeHint::typeHintForPhpVer('int|null', '7.0', TypeHint::TYPE_KIND_ARG), equalTo(null));
        assertThat(TypeHint::typeHintForPhpVer('int|null', '7.1', TypeHint::TYPE_KIND_ARG), equalTo('?int'));
    }

    public function testUnionTypesRequirePhp80(): void
    {
        assertThat(TypeHint::typeHintForPhpVer(['int', 'string'], '7.4', TypeHint::TYPE_KIND_ARG), equalTo(null));
        assertThat(TypeHint::typeHintForPhpVer(['int', 'string'], '8.0', TypeHint::TYPE_KIND_ARG), equalTo('int|string'));
    }

    public function testMixedRequiresPhp80(): void
    {
        assertThat(TypeHint::typeHintForPhpVer('mixed', '7.4', TypeHint::TYPE_KIND_ARG), equalTo(null));
        assertThat(TypeHint::typeHintForPhpVer('mixed|null', '8.1', TypeHint::TYPE_KIND_ARG), equalTo('mixed'));
    }

    public function testFalseLiteralHandling(): void
    {
        assertThat(TypeHint::typeHintForPhpVer('false', '8.1', TypeHint::TYPE_KIND_RETURN), equalTo('bool'));
        assertThat(TypeHint::typeHintForPhpVer('false', '8.2', TypeHint::TYPE_KIND_RETURN), equalTo('false'));
    }

    public function testNullStandaloneRequiresPhp82(): void
    {
        assertThat(TypeHint::typeHintForPhpVer('null', '8.1', TypeHint::TYPE_KIND_RETURN), equalTo(null));
        assertThat(TypeHint::typeHintForPhpVer('null', '8.2', TypeHint::TYPE_KIND_RETURN), equalTo('null'));
    }

    public function testIntersectionAndDnfTypes(): void
    {
        assertThat(TypeHint::typeHintForPhpVer('A&B', '8.0', TypeHint::TYPE_KIND_ARG), equalTo(null));
        assertThat(TypeHint::typeHintForPhpVer('A&B', '8.1', TypeHint::TYPE_KIND_ARG), equalTo('A&B'));
        assertThat(TypeHint::typeHintForPhpVer('A&B|C', '8.1', TypeHint::TYPE_KIND_ARG), equalTo(null));
        assertThat(TypeHint::typeHintForPhpVer('A&B|C', '8.2', TypeHint::TYPE_KIND_ARG), equalTo('A&B|C'));
    }

    public function testConstantTypingRequiresPhp83(): void
    {
        assertThat(TypeHint::typeHintForPhpVer('int', '8.2', TypeHint::TYPE_KIND_CONST), equalTo(null));
        assertThat(TypeHint::typeHintForPhpVer('int', '8.3', TypeHint::TYPE_KIND_CONST), equalTo('int'));
    }

    public function testSortingOfTypes(): void
    {
        assertThat(TypeHint::typeHintForPhpVer(['MyClass', 'int'], '8.0', TypeHint::TYPE_KIND_ARG), equalTo('int|MyClass'));
    }
}
