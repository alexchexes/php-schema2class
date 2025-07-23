<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\ReferencedType;

use Helmich\Schema2Class\Generator\GeneratorRequest;

/**
 * Reference to another generated PHP class.
 *
 * It resolves type hints and annotations relative to the current namespace
 * so that the generated code can refer to generated classes correctly.
 */
readonly class ReferencedTypeClass implements ReferencedType
{
    public function __construct(private string $className)
    {
    }

    public function name(): string
    {
        return $this->className;
    }

    private function relativeName(GeneratorRequest $req): string
    {
        $ns = $req->getTargetNamespace();
        if ($ns !== '' && str_starts_with($this->className, $ns . '\\')) {
            return substr($this->className, strlen($ns) + 1);
        }

        return '\\' . $this->className;
    }

    public function typeAnnotation(GeneratorRequest $req): string
    {
        return $this->relativeName($req);
    }

    public function typeHint(GeneratorRequest $req): ?string
    {
        return $this->relativeName($req);
    }

    public function serializedInputTypeHint(GeneratorRequest $req): ?string
    {
        return 'array|object';
    }

    public function serializedTypeHint(GeneratorRequest $req): ?string
    {
        return 'array';
    }

    public function serializedTypeHintStdClass(GeneratorRequest $req): ?string
    {
        return 'object';
    }

    public function typeAssertionExpr(GeneratorRequest $req, string $expr): string
    {
        $cls = $this->relativeName($req);
        return "({$expr}) instanceof {$cls}";
    }

    public function inputAssertionExpr(GeneratorRequest $req, string $expr): string
    {
        $cls = $this->relativeName($req);
        return "{$cls}::validateInput({$expr}, true)";
    }

    public function inputMappingExpr(GeneratorRequest $req, string $expr): string
    {
        $validateArg = $req->getCurrValidateArgAlias();
        $materializeArg = $req->getCurrMaterializeArgAlias();

        $args = [$expr, '$' . $validateArg];
        if ($materializeArg !== null) {
            $args[] = '$' . $materializeArg;
        }
        $argsStr = implode(', ', $args);
        
        $cls = $this->relativeName($req);
        return "{$cls}::buildFromInput({$argsStr})";
    }

    public function outputMappingExpr(GeneratorRequest $req, string $expr): string
    {
        $inclDefaultsArg = $req->getCurrReqHasDefaults() ? '$includeDefaults' : '';
        return "{$expr}->toArray({$inclDefaultsArg})";
    }

    public function outputMappingExprStdClass(GeneratorRequest $req, string $expr): string
    {
        $inclDefaultsArg = $req->getCurrReqHasDefaults() ? '$includeDefaults' : '';
        return "{$expr}->toStdClass({$inclDefaultsArg})";
    }
}
