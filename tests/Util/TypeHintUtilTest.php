<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class kind extends TypeHintUtilTest {
    protected const RETURN = TypeHint::KIND_RETURN;
    protected const ARG = TypeHint::KIND_ARG;
    protected const PROP = TypeHint::KIND_PROP;
}

class TypeHintUtilTest extends TestCase
{
    private const ALL_KINDS = [
        kind::RETURN,
        kind::ARG,
        kind::PROP,
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

    /** 
     * Converts data from convenient format to format suitable for use in tests
     */
    private static function convertToProviderArray($cases)
    {
        $data = [];
        $uniqueNames = [];

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
                // we do 'array_find' but since there can be multiple version-case entries for the same
                // php ver (with different flags, for example), we use 'array_reverse' to get the last one
                $prevVerData = array_find(array_reverse($versionCases), fn($verData) => $verData[0] === $prevVer);

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

            // iterate input cases on this level to get a bit prettier output when running with -d --enable-pretty-print
            foreach ($inputCases as $inputCase) {

                // now that we have a complete array of version cases, iterate and build test cases for each:
                // - Input case
                //   - Version case
                //     - Kind of type (arg, return, prop, etc)
                // Now iterating over elements that look like ['7.0', ['int', kind::PROP => null], 'flag' => ...] or ['7.0', null]
                foreach ($versionCases as $versionData) {
                    // '7.0'
                    $ver = $versionData[0];

                    // 'flag' => ...
                    if (array_key_exists('flag', $versionData)) {
                        $legacyFlag = $versionData['flag'];
                    } else {
                        $legacyFlag = $baseVersionData['flag'] ?? null;
                    }

                    // ['int', kind::PROP => null]
                    if (array_key_exists(1, $versionData)) {
                        $kindCases = $versionData[1];
                    } else {
                        $kindCases = $baseKindCases;
                    }
                    // normalize plain to array
                    $kindCases = self::normalizeKindCases($kindCases);

                    // take 'int' from ['int', kind::PROP => null]

                    // pad array with value expected for all kinds:
                    // either the string given instead of array or from first numeric-indexed elem
                    // if some "kind" was not provided, fill in with the 'expectedForAllKinds' value
                    $kindCases = [
                        ...$baseKindCases,
                        ...$kindCases,
                    ];
            
                    // now we have "kind cases" normalized for all the vesion cases

                    foreach ($kindCases as $kind => $expectedResult) {
                        $exceptionClass = null;

                        if ($expectedResult === self::EXPECT_EXCEPTION) {
                            $expectedResult = null;
                            $exceptionClass = \InvalidArgumentException::class;
                            $exceptionClass = $exceptionClass;
                            $expectedResultStr = $exceptionClass;
                        } else {
                            $expectedResultStr = self::exportVar($expectedResult);
                        }
                    
                        $caseData = [];

                        // create string respresentation of the input type to show in test case names or errors
                        $inputTypeString = self::exportVar($inputCase);

                        $legacyFlagStr = '';
                        if ($legacyFlag) {
                            $legacyFlagStr = " with flag '$legacyFlag'";
                        }

                        $caseName = "{$inputTypeString} → {$expectedResultStr} for php {$ver} for '{$kind}'{$legacyFlagStr}";
                        $uniqueName = $caseName;
                        if (array_key_exists($uniqueName, $uniqueNames)) {
                            $uniqueName = "$uniqueName (2)"; // avoid overriding
                        }

                        $caseNameLower = mb_strtolower($caseName); // or pretty-printing will split everything by uppercased chars
                        if (array_key_exists($caseNameLower, $data)) {
                            $caseNameLower = "$caseNameLower (2)"; // avoid overriding
                        }

                        $caseData = [
                            'caseName'          => $caseName,
                            'uniqueName'        => $uniqueName,
                            'input'             => $inputCase,
                            'ver'               => $ver,
                            'kind'              => $kind,
                            'legacyflag'        => $legacyFlag,
                            'expected'          => $expectedResult,
                            'exceptionClass'   => $exceptionClass,
                        ];

                        $data[$caseNameLower] = $caseData;
                    }
                }
            }
        }

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

    private static function sortVersionCases(array $versionCases, bool $descending = false): array
    {
        $sortFn = $descending
            ? static fn($verDataA, $verDataB) => version_compare($verDataB[0], $verDataA[0])
            : static fn($verDataA, $verDataB) => version_compare($verDataA[0], $verDataB[0]);

        usort($versionCases, $sortFn(...));
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

    private static function exportVar(mixed $var): string
    {
        if (is_array($var)) {
            return "[" . implode(", ", array_map(fn ($v) => self::exportVar($v), $var)) . "]";
        } 
        return $var === null ? 'null' : var_export($var, true);
    }

    /*---------------------------*
    *        Core harness        *
    *----------------------------*/
     
    private function assertDataset(string $name, array $cases): void
    {
        $flatCases = self::convertToProviderArray($cases);

        // Re-key the flat list into nested [ver][kind|flag][input] => ['ok' => string] or ['ex' => class]
        [$expectedMap, $actualMap] = $this->buildMaps($flatCases);

        // Stable ordering helps diffs
        $this->ksortDeep($expectedMap);
        $this->ksortDeep($actualMap);

        $this->assertSame(count($flatCases), count($expectedMap), "Test is incorrect");
        $this->assertSame(count($flatCases), count($actualMap), "Test is incorrect");

        // keep assertion count meaningful: 1 per combination, also substract 3 auxiliary assertions,
        // and perform it here (not before maps assertion) to keep count the same when map assertion fails
        $this->addToAssertionCount(count($flatCases) - 3);

        $this->assertSame(
            $expectedMap,
            $actualMap,
            "Fail in '$name'."
        );
    }

    /**
     * @return array{0: array, 1: array}
     */
    private function buildMaps(array $flatCases): array
    {
        $expected = [];
        $actual   = [];

        foreach ($flatCases as $case) {
            $uniqueName  = $case['uniqueName'];

            // expected
            if ($case['exceptionClass']) {
                $expected[$uniqueName] = ['ex' => $case['exceptionClass']];
            } else {
                $expected[$uniqueName] = ['ok' => $case['expected']]; // expected is already a string or null
            }

            // actual
            try {
                $res = TypeHint::forPhpVer($case['input'], $case['ver'], $case['kind'], $case['legacyflag']);
                $actual[$uniqueName] = ['ok' => $res];
            } catch (\Throwable $e) {
                $actual[$uniqueName] = ['ex' => $e::class];
            }
        }

        return [$expected, $actual];
    }

    private function ksortDeep(array &$a): void
    {
        ksort($a);
        foreach ($a as &$v) {
            if (is_array($v)) {
                $this->ksortDeep($v);
            }
        }
    }

    public function testThrowIfVersionIsOlderThan56(): void
    {
        self::expectException(InvalidArgumentException::class);
        TypeHint::forPhpVer('array', '5.4', kind::ARG);
    }

    /*------------------------*
    *        Test sets        *
    *-------------------------*/

    public function testBooleans(): void
    {
        $this->assertDataset(__METHOD__, [
            [
                // input cases
                [
                    'bool|false', ['bool', 'false'],
                    'bool|true', ['bool', 'true'],
                    'true|false', ['false', 'true'],
                    'BOOL|FALSE', ['BOOL', 'FALSE'],
                    'BOOL|TRUE', ['BOOL', 'TRUE'],
                    'TRUE|FALSE', ['FALSE', 'TRUE'],
                ], /* ———————————————————> */ 'bool', // ← "base" that is used for all versions until overriden and for versions newer than the latest specified
                // ↓ Overrides the "base". The expanding rule is "from this version up until newer version specified (but not including it) use this data"
                ['5.6', null],
                ['7.0', [kind::PROP => null]], // specified kinds override the base
                ['7.4'], // we must specify where the 7.0 "rules" end
            ],
            [
                [
                    '?false',
                    'false|null',
                    ['null', 'false'],
                ], /* ———————————————————> */ '?false',
                ['5.6', null],
                ['7.0', [self::EXPECT_EXCEPTION, kind::PROP => null]], // if no flag specified, must throw (except when kind is PROP)
                ['7.0', null, 'flag' => TypeHint::LEGACY_NULLABLE_OMIT_TYPE],
                ['7.0', ['bool', kind::PROP => null], 'flag' => TypeHint::LEGACY_NULLABLE_DROP_NULL],
                ['7.1', ['?bool', kind::PROP => null]],
                ['7.4', '?bool'],
                ['8.2'],
            ],
            [
                [
                    'false',
                ], /* ———————————————————> */ 'false',
                ['5.6', null],
                ['7.0', ['bool', kind::PROP => null]],
                ['7.4', 'bool'],
                ['8.2'],
            ],
            [
                [
                    'false|string',
                    ['string', 'false'],
                ], /* ———————————————————> */ 'string|false',
                ['5.6', null],
                ['8.0'],
            ],
            [
                [
                    'true|string',
                    ['string', 'true'],
                ], /* ———————————————————> */ 'string|true',
                ['5.6', null],
                ['8.0', 'string|bool'],
                ['8.2'],
            ],
            [
                [
                    '?bool|false|null',
                    ['bool', 'false|null'],
                ], /* ———————————————————> */ '?bool',
                ['5.6', null],
                ['7.0', [self::EXPECT_EXCEPTION, kind::PROP => null]],
                ['7.0', null, 'flag' => TypeHint::LEGACY_NULLABLE_OMIT_TYPE],
                ['7.0', ['bool', kind::PROP => null], 'flag' => TypeHint::LEGACY_NULLABLE_DROP_NULL],
                ['7.1', [kind::PROP => null]],
                ['7.4'],
            ],
        ]);
    }

    public function testScalarsOnly(): void
    {
        $cases = [];
        foreach (TypeHint::SCALARS as $type) {
            $cases[] = [
                [
                    $type,
                    strtoupper($type), // 'INT'
                    [$type],
                    "$type|" . ucfirst($type) . "|" . strtoupper($type), // 'int|Int|INT'
                    ["$type|" . ucfirst($type) . "|" . strtoupper($type), $type], // ['int|Int|INT', 'int']
                ], /* ———————————————————> */ $type,
                ['5.6', null],
                ['7.0', [kind::PROP => null]],
                ['7.4'],
            ];
        }
        $this->assertDataset(__METHOD__, $cases);
    }

    public function testNullableScalars(): void
    {
        $cases = [];
        foreach (TypeHint::SCALARS as $type) {
            $cases[] = [
                [
                    "?$type",
                    "?" . strtoupper($type),
                    ["?$type"],
                    "null|$type",
                    "null|" . strtoupper($type),
                    ['null', $type],
                    "$type|" . ucfirst($type) . "|?" . strtoupper($type), // 'int|Int|?INT'
                    ["$type|" . ucfirst($type) . "|?" . strtoupper($type), "$type"], // ['int|Int|?INT', 'int']
                ], /* ———————————————————> */ "?$type",
                ['5.6', null],
                ['7.0', [self::EXPECT_EXCEPTION, kind::PROP => null]],
                ['7.0', null, 'flag' => TypeHint::LEGACY_NULLABLE_OMIT_TYPE],
                ['7.0', [$type, kind::PROP => null], 'flag' => TypeHint::LEGACY_NULLABLE_DROP_NULL],
                ['7.1', [kind::PROP => null]],
                ['7.4'],
            ];
        }
        $this->assertDataset(__METHOD__, $cases);
    }

    public function testNullablesOnly(): void
    {
        $this->assertDataset(__METHOD__, [
            [
                [
                    'array|null',
                    '?array|null',
                    '?array',
                    ['?array'],
                    ['ARRAY|NULL'],
                    ['ARRAY', 'NULL'],
                    ['?ARRAY', 'ARRAY'],
                    ['ARRAY|NULL', 'ARRAY'],
                ], /* ———————————————————> */ '?array',
                ['5.6', [self::EXPECT_EXCEPTION, kind::PROP => null, kind::RETURN => null]],
                ['5.6', null, 'flag' => TypeHint::LEGACY_NULLABLE_OMIT_TYPE],
                ['5.6', ['array', kind::PROP => null, kind::RETURN => null], 'flag' => TypeHint::LEGACY_NULLABLE_DROP_NULL],
                ['7.0', [self::EXPECT_EXCEPTION, kind::PROP => null]],
                ['7.0', null, 'flag' => TypeHint::LEGACY_NULLABLE_OMIT_TYPE],
                ['7.0', ['array', kind::PROP => null], 'flag' => TypeHint::LEGACY_NULLABLE_DROP_NULL],
                ['7.1', [kind::PROP => null]],
                ['7.4'],
            ],
        ]);
    }

    public function testStandaloneNull(): void
    {
        $this->assertDataset(__METHOD__, [
            [
                [
                    'null', ['null'],
                    'NULL', ['NULL'],
                    '?null', ['?null'],
                    'null|null', ['null', 'null'],
                    'Null|NULL|null', ['Null', 'NULL', 'null'],
                ], /* ———————————————————> */ 'null',
                ['5.6', null],
                ['8.2'],
            ],
        ]);
    }

    public function testNeverType(): void
    {
        $this->assertDataset(__METHOD__, [
            [
                [
                    'never'
                ], /* ———————————————————> */ [[self::EXPECT_EXCEPTION, kind::RETURN => 'never']],
                ['5.6', [kind::RETURN => null]],
                ['7.4', [kind::RETURN => 'void']],
                ['8.1'],
            ],
            [
                [
                    'never|null', ['never', 'null'],
                    'never|string', ['never', 'string'],
                ], /* ———————————————————> */ self::EXPECT_EXCEPTION,
            ],
        ]);
    }

    public function testVoidType(): void
    {
        $this->assertDataset(__METHOD__, [
            [
                [
                    'void'
                ], /* ———————————————————> */ [[self::EXPECT_EXCEPTION, kind::RETURN => 'void']],
                ['5.6', [kind::RETURN => null]],
                ['7.4', [kind::RETURN => 'void']],
            ],
            [
                [
                    'void|null', ['void', 'null'],
                    'void|string', ['void', 'string'],
                ], /* ———————————————————> */ self::EXPECT_EXCEPTION,
            ],
        ]);
    }

    public function testUnionsAll(): void
    {
        $this->assertDataset(__METHOD__, [
            [
                [
                    'int|string', ['int', 'string'],
                ], /* ———————————————————> */ 'string|int',
                ['5.6', null],
                ['8.0'],
            ],
            [
                [
                    'int|null|string',
                    '?int|string',
                    ['int', 'null', 'string'],
                    ['int|string', 'null'],
                    ['?int', 'string'],
                ], /* ———————————————————> */ 'string|int|null',
                ['5.6', null],
                ['8.0'],
            ],
        ]);
    }

    public function testUnionSorting(): void
    {
        $this->assertDataset(__METHOD__, [
            [
                [
                    'B|A|null|int|string',
                    '?B|A|int|string',
                    ['?B', 'int', 'string', 'A'],
                    ['B', 'null|A', 'A|int|string', 'B'],
                    ['string|B', 'null', 'A|int', 'B|A'],
                ], /* ———————————————————> */ 'A|B|string|int|null',
                ['5.6', null],
                ['8.0'],
            ],
            [
                [
                    '?\B|B|int|\C|string',
                    ['?B', 'int', 'string', '\C', '\B'],
                    ['\B', 'null|B', 'B|int|string|\C', '\B'],
                    ['string|\B', 'null', '\C|int', '\B|B'],
                ], /* ———————————————————> */ '\B|\C|B|string|int|null',
                ['5.6', null],
                ['8.0'],
            ],
            [
                [
                    'Class2|null|Class1|\MyNs\MySubNs\MyClass',
                    ['Class2|\MyNs\MySubNs\MyClass', '?Class1'],
                ], /* ———————————————————> */ '\MyNs\MySubNs\MyClass|Class1|Class2|null',
                ['5.6', null],
                ['7.2', ['?object', kind::PROP => null]],
                ['7.4', ['?object']],
                ['8.0'],
            ],
            [
                [
                    'array|false|true|float|bool|int|MyObject|object|A|\B|string|null|callable',
                    ['array', 'false', 'true', 'float', 'bool', 'int', 'MyObject', 'object', 'A', '\B', 'string', 'null', 'callable'],
                ], /* ———————————————————> */ 'callable|object|array|string|float|int|bool|null',
                ['5.6', null],
                ['8.0'],
            ],
        ]);
    }

    public function testRedundantAndDuplicate(): void
    {
        $this->assertDataset(__METHOD__, [
            [
                [
                    'mixed|A|B|callable|object|array|string|float|int|false|null',
                    ['int|mixed', 'A', 'B', 'callable', 'object', 'array', 'int|string', 'float', 'int', 'false', 'null'],
                    '?mixed|A|B|callable|object|array|string|float|int|false',
                ], /* ———————————————————> */ 'mixed',
                ['5.6', null],
                ['8.0'],
            ],
            [
                [
                    'INT|STRING|string|int',
                    ['INT', 'STRING', 'string', 'int'],
                    ['INT|STRING', 'string|int'],
                    ['INT|int', 'string|STRING'],
                ], /* ———————————————————> */ 'string|int',
                ['5.6', null],
                ['8.0'],
            ],
            [
                [
                    'object|A|B',
                    ['object', 'A', 'B'],
                    ['object|A', 'B'],
                    'OBJECT|A|B',
                    ['OBJECT', 'A', 'B'],
                    ['OBJECT|A', 'B'],
                ], /* ———————————————————> */ 'object',
                ['5.6', null],
                ['7.2', [kind::PROP => null]],
                ['7.4'],
            ],
            [
                [
                    'object|A|B|null',
                    '?object|X',
                    'object|A|?B',
                    ['object', 'A', 'B', 'null'],
                    ['object', 'A', 'NULL|B'],
                    ['?OBJECT', 'A', 'B'],
                    ['OBJECT', 'A', '?B'],
                    ['OBJECT|?A', 'B'],
                ], /* ———————————————————> */ '?object',
                ['5.6', null],
                ['7.2', [kind::PROP => null]],
                ['7.4'],
            ],
            [
                [
                    'iterable|array|Traversable',
                    'array|Traversable|iterable',
                    ['iterable', 'array', 'Traversable'],
                    ['array', 'Traversable', 'iterable'],
                    'iterable|array',
                    ['ITERABLE', 'ARRAY'],
                    ['ARRAY', 'ITERABLE'],
                    'ITERABLE|Traversable',
                    ['ITERABLE', 'Traversable'],
                    ['Traversable', 'ITERABLE'],
                ], /* ———————————————————> */ 'iterable',
                ['5.6', null],
                ['7.2', [kind::PROP => null]],
                ['7.4'],
            ],
            [
                [
                    '?array|iterable|Traversable|A|object|B|int|INT|int|true|bool|false|NULL|null',
                ], /* ———————————————————> */ 'iterable|object|int|bool|null',
                ['8.2'],
            ],
            [
                [
                    'MyClass|Myclass',
                    'object|MyClass|Myclass',
                    '?MyClass|Myclass',
                    'MyClass|null|Myclass',
                    ['MyClass', 'Myclass'],
                    ['MyClass', 'MyClass', 'myclass'],
                    ['MyClass|MyClass', 'myclass'],
                ], /* ———————————————————> */ self::EXPECT_EXCEPTION, // throw for any non-built-ins with different casing since we cannot decide which one to drop, but php still don't allows such duplicating
            ],
            // tru|bool and other bool-related are provided in a dedicated method
        ]);
    }

    public function testInvalidInput(): void
    {
        $this->assertDataset(__METHOD__, [
            [
                [
                    'A&B', '(A&B)', ['(A)', 'B'], '(A)|B', // we don't dupport intersections/DNF yet
                    ['A', '|', 'B'],
                    'My Class',
                    '123Class',
                    ['123', 'MyClass'],
                    '? MyClass',
                    '-', '+',
                    '&', '(', ')',
                    '?', ['?', 'array'],
                    '|', ['|', 'array'],
                    '||', ['||', 'array'],
                    'A|', ['A|', 'array'],
                    '\\', ['\\', 'array'],
                    '\|MyClass',
                    'A\\\B',
                    '\\\MyClass',
                    'MyClass\\',
                    'string |array', ['string ', 'array'],
                    'string?|array', ['string ', '?|array'],
                ], /* ———————————————————> */ self::EXPECT_EXCEPTION,
            ],
        ]);
    }
}
