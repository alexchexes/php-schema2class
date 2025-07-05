<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\Definitions\Definition;
use Helmich\Schema2Class\Generator\ReferencedType;
use Helmich\Schema2Class\Generator\ReferencedTypeClass;
use Helmich\Schema2Class\Generator\ReferencedTypeUnknown;
use Helmich\Schema2Class\Generator\ReferencedTypeEnum;

class DefinitionsReferenceLookup implements ReferenceLookup
{
    /** @var array<string, Definition> */
    private array $definitions;

    /** @param array<string, Definition> $definitions */
    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

    private function canonicalize(string $reference): string
    {
        $reference = rawurldecode($reference);

        if (isset($this->definitions[$reference])) {
            return $reference;
        }

        if (str_starts_with($reference, '#/definitions/')) {
            $alt = '#/$defs/' . substr($reference, strlen('#/definitions/'));
            if (isset($this->definitions[$alt])) {
                return $alt;
            }
        } elseif (str_starts_with($reference, '#/$defs/')) {
            $alt = '#/definitions/' . substr($reference, strlen('#/$defs/'));
            if (isset($this->definitions[$alt])) {
                return $alt;
            }
        }

        return $reference;
    }

    public function lookupReference(string $reference): ReferencedType
    {
        $reference = $this->canonicalize($reference);

        if (!isset($this->definitions[$reference])) {
            return new ReferencedTypeUnknown();
        }

        $def = $this->definitions[$reference];

        if (isset($def->schema['enum'])) {
            return new ReferencedTypeEnum($def->classFQN, $def->schema);
        }

        return new ReferencedTypeClass($def->classFQN);
    }

    public function lookupSchema(string $reference): array
    {
        $reference = $this->canonicalize($reference);

        return $this->definitions[$reference]->schema ?? [];
    }
}