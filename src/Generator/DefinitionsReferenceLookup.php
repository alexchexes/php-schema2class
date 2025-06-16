<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\Definitions\Definition;
use Helmich\Schema2Class\Generator\ReferencedType;
use Helmich\Schema2Class\Generator\ReferencedTypeClass;
use Helmich\Schema2Class\Generator\ReferencedTypeUnknown;

class DefinitionsReferenceLookup implements ReferenceLookup
{
    /** @var array<string, Definition> */
    private array $definitions;

    /** @param array<string, Definition> $definitions */
    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

    public function lookupReference(string $reference): ReferencedType
    {
        if (isset($this->definitions[$reference])) {
            return new ReferencedTypeClass($this->definitions[$reference]->classFQN);
        }
        return new ReferencedTypeUnknown();
    }

    public function lookupSchema(string $reference): array
    {
        return $this->definitions[$reference]->schema ?? [];
    }
}
