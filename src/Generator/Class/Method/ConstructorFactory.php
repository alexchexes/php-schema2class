<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollectionFilterFactory;
use Helmich\Schema2Class\Util\StringUtils;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class ConstructorFactory
{
    public function __construct(
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
            $typeHint = $requiredProperty->typeHint();
            $annotType = $requiredProperty->typeAnnotation();
            if ($annotType === '') {
                $annotType = null;
            } elseif ($typeHint !== null && StringUtils::isAnnotationSameAsTypeHint($annotType, $typeHint)) {
                $annotType = null;
            }

            $paramName = $requiredProperty->varName();
            $params[] = new ParameterGenerator($paramName, $typeHint);

            if ($annotType) {
                $tags[] = new ParamTag($paramName, [$annotType]);
            }

            $assignments[] = "\$this->{$requiredProperty->propName()} = \${$paramName};";
        }

        if ($assignments === []) {
            // don't generate empty ctors
            return null;
        }


        $body = join("\n", $assignments);

        $methodGen = new MethodGenerator(
            name: '__construct',
            parameters: $params,
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $body,
        );

        if ($tags) {
            $docBlock = new DocBlockGenerator(null, null, $tags);
            $docBlock->setWordWrap(false);
            $methodGen->setDocBlock($docBlock);
        }

        return $methodGen;
    }
}
