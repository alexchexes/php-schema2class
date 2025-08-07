<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Definition;

/**
 * Metadata about a definition collected from `#/definitions` or `#/$defs`.
 *
 * Instances of this class are created by {@see DefinitionsCollector} while
 * scanning a schema for embedded type definitions. They are later consumed by
 * {@see DefinitionsGenerator} to generate the PHP classes for those nested
 * definitions.
 */
readonly class Definition
{
    public function __construct(
        public string $namespace,
        public string $directory,
        public string $classFQN,
        public string $className,
        public array $schema,
    ) {
    }
}
