<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator;

/** 
 * Interface describing common methods contract for
 * {@see Helmich\Schema2Class\Generator\Property\Type\PropertyInterface} and
 * {@see Helmich\Schema2Class\Generator\ReferencedType\ReferencedTypeInterface}
 */
interface TypeExpressionInterface
{
    /**
     * Returns a PHPDoc type annotation for the property.  Implementations return
     * simple primitive types, class names or literal unions depending on the schema.
     */
    public function typeAnnotation(): string;

    /**
     * Returns the PHP type hint that should be used for this property for a given target PHP version.
     * Returned `null` (literal, not `'null'`) means no type hint should be emitted.
     */
    public function typeHint(): ?string;

    /**
     * Generates a boolean expression that checks a runtime value already stored
     * in the property for being of the expected PHP type.
     * 
     * Used during generation of `toArray()`/`toStdClass()` and `__clone()` methods
     * 
     * @param string $expr  PHP expression usually containing the variable name
     *                      or accessor like `$this->property`
     */
    public function typeAssertionExpr(string $expr): string;
    
    /**
     * Generates a boolean expression that checks whether a raw input value can
     * be converted to this property type.
     */
    public function inputAssertionExpr(string $expr): string;
    
    /**
     * Generates an expression that maps an input value to the internal PHP value representation.
     *
     * @param string $expr     Expression returning the raw input value.
     * @param bool   $asserted  When `$asserted` is true the caller promises that `$expr` already satisfies
     *                          the input assertion (typically by `{@see inputAssertionExpr()}`) 
     *                          and the implementation may omit redundant checks
     */
    public function inputMappingExpr(string $expr, bool $asserted = false): string;
    
    /**
     * Generates an expression converting a typed property value into a value
     * that can be safely serialized – typically a scalar, array or nested mapping.
     * 
     * Used for generation of `toArray` method.
     */
    public function outputMappingExpr(string $expr): string;
    
    /**
     * Like {@see outputMappingExpr()} but returns a value compatible with
     * an \StdClass representation.
     * 
     * Used for generation of `toStdClass` method.
     */
    public function outputMappingExprStdClass(string $expr): string;
}