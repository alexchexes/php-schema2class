<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method\Serialize;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\Class\VariableNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class ToArrayMethodFactory
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
                name: ArgumentNames::INCL_DEFAULTS,
                type: 'bool',
                defaultValue: false
            );
        }

        $method = new MethodGenerator(
            name: MethodNames::TO_ARRAY,
            parameters: $params,
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $this->generateBody(),
            docBlock: $this->buildDocblock(),
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $method->setReturnType('array');
        }

        return $method;
    }

    private function buildDocblock(): DocBlockGenerator
    {
        $tags = [];
        if ($this->defaults) {
            $tags[] = new ParamTag(
                variableName: ArgumentNames::INCL_DEFAULTS,
                types: ['bool'],
                description: 'Add defaults for missing properties'
            );
        }
        $tags[] = new ReturnTag(['array'], 'Converted array');

        $docBlock = new DocBlockGenerator(
            shortDescription: 'Converts this object back to a simple array that can be JSON-serialized',
            longDescription: null,
            tags: $tags
        );
        $docBlock->setWordWrap(false);

        return $docBlock;
    }

    private function generateBody(): string
    {
        $bodyParts = [];

        $OUTPUT_VAR_NAME = VariableNames::OUTPUT;

        if ($this->additionalsAllowed) {
            $ADDITIONAL_PROPS = PropertyNames::ADDITIONAL_PROPS;
            $bodyParts[] = "\${$OUTPUT_VAR_NAME} = json_decode(json_encode(\$this->{$ADDITIONAL_PROPS}), true);\n\n";
        } else {
            $bodyParts[] = "\${$OUTPUT_VAR_NAME} = [];\n";
        }

        $bodyParts[] = $this->schemaProperties->generateTypeToArrayConversionCode() . "\n";

        if ($this->defaults) {
            $DEFAULTS_ARG_NAME = ArgumentNames::INCL_DEFAULTS;
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

        return implode('', $bodyParts);
    }
}
