<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\ReferencedType;

use Helmich\Schema2Class\Generator\GeneratorRequest;

/**
 * Abstraction for a entity referenced via `$ref`.
 * 
 * Implementations represent referenced classes or enums (or unknown types as fallback)
 * and are produced by {@see ReferenceLookup} implementations.
 */
interface ReferencedTypeInterface
{
    function name(): string;
    function typeAnnotation(GeneratorRequest $req): string;
    function typeHint(GeneratorRequest $req): ?string;
    function serializedInputTypeHint(GeneratorRequest $req): ?string;
    function serializedTypeHint(GeneratorRequest $req): ?string;
    function serializedTypeHintStdClass(GeneratorRequest $req): ?string;
    function generateTypeAssertionExpr(GeneratorRequest $req, string $expr): string;
    function generateInputAssertionExpr(GeneratorRequest $req, string $expr): string;
    function generateInputMappingExpr(GeneratorRequest $req, string $expr): string;
    function generateOutputMappingExpr(GeneratorRequest $req, string $expr): string;
    function generateOutputMappingExprStdClass(GeneratorRequest $req, string $expr): string;
}