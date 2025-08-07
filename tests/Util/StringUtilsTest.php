<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

class StringUtilsTest extends TestCase
{
    public function testCapitalizeWordCapitalizesWord()
    {
        $capitalized = StringUtils::capitalize("foo");
        assertThat($capitalized, equalTo("Foo"));
    }

    public function testCapitalizeWordDoesNotModifyAlreadyCapitalizedWord()
    {
        $capitalized = StringUtils::capitalize("Foo");
        assertThat($capitalized, equalTo("Foo"));
    }

    public function testSafePascalCaseWorks()
    {
        $pascaled = StringUtils::safePascalCase("foo");
        assertThat($pascaled, equalTo("Foo"));
    }

    public function testSafePascalCaseHandlesWordsWithDashes()
    {
        $pascaled = StringUtils::safePascalCase("content-disposition");
        assertThat($pascaled, equalTo("ContentDisposition"));
    }

    public function testSafeCamelCaseWorks()
    {
        $camelCased = StringUtils::safeCamelCase("content-disposition");
        assertThat($camelCased, equalTo("contentDisposition"));
    }

    public function testSafeCamelCaseTransliteratesNonAscii()
    {
        $camelCased = StringUtils::safeCamelCase("ЕГРЮЛ Казахстан");
        assertThat($camelCased, equalTo("EGRIuLKazakhstan"));
    }

    public function testSafeCamelCaseFallbackForInvalidString()
    {
        $camel = StringUtils::safeCamelCase("!!!");
        $this->assertMatchesRegularExpression('/^_[a-f0-9]{8}$/', $camel);
    }

    public function testSafeCamelCasePrefixesNumericNames()
    {
        $camel = StringUtils::safeCamelCase("123name");
        assertThat($camel, equalTo("_123name"));
    }

    public function testSanitizeIdentifierTransliteratesAndRemovesInvalidCharacters()
    {
        $sanitized = StringUtils::sanitizeIdentifier("IP-адреса");
        assertThat($sanitized, equalTo("IP_adresa"));
    }

    public function testSanitizeIdentifierTransliteratesAndPlacesUnderscoresCorrectly()
    {

        $sanitized = StringUtils::sanitizeIdentifier("@@Беларусь");
        assertThat($sanitized, equalTo("_Belarus"));
    }

    public function testCapitalizeWordHandlesEmptyString()
    {
        $capitalized = StringUtils::capitalize("");
        assertThat($capitalized, equalTo(""));
    }

    public function testSanitizeIdentifierFallbackForInvalidString()
    {
        $sanitized = StringUtils::sanitizeIdentifier("~!@#$%^");
        $this->assertMatchesRegularExpression('/^_[a-f0-9]{8}$/', $sanitized);
    }
}
