<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\Decorator\PropertyDecoratorInterface;
use Helmich\Schema2Class\Generator\Property\PropertyQuery;
use Helmich\Schema2Class\Generator\Property\Type\ObjectArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\PrimitiveArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Generator\Property\Type\ReferenceArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\TypedArrayProperty;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class SetterFactory
{
    private bool $mutating;
    private bool $chainable;

    public function __construct(
        private GeneratorRequest $request,
    ) {
        $mutableConfig = $this->request->getMutableSetters();
        $this->mutating = $mutableConfig !== null;
        $this->chainable = $mutableConfig === 'chainable' || $this->mutating === false;
    }

    public function generateSetter(PropertyInterface $property, string $pascalName): ?MethodGenerator
    {
        if ($this->request->getNoSetters()) {
            return null;
        }

        $prefix = $this->mutating ? 'set' : 'with';
        $methodName = $prefix . $pascalName;
        $propName = $property->name();

        // We first unwrap only optional property decorator but leave other
        // decorators in place to get the correct type hint / annotation
        // TODO: FIXME This logic flawed: sometimes we generate setters with parameter types that are
        // misaligned with PHPDoc type annotations of those params, and we also sometimes don't allow nulls where we should've.
        $requiredProperty = ($property instanceof OptionalPropertyDecorator) ? $property->unwrap() : $property;
        $propAnnotatedType = $requiredProperty->typeAnnotation();
        $propTypeHint = $requiredProperty->typeHint();

        // then we fully unwrap any other decorators to be able to see the real type
        $base = $property;
        while ($base instanceof PropertyDecoratorInterface) {
            /** @var PropertyDecoratorInterface $base */
            $base = $base->unwrap();
        }

        $isArray = $base instanceof PrimitiveArrayProperty
            || $base instanceof ObjectArrayProperty
            || $base instanceof ReferenceArrayProperty
            || $base instanceof TypedArrayProperty;

        $addValidation = true;

        // If property is complex (except arrays), no validation needed
        // TODO: this is not right; Validation is not generated in some cases where it should've been
        if ($property->isComplex() && !$isArray) {
            $addValidation = false;
        }

        $docBlock = $this->buildDocBlock($property, $propName, $propAnnotatedType, $addValidation);
        $parameters = $this->buildParams($propName, $propTypeHint, $addValidation);
        $body = $this->generateBody($property, $propName, $addValidation);

        $methodGen = new MethodGenerator(
            name: $methodName,
            parameters: $parameters,
            flags: MethodGenerator::FLAG_PUBLIC,
            body: $body,
            docBlock: $docBlock
        );

        if ($this->chainable && $this->request->isAtLeastPHP('7.0')) {
            $methodGen->setReturnType('self');
        } elseif (!$this->chainable) {
            if ($this->request->isAtLeastPHP('7.1')) {
                $methodGen->setReturnType('void');
            }
        }

        return $methodGen;
    }

    private function buildDocBlock(
        PropertyInterface $property,
        string $propName,
        string $propAnnotatedType,
        bool $addValidation,
    ): DocBlockGenerator
    {
        $tags = [
            new ParamTag($propName, [str_replace('|null', '', $propAnnotatedType)])
        ];

        if ($this->chainable) {
            $tags[] = new ReturnTag('self');
        }

        if (PropertyQuery::isDeprecated($property)) {
            $tags[] = new GenericTag('deprecated');
        }

        $docBlock = new DocBlockGenerator(null, null, $tags);
        $docBlock->setWordWrap(false);

        if ($addValidation) {
            $tags[] = new ParamTag('validate', ['bool']);
            $docBlock = new DocBlockGenerator(null, null, $tags);
            $docBlock->setWordWrap(false);
        }

        return $docBlock;
    }

    private function buildParams(string $propName, ?string $propTypeHint, bool $addValidation): array
    {
        $parameters = [new ParameterGenerator($propName, $propTypeHint)];
        if ($addValidation) {
            $validateParam = new ParameterGenerator('validate', 'bool');
            $validateParam->setDefaultValue(true);
            $parameters[] = $validateParam;
        }
        return $parameters;
    }

    private function generateBody(PropertyInterface $property, string $propName, bool $addValidation): string
    {
        $propKey = var_export($property->key(), true);
        
        $validationBlock = '';
        if ($addValidation) {
            $newValidatorExpr = $this->request->getOptions()->getNewValidatorExpr();

            $SCHEMA = PropertyNames::SCHEMA;
            $validationBlock =
                <<<PHP
                if (\$validate) {
                    \$validator = {$newValidatorExpr};
                    \$validator->validate(\$$propName, self::\${$SCHEMA}['properties'][{$propKey}]);
                    if (!\$validator->isValid()) {
                        throw new \\InvalidArgumentException(\$validator->getErrors()[0]['message']);
                    }
                }\n\n
                PHP;
        }

        $OPTIONALS = PropertyNames::OPTIONALS;

        if ($this->mutating) {
            $body =
                $validationBlock .
                "\$this->{$propName} = \$$propName;";

            if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
                $body .= "\n\$this->{$OPTIONALS}[{$propKey}] = true;";
            }

            if ($this->chainable) {
                $body .= "\n\nreturn \$this;";
            }
        } else {
            $body =
                $validationBlock .
                "\$clone = clone \$this;\n" .
                "\$clone->$propName = \$$propName;\n";

            if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
                $body .= "\$clone->{$OPTIONALS}[{$propKey}] = true;\n";
            }

            $body .= "\nreturn \$clone;";
        }

        return $body;
    }
}
