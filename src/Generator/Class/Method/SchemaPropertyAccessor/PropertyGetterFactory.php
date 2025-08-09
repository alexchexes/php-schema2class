<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method\SchemaPropertyAccessor;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\PropertyQuery;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Util\StringUtils;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;

class PropertyGetterFactory
{
    public function __construct(
        private GeneratorRequest $request,
    ) {}

    public function generate(PropertyInterface $property): ?MethodGenerator
    {
        if ($this->request->getNoGetters()) {
            return null;
        }

        $methodName = 'get' . $property->methodName();

        $docBlock = $this->buildDocBlock($property);

        $methodGen = new MethodGenerator(
            name: $methodName,
            parameters: [],
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $this->generateBody($property),
            docBlock: $docBlock,
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $typeHint = $property->typeHint();
            if ($typeHint !== null) {
                $methodGen->setReturnType($typeHint);
            }
        }

        return $methodGen;
    }

    private function generateBody(PropertyInterface $property): string
    {
        $propName = $property->propName();

        if ($property instanceof OptionalPropertyDecorator) {
            if ($this->request->isAtLeastPHP('7.0')) {
                $body = "return \$this->{$propName} ?? null;";
            } else {
                $body = "return isset(\$this->{$propName}) ? \$this->{$propName} : null;";
            }
        } else {
            $body = "return \$this->{$propName};";
        }

        return $body;
    }

    private function buildDocBlock(PropertyInterface $property): ?DocBlockGenerator
    {
        $docBlockTags = [];

        $typeHint = $property->typeHint();
        $annotType = $property->typeAnnotation();

        if ($annotType === '') {
            $annotType = null;
        } elseif ($typeHint !== null && StringUtils::isAnnotationSameAsTypeHint($annotType, $typeHint)) {
            $annotType = null;
        }

        if ($annotType) {
            $docBlockTags[] = new ReturnTag($annotType);
        }

        if (PropertyQuery::isDeprecated($property)) {
            $docBlockTags[] = new GenericTag('deprecated');
        }
        
        $description = $property->description();
        if ($description === '') {
            $description = null;
        }

        $docBlock = null;
        if ($description || $docBlockTags) {
            $docBlock = new DocBlockGenerator(null, $description, $docBlockTags);
            $docBlock->setWordWrap(false);
        }

        return $docBlock;
    }
}
