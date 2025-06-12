<?php

declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\SchemaToClass;
use Helmich\Schema2Class\Util\StringUtils;
use Laminas\Code\Generator\PropertyValueGenerator;

abstract class AbstractProperty implements PropertyInterface
{
    protected string $key;

    protected string $name;

    protected array $schema;

    protected string $capitalizedName;

    protected ?string $description;

    protected GeneratorRequest $generatorRequest;

    public function __construct(string $key, array $schema, GeneratorRequest $generatorRequest)
    {
        $this->key              = $key;
        $this->schema           = $schema;
        $this->capitalizedName  = StringUtils::pascalCase($this->key);
        $this->generatorRequest = $generatorRequest;
        $this->description      = $schema['description'] ?? null;

        if ($generatorRequest->getOptions()->getPreservePropertyNames()) {
            $this->name = StringUtils::sanitizeIdentifier($key);
        } else {
            $this->name = StringUtils::camelCase($key);
        }
    }

    public function isComplex(): bool
    {
        return false;
    }

    public function schema(): array
    {
        return $this->schema;
    }

    public function key(): string
    {
        return $this->key;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function cloneProperty(): ?string
    {
        $name       = $this->name;
        $expr      = "\$this->{$name}";
        $exprClone = $this->generateCloneExpr($expr);

        if ($expr !== $exprClone) {
            return "\$this->$name = {$exprClone};";
        }

        return null;
    }

    public function convertJSONToType(string $inputVarName = 'input', bool $object = false): string
    {
        $name = $this->name;
        $key  = $this->key;
        $keyS = var_export($key, true);
        // build the raw lookup expression (using the JSON key only inside the braces)
        if ($object) {
            $lookup = "\${$inputVarName}->{{$keyS}}";
        } else {
            $lookup = "\${$inputVarName}[{$keyS}]";
        }
        // now map from JSON→Type (this will call buildFromInput, etc.)
        $map = $this->generateInputMappingExpr($lookup);
        // assign to the *camelCased* local variable name
        return "\${$name} = {$map};";
    }

    public function convertTypeToJSON(string $outputVarName = 'output'): string
    {
        $key    = $this->key;
        $keyStr = var_export($key, true);
        $map    = $this->generateOutputMappingExpr("\$this->{$this->name}");
        return "\${$outputVarName}[{$keyStr}] = {$map};";
    }

    public function generateInputAssertionExpr(string $expr): string
    {
        return $this->generateTypeAssertionExpr($expr);
    }

    public function generateInputMappingExpr(string $expr, bool $asserted = false): string
    {
        return $expr;
    }

    public function generateOutputMappingExpr(string $expr): string
    {
        return $expr;
    }

    public function generateCloneExpr(string $expr): string
    {
        return $expr;
    }

    public function generateSubTypes(SchemaToClass $generator): void
    {
    }

    public function formatValue(mixed $value): PropertyValueGenerator
    {
        return new PropertyValueGenerator($value);
    }

    public function allowsNull(): bool
    {
        // Fast: enumeration contains null
        if (isset($this->schema['enum']) && in_array(null, $this->schema['enum'], true)) {
            return true;
        }

        // Common cases
        if (($this->schema['type'] ?? null) === 'null') {
            return true;
        }
        if (is_array($this->schema['type'] ?? null) && in_array('null', $this->schema['type'], true)) {
            return true;
        }

        // anyOf / oneOf with a null arm
        foreach (['anyOf', 'oneOf'] as $k) {
            if (!isset($this->schema[$k]) || !is_array($this->schema[$k])) {
                continue;
            }
            foreach ($this->schema[$k] as $sub) {
                if (($sub['type'] ?? null) === 'null') {
                    return true;
                }
            }
        }
        
        return false;
    }

}
