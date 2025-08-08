<?php

namespace Helmich\Schema2Class\Generator\Class;

class MethodNames
{
    public const FROM_INPUT = 'fromInput';
    public const TO_ARRAY = 'toArray';
    public const TO_STD_CLASS = 'toStdClass';
    public const VALIDATE_SELF = 'validate';
    public const VALIDATE_INPUT = 'validateInput';
    public const IS_PROVIDED = 'isOptionalProvided';

    public const ADDITIONAL_PROPERTIES = 'AdditionalProperties';
    public const ADDITIONAL_PROPERTY = 'AdditionalProperty';

    static public function all(): array
    {
        return [
            self::FROM_INPUT,
            self::TO_ARRAY,
            self::TO_STD_CLASS,
            self::VALIDATE_INPUT,
            self::IS_PROVIDED,
            self::ADDITIONAL_PROPERTIES,
            self::ADDITIONAL_PROPERTY,
        ];
    }
}
