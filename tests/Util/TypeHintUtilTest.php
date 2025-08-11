<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Util;

use Exception;
use InvalidArgumentException;
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

    public function dataProvider()
    {
        $cases = [
            [
                ['bool', 'false'], /* ———> */ 'bool',
                // Overridees ↓. Each version means "from this version up until newer version specified, but not including it"
                ['5.6', null],
                ['7.0', [kind::PROP => null, kind::CONST => null]], // kinds specified override the initial 'allVersions'   => ['allKinds' => 'bool']
                ['7.4', [kind::CONST => null]],
                ['8.2', [kind::CONST => null]],
                // we don't specify 8.3 here because 'allVersions' expands to it as well.
            ],
            [
                'int|null', /* ———> */ '?int',
                ['5.6', null],
                ['7.1',     self::EXPECT_EXCEPTION], // since MODE must be specified
                ['7.1', null, 'flag' => TypeHint::LEGACY_NULLABLE_OMIT_TYPE],
                ['7.1', 'int', 'flag' => TypeHint::LEGACY_NULLABLE_DROP_NULL],
            ],
        ];

        foreach ($cases as $caseIdx => $case) {
            $caseNum = $caseIdx + 1;
            $inputType = array_shift($case);

            // create string respresentation of the input type to show in test case names or errors
            $inputTypeString = $inputType;
            if (is_array($inputType)) {
                $inputTypeString = "['" . implode("', '") . "']";
            }

            $lastVersionData = array_shift($case);

            $versions = $case;

            // make sure all versions are specified in the ALL_VERS
            if (array_diff(array_map(fn($verData) => $verData[0], $versions), self::ALL_VERS)) {
                throw new Exception("Case #{$caseNum} ({$inputTypeString}) is incorrect: ");
            }

            // sort specified versions semver-aware
            usort($versions, fn($a, $b) => version_compare($b[0], $a[0]));

            // get the latest specified version
            $latestSpecifiedVer = $versions[array_key_last($versions)];
            
            // normalize data for the "last" (latest specified +1) version
            if (!is_array($lastVersionData)) {
                $lastVersionData = [
                    $lastVersionData, // expected value
                ];
            }
            $lastVersionData = [
                self::ALL_VERS[array_key_last(self::ALL_VERS)], // the next ver after the latest specified for this case
                ...$lastVersionData,
            ];

            // now pad the array with data for all versions starting from the lowest specified ending with the latest in ALL_VERS or 
            foreach ($versions as $versionData) {
                # code...
            }
        }
    }

    // test: early throws if input has any char except allowed, which are (rough!):
    // \ & ? | ( ) and \w+ with unicode except forbidden for identifiers, plus it cannot not start with digit

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

    private function throwIfVersionIsOlderThan5_6($arg): void
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

    public function testVoidUsedNotForReturnTypeThrows($arg): void
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


    public function testPropertyTypingRequires7_4()
    {
        assertThat(TypeHint::forPhpVer('int', '7.3', kind::PROP), equalTo(null));
        assertThat(TypeHint::forPhpVer('int', '7.4', kind::PROP), equalTo('int'));
    }

    public function testKindsSupportedOnlyIn8_3()
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