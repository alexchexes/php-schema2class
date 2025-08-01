<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class\Method;

use Helmich\Schema2Class\Generator\Class\PropertyNames;
use Helmich\Schema2Class\Generator\GeneratorRequest;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollection;
use Helmich\Schema2Class\Generator\Property\Collection\PropertyCollectionFilterFactory;
use Helmich\Schema2Class\Generator\Property\Decorator\OptionalPropertyDecorator;
use Helmich\Schema2Class\Generator\Property\Decorator\PropertyDecoratorInterface;
use Helmich\Schema2Class\Generator\Property\PropertyQuery;
use Helmich\Schema2Class\Generator\Property\Type\ObjectArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\PrimitiveArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\PropertyInterface;
use Helmich\Schema2Class\Generator\Property\Type\ReferenceArrayProperty;
use Helmich\Schema2Class\Generator\Property\Type\TypedArrayProperty;
use Helmich\Schema2Class\Util\StringUtils;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

class SetterFactory
{
    public function __construct(
        private GeneratorRequest $request,
        private PropertyCollection $schemaProperties,
    )
    {}

    /** 
     * @return MethodGenerator[]
     */
    public function generateSetters(): array
    {
        if ($this->request->getNoSetters()) {
            return [];
        }

        $mutableConfig = $this->request->getMutableSetters();

        $methods = [];

        $filteredProperties = $this->schemaProperties->filter(
            PropertyCollectionFilterFactory::withoutDeprecatedAndSameName($this->schemaProperties)
        );

        foreach ($filteredProperties as $property) {
            if ($mutableConfig !== null) {
                $methods[] = $this->generateMutatingSetter($property, $mutableConfig === 'chainable');
                if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
                    $methods[] = $this->generateMutatingUnsetter($property, $mutableConfig === 'chainable');
                }
            } else {
                $methods[] = $this->generateNonMutatingSetter($property);

                if ($property instanceof OptionalPropertyDecorator) {
                    $methods[] = $this->generateNonMutatingUnsetter($property);
                }
            }
        }

        return $methods;
    }

    private function generateNonMutatingSetter(PropertyInterface $property): MethodGenerator
    {
        $key  = $property->key();
        $keyStr = var_export($key, true);
        $name = $property->name();
        $camelCaseName = $this->request->getOptions()->getPreservePropertyNames()
            ? StringUtils::pascalCasePreserveOuterUnderscores($name)
            : StringUtils::safePascalCase($name);

        $requiredProperty = ($property instanceof OptionalPropertyDecorator) ? $property->unwrap() : $property;

        $annotatedType = $requiredProperty->typeAnnotation();
        $typeHint      = $requiredProperty->typeHint($this->request->getTargetPHPVersion());

        $base = $property;
        while ($base instanceof PropertyDecoratorInterface) {
            /** @var PropertyDecoratorInterface $base */
            $base = $base->unwrap();
        }

        $isArray = $base instanceof PrimitiveArrayProperty
            || $base instanceof ObjectArrayProperty
            || $base instanceof ReferenceArrayProperty
            || $base instanceof TypedArrayProperty;

        $newValidatorExpr = $this->request->getOptions()->getNewValidatorExpr();

        if ($property->isComplex() && !$isArray) {
            $setterValidation = '';
        } else {
            $setterValidation =
                "if (\$validate) {\n"
                . "    \$validator = {$newValidatorExpr};\n"
                . "    \$validator->validate(\$$name, self::\$".PropertyNames::SCHEMA."['properties'][{$keyStr}]);\n"
                . "    if (!\$validator->isValid()) {\n"
                . "        throw new \\InvalidArgumentException(\$validator->getErrors()[0]['message']);\n"
                . "    }\n"
                . "}\n\n";
        }

        $tags = [
            new ParamTag($name, [str_replace('|null', '', $annotatedType)]),
            new ReturnTag('self'),
        ];

        if (PropertyQuery::isDeprecated($property)) {
            $tags[] = new GenericTag('deprecated');
        }

        $docBlock = new DocBlockGenerator(null, null, $tags);
        $docBlock->setWordWrap(false);

        $parameters = [new ParameterGenerator($name, $typeHint)];
        if ($setterValidation !== '') {
            $validateParam = new ParameterGenerator('validate', 'bool');
            $validateParam->setDefaultValue(true);
            $parameters[] = $validateParam;

            $tags[] = new ParamTag('validate', ['bool']);
            $docBlock = new DocBlockGenerator(null, null, $tags);
            $docBlock->setWordWrap(false);
        }

        $body = $setterValidation . "\$clone = clone \$this;\n" .
            "\$clone->$name = \$$name;\n";

        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $body .= "\$clone->".PropertyNames::OPTIONALS."[{$keyStr}] = true;\n";
        }

        $body .= "\nreturn \$clone;";

        $setMethod = new MethodGenerator(
            'with' . $camelCaseName,
            $parameters,
            MethodGenerator::FLAG_PUBLIC,
            $body,
            $docBlock
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $setMethod->setReturnType('self');
        }

        return $setMethod;
    }

    private function generateNonMutatingUnsetter(PropertyInterface $property): MethodGenerator
    {
        $name = $property->name();
        $key  = $property->key();
        $keyStr = var_export($key, true);
        $camelCasedName = $this->request->getOptions()->getPreservePropertyNames()
            ? StringUtils::pascalCasePreserveOuterUnderscores($name)
            : StringUtils::safePascalCase($name);

        $body = "\$clone = clone \$this;\n";
        $body .= "unset(\$clone->$name);\n";

        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $body .= "unset(\$clone->".PropertyNames::OPTIONALS."[{$keyStr}]);\n";
        }

        $body .= "\nreturn \$clone;";

        $unsetMethod = new MethodGenerator(
            'without' . $camelCasedName,
            [],
            MethodGenerator::FLAG_PUBLIC,
            $body,
            new DocBlockGenerator(null, null, [
                new ReturnTag('self'),
            ])
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $unsetMethod->setReturnType('self');
        }

        return $unsetMethod;
    }

    private function generateMutatingSetter(PropertyInterface $property, bool $chainable): MethodGenerator
    {
        $key  = $property->key();
        $keyStr = var_export($key, true);
        $name = $property->name();
        $camelCaseName = $this->request->getOptions()->getPreservePropertyNames()
            ? StringUtils::pascalCasePreserveOuterUnderscores($name)
            : StringUtils::safePascalCase($name);

        $requiredProperty = ($property instanceof OptionalPropertyDecorator) ? $property->unwrap() : $property;
        $annotatedType = $requiredProperty->typeAnnotation();
        $typeHint = $requiredProperty->typeHint($this->request->getTargetPHPVersion());

        $base = $property;
        while ($base instanceof PropertyDecoratorInterface) {
            /** @var PropertyDecoratorInterface $base */
            $base = $base->unwrap();
        }

        $isArray = $base instanceof PrimitiveArrayProperty
            || $base instanceof ObjectArrayProperty
            || $base instanceof ReferenceArrayProperty
            || $base instanceof TypedArrayProperty;

        $newValidatorExpr = $this->request->getOptions()->getNewValidatorExpr();

        if ($property->isComplex() && !$isArray) {
            $setterValidation = '';
        } else {
            $setterValidation =
                "if (\$validate) {\n" .
                "    \$validator = {$newValidatorExpr};\n" .
                "    \$validator->validate(\$$name, self::\$".PropertyNames::SCHEMA."['properties'][{$keyStr}]);\n" .
                "    if (!\$validator->isValid()) {\n" .
                "        throw new \\InvalidArgumentException(\$validator->getErrors()[0]['message']);\n" .
                "    }\n" .
                "}\n\n";
        }

        $tags = [new ParamTag($name, [str_replace('|null', '', $annotatedType)])];
        if ($chainable) {
            $tags[] = new ReturnTag('self');
        }
        if (PropertyQuery::isDeprecated($property)) {
            $tags[] = new GenericTag('deprecated');
        }
        $docBlock = new DocBlockGenerator(null, null, $tags);
        $docBlock->setWordWrap(false);

        $parameters = [new ParameterGenerator($name, $typeHint)];
        if ($setterValidation !== '') {
            $validateParam = new ParameterGenerator('validate', 'bool');
            $validateParam->setDefaultValue(true);
            $parameters[] = $validateParam;

            $tags[] = new ParamTag('validate', ['bool']);
            $docBlock = new DocBlockGenerator(null, null, $tags);
            $docBlock->setWordWrap(false);
        }

        $body = $setterValidation . "\$this->{$name} = \$$name;";
        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $body .= "\n\$this->".PropertyNames::OPTIONALS."[{$keyStr}] = true;";
        }
        if ($chainable) {
            $body .= "\n\nreturn \$this;";
        }

        $setMethod = new MethodGenerator(
            'set' . $camelCaseName,
            $parameters,
            MethodGenerator::FLAG_PUBLIC,
            $body,
            $docBlock
        );

        if ($chainable && $this->request->isAtLeastPHP('7.0')) {
            $setMethod->setReturnType('self');
        } elseif (!$chainable) {
            if ($this->request->isAtLeastPHP('7.1')) {
                $setMethod->setReturnType('void');
            }
        }

        return $setMethod;
    }

    private function generateMutatingUnsetter(PropertyInterface $property, bool $chainable): MethodGenerator
    {
        $key  = $property->key();
        $keyStr = var_export($key, true);
        $name = $property->name();
        $camelCaseName = $this->request->getOptions()->getPreservePropertyNames()
            ? StringUtils::pascalCasePreserveOuterUnderscores($name)
            : StringUtils::safePascalCase($name);

        $body = "\$this->{$name} = null;\n";
        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $body .= "unset(\$this->".PropertyNames::OPTIONALS."[{$keyStr}]);\n";
        }
        if ($chainable) {
            $body .= "\nreturn \$this;";
        }

        $unsetMethod = new MethodGenerator(
            'unset' . $camelCaseName,
            [],
            MethodGenerator::FLAG_PUBLIC,
            $body,
            new DocBlockGenerator(null, null, $chainable ? [new ReturnTag('self')] : [])
        );

        if ($chainable && $this->request->isAtLeastPHP('7.0')) {
            $unsetMethod->setReturnType('self');
        } elseif (!$chainable && $this->request->isAtLeastPHP('7.1')) {
            $unsetMethod->setReturnType('void');
        }

        return $unsetMethod;
    }
}
