<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\Expression\MatchGenerator;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

class MatchGeneratorTest extends TestCase
{
    public function testDefaultCaseReplacesRedundantArms()
    {
        $generator = new MatchGenerator('$foo');
        $generator->addArm('a === x', '1');
        $generator->addArm('a === y', '2');
        $generator->addArm('default', '2');

        $expected = <<<CODE
match (\$foo) {
    a === x => 1,
    default => 2,
}
CODE;

        assertThat($generator->generate(), equalTo($expected));
    }

    public function testNoMatchExpressionWhenOnlyDefaultArm()
    {
        $generator = new MatchGenerator('$foo');
        $generator->addArm('a === x', '1');
        $generator->addArm('default', '1');

        $expected = '1';

        assertThat($generator->generate(), equalTo($expected));
    }
}