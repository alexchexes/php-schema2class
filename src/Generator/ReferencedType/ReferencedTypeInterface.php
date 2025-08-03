<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\ReferencedType;

use Helmich\Schema2Class\Generator\TypeExpressionInterface;

/**
 * Abstraction for a entity referenced via `$ref`.
 * 
 * Implementations represent referenced classes or enums (or unknown types as fallback)
 * and are produced by {@see ReferenceLookup} implementations.
 */
interface ReferencedTypeInterface extends TypeExpressionInterface
{
    function name(): string;

    function serializedInputTypeHint(): ?string;
    function serializedTypeHint(): ?string;
    function serializedTypeHintStdClass(): ?string;
}