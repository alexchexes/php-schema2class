<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlock\Tag\ThrowsTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class IsOptionalProvidedMethodFactory
{
    public function __construct(
        private GeneratorRequest $request,
        private bool $hasOptionalNullable,
    )
    {}

    public function generate(): ?MethodGenerator
    {
        if (!$this->hasOptionalNullable) {
            return null;
        }

        $method = new MethodGenerator(
            name: MethodNames::IS_PROVIDED,
            parameters: [new ParameterGenerator(ArgumentNames::PROPERTY_NAME, 'string')],
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $this->generateBody(),
            docBlock: $this->buildDocBlock(),
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $method->setReturnType('bool');
        }

        return $method;
    }

    private function generateBody(): string
    {
        $NAMES_MAP = PropertyNames::NAMES_MAP;
        $OPTIONALS_PROP = PropertyNames::PROVIDED_OPTIONALS;
        $PROPERTY_NAME_ARG = ArgumentNames::PROPERTY_NAME;

        $body = 
            <<<PHP
            if (!array_key_exists(\${$PROPERTY_NAME_ARG}, self::\${$NAMES_MAP})) {
                throw new \InvalidArgumentException("Unknown property: {\${$PROPERTY_NAME_ARG}}");
            }
            return
                array_key_exists(\${$PROPERTY_NAME_ARG}, \$this->{$OPTIONALS_PROP})
                || isset(\$this->{ self::\${$NAMES_MAP}[\${$PROPERTY_NAME_ARG}] });
            PHP;

        return $body;
    }

    private function buildDocBlock(): DocBlockGenerator
    {
        $tags = [
            new ParamTag(
                variableName: ArgumentNames::PROPERTY_NAME,
                types: ['string'],
                description: 'Property name to check (exactly as it appears in the schema).'
            ),
            new ThrowsTag(['\InvalidArgumentException'], "If property with that name doesn't exist."),
        ];
        
        if (!$this->request->isAtLeastPHP('7.0')) {
            $tags[] = new ReturnTag('bool');
        }

        $docBlock = new DocBlockGenerator(
            shortDescription: "Checks if an optional nullable property was explicitly set.",
            longDescription: null,
            tags: $tags,
        );

        $docBlock->setWordWrap(false);

        return $docBlock;
    }
}
