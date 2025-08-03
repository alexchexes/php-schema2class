<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Property;

use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\PropertyQuery;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlockGenerator;

class SchemaPropertyFactory
{
    public function __construct(
      private GeneratorRequest $request,
    ) {}
    
    public function generateClassSchemaProperty(PropertyInterface $schemaProp): PropertyGenerator
    {
        $visibility = $this->request->getNoGetters()
            ? PropertyGenerator::FLAG_PUBLIC
            : PropertyGenerator::FLAG_PRIVATE;

        $schema     = $schemaProp->schema();
        $isOptional = false;
        $propertyGenerator    = new PropertyGenerator(
            name: $schemaProp->name(),
            defaultValue: $schemaProp->formatValue(null),
            flags: $visibility
        );

        if ($schemaProp instanceof OptionalPropertyDecorator) {
            $isOptional = true;
        }

        $docBlockTags = [new GenericTag("var", trim($schemaProp->typeAnnotation()))];
        if (PropertyQuery::isDeprecated($schemaProp)) {
            $docBlockTags[] = new GenericTag("deprecated");
        }

        $docBlock = new DocBlockGenerator(
            $schema["description"] ?? null,
            null,
            $docBlockTags
        );
        $docBlock->setWordWrap(false);

        $propertyGenerator->setDocBlock($docBlock);

        $typeHint = $schemaProp->typeHint();
        if ($this->request->isAtLeastPHP("7.4") && $typeHint !== null) {
            $propertyGenerator->setTypeHint($typeHint);
        }

        // omit default `null` for every required field, unsless default is specified in the schema
        $propertyGenerator->omitDefaultValue(!$isOptional);

        return $propertyGenerator;
    }
}