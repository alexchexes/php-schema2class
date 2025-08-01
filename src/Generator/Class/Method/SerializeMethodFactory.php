<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class SerializeMethodFactory
{
    public const DEFAULTS_ARG_NAME = 'includeDefaults';
    public const OUTPUT_VAR_NAME = 'output';

    public function __construct(
        private GeneratorRequest $request,
        private PropertyCollection $schemaProperties,
        private array $defaults = [],
    ) {}

    /** 
     * @return MethodGenerator[]
     */
    public function generateSerializeMethods(): array
    {
      return [
        $this->generateToArrayMethod(),
        $this->generateToStdClassMethod(),
      ];
    }

    private function generateToArrayMethod(): MethodGenerator
    {
        $tags = [];
        if ($this->defaults) {
            $tags[] = new ParamTag(self::DEFAULTS_ARG_NAME, ['bool'], 'Add defaults for missing properties');
        }
        $tags[] = new ReturnTag(['array'], 'Converted array');

        $docBlock = new DocBlockGenerator(
            'Converts this object back to a simple array that can be JSON-serialized',
            null,
            $tags
        );
        $docBlock->setWordWrap(false);

        $params = [];
        if ($this->defaults) {
            $params[] = new ParameterGenerator(self::DEFAULTS_ARG_NAME, 'bool', false);
        }

        $bodyParts = [];

        $OUTPUT_VAR_NAME = self::OUTPUT_VAR_NAME;

        $bodyParts[] =
            "\${$OUTPUT_VAR_NAME} = [];\n" .
            $this->schemaProperties->generateTypeToArrayConversionCode() .
            "\n";

        if ($this->defaults) {
            $DEFAULTS_ARG_NAME = self::DEFAULTS_ARG_NAME;
            $DEFAULTS = PropertyNames::DEFAULTS;

            $bodyParts[] =
                <<<PHP
                \nif (\${$DEFAULTS_ARG_NAME}) {
                    foreach (self::\${$DEFAULTS} as \$k => \$v) {
                        if (!array_key_exists(\$k, \${$OUTPUT_VAR_NAME})) {
                            \${$OUTPUT_VAR_NAME}[\$k] = \$v['default'];
                        }
                    }
                }\n
                PHP;
        }

        $bodyParts[] = "\nreturn \${$OUTPUT_VAR_NAME};";

        $method = new MethodGenerator(
            MethodNames::TO_ARRAY,
            $params,
            MethodGenerator::FLAG_PUBLIC,
            implode('', $bodyParts),
            $docBlock
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $method->setReturnType('array');
        }

        return $method;
    }

    private function generateToStdClassMethod(): MethodGenerator
    {
        $tags = [];
        if ($this->defaults) {
            $tags[] = new ParamTag(self::DEFAULTS_ARG_NAME, ['bool'], 'Add defaults for missing properties');
        }
        $tags[] = new ReturnTag(['\\stdClass'], 'Converted object');

        $docBlock = new DocBlockGenerator(
            'Converts this object to a stdClass that can be JSON-serialized',
            null,
            $tags
        );
        $docBlock->setWordWrap(false);

        $params = [];
        if ($this->defaults) {
            $params[] = new ParameterGenerator(self::DEFAULTS_ARG_NAME, 'bool', false);
        }

        $bodyParts = [];

        $OUTPUT_VAR_NAME = self::OUTPUT_VAR_NAME;

        $bodyParts[] =
            "\${$OUTPUT_VAR_NAME} = new \\stdClass();\n" .
            $this->schemaProperties->generateTypeToStdClassConversionCode() .
            "\n";
        

        if ($this->defaults) {
            $DEFAULTS_ARG_NAME = self::DEFAULTS_ARG_NAME;
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

        $method = new MethodGenerator(
            MethodNames::TO_STD_CLASS,
            $params,
            MethodGenerator::FLAG_PUBLIC,
            implode('', $bodyParts),
            $docBlock
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $method->setReturnType('\\stdClass');
        }

        return $method;
    }
}
