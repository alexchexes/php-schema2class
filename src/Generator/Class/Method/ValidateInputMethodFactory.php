<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\Expression\ArrayMapGenerator;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Util\StringUtils;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlock\Tag\ThrowsTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class ValidateInputMethodFactory
{
    public function __construct(
        private GeneratorRequest $request,
    ) {}
    
    public function generate(): MethodGenerator
    {
        $params = [
            new ParameterGenerator(
                name: ArgumentNames::INPUT,
                type: $this->request->isAtLeastPHP('8.0') ? 'array|object' : null
            ),
            new ParameterGenerator(
                name: ArgumentNames::RETURN,
                type: $this->request->isAtLeastPHP('7.0') ? 'bool' : null,
                defaultValue: false
            ),
        ];

        $docBlock = $this->buildDocBlock();

        $method = new MethodGenerator(
            name: MethodNames::VALIDATE_INPUT,
            parameters: $params,
            flags: MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC,
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
        $RETURN_ARG_NAME = ArgumentNames::RETURN;

        $tags = [
            new ParamTag(ArgumentNames::INPUT, ['array|object'], 'Input data'),
            new ParamTag(ArgumentNames::RETURN, ['bool'], 'Return instead of throwing errors'),
            new ReturnTag(['bool'], "Validation result if `\${$RETURN_ARG_NAME}` is `true`"),
            new ThrowsTag('\\InvalidArgumentException'),
        ];

        $docBlock = new DocBlockGenerator(
            shortDescription: 'Validates an input array',
            longDescription: null,
            tags: $tags
        );
        $docBlock->setWordWrap(false);

        return $docBlock;
    }

    private function generateBody(): string
    {
        $newValidatorExpr = $this->request->getOptions()->getNewValidatorExpr();
        $arrayToObjectExpr = $this->request->getOptions()->getArrayToObjectExpr();

        $SCHEMA = PropertyNames::SCHEMA;
        $INPUT_ARG = ArgumentNames::INPUT;
        $RETURN_ARG = ArgumentNames::RETURN;
        
        $errorsMapExpr = ArrayMapGenerator::make(
            itemParam: '$e',
            mapExpr: '($e["property"] ? $e["property"] . ": " : "") . $e["message"]',
            arrayExpr: '$validator->getErrors()',
            itemType: 'array',
            returnType: 'string',
            phpVer: $this->request->getTargetPHPVersion(),
        );

        $errorsMapBlock = StringUtils::indentCode("\$errors = {$errorsMapExpr};");

        $body =
            <<<PHP
            \$validator = {$newValidatorExpr};
            \${$INPUT_ARG} = is_array(\${$INPUT_ARG}) ? {$arrayToObjectExpr}(\${$INPUT_ARG}) : \${$INPUT_ARG};
            \$validator->validate(\${$INPUT_ARG}, self::\${$SCHEMA});
            
            if (!\$validator->isValid() && !\${$RETURN_ARG}) {
            {$errorsMapBlock}
                throw new \\InvalidArgumentException(join(".\\n", \$errors));
            }
            
            return \$validator->isValid();
            PHP;
        
        return $body;
    }
}
