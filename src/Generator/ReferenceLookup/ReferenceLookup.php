<?php
namespace Helmich\Schema2Class\Generator\ReferenceLookup;

use Helmich\Schema2Class\Generator\ReferencedType\ReferencedType;

/**
 * Resolves JSON Schema `$ref` links during generation.
 *
 * Implement this interface and register the instance on a {@link GeneratorRequest}
 * when your schema references definitions outside of the current document.
 */
interface ReferenceLookup
{
    /**
     * Returns information about the PHP type referenced by the given `$ref` pointer.
     *
     * When the reference cannot be resolved a {@link ReferencedTypeUnknown}
     * instance should be returned.
     */
    public function lookupReference(string $ref): ReferencedType;
    
    /**
     * Returns the schema array referenced by the given `$ref` pointer.
     *
     * Implementations are free to return an empty array when no schema is
     * available for the given reference.
     *
     * @return array<mixed>
     */
    public function lookupSchema(string $ref): array;
}