<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Property\Type;

use Helmich\Schema2Class\Generator\Class\Method\SerializeMethodFactory;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Util\StringUtils;
use Helmich\Schema2Class\Writer\WriterInterface;
use Laminas\Code\Generator\PropertyValueGenerator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Base implementation of {@see PropertyInterface} containing common logic for
 * property mapping and name handling.
 * Subclasses implement the actual mapping, type hints and validation logic for
 * a specific JSON Schema type.
 */
abstract class AbstractProperty implements PropertyInterface
{
    protected string $key;

    protected string $name;
    protected string $varName;
    protected string $methodName;
    protected string $nameForClass;

    protected array $schema;
    protected ?string $description;

    protected GeneratorRequest $request;

    public function __construct(string $key, array $schema, GeneratorRequest $request)
    {
        $this->schema           = $schema;
        $this->description      = $schema['description'] ?? null;
        $this->request = $request;

        $this->key = $key;

        if ($request->getOptions()->getPreservePropertyNames()) {
            $this->name = StringUtils::sanitizeIdentifier($key);
            $this->methodName = StringUtils::pascalCasePreserveOuterUnderscores($key);
        } else {
            $this->name = StringUtils::safeCamelCase($key);
            $this->methodName = StringUtils::safePascalCase($key);
        }

        $this->varName = $this->name;
        $this->nameForClass = StringUtils::safePascalCase($key);
    }

    public function isComplex(): bool
    {
        return false;
    }

    public function schema(): array
    {
        return $this->schema;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function key(): string
    {
        return $this->key;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function varName(): string
    {
        return $this->varName;
    }

    public function methodName(): string
    {
        return $this->methodName;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setVarName(string $name): void
    {
        $this->varName = $name;
    }

    public function setMethodName(string $name): void
    {
        $this->methodName = $name;
    }

    public function convertInputToType(string $inputVarName, string $optionalsVarName): string
    {
        $name = $this->name;
        $key  = $this->key;
        $keyStr = var_export($key, true);
        // build the raw lookup expression (using the JSON key only inside the braces)
        $accessor = "\${$inputVarName}->{{$keyStr}}";
        // now map from JSON→Type (this will call fromInput, etc.)
        $map = $this->inputMappingExpr($accessor);
        // assign to the *camelCased* local variable name
        return "\${$name} = {$map};";
    }

    public function convertTypeToArray(): string
    {
        $key    = $this->key;
        $keyStr = var_export($key, true);
        $map    = $this->outputMappingExpr("\$this->{$this->name}");
        $outputVarName = SerializeMethodFactory::OUTPUT_VAR_NAME;
        return "\${$outputVarName}[{$keyStr}] = {$map};";
    }

    public function convertTypeToStdClass(): string
    {
        $key    = $this->key;
        $keyStr = var_export($key, true);
        $map    = $this->outputMappingExprStdClass("\$this->{$this->name}");
        $outputVarName = SerializeMethodFactory::OUTPUT_VAR_NAME;
        return "\${$outputVarName}->{{$keyStr}} = {$map};";
    }

    public function inputAssertionExpr(string $expr): string
    {
        return $this->typeAssertionExpr($expr);
    }

    public function inputMappingExpr(string $expr, bool $asserted = false): string
    {
        return $expr;
    }

    public function outputMappingExpr(string $expr): string
    {
        return $expr;
    }

    public function outputMappingExprStdClass(string $expr): string
    {
        return $expr;
    }

    public function cloneExpr(string $expr): string
    {
        return $expr;
    }

    /**
     * @return string|null
     */
    public function cloneAssignment(): ?string
    {
        $name       = $this->name;
        $expr      = "\$this->{$name}";
        $exprClone = $this->cloneExpr($expr);

        if ($expr !== $exprClone) {
            return "\$this->$name = {$exprClone};";
        }

        return null;
    }

    /**
     * Propagate root definitions from the current request to a new request.
     */
    protected function propagateRootDefinitions(GeneratorRequest $req): GeneratorRequest
    {
        $defs = $this->request->getRootDefinitions();
        if ($defs === null) {
            $schema = $this->request->getSchema();
            $defs = array_merge($schema['definitions'] ?? [], $schema['$defs'] ?? []);
        }

        if (count($defs) > 0) {
            return $req->withRootDefinitions($defs);
        }

        return $req;
    }

    public function generateSubTypes(WriterInterface $writer, OutputInterface $output): void
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
