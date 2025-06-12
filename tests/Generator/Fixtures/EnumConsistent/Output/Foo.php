<?php

declare(strict_types=1);

namespace Ns\EnumConsistent;

enum Foo: string {
    case VALUE_FOO = 'Foo';
    case VALUE_1FOO = '1Foo';
}