<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollectionFilterFactory;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
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
        $optionalProperties = $this->schemaProperties->filter(PropertyCollectionFilterFactory::onlyOptional());

        foreach ([...$requiredProperties, ...$optionalProperties] as $property) {
            $params[] = $this->buildParameter($property);
            $assignments[] = "\$this->{$property->propName()} = \${$property->varName()};";
            
            $tag = $this->buildTag($property);
            if ($tag !== null) {
                $tags[] = $tag;
            }
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

    private function buildParameter(PropertyInterface $property): ParameterGenerator
    {
        $typeHint = $property->typeHint();
        $paramGen = new ParameterGenerator($property->varName());

        if ($property instanceof OptionalPropertyDecorator) {
            // if ($typeHint) {
            //     $isUnion = str_contains($typeHint, '|');
            //     if ($isUnion && !str_contains($typeHint, 'null')) {
            //         $typeHint = $typeHint . '|null';
            //     } elseif (!$isUnion && $typeHint[0] !== '?') {
            //         $typeHint = '?' . $typeHint;
            //     }
            // }
            $paramGen->setDefaultValue(null);
        }

        if ($typeHint) {
            $paramGen->setType($typeHint);
        }
        return $paramGen;
    }
    
    private function buildTag(PropertyInterface $property): ?ParamTag
    {
        $typeHint = $property->typeHint();
        $annotType = $property->typeAnnotation();

        if ($annotType === '') {
            $annotType = null;
        } elseif ($typeHint !== null && StringUtils::isAnnotationSameAsTypeHint($annotType, $typeHint)) {
            $annotType = null;
        }

        $paramName = $property->varName();

        if ($annotType !== null) {
            return new ParamTag($paramName, [$annotType]);
        }
        return null;
    }
}
