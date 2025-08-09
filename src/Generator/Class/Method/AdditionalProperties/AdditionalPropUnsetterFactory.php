<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method\AdditionalProperties;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\Class\VariableNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Util\SchemaUtils;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class AdditionalPropUnsetterFactory
{
    private bool $mutating;
    private bool $chainable;

    public function __construct(
        private GeneratorRequest $request,
        private array $schema,
    )
    {
        $mutableConfig = $this->request->getMutableSetters();
        $this->mutating = $mutableConfig !== null;
        $this->chainable = $mutableConfig === 'chainable' || $this->mutating === false;
    }

    public function generate(): MethodGenerator
    {
        $addValidation = false;
        if (SchemaUtils::needsRevalidationOnPropertyChange($this->schema, true, false)) {
            $addValidation = true;
        }

        $prefix = $this->mutating ? 'unset' : 'without';

        $methodGen = new MethodGenerator(
            name: $prefix . MethodNames::ADDITIONAL_PROPERTIES,
            parameters: $this->buildParams($addValidation),
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $this->buildBody($addValidation),
            docBlock: $this->buildDocBlock($addValidation),
        );

        if ($this->chainable && $this->request->isAtLeastPHP('7.0')) {
            $methodGen->setReturnType('self');
        } elseif (!$this->chainable) {
            if ($this->request->isAtLeastPHP('7.1')) {
                $methodGen->setReturnType('void');
            }
        }

        return $methodGen;
    }

    /** 
     * @return ParameterGenerator[]
     */
    private function buildParams(bool $addValidation): array
    {
        $params = [];
        
        if ($addValidation) {
            $params[] = new ParameterGenerator(
                name: ArgumentNames::VALIDATE,
                type: $this->request->isAtLeastPHP('7.0') ? 'bool' : null,
                defaultValue: true,
            );
        }

        return $params;
    }

    private function buildDocBlock(bool $addValidation): DocBlockGenerator
    {
        $tags = [];

        if ($addValidation) {
            $tags[] = new ParamTag(
                variableName: ArgumentNames::VALIDATE,
                types: ['bool'],
                description: 'Whether to revalidate the resulting object.',
            );
        }

        if ($this->chainable && !$this->request->isAtLeastPHP('7.0')) {
            $tags[] = new ReturnTag('self');
        }

        $docBlock = new DocBlockGenerator(
            shortDescription: "Removes all extra properties not specified in the schema.",
            tags: $tags,
        );

        $docBlock->setWordWrap(false);

        return $docBlock;
    }

    private function buildBody(bool $addValidation): string
    {
        $ADDITIONAL_PROPS = PropertyNames::ADDITIONAL_PROPS;
        $VALIDATE_SELF = MethodNames::VALIDATE_SELF;
        $VALIDATE_ARG = ArgumentNames::VALIDATE;
        $CLONE_VAR = VariableNames::CLONE;
        $object = $this->mutating ? 'this' : $CLONE_VAR;

        $bodyParts = [];

        if (!$this->mutating) {
            $bodyParts[] = "\${$CLONE_VAR} = clone \$this;\n";
        }

        $bodyParts[] = "\${$object}->{$ADDITIONAL_PROPS} = new \stdClass();\n";

        if ($addValidation) {
            $bodyParts[] =
                <<<PHP
                if (\${$VALIDATE_ARG}) {
                    \${$object}->{$VALIDATE_SELF}();
                }\n
                PHP;
        }

        if ($this->chainable) {
            $bodyParts[] = "return \${$object};";
        }

        return implode('', $bodyParts);
    }
}
