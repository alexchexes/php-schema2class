<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\Generator\PropertyValueGenerator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Contract implemented by all property types that can generate code for a schema field.
 *
 * Implementations know how to convert between PHP values and serialized JSON data.
 * They are produced via {@see \Helmich\Schema2Class\Generator\PropertyBuilder}.
 */
interface PropertyInterface
{
    /**
     * Returns whether this property class can handle the given schema fragment.
     *
     * The {@see PropertyBuilder} iterates over all implementations and the first one that
     * returns `true` will be used.
     */
    public static function canHandleSchema(array $schema): bool;

    /**
     * Returns the raw JSON Schema snippet from which this property was built.
     * 
     * The information is used when generating nested types or performing validations.
     */
    public function schema(): array;

    /**
     * Original name of the property inside the JSON document.  This key is used
     * when reading values from an input array or when serialising back to JSON.
     */
    public function key(): string;

    /**
     * Returns the PHP property name used in the generated class. This can be
     * sanitized {@see key()} in camelCase if `preservePropertyNames` generator
     * option is true, or, if false, it can be identical to `key` except cases when
     * sanitization changed it.
     */
    public function name(): string;

    /** 
     * Sets property name used for generating class property.
     * Allows renaming property when collisions detected.
     */
    public function setName(string $name): void;

    /** 
     * Gets the string from "description" field in the schema
     */
    public function description(): ?string;

    /**
     * Indicates whether the property maps to a complex value (object, collection, enum)
     * rather than a simple scalar. Complex properties may trigger generation
     * of additional classes and require deep cloning.
     */
    public function isComplex(): bool;

    /**
     * Generates a code snippet that reads the value for this property from the
     * provided input variable and assigns the correctly typed local variable.
     *
     * @param string $inputVarName Name of the input variable used internally
     *                             in a generated `buildFromFromInput()` method.
     */
    public function convertInputToType(string $inputVarName, string $optionalsVarName): string;

    /**
     * Generates a statement that writes this property to an output array in
     * a generated `toArray()` method.
     */
    public function convertTypeToArray(): string;

    /**
     * Like {@see convertTypeToArray()} but writes to a \stdClass instance for
     * the `toStdClass()` helper of the generated class.
     */
    public function convertTypeToStdClass(): string;

    /**
     * Generates additional PHP classes or enums required by this property.
     *
     * For example nested object definitions or union arms may be emitted as
     * separate classes via a nested invocation of {@see SchemaToClass}.
     */
    public function generateSubTypes(WriterInterface $writer, OutputInterface $output): void;

    /**
     * Returns a PHPDoc type annotation for the property.  Implementations return
     * simple primitive types, class names or literal unions depending on the schema.
     */
    public function typeAnnotation(): string;

    /**
     * Returns the PHP type hint that should be used for this property for a given target PHP version.
     * Returned `null` means no type hint should be emitted.
     */
    public function typeHint(string $phpVersion): ?string;

    /**
     * Generates a boolean expression that checks a runtime value already stored
     * in the property for being of the expected PHP type.
     * 
     * Used during generation of `toArray()`/`toStdClass()` and `__clone()` methods
     * 
     * @param string $expr  PHP expression usually containing the variable name
     *                      or accessor like `$this->property`
     */
    public function generateTypeAssertionExpr(string $expr): string;

    /**
     * Generates a boolean expression that checks whether a raw input value can
     * be converted to this property type.
     */
    public function generateInputAssertionExpr(string $expr): string;

    /**
     * Generates an expression that maps an input value to the internal PHP value representation.
     *
     * @param string $expr     Expression returning the raw input value.
     * @param bool   $asserted  When `$asserted` is true the caller promises that `$expr` already satisfies
     *                          the input assertion (typically by `{@see generateInputAssertionExpr()}`) 
     *                          and the implementation may omit redundant checks
     */
    public function generateInputMappingExpr(string $expr, bool $asserted = false): string;

    /**
     * Generates an expression converting a typed property value into a value
     * that can be safely serialized – typically a scalar, array or nested mapping.
     * 
     * Used for generation of `toArray` method.
     */
    public function generateOutputMappingExpr(string $expr): string;

    /**
     * Like {@see generateOutputMappingExpr()} but returns a value compatible with
     * an \StdClass representation.
     * 
     * Used for generation of `toStdClass` method.
     */
    public function generateOutputMappingExprStdClass(string $expr): string;

    /**
     * Generates an expression for cloning this property when cloning the containing object.
     * Complex properties may need deep clones while primitive ones can simply return the
     * original expression.
     * 
     * Used for generation of `__clone` method.
     */
    public function generateCloneExpr(string $expr): string;

    /**
     * Provides a full cloning statement used inside the generated `__clone`
     * method or `null` when cloning the property requires no special handling.
     */
    public function cloneProperty(): ?string;

    /** 
     * Formats a literal "default" value for inclusion in generated code.
     * The returned {@see PropertyValueGenerator} knows how to render the PHP
     * expression for that value when emitting default values in the generated class.
     */
    public function formatValue(mixed $value): PropertyValueGenerator;

    /**
     * Indicates whether the schema allows `null` as a valid value for this property.
     */
    public function allowsNull(): bool;
}
