<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollectionFilterFactory;
use Helmich\Schema2Class\Generator\Property\PropertyQuery;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Util\StringUtils;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;

class GetterFactory
{
    public function __construct(
        private GeneratorRequest $request,
        private PropertyCollection $schemaProperties,
    )
    {}

    /** 
     * @return MethodGenerator[]
     */
    public function generateGetters(): array
    {
        if ($this->request->getNoGetters()) {
            return [];
        }

        $methods = [];

        $filteredProperties = $this->schemaProperties->filter(
            PropertyCollectionFilterFactory::withoutDeprecatedAndSameName($this->schemaProperties)
        );

        foreach ($filteredProperties as $property) {
            $methods[] = $this->generateGetter($property);
        }

        return $methods;
    }

    private function generateGetter(PropertyInterface $property): MethodGenerator
    {
        $propertyName = $property->name();

        if ($this->request->getOptions()->getPreservePropertyNames()) {
            $camelCasedName = StringUtils::pascalCasePreserveOuterUnderscores($property->name());
        } else {
            $camelCasedName = StringUtils::safePascalCase($property->name());
        }

        $methodName = 'get' . $camelCasedName;

        $docBlockTags = [new ReturnTag($property->typeAnnotation())];

        if (PropertyQuery::isDeprecated($property)) {
            $docBlockTags[] = new GenericTag('deprecated');
        }

        $docBlock = new DocBlockGenerator(null, $property->description(), $docBlockTags);
        $docBlock->setWordWrap(false);

        $methodGen = new MethodGenerator(
            name: $methodName,
            parameters: [],
            flags: MethodGenerator::FLAG_PUBLIC,
            body: null,
            docBlock: $docBlock,
        );

        $body = "return \$this->{$propertyName};";

        if ($this->request->isAtLeastPHP('7.0')) {
            $typeHint = $property->typeHint($this->request->getTargetPHPVersion());

            if ($typeHint !== null) {
                $methodGen->setReturnType($typeHint);

                if ($typeHint[0] === '?') {
                    $body = "return \$this->{$propertyName} ?? null;";
                }
            }
        }

        $methodGen->setBody($body);

        return $methodGen;
    }
}
