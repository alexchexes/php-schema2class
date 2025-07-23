<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Interface;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\RenameablePropertyInterface;
use Helmich\Schema2Class\Generator\SchemaToClass;
use Helmich\Schema2Class\Util\StringUtils;
use Laminas\Code\Generator\PropertyValueGenerator;

/**
 * Base implementation of {@see PropertyInterface} containing common logic for
 * property mapping and name handling.
 * Subclasses implement the actual mapping, type hints and validation logic for
 * a specific JSON Schema type.
 */
abstract class AbstractProperty implements PropertyInterface, RenameablePropertyInterface
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
        $this->capitalizedName  = StringUtils::safePascalCase($this->key);
        $this->generatorRequest = $generatorRequest;
        $this->description      = $schema['description'] ?? null;

        if ($generatorRequest->getOptions()->getPreservePropertyNames()) {
            $this->name = StringUtils::sanitizeIdentifier($key);
        } else {
            $this->name = StringUtils::safeCamelCase($key);
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

    public function setName(string $name): void
    {
        $this->name = $name;
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

    public function convertInputToType(string $inputVarName = 'input', bool $object = false): string
    {
        $name = $this->name;
        $key  = $this->key;
        $keyStr = var_export($key, true);
        // build the raw lookup expression (using the JSON key only inside the braces)
        if ($object) {
            $lookup = "\${$inputVarName}->{{$keyStr}}";
        } else {
            $lookup = "\${$inputVarName}[{$keyStr}]";
        }
        // now map from JSON→Type (this will call buildFromInput, etc.)
        $map = $this->generateInputMappingExpr($lookup);
        // assign to the *camelCased* local variable name
        return "\${$name} = {$map};";
    }

    public function convertTypeToArray(string $outputVarName = 'output'): string
    {
        $key    = $this->key;
        $keyStr = var_export($key, true);
        $map    = $this->generateOutputMappingExpr("\$this->{$this->name}");
        return "\${$outputVarName}[{$keyStr}] = {$map};";
    }

    public function convertTypeToStdClass(string $outputVarName = 'output'): string
    {
        $key    = $this->key;
        $keyStr = var_export($key, true);
        $map    = $this->generateOutputMappingExprStdClass("\$this->{$this->name}");
        return "\${$outputVarName}->{{$keyStr}} = {$map};";
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

    public function generateOutputMappingExprStdClass(string $expr): string
    {
        return $expr;
    }

    public function generateCloneExpr(string $expr): string
    {
        return $expr;
    }

    /**
     * Propagate root definitions from the current request to a new request.
     */
    protected function propagateRootDefinitions(GeneratorRequest $req): GeneratorRequest
    {
        $defs = $this->generatorRequest->getRootDefinitions();
        if ($defs === null) {
            $schema = $this->generatorRequest->getSchema();
            $defs = array_merge($schema['definitions'] ?? [], $schema['$defs'] ?? []);
        }

        if (count($defs) > 0) {
            return $req->withRootDefinitions($defs);
        }

        return $req;
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
