<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method\AdditionalProperties;

use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Util\SchemaUtils;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class AdditionalPropGetterFactory
{
    public const AS_ARRAY_ARG = 'asArray';

    public function __construct(
        private GeneratorRequest $request,
    ) {}

    public function generate(): MethodGenerator
    {
        $params = [
            new ParameterGenerator(
                name: self::AS_ARRAY_ARG,
                type: $this->request->isAtLeastPHP('7.0') ? 'bool' : null,
                defaultValue: true,
            ),
        ];

        $methodGen = new MethodGenerator(
            name: 'get' . MethodNames::ADDITIONAL_PROPERTIES,
            parameters: $params,
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $this->buildBody(),
            docBlock: $this->buildDocBlock(),
        );

        if ($this->request->isAtLeastPHP('8.0')) {
            $methodGen->setReturnType('array|object');
        }

        return $methodGen;
    }

    private function buildDocBlock(): DocBlockGenerator
    {
        $tags = [
            new ParamTag(
                variableName: self::AS_ARRAY_ARG,
                types: 'bool',
                description: 'Whether return array instead of `stdClass` object.',
            ),
        ];

        if (!$this->request->isAtLeastPHP('8.0')) {
            $tags[] = new ReturnTag(['array', 'object']);
        }

        $docBlock = new DocBlockGenerator(
            shortDescription: "Object or array containing name/value pairs for properties not specified in the schema.",
            tags: $tags,
        );

        $docBlock->setWordWrap(false);

        return $docBlock;
    }

    private function buildBody(): string
    {
        $ADDITIONAL_PROPS = PropertyNames::ADDITIONAL_PROPS;
        $AS_ARRAY_ARG = self::AS_ARRAY_ARG;

        $body =
            <<<PHP
            return \${$AS_ARRAY_ARG}
                ? json_decode(json_encode(\$this->{$ADDITIONAL_PROPS}), true)
                : \$this->{$ADDITIONAL_PROPS};
            PHP;

        return $body;
    }
}
