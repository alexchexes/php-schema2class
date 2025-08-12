<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

class kind extends TypeHintUtilTest {
    protected const RETURN = TypeHint::KIND_RETURN;
    protected const ARG = TypeHint::KIND_ARG;
    protected const PROP = TypeHint::KIND_PROP;
    protected const CONST = TypeHint::KIND_CONST;
}

class TypeHintUtilTest extends TestCase
{
    private const ALL_KINDS = [
        kind::RETURN,
        kind::ARG,
        kind::PROP,
        kind::CONST,
    ];

    private const ALL_VERS = [
        '5.6',
        '7.0',
        '7.1',
        '7.2',
        '7.3',
        '7.4',
        '8.0',
        '8.1',
        '8.2',
        '8.3',
        '8.4',
    ];

    private const EXPECT_EXCEPTION = 'expect-exception-when-this-value-in-array';

    private static function getDataForMainTestCasesSet(): array
    {
        $cases = [
            [
                [
                    'bool|false',
                    ['bool', 'false'],
                    'bool|true',
                    'true|false',
                ], /* ———————————————————> */ 'bool',
                // Overridees ↓. Each version means "from this version up until newer version specified, but not including it"
                ['5.6', null],
                ['7.0', [kind::PROP => null, kind::CONST => null]], // specified kinds override the base
                ['7.4', [kind::CONST => null]],
                ['8.2', [kind::CONST => null]],
                // we don't specify 8.3 here because 'allVersions' expands to it as well.
            ],
            [
                [
                    'int|null'
                ], /* ———————————————————> */ '?int',
                ['5.6', null],
                ['7.0',     self::EXPECT_EXCEPTION], // MODE must be specified
                ['7.0', null, 'flag' => TypeHint::LEGACY_NULLABLE_OMIT_TYPE],
                ['7.0', ['int', kind::PROP => null, kind::CONST => null], 'flag' => TypeHint::LEGACY_NULLABLE_DROP_NULL],
                ['7.1', [kind::CONST => null]],
            ],
            [
                [
                    '?', ['?', 'string'],
                    '|', ['|', 'string'],
                    '(', ['(', 'string'],
                    '&', ['&', 'string'],
                    '\\', ['\\', 'string'],
                ], /* ———————————————————> */ [self::EXPECT_EXCEPTION],
            ],
        ];

        return $cases;
    }

    private static function getDataForScalarsTestCases(): array
    {
        $cases = [];
        foreach (TypeHint::SCALARS as $type) {
            $cases[] = [
                [
                    $type,
                    [$type],
                ], /* ———————————————————> */ $type,
                ['5.6', null],
                ['7.0', [kind::PROP => null, kind::CONST => null]],
                ['7.4', [kind::CONST => null]],
                ['8.2', [kind::CONST => null]],
            ];
        }
        return $cases;
    }

    public static function getTestCasesForTypeHintTest()
    {
        $cases = array_merge(
            self::getDataForScalarsTestCases(),
            self::getDataForMainTestCasesSet(),
        );

        // Generate test cases for all scalars

        $data = [];

        foreach ($cases as $caseIdx => $case) {
            $caseNum = $caseIdx + 1;
            $inputCases = array_shift($case);

            // base data for all versions until overriden and for the "latest specified + 1 until"
            $baseVersionData = array_shift($case); 
            $versionCases = $case; // copy the shifted for convenience

            // normalize the "base version data"
            if (!is_array($baseVersionData)) {
                $baseVersionData = [$baseVersionData];
            }


            // if no versions specified except the base, start with the minimal known version
            if ($versionCases === []) {
                $versionCases[] = [
                    self::ALL_VERS[array_key_first(self::ALL_VERS)],
                    ...$baseVersionData,
                ];
            }

            // make sure all provided versions are listed in the ALL_VERS
            $providedVersions = array_map(fn($verData) => $verData[0], $versionCases);
            $unknownVers = array_diff($providedVersions, self::ALL_VERS);
            if ($unknownVers) {
                throw new Exception("Incorrect case #{$caseNum}: PHP version not found in the known versions list: " . implode(', ', $unknownVers));
            }

            // sort version cases (semver-aware)
            $versionCases = self::sortVersionCases($versionCases);

            // from the ALL_VERS list, get version next after the latest specified in the test version cases
            $latestSpecifiedVer = $versionCases[array_key_last($versionCases)][0];
            $verLatestPlus1 = self::getNextKnownVer($latestSpecifiedVer);
            
            // if found, append it to the version cases
            if ($verLatestPlus1) {
                $versionCases[] = [
                    $verLatestPlus1, // version
                    ...$baseVersionData, // data for that version
                ];
            }
            // if not found - then the latest known version is already in the version cases, don't add anything

            // now pad the array with data for all missing versions starting from the lowest specified
            $providedVersions = array_map(fn($verData) => $verData[0], $versionCases);
            $missing_vers = array_diff(self::ALL_VERS, $providedVersions);
            foreach ($missing_vers as $missingVer) {
                $prevVer = self::getPrevKnownVer($missingVer);
                $prevVerData = array_find($versionCases, fn($verData) => $verData[0] === $prevVer);

                if ($prevVer && $prevVerData) {
                    $versionCases[] = [
                        $missingVer,
                        ...array_slice($prevVerData, 1),
                    ];
                }
            }
            // sort array again after padding
            $versionCases = self::sortVersionCases($versionCases);

            // it's actually needed in the next, inner loop, but place it here to avoid redundant redeclarations
            $baseKindCases = $baseVersionData[0];
            $baseKindCases = self::normalizeKindCases($baseKindCases);
            if (array_diff(self::ALL_KINDS, array_keys($baseKindCases))) {
                throw new Exception("Incorrect case #{$caseNum}: Expected values specified not for all kinds");
            }

            // now that we have a complete array of version cases, iterate and build test cases for each:
            // - Input case
            //   - Version case
            //     - Kind of type (arg, return, prop, etc)
            // Now iterating over elements that look like ['7.0', ['int', kind::CONST => null], 'flag' => ...]
            foreach ($versionCases as $versionData) {
                // '7.0'
                $ver = $versionData[0];

                // 'flag' => ...
                if (array_key_exists('flag', $versionData)) {
                    $legacyFlag = $versionData['flag'];
                } else {
                    $legacyFlag = $baseVersionData['flag'] ?? null;
                }

                // ['int', kind::CONST => null]
                if (array_key_exists(1, $versionData)) {
                    $kindCases = $versionData[1];
                } else {
                    $kindCases = $baseKindCases;
                }
                // normalize plain to array
                $kindCases = self::normalizeKindCases($kindCases);

                // take 'int' from ['int', kind::CONST => null]

                // pad array with value expected for all kinds:
                // either the string given instead of array or from first numeric-indexed elem
                // if some "kind" was not provided, fill in with the 'expectedForAllKinds' value
                $kindCases = [
                    ...$baseKindCases,
                    ...$kindCases,
                ];

                // now we have "kind cases" normalized for all the vesion cases
                
                // we actually could iterate inputCases on the top level right inside `$cases` foreach loop,
                // but that would add one more indent level to the whole code and not necessary add more clarity
                foreach ($inputCases as $inputCase) {

                    foreach ($kindCases as $kind => $expectedResult) {
                        $expectedResultStr = $expectedResult === self::EXPECT_EXCEPTION
                            ? \InvalidArgumentException::class
                            : ($expectedResult === null ? 'null' : var_export($expectedResult, true));
                    
                        $caseData = [];

                        // create string respresentation of the input type to show in test case names or errors
                        if (is_array($inputCase)) {
                            $inputTypeString = "[" . implode(", ", array_map(fn($v)=>var_export($v, true), $inputCase)) . "]";
                        } else {
                            $inputTypeString = var_export($inputCase, true);
                        }

                        $legacyFlagStr = '';
                        if ($legacyFlag) {
                            $legacyFlagStr = " with flag '$legacyFlag'";
                        }

                        $caseData = [
                            'input'      => $inputCase,
                            'ver'        => $ver,
                            'kind'       => $kind,
                            'legacyflag' => $legacyFlag,
                            'expected'   => $expectedResult,
                        ];

                        $caseName = "{$inputTypeString} → {$expectedResultStr} on php {$ver} for '{$kind}'{$legacyFlagStr}";

                        $data[$caseName] = $caseData;
                    }
                }
            }
        }

        // echo "\n---\$data:\n";  print_r($data);  echo "\n---";

        return $data;
    }
    
    private static function getNextKnownVer(string $ver): ?string
    {
        $k = array_search($ver, self::ALL_VERS, true);
        return self::ALL_VERS[$k + 1] ?? null;
    }
    
    private static function getPrevKnownVer(string $ver): ?string
    {
        $k = array_search($ver, self::ALL_VERS, true);
        return self::ALL_VERS[$k - 1] ?? null;
    }

    private static function sortVersionCases(array $versionCases): array
    {
        usort($versionCases, static fn($a, $b) => version_compare($a[0], $b[0]));
        return $versionCases;
    }

    private static function normalizeKindCases(array|string|null $kindCases): array
    {
        $kindCases = is_array($kindCases) ? $kindCases : [$kindCases];

        if (array_key_exists(0, $kindCases)) {
            $expectedForAll = $kindCases[0];
            unset($kindCases[0]);
            $kindCases = [
                ...array_fill_keys(self::ALL_KINDS, $expectedForAll),
                ...$kindCases,
            ];
        }

        return $kindCases;
    }

    #[DataProvider('getTestCasesForTypeHintTest')]
    public function testTypeHint(
        array|string $input,
        string $ver,
        string $kind,
        ?string $legacyflag,
        ?string $expected,
    ): void
    {
        if ($expected === self::EXPECT_EXCEPTION) {
            $this->expectException(\InvalidArgumentException::class);
            TypeHint::forPhpVer($input, $ver, $kind, $legacyflag);
        } else {
            assertThat(
                TypeHint::forPhpVer($input, $ver, $kind),
                equalTo($expected)
            );
        }
    }

    // test: early throws if input has any char except allowed, which are (rough!):
    // \ & ? | ( ) and \w+ with unicode except forbidden for identifiers, plus it cannot not start with digit
    // OR IF THE CHAR THAT CANNOT BE USED STANDALONE PASSED AS ARRAY ELEM, OR IS IN A WRONG PLACE 

    public function testPropertyTypingRequiresPhp74()
    {
        assertThat(TypeHint::forPhpVer('string', '7.3', kind::PROP), equalTo(null));
        assertThat(TypeHint::forPhpVer('string', '7.4', kind::PROP), equalTo('string'));
    }

    public function testNullableType()
    {
        assertThat(TypeHint::forPhpVer('int|null', '7.0', kind::ARG), equalTo(null));
        assertThat(TypeHint::forPhpVer('int|null', '7.1', kind::ARG), equalTo('?int'));

        assertThat(TypeHint::forPhpVer('string|null', '7.0', kind::RETURN), equalTo(null));
        assertThat(TypeHint::forPhpVer('string|null', '7.1', kind::RETURN), equalTo('?string'));
        assertThat(TypeHint::forPhpVer('string|null', '7.4', kind::RETURN), equalTo('?string'));
    }

    public function testUnionTypesRequirePhp8()
    {
        assertThat(TypeHint::forPhpVer('int|string', '7.4', kind::ARG), equalTo(null));
        assertThat(TypeHint::forPhpVer('int|string', '8.0', kind::ARG), equalTo('int|string'));
        assertThat(TypeHint::forPhpVer(['int', 'string'], '7.4', kind::ARG), equalTo(null));
        assertThat(TypeHint::forPhpVer(['int', 'string'], '7.4', kind::ARG), equalTo(null));
        assertThat(TypeHint::forPhpVer(['int', 'string'], '8.0', kind::ARG), equalTo('int|string'));
        assertThat(TypeHint::forPhpVer(['int', 'string'], '8.0', kind::ARG), equalTo('int|string'));
    }

    public function testUnionTypesAreSortedAndNullLast()
    {
        assertThat(TypeHint::forPhpVer('string|int|null', '8.0', kind::RETURN), equalTo('int|string|null'));
        assertThat(TypeHint::forPhpVer(['int', 'MyClass'], '8.0', kind::ARG), equalTo('MyClass|int'));
        assertThat(TypeHint::forPhpVer(['string', 'null', 'int'], '8.1', kind::RETURN), equalTo('int|string|null'));
    }

    public function testMixedType()
    {
        assertThat(TypeHint::forPhpVer('mixed', '7.4', kind::ARG), equalTo(null));
        assertThat(TypeHint::forPhpVer('mixed', '7.4', kind::ARG), equalTo(null));
        assertThat(TypeHint::forPhpVer('mixed', '7.4', kind::ARG), equalTo(null));
        assertThat(TypeHint::forPhpVer('mixed', '8.0', kind::ARG), equalTo('mixed'));
        assertThat(TypeHint::forPhpVer('mixed|null', '8.0', kind::RETURN), equalTo('mixed'));
        assertThat(TypeHint::forPhpVer('mixed|null', '8.0', kind::RETURN), equalTo('mixed'));
        assertThat(TypeHint::forPhpVer('mixed|null', '8.1', kind::ARG), equalTo('mixed'));
    }
    public function testMixedWithOtherTypesThrows()
    {
        self::expectException(\InvalidArgumentException::class);
        TypeHint::forPhpVer('mixed|string', '8.0', kind::RETURN);
    }

    public function testLiteralTrueFalseHandling()
    {
        // need to revise this. PHP docs say:
        // 8.2.0	Support for the literal type true has been added.
        // 8.2.0	The types null and false can now be used standalone.
        // so in before 8.2 we could use false and null as part of union but not standalone, right?

        assertThat(TypeHint::forPhpVer('?false', '8.1', kind::RETURN), equalTo('?bool'));
        assertThat(TypeHint::forPhpVer('false', '8.1', kind::ARG), equalTo('bool'));
        assertThat(TypeHint::forPhpVer('false', '8.1', kind::RETURN), equalTo('bool'));
        assertThat(TypeHint::forPhpVer('false', '8.2', kind::RETURN), equalTo('false'));
        assertThat(TypeHint::forPhpVer('false|null', '8.1', kind::RETURN), equalTo('?bool'));
        assertThat(TypeHint::forPhpVer('false|string', '8.1', kind::RETURN), equalTo('bool|string'));
        assertThat(TypeHint::forPhpVer('null', '8.2', kind::RETURN), equalTo('null'));
        assertThat(TypeHint::forPhpVer('true', '8.1', kind::ARG), equalTo('bool'));
        assertThat(TypeHint::forPhpVer('true|int', '8.1', kind::RETURN), equalTo('bool|int'));
        assertThat(TypeHint::forPhpVer(['false', 'string'], '8.1', kind::RETURN), equalTo('bool|string'));
        assertThat(TypeHint::forPhpVer(['true', 'false'], '8.1', kind::RETURN), equalTo('bool'));
        assertThat(TypeHint::forPhpVer(['true', 'false'], '8.2', kind::RETURN), equalTo('bool'));
        


        // DRAFT TO DETERMINE SHAPE OF CORTESSIAN PRODUCT ↓↓↓

        // test more broad types collapse
        assertThat(TypeHint::forPhpVer('bool|false', '8.3', kind::RETURN),    equalTo('bool'));
        assertThat(TypeHint::forPhpVer('bool|false', '8.3', kind::ARG),       equalTo('bool'));
        assertThat(TypeHint::forPhpVer('bool|false', '8.3', kind::PROP),      equalTo('bool'));
        assertThat(TypeHint::forPhpVer('bool|false', '8.3', kind::CONST),     equalTo('bool'));
        assertThat(TypeHint::forPhpVer('bool|false', '8.2', kind::RETURN),    equalTo('bool'));
        assertThat(TypeHint::forPhpVer('bool|false', '8.2', kind::ARG),       equalTo('bool'));
        assertThat(TypeHint::forPhpVer('bool|false', '8.2', kind::PROP),      equalTo('bool'));
        assertThat(TypeHint::forPhpVer('bool|false', '8.2', kind::CONST),     equalTo(null));
        assertThat(TypeHint::forPhpVer('bool|false', '7.4', kind::RETURN),    equalTo('bool'));
        assertThat(TypeHint::forPhpVer('bool|false', '7.4', kind::ARG),       equalTo('bool'));
        assertThat(TypeHint::forPhpVer('bool|false', '7.4', kind::PROP),      equalTo('bool'));
        assertThat(TypeHint::forPhpVer('bool|false', '7.4', kind::CONST),     equalTo(null));
        assertThat(TypeHint::forPhpVer('bool|false', '7.0', kind::RETURN),    equalTo('bool'));
        assertThat(TypeHint::forPhpVer('bool|false', '7.0', kind::ARG),       equalTo('bool'));
        assertThat(TypeHint::forPhpVer('bool|false', '7.0', kind::PROP),      equalTo(null));
        assertThat(TypeHint::forPhpVer('bool|false', '7.0', kind::CONST),     equalTo(null));
        assertThat(TypeHint::forPhpVer('bool|false', '5.6', kind::RETURN),    equalTo(null));
        assertThat(TypeHint::forPhpVer('bool|false', '5.6', kind::ARG),       equalTo(null));
        assertThat(TypeHint::forPhpVer('bool|false', '5.6', kind::PROP),      equalTo(null));
        assertThat(TypeHint::forPhpVer('bool|false', '5.6', kind::CONST),     equalTo(null));

        // DRAFT TO DETERMINE SHAPE OF CORTESSIAN PRODUCT ↑↑↑



        assertThat(TypeHint::forPhpVer('bool|false|null', '7.4', kind::RETURN), equalTo('?bool'));
        assertThat(TypeHint::forPhpVer('bool|false|null', '8.2', kind::RETURN), equalTo('?bool'));
        assertThat(TypeHint::forPhpVer('true|bool', '7.4', kind::RETURN), equalTo('bool'));
        assertThat(TypeHint::forPhpVer('true|bool', '8.2', kind::RETURN), equalTo('bool'));
        assertThat(TypeHint::forPhpVer('true|false', '7.4', kind::RETURN), equalTo('bool'));
        assertThat(TypeHint::forPhpVer('true|false', '8.2', kind::RETURN), equalTo('bool'));
        assertThat(TypeHint::forPhpVer('true|null|false', '7.4', kind::RETURN), equalTo('?bool'));
        assertThat(TypeHint::forPhpVer('true|null|false', '8.2', kind::RETURN), equalTo('?bool'));
        // no nullable types in PHP <=7.0. So we need to drop null, but only when a flag given to ensure it's intentionall, otherwise throw
        // assertThat(TypeHint::forPhpVer('bool|false|null', '7.0', kind::RETURN), equalTo(bool));
        assertThat(TypeHint::forPhpVer('bool|false|null', '5.6', kind::RETURN), equalTo(null));

        // Each name-resolved type may only occur once
        assertThat(TypeHint::forPhpVer('int|string|INT', '7.4', kind::RETURN), equalTo('int|string'));
    }

    private function throwIfVersionIsOlderThan56(): void
    {
        self::expectException(InvalidArgumentException::class);
        TypeHint::forPhpVer('array', '5.4', kind::ARG);
    }

    public function testNeverType()
    {
        assertThat(TypeHint::forPhpVer('never', '7.4', kind::RETURN), equalTo('void'));
        assertThat(TypeHint::forPhpVer('never', '8.0', kind::RETURN), equalTo('void'));
        assertThat(TypeHint::forPhpVer('never', '8.1', kind::RETURN), equalTo('never'));
    }

    public function testVoidType()
    {
        assertThat(TypeHint::forPhpVer('void', '7.0', kind::RETURN), equalTo(null));
        assertThat(TypeHint::forPhpVer('void', '7.1', kind::RETURN), equalTo('void'));
        assertThat(TypeHint::forPhpVer('void', '7.1', kind::RETURN), equalTo('void'));
        assertThat(TypeHint::forPhpVer('void', '7.4', kind::RETURN), equalTo('void'));
    }

    public function testVoidUsedNotForReturnTypeThrows(): void
    {
        self::expectException(InvalidArgumentException::class);
        TypeHint::forPhpVer('void', '7.4', kind::ARG);        
    }

    public function testObjectType()
    {
        assertThat(TypeHint::forPhpVer('object', '7.1', kind::ARG), equalTo(null));
        assertThat(TypeHint::forPhpVer('object', '7.2', kind::ARG), equalTo('object'));
    }

    public function testStandaloneNull()
    {
        assertThat(TypeHint::forPhpVer('null', '8.1', kind::RETURN), equalTo(null));
        assertThat(TypeHint::forPhpVer('null', '8.2', kind::RETURN), equalTo('null'));
        assertThat(TypeHint::forPhpVer('null', '8.2', kind::RETURN), equalTo('null'));
    }

    public function testIntersectionAndDnf()
    {        
        assertThat(TypeHint::forPhpVer('A&B', '8.0', kind::ARG), equalTo(null));
        assertThat(TypeHint::forPhpVer('A&B', '8.1', kind::ARG), equalTo('A&B'));
        assertThat(TypeHint::forPhpVer('A&B|C', '8.1', kind::ARG), equalTo(null));
        assertThat(TypeHint::forPhpVer('A&B|C', '8.2', kind::ARG), equalTo('A&B|C')); // not valid? should be (A&B)|C ?
        assertThat(TypeHint::forPhpVer('A&B', '8.1', kind::RETURN), equalTo('A&B'));
        assertThat(TypeHint::forPhpVer('A&B|null', '8.1', kind::RETURN), equalTo(null));
        assertThat(TypeHint::forPhpVer('A&B|null', '8.2', kind::RETURN), equalTo('A&B|null'));

        assertThat(TypeHint::forPhpVer(['T', 'X&Y'], '8.2', kind::ARG), equalTo('T|(X&Y)'));
    }


    public function testPropertyTypingRequires74()
    {
        assertThat(TypeHint::forPhpVer('int', '7.3', kind::PROP), equalTo(null));
        assertThat(TypeHint::forPhpVer('int', '7.4', kind::PROP), equalTo('int'));
    }

    public function testKindsSupportedOnlyIn83()
    {
        // 8.3.0	Support for class, interface, trait, and enum constant typing has been added
        assertThat(TypeHint::forPhpVer('int', '8.2', kind::CONST), equalTo(null));
        assertThat(TypeHint::forPhpVer('int', '8.3', kind::CONST), equalTo('int'));
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
}

    // ['bool', 'false|null'] !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!