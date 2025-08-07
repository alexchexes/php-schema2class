<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlock\Tag\ThrowsTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class ValidateMethodFactory
{
    public const RETURN_ARG_NAME = 'return';

    public function __construct(
        private GeneratorRequest $request,
    ) {}
    
    public function generate(): MethodGenerator
    {
        $params = [
            new ParameterGenerator(
                name: self::RETURN_ARG_NAME,
                type: $this->request->isAtLeastPHP('7.0') ? 'bool' : null,
                defaultValue: false
            ),
        ];

        $docBlock = $this->buildDocBlock();

        $method = new MethodGenerator(
            name: MethodNames::VALIDATE_SELF,
            parameters: $params,
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $this->generateBody(),
            docBlock: $docBlock,
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $method->setReturnType('bool');
        }

        return $method;
    }

    private function buildDocBlock(): DocBlockGenerator
    {
        $RETURN_ARG_NAME = self::RETURN_ARG_NAME;

        $tags = [
            new ParamTag(self::RETURN_ARG_NAME, ['bool'], 'Return instead of throwing errors'),
            new ReturnTag(['bool'], "Validation result if `\${$RETURN_ARG_NAME}` is `true`"),
            new ThrowsTag('\\InvalidArgumentException'),
        ];

        $docBlock = new DocBlockGenerator(
            shortDescription: 'Validates the current instance against its schema',
            longDescription: null,
            tags: $tags
        );
        $docBlock->setWordWrap(false);

        return $docBlock;
    }

    private function generateBody(): string
    {
        $TO_STD_CLASS = MethodNames::TO_STD_CLASS;
        $VALIDATE_INPUT = MethodNames::VALIDATE_INPUT;
        $RETURN_ARG = self::RETURN_ARG_NAME;

        $body = "return self::{$VALIDATE_INPUT}(\$this->{$TO_STD_CLASS}(), \${$RETURN_ARG});";

        return $body;
    }
}
