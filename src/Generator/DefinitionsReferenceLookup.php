<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

use Helmich\Schema2Class\Generator\ReferencedTypeClass;

class DefinitionsReferenceLookup implements ReferenceLookup
{
    /** @var array<string,array> */
    private array $definitions;

    /** @param array<string,array> $definitions */
    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

    public function lookupReference(string $reference): ReferencedType
    {
        $name = $this->extractName($reference);
        if ($name !== null && isset($this->definitions[$name])) {
            return new ReferencedTypeClass($name);
        }
        return new ReferencedTypeUnknown();
    }

    public function lookupSchema(string $reference): array
    {
        $name = $this->extractName($reference);
        return $name !== null && isset($this->definitions[$name])
            ? $this->definitions[$name]
            : [];
    }

    private function extractName(string $ref): ?string
    {
        // only support local #/definitions/Name
        if (preg_match('/^#\/definitions\/([^\/]+)$/', $ref, $m)) {
            return $m[1];
        }
        return null;
    }
}
