<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\Expression\ArrayMapGenerator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

class ArrayMapGeneratorTest extends TestCase
{
    public static function provideData(): array
    {
        $cases = [
            [
                'params' => [
                    'itemParam'   => '$i',
                    'mapExpr'     => '$i->fromInput()',
                    'arrayExpr'   => '$this->foo',
                    'itemType'    => 'A|B',
                    'useVars'     => ['$var1', '$var2'],
                    'returnType'  => 'A|B',
                ],
                'expectedByVer' => [
                    '5.6' => <<<'CODE'
array_map(function($i) use ($var1, $var2) { return $i->fromInput(); }, $this->foo)
CODE,

                    '7.0' => <<<'CODE'
array_map(function($i) use ($var1, $var2) { return $i->fromInput(); }, $this->foo)
CODE,

                    '7.4' => <<<'CODE'
array_map(fn (object $i): object => $i->fromInput(), $this->foo)
CODE,

                    '8.4' => <<<'CODE'
array_map(fn (A|B $i): A|B => $i->fromInput(), $this->foo)
CODE,
                ],
            ],
            [
                'params' => [
                    'itemParam'   => '$i',
                    'mapExpr'     => 'SomeVeryLongClassNameToTestWrapping::fromInput($i)',
                    'arrayExpr'   => '$this->foo',
                    'itemType'    => 'array',
                    'useVars'     => ['$var1', '$var2'],
                    'returnType'  => 'SomeVeryLongClassNameToTestWrapping',
                ],
                'expectedByVer' => [
                    '5.6' => <<<'CODE'
array_map(
    function(array $i) use ($var1, $var2) { return SomeVeryLongClassNameToTestWrapping::fromInput($i); },
    $this->foo
)
CODE,

                    '7.0' => <<<'CODE'
array_map(function(array $i) use ($var1, $var2): SomeVeryLongClassNameToTestWrapping {
    return SomeVeryLongClassNameToTestWrapping::fromInput($i);
}, $this->foo)
CODE,

                    '7.4' => <<<'CODE'
array_map(
    fn (array $i): SomeVeryLongClassNameToTestWrapping => SomeVeryLongClassNameToTestWrapping::fromInput($i),
    $this->foo,
)
CODE,

                    '8.4' => <<<'CODE'
array_map(
    fn (array $i): SomeVeryLongClassNameToTestWrapping => SomeVeryLongClassNameToTestWrapping::fromInput($i),
    $this->foo,
)
CODE,
                ],
            ],
            [
                'params' => [
                    'itemParam'   => '$i',
                    'mapExpr'     => "(\$i instanceof SomeClassA)\n    ? SomeClassA::fromInput(\$i)\n    : SomeClassB::fromInput(\$i)",
                    'arrayExpr'   => '$this->foo',
                    'itemType'    => 'SomeClassA|SomeClassB',
                    'useVars'     => ['$somePrettyLongVarName1', '$somePrettyLongVarName2', '$somePrettyLongVarName3'],
                    'returnType'  => 'MyClass',
                ],
                'expectedByVer' => [
                    '7.2' => <<<'CODE'
array_map(function(object $i) use (
    $somePrettyLongVarName1,
    $somePrettyLongVarName2,
    $somePrettyLongVarName3
): MyClass {
    return ($i instanceof SomeClassA)
        ? SomeClassA::fromInput($i)
        : SomeClassB::fromInput($i);
}, $this->foo)
CODE,
                ],
            ],
        ];

        $data = [];
        foreach ($cases as $caseData) {
            $params = $caseData['params'];
            $expectedByVer = $caseData['expectedByVer'];
            foreach ($expectedByVer as $ver => $expected) {
                $data[] = [
                    'params' => [...$params, 'phpVer' => $ver],
                    'expected' => $expected,
                ];
            }
        }
        return $data;
    }

    #[DataProvider('provideData')]
    public function testArrayMapGenerator(array $params, string $expected): void
    {
        $generated = ArrayMapGenerator::make(...$params);

        $paramsStr = print_r($params, true);
        assertThat($generated, equalTo($expected), "Fail with params: {$paramsStr}");
    }
}