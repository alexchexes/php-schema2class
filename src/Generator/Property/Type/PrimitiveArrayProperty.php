<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Util\SchemaKeywords;
use Helmich\Schema2Class\Util\SchemaUtils;
use Helmich\Schema2Class\Writer\WriterInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Represents an array of primitive values or an associative array described via "additionalProperties".
 */
class PrimitiveArrayProperty extends AbstractProperty
{
    private bool $isAssociativeArray;

    public static function canHandleSchema(array $schema): bool
    {
        $isAssociativeArray = isset($schema["additionalProperties"]) && is_array($schema["additionalProperties"]);
        $isArray = isset($schema["type"]) && $schema["type"] === "array";

        if (!$isArray && !$isAssociativeArray) {
            return false;
        }

        return !ObjectArrayProperty::canHandleSchema($schema);
    }

    public function __construct(string $key, array $schema, GeneratorRequest $generatorRequest)
    {
        parent::__construct($key, $schema, $generatorRequest);

        $this->isAssociativeArray = isset($schema["additionalProperties"]) && is_array($schema["additionalProperties"]);
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

        if (isset($this->schema["additionalProperties"]) && is_array($this->schema["additionalProperties"])) {
            $annot = SchemaUtils::extractTypeForAnnotation($this->schema["additionalProperties"]);
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
        if (SchemaKeywords::has($this->schema, SchemaKeywords::ARRAY_VALIDATION)) {
            return true;
        }
        $hasItems = isset($this->schema['items']) && is_array($this->schema['items']) && count($this->schema['items']) > 0;
        $hasAdditional = isset($this->schema['additionalProperties']) && is_array($this->schema['additionalProperties']) && count($this->schema['additionalProperties']) > 0;
        return $hasItems || $hasAdditional;
    }
}
