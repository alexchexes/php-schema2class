<?php

declare(strict_types=1);

namespace Ns\EnumString_8_4;

enum MyClass: string {
    case VALUE_FOO = 'Foo';
    case VALUE_BAR = 'Bar';
    case VALUE_BAZ = 'Baz';
    case VALUE_FOO_BAR = 'Foo-Bar';
    case VALUE_FOO__2 = 'Foo';
    case VALUE__FOO = '-Foo';
    case VALUE__FOO__2 = '~Foo';
    case VALUE_FOO_ = 'Foo!';
    case VALUE_FOO___2 = 'Foo~';
    case VALUE_1FOO = '1Foo';
    case VALUE_1_FOO = '1_Foo';
    case VALUE_1 = '1';
    case VALUE__1 = '-1';
    case VALUE__1__2 = '~1';
}
