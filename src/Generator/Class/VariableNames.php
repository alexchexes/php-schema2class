<?php

namespace Helmich\Schema2Class\Generator\Class;

class VariableNames
{
    public const ADDITIONAL_PROPS = PropertyNames::ADDITIONAL_PROPS;
    public const CLONE = 'clone';
    public const OBJ = 'obj';
    public const OUTPUT = 'output';
    public const PROVIDED_OPTIONALS = PropertyNames::PROVIDED_OPTIONALS;

    static public function all(): array
    {
        return [            
            self::ADDITIONAL_PROPS,
            self::CLONE,
            self::OBJ,
            self::OUTPUT,
            self::PROVIDED_OPTIONALS,
        ];
    }
}
