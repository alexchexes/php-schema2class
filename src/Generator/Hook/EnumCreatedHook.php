<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Hook;

use Helmich\Schema2Class\Generator\Enum\EnumGenerator;

/**
 * Interface definition for hooks that are called when an enumeration is created.
 */
interface EnumCreatedHook
{
    function onEnumCreated(string $enumName, EnumGenerator $enum): void;
}