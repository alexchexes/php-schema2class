<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\ReferenceLookup;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeInterface;

/**
 * Resolves `$ref` pointers encountered during generation.
 * 
 * Implementations are used by {@see GeneratorRequest} to map a reference string
 * to either a concrete class or enum (or an unknown type as a fallback).
 *
 * To customize resolution behavior, user can implement this interface
 * and register the instance on a {@link GeneratorRequest}.
 */
interface ReferenceLookup
{
    /**
     * Returns information about the PHP type referenced by the given `$ref` pointer.
     *
     * The current {@see GeneratorRequest} is provided so implementations can
     * respect options such as the target namespace or PHP version. When the
     * reference cannot be resolved a {@link ReferencedTypeUnknown} instance
     * should be returned.
     */
    public function lookupReference(string $ref, GeneratorRequest $currentRequest): ReferencedTypeInterface;
    
    /**
     * Returns the schema array referenced by the given `$ref` pointer.
     *
     * Implementations can return an empty array when no schema is available for the given reference.
     */
    public function lookupSchema(string $ref): array;
}
