<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method\Serialize;

use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class ToStdClassMethodFactory
{
    public function __construct(
        private GeneratorRequest $request,
        private PropertyCollection $schemaProperties,
        private array $defaults,
        private bool $additionalsAllowed,
    ) {}

    public function generate(): MethodGenerator
    {
        $params = [];
        if ($this->defaults) {
            $params[] = new ParameterGenerator(
                name: SerializeMethodFactory::DEFAULTS_ARG_NAME,
                type: 'bool',
                defaultValue: false
            );
        }

        $method = new MethodGenerator(
            name: MethodNames::TO_STD_CLASS,
            parameters: $params,
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $this->generateBody(),
            docBlock: $this->buildDocblock(),
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $method->setReturnType('\\stdClass');
        }

        return $method;
    }

    private function buildDocblock(): DocBlockGenerator
    {
        $tags = [];
        if ($this->defaults) {
            $tags[] = new ParamTag(
                variableName: SerializeMethodFactory::DEFAULTS_ARG_NAME,
                types: ['bool'],
                description: 'Add defaults for missing properties'
            );
        }
        $tags[] = new ReturnTag(['\\stdClass'], 'Converted object');

        $docBlock = new DocBlockGenerator(
            shortDescription: 'Converts this object to a stdClass that can be JSON-serialized',
            longDescription: null,
            tags: $tags
        );
        $docBlock->setWordWrap(false);

        return $docBlock;
    }

    private function generateBody(): string
    {
        $bodyParts = [];

        $OUTPUT_VAR_NAME = SerializeMethodFactory::OUTPUT_VAR_NAME;

        $bodyParts[] =
            "\${$OUTPUT_VAR_NAME} = new \\stdClass();\n" .
            $this->schemaProperties->generateTypeToStdClassConversionCode() .
            "\n";
        

        if ($this->defaults) {
            $DEFAULTS_ARG_NAME = SerializeMethodFactory::DEFAULTS_ARG_NAME;
            $DEFAULTS = PropertyNames::DEFAULTS;
            $arrayToObjectExpr = $this->request->getOptions()->getArrayToObjectExpr();

            $bodyParts[] =
                <<<PHP
                \nif (\${$DEFAULTS_ARG_NAME}) {
                    foreach (self::\${$DEFAULTS} as \$k => \$v) {
                        if (!property_exists(\${$OUTPUT_VAR_NAME}, (string) \$k)) {
                            \${$OUTPUT_VAR_NAME}->{\$k} = (isset(\$v['type']) && \$v['type'] === 'object')
                               ? {$arrayToObjectExpr}(\$v['default'])
                               : \$v['default'];
                        }
                    }
                }\n
                PHP;
        }

        $bodyParts[] = "\nreturn \${$OUTPUT_VAR_NAME};";

        return implode('', $bodyParts);
    }
}
