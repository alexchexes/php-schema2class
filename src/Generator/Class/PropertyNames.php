<?php

namespace Helmich\Schema2Class\Generator\Class;

class PropertyNames
{
    public const SCHEMA = '_schema';
    public const DEFAULTS = '_defaults';
    public const OPTIONALS = '_providedOptionals';

    static public function all(): array
    {
        return [
            self::SCHEMA,
            self::DEFAULTS,
            self::OPTIONALS,
        ];
    }
}
