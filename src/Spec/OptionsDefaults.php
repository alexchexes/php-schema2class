<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Spec;

use Helmich\Schema2Class\Generator\GeneratorRequest;

/**
 * Utility functions for working with SpecificationOptions.
 */
final class OptionsDefaults
{
    public static function applyDefaults(SpecificationOptions $options): SpecificationOptions
    {
        $opts = $options->toArray();

        $defaults = [
            'cleanTargetDirectory'              => false,
            'disableStrictTypes'                => false,
            'treatValuesWithDefaultAsOptional'  => false,
            'inlineAllofReferences'             => false,
            'targetPHPVersion'                  => GeneratorRequest::DEFAULT_PHP8_VERSION,
            'newValidatorClassExpr'             => 'new \JsonSchema\Validator()',
            'preservePropertyNames'             => false,
            'noGetters'                         => false,
            'noSetters'                         => false,
            'noSchemaMetadata'                  => false,
            'singleLineSchema'                  => false,
            'noEnums'                           => false,
        ];

        $all_keys = array_unique(array_merge(array_keys($opts), array_keys($defaults)));

        foreach ($all_keys as $key) {
            if (!isset($opts[$key])) {
                $opts[$key] = $defaults[$key];
            }
        }

        return SpecificationOptions::buildFromInput($opts);
    }

    /**
     * Merge global and file-specific options, with file options taking precedence.
     */
    /**
     * @param SpecificationOptions $base
     * @param object|null $override SpecificationOptions
     */
    public static function mergeOptions(SpecificationOptions $base, object|null $override): SpecificationOptions
    {
        if ($override === null) {
            return self::applyDefaults(clone $base);
        }

        $merged = array_merge($base->toArray(), $override->toArray());
        $opts = SpecificationOptions::buildFromInput($merged, validate: false);

        return self::applyDefaults($opts);
    }
}
