<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class IsProvidedMethodFactory
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

        $argumentName = 'propertyName';

        $docBlockTags = [
            new ParamTag(
                $argumentName,
                ['string'],
                'Property name to check (exactly as it appears in the schema)'
            ),
            new ReturnTag('bool'),
        ];

        $docBlock = new DocBlockGenerator(
            'Checks if an optional nullable property was explicitly set',
            null,
            $docBlockTags,
        );

        $docBlock->setWordWrap(false);

        $OPTIONALS_PROP = PropertyNames::OPTIONALS;

        $body = "return array_key_exists(\${$argumentName}, \$this->{$OPTIONALS_PROP});";

        $method = new MethodGenerator(
            name: MethodNames::IS_PROVIDED,
            parameters: [new ParameterGenerator($argumentName, 'string')],
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $body,
            docBlock: $docBlock
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $method->setReturnType('bool');
        }

        return $method;
    }
}
