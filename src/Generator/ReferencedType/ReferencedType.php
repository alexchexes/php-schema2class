<?php

namespace Helmich\Schema2Class\Generator\ReferencedType;

use Helmich\Schema2Class\Generator\GeneratorRequest;

/**
 * Abstraction for a schema entity referenced via `$ref` for which a dedicated PHP class or Enum exists.
 * 
 * Implementations are produced by {@see ReferenceLookup} implementations.
 */
interface ReferencedType
{
    function name(): string;
    function typeAnnotation(GeneratorRequest $req): string;
    function typeHint(GeneratorRequest $req): ?string;
    function serializedInputTypeHint(GeneratorRequest $req): ?string;
    function serializedTypeHint(GeneratorRequest $req): ?string;
    function serializedTypeHintStdClass(GeneratorRequest $req): ?string;
    function typeAssertionExpr(GeneratorRequest $req, string $expr): string;
    function inputAssertionExpr(GeneratorRequest $req, string $expr): string;
    function inputMappingExpr(GeneratorRequest $req, string $expr): string;
    function outputMappingExpr(GeneratorRequest $req, string $expr): string;
    function outputMappingExprStdClass(GeneratorRequest $req, string $expr): string;
}