<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlock\Tag\ThrowsTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class ValidateInputMethodFactory
{
    public const INPUT_ARG_NAME = 'input';
    public const RETURN_ARG_NAME = 'return';

    public function __construct(
        private GeneratorRequest $request,
    ) {}
    
    public function generate(): MethodGenerator
    {
        $params = [
            new ParameterGenerator(
                name: self::INPUT_ARG_NAME,
                type: $this->request->isAtLeastPHP('8.0') ? 'array|object' : null
            ),
            new ParameterGenerator(
                name: self::RETURN_ARG_NAME,
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
        $RETURN_ARG_NAME = self::RETURN_ARG_NAME;

        $tags = [
            new ParamTag(self::INPUT_ARG_NAME, ['array|object'], 'Input data'),
            new ParamTag(self::RETURN_ARG_NAME, ['bool'], 'Return instead of throwing errors'),
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
        $INPUT_ARG = self::INPUT_ARG_NAME;
        $RETURN_ARG = self::RETURN_ARG_NAME;

        $errorsMapExpr = $this->request->isAtLeastPHP('7.0')
            ? '$errors = array_map(function(array $e): string'
            : '$errors = array_map(function($e)';

        $body =
            <<<PHP
            \$validator = {$newValidatorExpr};
            \${$INPUT_ARG} = is_array(\${$INPUT_ARG}) ? {$arrayToObjectExpr}(\${$INPUT_ARG}) : \${$INPUT_ARG};
            \$validator->validate(\${$INPUT_ARG}, self::\${$SCHEMA});\n
            if (!\$validator->isValid() && !\${$RETURN_ARG}) {
                $errorsMapExpr {
                    return (\$e["property"] ? \$e["property"] . ": " : "") . \$e["message"];
                }, \$validator->getErrors());
                throw new \\InvalidArgumentException(join(".\\n", \$errors));
            }
            
            return \$validator->isValid();
            PHP;
        
        return $body;
    }
}
