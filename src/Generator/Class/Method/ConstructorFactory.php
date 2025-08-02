<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollectionFilterFactory;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class ConstructorFactory
{
    public function __construct(
        private GeneratorRequest $request,
        private PropertyCollection $schemaProperties,
    ) {}

    /** 
     * Returns `null` when constructor should not be generated
     */
    public function generateConstructor(): ?MethodGenerator
    {
        $params      = [];
        $tags        = [];
        $assignments = [];

        $requiredProperties = $this->schemaProperties->filter(PropertyCollectionFilterFactory::onlyRequired());

        foreach ($requiredProperties as $requiredProperty) {
            $paramName = $requiredProperty->name();
            $params[]  = new ParameterGenerator(
                $paramName,
                $requiredProperty->typeHint($this->request->getTargetPHPVersion())
            );

            $tags[] = new ParamTag(
                $paramName,
                [$requiredProperty->typeAnnotation()]
            );

            $assignments[] = "\$this->{$requiredProperty->name()} = \${$paramName};";
        }

        if ($assignments === []) {
            // don't generate empty ctors
            return null;
        }

        $docBlock = new DocBlockGenerator(null, null, $tags);
        $docBlock->setWordWrap(false);

        $body = join("\n", $assignments);

        return new MethodGenerator(
            name: '__construct',
            parameters: $params,
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $body,
            docBlock: $docBlock
        );
    }
}
