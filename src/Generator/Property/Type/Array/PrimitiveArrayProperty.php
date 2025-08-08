<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type\Array;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Type\AbstractProperty;
use Helmich\Schema2Class\Util\SchemaKeywords;
use Helmich\Schema2Class\Util\SchemaUtils;
use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Represents an array of primitive values or an associative array described via "additionalProperties".
 * 
 * TODO: exctract 'additionalProperties' case into its own property type to simplify things, esp 'needsValidation' method
 */
class PrimitiveArrayProperty extends AbstractProperty
{
    private bool $isAssociativeArray;

    public static function canHandleSchema(array $schema): bool
    {
        $isAssociativeArray = self::allowsAdditionalProperties($schema);
        $isArray = isset($schema["items"]) || (isset($schema["type"]) && $schema["type"] === "array");
        return $isArray || $isAssociativeArray;
    }

    public function __construct(string $key, array $schema, GeneratorRequest $generatorRequest)
    {
        parent::__construct($key, $schema, $generatorRequest);
        $this->isAssociativeArray = self::allowsAdditionalProperties($schema);
    }

    private static function allowsAdditionalProperties($schema): bool
    {
        $additionalProperties = $schema["additionalProperties"] ?? null;
        return is_array($additionalProperties) || $additionalProperties === true;
    }

    public function generateSubTypes(WriterInterface $writer, OutputInterface $output): void
    {
    }

    public function typeAnnotation(): string
    {
        if (isset($this->schema["items"])) {
            $annot = SchemaUtils::extractTypeForAnnotation($this->schema["items"]);
            return $annot . "[]";
        }

        if (
            isset($this->schema["additionalProperties"])
            && (is_array($this->schema["additionalProperties"]) || $this->schema["additionalProperties"] === true)
        ) {
            if (is_array($this->schema["additionalProperties"])) {
                $annot = SchemaUtils::extractTypeForAnnotation($this->schema["additionalProperties"]);
            } elseif ($this->schema["additionalProperties"] === true) {
                $annot = 'mixed';
            }
            return $annot . "[]";
        }

        return "array";
    }

    public function typeHint(): ?string
    {
        return "array";
    }

    public function typeAssertionExpr(string $expr): string
    {
        return "is_array({$expr})";
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        if ($this->isAssociativeArray) {
            return "(array){$expr}";
        }
        return parent::inputMappingExpr($expr, $asserted);
    }

    public function needsValidation(): bool
    {
        // array-level constraints
        if (SchemaKeywords::hasAny($this->schema, SchemaKeywords::ARRAY_VALIDATION)) {
            return true;
        }

        // typed items
        if (isset($this->schema['items']) && is_array($this->schema['items'])) {
            $items = $this->schema['items'];
            if (
                isset($items['type'])
                || SchemaKeywords::hasAny($items, SchemaKeywords::STRING_VALIDATION)
                || SchemaKeywords::hasAny($items, SchemaKeywords::NUMERIC_VALIDATION)
                || SchemaKeywords::hasAny($items, SchemaKeywords::BOOLEAN_VALIDATION)
            ) {
                return true;
            }
        }
        
        if ($this->isAssociativeArray) {
            // `true` means "no restrictions"
            if ($this->schema['additionalProperties'] === true) {
                return false;
            }

            // if we're here, 'additionalProperties' is array.
            // "items" may set restrictions, but may only contain annotations
            $items = $this->schema['additionalProperties'];
            if (
                isset($items['type'])
                || SchemaKeywords::hasAny($items, SchemaKeywords::STRING_VALIDATION)
                || SchemaKeywords::hasAny($items, SchemaKeywords::NUMERIC_VALIDATION)
                || SchemaKeywords::hasAny($items, SchemaKeywords::BOOLEAN_VALIDATION)
            ) {
                return true;
            }
        }
                
        return false;
    }
}
