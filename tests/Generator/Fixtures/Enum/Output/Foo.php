<?php

declare(strict_types=1);

namespace Ns\Enum;

enum Foo: string {
    case FOO = 'Foo';
    case BAR = 'Bar';
    case BAZ = 'Baz';
    case FOO_BAR = 'Foo-Bar';
}
