<?php

declare(strict_types=1);

namespace Ns\EnumString_8_4;

enum MyClass: string {
    case EMPTY = '';
    case SPACE = ' ';
    case _3232 = '  ';
    case _33 = '!';
    case _3636 = '$$';
    case _1_ = '1';
    case _1_FOO = '1 Foo';
    case MINUS_1 = '-1';
    case _1FOO = '1Foo';
    case _1_FOO__2 = '1_Foo';
    case _1 = '~1';
    case BAR = 'Bar';
    case BAZ = 'Baz';
    case FOO = 'Foo';
    case FOO_2 = 'Foo 2';
    case _FOO = 'Foo!';
    case _FOO__2 = '-Foo';
    case FOO_BAR = 'Foo-Bar';
    case _FOO__3 = '~Foo';
    case FOO__3 = 'Foo~';
    case _969696 = '```';
    case _126 = '~';
    case IO = 'ё';
    case NI = '你';
    case EUR = '€';
    case MOSKVA = 'Москва';
}
