<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\TypeExpressionInterface;
use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\Generator\PropertyValueGenerator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Contract implemented by all property types that can generate code for a schema field.
 *
 * Implementations know how to convert between PHP values and serialized JSON data.
 * They are produced via {@see \Helmich\Schema2Class\Generator\PropertyBuilder}.
 */
interface PropertyInterface extends TypeExpressionInterface
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
     * {@see key} after `var_export` - suitable to use as PHP string
     */
    public function keyStr(): string;

    /**
     * Returns name used for the generated class property name. This can be
     * sanitized {@see key()} in camelCase if `preservePropertyNames` generator
     * option is true, or, if false, it can be identical to `key` except when
     * sanitization or uniqueness check changed it.
     */
    public function propName(): string;

    /** 
     * Name used for creating temporary variable names inside `fromInput` and other methods
     */
    public function varName(): string;

    /** 
     * Base name used for creating methods for this property.
     * Basically a *suffix* for all methods: get/set/unset/with/without.
     * There must be special treatment of names starting with "out" to avoid
     * collisions in case schema has, for example, "bound" and "outbound" properties,
     * and the generated methods SHOULD NOT be like:
     * withBound / withoutBound / withOutbound / withoutOutbound
     */
    public function methodName(): string;

    /** 
     * Sets name used for generating class property.
     * Allows renaming property when collisions detected.
     */
    public function setName(string $name): void;

    /** 
     * Sets name used for creating temporary variable names inside `fromInput` method
     */
    public function setVarName(string $name): void;

    /** 
     * Sets base name used for creating methods for this property.
     * (a suffix for all methods: get/set/unset/with/without).
     */
    public function setMethodName(string $name): void;

    /** 
     * Gets the string from "description" field in the schema
     */
    public function description(): ?string;

    /**
     * Determines whether setter methods should perform schema-based validation
     * for values of this property.
     *
     * Implementations must take the target PHP version and schema constraints
     * into account: validation should only be required when PHP's type system
     * cannot fully guard the property or when additional restrictions (like
     * numeric ranges, string patterns, enum values, …) are present.
     */
    public function needsValidation(): bool;

    /**
     * Generates a code snippet that reads the value for this property from the
     * input variable and assigns the correctly typed local variable.
     */
    public function convertInputToType(): string;

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
     * Generates an expression for cloning this property when cloning the containing object.
     * Complex properties may need deep clones while primitive ones can simply return the
     * original expression.
     * 
     * Used for generation of `__clone` method.
     */
    public function cloneExpr(string $expr): string;

    /**
     * Provides a full cloning statement used inside the generated `__clone`
     * method or `null` when cloning the property requires no special handling.
     * Returned string usually looks like `$this->prop = <cloneExpr()>`
     */
    public function cloneAssignment(): ?string;

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
