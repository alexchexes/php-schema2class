<?php

namespace Helmich\Schema2Class\Generator\Hook;

use Helmich\Schema2Class\Generator\PhpParserEnumGenerator;

/**
 * Interface definition for hooks that are called when an enumeration is created.
 */
interface EnumCreatedHook
{
    function onEnumCreated(string $enumName, PhpParserEnumGenerator $enum): void;
}