<?php

namespace Helmich\Schema2Class\Generator\Class;

class PropertyNames
{
    public const ADDITIONAL_PROPS = '_additionalProperties';
    public const DEFAULTS = '_defaults';
    public const NAMES_MAP = '_namesMap';
    public const PROVIDED_OPTIONALS = '_providedOptionals';
    public const SCHEMA = '_schema';

    static public function all(): array
    {
        return [
            self::ADDITIONAL_PROPS,
            self::DEFAULTS,
            self::NAMES_MAP,
            self::PROVIDED_OPTIONALS,
            self::SCHEMA,
        ];
    }
}
