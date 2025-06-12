<?php

namespace Helmich\Schema2Class\Util;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

class StringUtilsTest extends TestCase
{
    public function testCapitalizeWordCapitalizesWord()
    {
        $capitalized = StringUtils::capitalizeWord("foo");
        assertThat($capitalized, equalTo("Foo"));
    }

    public function testCapitalizeWordDoesNotModifyAlreadyCapitalizedWord()
    {
        $capitalized = StringUtils::capitalizeWord("Foo");
        assertThat($capitalized, equalTo("Foo"));
    }

    public function testPascalCaseMakesWordPascalCase()
    {
        $pascaled = StringUtils::pascalCase("foo");
        assertThat($pascaled, equalTo("Foo"));
    }

    public function testPascalCaseHandlesWordsWithDashes()
    {
        $pascaled = StringUtils::pascalCase("content-disposition");
        assertThat($pascaled, equalTo("ContentDisposition"));
    }

    public function testCamelCaseCamelCases()
    {
        $camelCased = StringUtils::camelCase("content-disposition");
        assertThat($camelCased, equalTo("contentDisposition"));
    }

    public function testCamelCaseTransliteratesNonAsciiCharacters()
    {
        $camelCased = StringUtils::camelCase("ЕГРЮЛ Казахстан");
        assertThat($camelCased, equalTo("EGRULKazahstan"));
    }

    public function testSanitizeIdentifierTransliteratesAndRemovesInvalidCharacters()
    {
        $sanitized = StringUtils::sanitizeIdentifier("IP-адреса");
        assertThat($sanitized, equalTo("IPadresa"));
    }
}