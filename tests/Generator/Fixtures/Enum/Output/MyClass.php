<?php

declare(strict_types=1);

namespace Ns\Enum;

enum MyClass: string {
    case FOO = 'Foo';
    case BAR = 'Bar';
    case BAZ = 'Baz';
    case FOO_BAR = 'Foo-Bar';
}
