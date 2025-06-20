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
        $opts = clone $options;

        if ($opts->getCleanTargetDirectory() === null) {
            $opts = $opts->withCleanTargetDirectory(false);
        }
        if ($opts->getDisableStrictTypes() === null) {
            $opts = $opts->withDisableStrictTypes(false);
        }
        if ($opts->getTreatValuesWithDefaultAsOptional() === null) {
            $opts = $opts->withTreatValuesWithDefaultAsOptional(false);
        }
        if ($opts->getInlineAllofReferences() === null) {
            $opts = $opts->withInlineAllofReferences(false);
        }
        if ($opts->getTargetPHPVersion() === null) {
            $opts = $opts->withTargetPHPVersion(GeneratorRequest::DEFAULT_PHP8_VERSION);
        }
        if ($opts->getNewValidatorClassExpr() === null) {
            $opts = $opts->withNewValidatorClassExpr('new \\JsonSchema\\Validator()');
        }
        if ($opts->getPreservePropertyNames() === null) {
            $opts = $opts->withPreservePropertyNames(false);
        }
        if ($opts->getNoGetters() === null) {
            $opts = $opts->withNoGetters(false);
        }
        if ($opts->getNoSetters() === null) {
            $opts = $opts->withNoSetters(false);
        }
        if ($opts->getNoDescriptionsInSchema() === null) {
            $opts = $opts->withNoDescriptionsInSchema(false);
        }
        if ($opts->getSingleLineSchema() === null) {
            $opts = $opts->withSingleLineSchema(false);
        }
        if ($opts->getNoEnums() === null) {
            $opts = $opts->withNoEnums(false);
        }

        return $opts;
    }
}
