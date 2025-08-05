<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\GeneratorRequest;
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
    ) {}

    public function generateGetter(PropertyInterface $property): ?MethodGenerator
    {
        if ($this->request->getNoGetters()) {
            return null;
        }

        $propName = $property->propName();
        $methodName = 'get' . $property->methodName();

        $typeHint = $property->typeHint();
        $annotType = $property->typeAnnotation();
        if ($annotType === '') {
            $annotType = null;
        } elseif ($typeHint !== null && StringUtils::isAnnotationSameAsTypeHint($annotType, $typeHint)) {
            $annotType = null;
        }

        $docBlockTags = [];

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

        $methodGen = new MethodGenerator(
            name: $methodName,
            parameters: [],
            flags: MethodGenerator::FLAG_PUBLIC,
            body: null,
            docBlock: $docBlock,
        );

        $body = "return \$this->{$propName};";

        if ($this->request->isAtLeastPHP('7.0')) {
            if ($typeHint !== null) {
                $methodGen->setReturnType($typeHint);

                if ($typeHint[0] === '?') {
                    $body = "return \$this->{$propName} ?? null;";
                }
            }
        }

        $methodGen->setBody($body);

        return $methodGen;
    }
}
