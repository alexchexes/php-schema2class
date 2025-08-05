<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\ReferenceLookup;

use Helmich\Schema2Class\Generator\Definition\Definition;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeInterface;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeClass;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeUnknown;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeEnum;

/**
 * Resolves "$ref" pointers that target local definitions (`#/definitions` and `#/$defs`)
 * collected from a schema by {@see DefinitionsCollector}
 *
 * Instances of this class are attached to a {@see GeneratorRequest}, and the `lookupReference`
 * returns {@see ReferencedType} objects describing the target class or enum so that
 * property builders can correctly embed object and enum definitions while building nested classes.
 */
class DefinitionsReferenceLookup implements ReferenceLookup
{
    /**
     * @param array<string,Definition> $definitions
     */
    public function __construct(
        private array $definitions,
    ) {}

    private function canonicalize(string $ref): string
    {
        $ref = rawurldecode($ref);

        if (isset($this->definitions[$ref])) {
            return $ref;
        }

        if (str_starts_with($ref, '#/definitions/')) {
            $alt = '#/$defs/' . substr($ref, strlen('#/definitions/'));
            if (isset($this->definitions[$alt])) {
                return $alt;
            }
        } elseif (str_starts_with($ref, '#/$defs/')) {
            $alt = '#/definitions/' . substr($ref, strlen('#/$defs/'));
            if (isset($this->definitions[$alt])) {
                return $alt;
            }
        }

        return $ref;
    }

    public function lookupReference(string $ref, GeneratorRequest $currentRequest): ReferencedTypeInterface
    {
        $ref = $this->canonicalize($ref);

        if (!isset($this->definitions[$ref])) {
            return new ReferencedTypeUnknown($currentRequest);
        }

        $def = $this->definitions[$ref];

        if (isset($def->schema['enum'])) {
            return new ReferencedTypeEnum($def->classFQN, $def->schema, $currentRequest);
        }

        return new ReferencedTypeClass($def->classFQN, $currentRequest);
    }

    public function lookupSchema(string $ref): array
    {
        $ref = $this->canonicalize($ref);

        return $this->definitions[$ref]->schema ?? [];
    }
}