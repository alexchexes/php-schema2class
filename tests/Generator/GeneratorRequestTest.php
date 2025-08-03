<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Spec\SpecificationOptions;
use Helmich\Schema2Class\Spec\ValidatedSpecificationFilesItem;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNotSame;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class GeneratorRequestTest extends TestCase
{

    const TARGET_DIR = 'targetDir';
    const TARGET_NAMESPACE = 'targetNameSpace';
    const TARGET_CLASS_NAME = 'targetClassName';

    private GeneratorRequest $request;

    protected function setUp(): void
    {
        $this->request = new GeneratorRequest(
            [],
            new ValidatedSpecificationFilesItem(
                self::TARGET_NAMESPACE,
                self::TARGET_CLASS_NAME,
                self::TARGET_DIR,
            ),
            (new SpecificationOptions())->withTargetPHPVersion("7.0")
        );
    }

    #[TestDox("is at least PHP 7 and not 8")]
    public function testIsAtLeastPHP7vs8()
    {
        $req = $this->request->withPHPVersion("7.1");

        assertTrue($req->isAtLeastPHP('7'));
        assertFalse($req->isAtLeastPHP('8'));
    }

    #[TestDox("is at least PHP 8 and not 8.1")]
    public function testIsAtLeastPHP8vs8_1()
    {
        $req = $this->request->withPHPVersion("8.0");

        assertTrue($req->isAtLeastPHP('8'));
        assertFalse($req->isAtLeastPHP('8.1'));

    }

    #[TestDox("target PHP version \"7\" satisfies isAtLeastPHP('7.4') check")]
    public function testTargetPhp7SatisfiesIsAtLeast7_4()
    {
        $req = $this->request->withPHPVersion('7');
        assertTrue($req->isAtLeastPHP('7.4'));
    }

    #[TestDox("target PHP version \"8\" satisfies isAtLeastPHP('8.4') check")]
    public function testTargetPhp8SatisfiesIsAtLeast8_4()
    {
        $req = $this->request->withPHPVersion('8');
        assertTrue($req->isAtLeastPHP('8.4'));
    }

    public function testGetTargetNamespace()
    {
        assertSame(self::TARGET_NAMESPACE, $this->request->getTargetNamespace());
    }

    public function testWithClass()
    {
        $underTest = $this->request->withClass('Foo');

        assertNotSame($underTest, $this->request);
        assertSame('Foo', $underTest->getTargetClass());
        assertSame(self::TARGET_CLASS_NAME, $this->request->getTargetClass());
    }

    public function testWithSchema()
    {
        $schema = ['properties' => ['Foo']];

        $underTest = $this->request->withSchema($schema);

        assertNotSame($underTest, $this->request);
        assertSame($schema, $underTest->getSchema());
        assertSame([], $this->request->getSchema());
    }

    public function testGetPhpTargetVersion()
    {
        $req = $this->request->withPHPVersion("7.2");
        assertSame("7.2.0", $req->getTargetPHPVersion());

        $req = $this->request->withPHPVersion("5.6.1");
        assertSame("5.6.1", $req->getTargetPHPVersion());
    }

    public function testGetTargetDirectory()
    {
        assertSame(self::TARGET_DIR, $this->request->getTargetDirectory());
    }
}
