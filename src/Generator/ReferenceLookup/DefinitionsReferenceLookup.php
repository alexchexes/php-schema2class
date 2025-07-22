<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\ReferenceLookup;

use Helmich\Schema2Class\Generator\Definitions\Definition;
use Helmich\Schema2Class\Generator\ReferencedType\ReferencedType;
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
    /** @var array<string, Definition> */
    private array $definitions;

    /** @param array<string, Definition> $definitions */
    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

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

    public function lookupReference(string $ref): ReferencedType
    {
        $ref = $this->canonicalize($ref);

        if (!isset($this->definitions[$ref])) {
            return new ReferencedTypeUnknown();
        }

        $def = $this->definitions[$ref];

        if (isset($def->schema['enum'])) {
            return new ReferencedTypeEnum($def->classFQN, $def->schema);
        }

        return new ReferencedTypeClass($def->classFQN);
    }

    public function lookupSchema(string $ref): array
    {
        $ref = $this->canonicalize($ref);

        return $this->definitions[$ref]->schema ?? [];
    }
}