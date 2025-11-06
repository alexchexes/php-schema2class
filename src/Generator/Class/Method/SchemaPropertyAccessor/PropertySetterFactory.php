<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method\SchemaPropertyAccessor;

use Helmich\Schema2Class\Generator\Class\ArgumentNames;
use Helmich\Schema2Class\Generator\Class\MethodNames;
use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\Class\VariableNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\PropertyQuery;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Util\SchemaUtils;
use Helmich\Schema2Class\Util\StringUtils;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class PropertySetterFactory
{
    private bool $mutating;
    private bool $chainable;

    public function __construct(
        private GeneratorRequest $request,
        private array $schema,
    ) {
        $mutableConfig = $this->request->getMutableSetters();
        $this->mutating = $mutableConfig !== null;
        $this->chainable = $mutableConfig === 'chainable' || $this->mutating === false;
    }

    public function generate(PropertyInterface $property): ?MethodGenerator
    {
        if ($this->request->getNoSetters()) {
            return null;
        }

        // Determine which property should be used for parameter type information.
        // Optional properties are always stored as nullable internally, but
        // setters must only accept `null` when the schema explicitly allows it.
        $paramProperty = ($property instanceof OptionalPropertyDecorator && !$property->allowsNull())
            ? $property->unwrap()
            : $property;

        $propAnnotatedType = $paramProperty->typeAnnotation();
        $propTypeHint = $paramProperty->typeHint();

        // Determine whether setter needs runtime validation and whether
        // validation must be performed against the full schema or just the
        // property fragment.
        $isPropOptional = $property instanceof OptionalPropertyDecorator;
        $schemaNeedsValidation = SchemaUtils::needsRevalidationOnPropertyChange($this->schema, $isPropOptional, true);
        $propertyNeedsValidation = $property->needsValidation();

        $addValidation = $propertyNeedsValidation || $schemaNeedsValidation;
        $addFullValidation =
            $schemaNeedsValidation
            || ($propertyNeedsValidation && SchemaUtils::schemaHasRef($property->schema()));

        // Now construct all the components for the MethodGenerator

        $parameters = $this->buildParams($property, $propTypeHint, $addValidation);
        $body = $this->generateBody($property, $addValidation, $addFullValidation);
        $docBlock = $this->buildDocBlock($property, $propAnnotatedType, $propTypeHint, $addValidation);

        $prefix = $this->mutating ? 'set' : 'with';
        $methodName = $prefix . $property->methodName();

        $methodGen = new MethodGenerator(
            name: $methodName,
            parameters: $parameters,
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $body,
            docBlock: $docBlock
        );

        // set return type hint if possible
        if ($this->chainable && $this->request->isAtLeastPHP('7.0')) {
            $methodGen->setReturnType('self');
        } elseif (!$this->chainable && $this->request->isAtLeastPHP('7.1')) {
            $methodGen->setReturnType('void');
        }

        return $methodGen;
    }

    private function buildDocBlock(
        PropertyInterface $property,
        string $propAnnotatedType,
        ?string $propTypeHint,
        bool $addValidation,
    ): ?DocBlockGenerator
    {
        $tags = [];
        if ($propAnnotatedType === '') {
            $propAnnotatedType = null;
        } elseif ($propTypeHint !== null && StringUtils::isAnnotationSameAsTypeHint($propAnnotatedType, $propTypeHint)) {
            $propAnnotatedType = null;
        }

        if ($propAnnotatedType) {
            $tags[] = new ParamTag($property->varName(), explode('|', $propAnnotatedType));
        }

        if ($addValidation && !$this->request->isAtLeastPHP('7.0')) {
            $tags[] = new ParamTag(ArgumentNames::VALIDATE, ['bool']);
        }

        if ($this->chainable && !$this->request->isAtLeastPHP('7.0')) {
            $tags[] = new ReturnTag('self');
        }

        if (PropertyQuery::isDeprecated($property)) {
            $tags[] = new GenericTag('deprecated');
        }

        $description = $property->description();
        if ($description === '') {
            $description = null;
        }
        $docBlock = null;
        if ($tags || $description) {
            $docBlock = new DocBlockGenerator(null, $description, $tags);
            $docBlock->setWordWrap(false);
        }

        return $docBlock;
    }

    private function buildParams(PropertyInterface $property, ?string $propTypeHint, bool $addValidation): array
    {
        $parameters = [new ParameterGenerator($property->varName(), $propTypeHint)];

        if ($addValidation) {
            $type = $this->request->isAtLeastPHP('7.0') ? 'bool' : null;
            $validateParam = new ParameterGenerator(
                name: ArgumentNames::VALIDATE,
                type: $type,
                defaultValue: $this->request->getOptions()->getValidateArgValue(),
            );
            $parameters[] = $validateParam;
        }

        return $parameters;
    }

    private function generateBody(
        PropertyInterface $property,
        bool $addValidation,
        bool $addFullValidation,
    ): string
    {
        $newValidatorExpr = $this->request->getOptions()->getNewValidatorExpr();
        $OPTIONALS = PropertyNames::PROVIDED_OPTIONALS;
        $SCHEMA_PROP = PropertyNames::SCHEMA;
        $VALIDATE_SELF = MethodNames::VALIDATE_SELF;
        $VALIDATE_ARG = ArgumentNames::VALIDATE;
        $CLONE_VAR = VariableNames::CLONE;
        $propKey = $property->keyStr();
        $propName = $property->propName();
        $varName = $property->varName();
        $object = $this->mutating ? 'this' : $CLONE_VAR;

        $bodyParts = [];

        // partial validation (when the property canNOT invalidate the whole schema)
        if ($addValidation && !$addFullValidation) {
            $bodyParts[] =
                <<<PHP
                if (\${$VALIDATE_ARG}) {
                    \$validator = {$newValidatorExpr};
                    \$validator->validate(\$$varName, self::\${$SCHEMA_PROP}['properties'][{$propKey}]);
                    if (!\$validator->isValid()) {
                        throw new \InvalidArgumentException(\$validator->getErrors()[0]['message']);
                    }
                }\n\n
                PHP;
        }

        // self clone if in "immutable" mode
        if (!$this->mutating) {
            $bodyParts[] = "\${$CLONE_VAR} = clone \$this;\n";
        }

        // set the property
        $bodyParts[] = "\${$object}->{$propName} = \${$varName};\n";

        // add it to the list of provided optional nullables
        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $bodyParts[] = "\${$object}->{$OPTIONALS}[{$propKey}] = true;\n";
        }

        // full revalidation of the whole object after the property was set
        // (if schema has complex top-level constraints)
        if ($addValidation && $addFullValidation) {
            $bodyParts[] = 
                <<<PHP
                if (\${$VALIDATE_ARG}) {
                    \${$object}->{$VALIDATE_SELF}();
                }
                PHP;
        }

        // return $this or $clone
        if ($this->chainable) {
            $bodyParts[] = "\nreturn \${$object};";
        }

        return implode('', $bodyParts);
    }
}
