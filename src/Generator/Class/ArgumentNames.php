<?php

namespace Helmich\Schema2Class\Generator\Class;

class ArgumentNames
{
    public const ADDITIONAL_PROPS = 'additionalProperties';
    public const AS_ARRAY = 'asArray';
    public const INCL_DEFAULTS = 'includeDefaults';
    public const INPUT = 'input';
    public const MATRLZ_DEFAULTS = 'materializeDefaults';
    public const PROPERTY_NAME = 'propertyName';
    public const RETURN = 'return';
    public const VALIDATE = 'validate';

    static public function all(): array
    {
        return [
            self::ADDITIONAL_PROPS,
            self::AS_ARRAY,
            self::INCL_DEFAULTS,
            self::INPUT,
            self::MATRLZ_DEFAULTS,
            self::PROPERTY_NAME,
            self::RETURN,
            self::VALIDATE,
        ];
    }
}
