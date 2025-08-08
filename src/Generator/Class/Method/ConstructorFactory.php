<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\Class\PropertyNames;
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
    public function generate(): ?MethodGenerator
    {
        $params      = [];
        $tags        = [];
        $bodyParts = [];

        $requiredProperties = $this->schemaProperties->filter(PropertyCollectionFilterFactory::onlyRequired());
        $optionalProperties = $this->schemaProperties->filter(PropertyCollectionFilterFactory::onlyOptional());

        foreach ([...$requiredProperties, ...$optionalProperties] as $property) {
            $params[] = $this->buildParameter($property);

            $varName = $property->varName();

            $bodyParts[] = "\$this->{$property->propName()} = \${$varName};";
            
            // This doesn't make sense since we only can detect non-nulls, but non-nulls
            // get into the output anyway, even without their presence in the `_providedOptionals`

            // if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            //     $keyStr = $property->keyStr();
            //     $OPTIONALS = PropertyNames::OPTIONALS;
            //     $bodyParts[] =
            //         <<<PHP
            //         if (\${$varName} !== null) {
            //             \$this->{$OPTIONALS}[{$keyStr}] = true;
            //         }
            //         PHP;
            // }

            $tag = $this->buildTag($property);
            if ($tag !== null) {
                $tags[] = $tag;
            }
        }

        if ($bodyParts === []) {
            // don't generate empty constructors
            return null;
        }


        $body = join("\n", $bodyParts);

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
