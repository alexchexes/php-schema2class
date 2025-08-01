<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

use Helmich\Schema2Class\Generator\Class\Method\BuildMethodFactory;
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
use Laminas\Code\Generator\DocBlock\Tag\ThrowsTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;

/**
 * Factory for creating all methods of a generated class.
 */
class ClassMethodFactory
{
    public function __construct(
        private GeneratorRequest $request,
    ) {}

    /**
     * Generate all methods for a class and ensure unique names.
     *
     * @return MethodGenerator[]
     */
    public function generateMethods(
        PropertyCollection $schemaProperties,
        array $defaults,
        bool $hasOptionalNullable,
    ): array
    {
        $methodGenerators = [
            $this->generateConstructor($schemaProperties),
            ...$this->generateGetterMethods($schemaProperties),
            ...$this->generateSetterMethods($schemaProperties),
            (new BuildMethodFactory($this->request, $schemaProperties, $defaults, $hasOptionalNullable))->generate(),
            $this->generateToArrayMethod($schemaProperties, $defaults),
            $this->generateToStdClassMethod($schemaProperties, $defaults),
            $this->generateValidateMethod(),
            $this->generateCloneMethod($schemaProperties),
            $hasOptionalNullable ? $this->generateIsProvidedMethod() : null,
        ];

        // filter out empty items for methods that won't be generated
        $methodGenerators = array_values(array_filter($methodGenerators));

        // check whether all names are unique and rename if necessary
        $this->ensureUniqueMethodNames($methodGenerators);

        return $methodGenerators;
    }

    private function generateConstructor(PropertyCollection $schemaProperties): ?MethodGenerator
    {
        $params      = [];
        $tags        = [];
        $assignments = [];

        $requiredProperties = $schemaProperties->filter(PropertyCollectionFilterFactory::required());

        foreach ($requiredProperties as $requiredProperty) {
            $paramName = $requiredProperty->name();
            $params[]  = new ParameterGenerator(
                $paramName,
                $requiredProperty->typeHint($this->request->getTargetPHPVersion())
            );

            $tags[] = new ParamTag(
                $paramName,
                [$requiredProperty->typeAnnotation()]
            );

            $assignments[] = "\$this->{$requiredProperty->name()} = \${$paramName};";
        }

        if ($assignments === []) {
            return null;
        }

        $docBlock = new DocBlockGenerator("", "", $tags);
        $docBlock->setWordWrap(false);

        return new MethodGenerator(
            "__construct",
            $params,
            MethodGenerator::FLAG_PUBLIC,
            join("\n", $assignments),
            $docBlock
        );
    }

    private function generateToArrayMethod(PropertyCollection $schemaProperties, array $defaults = []): MethodGenerator
    {
        $tags = [];
        if ($defaults) {
            $tags[] = new ParamTag('includeDefaults', ['bool'], 'Add defaults for missing properties');
        }
        $tags[] = new ReturnTag(['array'], 'Converted array');

        $docBlock = new DocBlockGenerator(
            'Converts this object back to a simple array that can be JSON-serialized',
            null,
            $tags
        );
        $docBlock->setWordWrap(false);

        $params = [];
        if ($defaults) {
            $params[] = new ParameterGenerator('includeDefaults', 'bool', false);
        }

        $body = '$output = [];' . "\n" .
            $schemaProperties->generateTypeToArrayConversionCode('output') . "\n";

        if ($defaults) {
            $body .= "\nif (\$includeDefaults) {\n" .
            "    foreach (self::\$".PropertyNames::DEFAULTS_PROP." as \$k => \$v) {\n" .
            "        if (!array_key_exists(\$k, \$output)) {\n" .
            "            \$output[\$k] = \$v['default'];\n" .
            "        }\n" .
            "    }\n" .
            "}\n";
        }

        $body .= "\nreturn \$output;";

        $method = new MethodGenerator(
            'toArray',
            $params,
            MethodGenerator::FLAG_PUBLIC,
            $body,
            $docBlock
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $method->setReturnType('array');
        }

        return $method;
    }

    private function generateToStdClassMethod(PropertyCollection $schemaProperties, array $defaults = []): MethodGenerator
    {
        $tags = [];
        if ($defaults) {
            $tags[] = new ParamTag('includeDefaults', ['bool'], 'Add defaults for missing properties');
        }
        $tags[] = new ReturnTag(['\\stdClass'], 'Converted object');

        $docBlock = new DocBlockGenerator(
            'Converts this object to a stdClass that can be JSON-serialized',
            null,
            $tags
        );
        $docBlock->setWordWrap(false);

        $params = [];
        if ($defaults) {
            $params[] = new ParameterGenerator('includeDefaults', 'bool', false);
        }

        $body = '$output = new \\stdClass();' . "\n" .
            $schemaProperties->generateTypeToStdClassConversionCode('output') . "\n";
        
        $arrayToObjectExpr = $this->request->getOptions()->getArrayToObjectExpr();

        if ($defaults) {
            $body .= "\nif (\$includeDefaults) {\n" .
            "    foreach (self::\$".PropertyNames::DEFAULTS_PROP." as \$k => \$v) {\n" .
            "        if (!property_exists(\$output, (string) \$k)) {\n" .
            "            \$output->{\$k} = (isset(\$v['type']) && \$v['type'] === 'object')\n" .
            "               ? {$arrayToObjectExpr}(\$v['default'])\n" .
            "               : \$v['default'];\n" .
            "        }\n" .
            "    }\n" .
            "}\n";
        }

        $body .= "\nreturn \$output;";

        $method = new MethodGenerator(
            'toStdClass',
            $params,
            MethodGenerator::FLAG_PUBLIC,
            $body,
            $docBlock
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $method->setReturnType('\\stdClass');
        }

        return $method;
    }

    private function generateValidateMethod(): MethodGenerator
    {
        $docBlock = new DocBlockGenerator(
            'Validates an input array',
            null,
            [
                new ParamTag('input', ['array|object'], 'Input data'),
                new ParamTag('return', ['bool'], 'Return instead of throwing errors'),
                new ReturnTag(['bool'], 'Validation result'),
                new ThrowsTag('\\InvalidArgumentException'),
            ]
        );
        $docBlock->setWordWrap(false);

        $newValidatorExpr = $this->request->getOptions()->getNewValidatorExpr();
        $arrayToObjectExpr = $this->request->getOptions()->getArrayToObjectExpr();

        $method = new MethodGenerator(
            'validateInput',
            [
                new ParameterGenerator('input', $this->request->isAtLeastPHP('8.0') ? 'array|object' : null),
                new ParameterGenerator('return', $this->request->isAtLeastPHP('7.0') ? 'bool' : null, false),
            ],
            MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC,
            '$validator = ' . $newValidatorExpr . ";\n" .
            "\$input = is_array(\$input) ? {$arrayToObjectExpr}(\$input) : \$input;" . "\n" .
            '$validator->validate($input, self::$'.PropertyNames::SCHEMA_PROP.');' . "\n\n" .
            'if (!$validator->isValid() && !$return) {' . "\n" .
            (
                $this->request->isAtLeastPHP('7.0')
                ? '    $errors = array_map(function(array $e): string {' . "\n"
                : '    $errors = array_map(function($e) {' . "\n"
            ) .
            '        return ($e["property"] ? $e["property"] . ": " : "") . $e["message"];' . "\n" .
            '    }, $validator->getErrors());' . "\n" .
            '    throw new \\InvalidArgumentException(join(".\n", $errors));' . "\n" .
            '}' . "\n\n" .
            'return $validator->isValid();',
            $docBlock
        );
        if ($this->request->isAtLeastPHP('7.0')) {
            $method->setReturnType('bool');
        }

        return $method;
    }

    private function generateCloneMethod(PropertyCollection $schemaProperties): ?MethodGenerator
    {
        $clones = [];
        foreach ($schemaProperties as $property) {
            $c = $property->cloneProperty();
            if ($c !== null) {
                $clones[] = $c;
            }
        }

        if ($clones === []) {
            return null;
        }

        return new MethodGenerator(
            '__clone',
            [],
            MethodGenerator::FLAG_PUBLIC,
            join("\n", $clones)
        );
    }

    private function generateIsProvidedMethod(): MethodGenerator
    {
        $doc = new DocBlockGenerator(
            'Checks if an optional nullable property was explicitly set',
            null,
            [
                new ParamTag('propertyName', ['string'], 'Property name to check (exactly as it appears in the schema)'),
                new ReturnTag('bool'),
            ]
        );
        $doc->setWordWrap(false);

        $method = new MethodGenerator(
            'isOptionalProvided',
            [new ParameterGenerator('propertyName', 'string')],
            MethodGenerator::FLAG_PUBLIC,
            'return array_key_exists($propertyName, $this->'.PropertyNames::OPTIONALS_PROP.');',
            $doc
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $method->setReturnType('bool');
        }

        return $method;
    }

    private function generateGetterMethods(PropertyCollection $schemaProperties): array
    {
        if ($this->request->getNoGetters()) {
            return [];
        }

        $methods = [];

        $schemaProperties = $schemaProperties->filter(PropertyCollectionFilterFactory::withoutDeprecatedAndSameName($schemaProperties));

        foreach ($schemaProperties as $property) {
            $methods[] = $this->generateGetterMethod($property);
        }

        return $methods;
    }

    private function generateGetterMethod(PropertyInterface $property): MethodGenerator
    {
        $name           = $property->name();
        if ($this->request->getOptions()->getPreservePropertyNames()) {
            $camelCasedName = StringUtils::pascalCasePreserveOuterUnderscores($property->name());
        } else {
            $camelCasedName = StringUtils::safePascalCase($property->name());
        }
        $annotatedType  = $property->typeAnnotation();

        $tags = [new ReturnTag($annotatedType)];
        if (PropertyQuery::isDeprecated($property)) {
            $tags[] = new GenericTag('deprecated');
        }

        $docBlockGenerator = new DocBlockGenerator(null, $property->description(), $tags);
        $docBlockGenerator->setWordWrap(false);

        $getMethod = new MethodGenerator(
            name: 'get' . $camelCasedName,
            parameters: [],
            flags: MethodGenerator::FLAG_PUBLIC,
            body: "return \$this->$name;",
            docBlock: $docBlockGenerator,
        );

        if ($this->request->isAtLeastPHP('7.0')) {
            $typeHint = $property->typeHint($this->request->getTargetPHPVersion());
            if ($typeHint !== null) {
                $getMethod->setReturnType($typeHint);

                if ($typeHint[0] === '?') {
                    $getMethod->setBody("return \$this->{$name} ?? null;");
                }
            }
        }

        return $getMethod;
    }

    private function generateSetterMethods(PropertyCollection $schemaProperties): array
    {
        if ($this->request->getNoSetters()) {
            return [];
        }

        $mutable = $this->request->getMutableSetters();

        $methods    = [];
        $schemaProperties = $schemaProperties->filter(PropertyCollectionFilterFactory::withoutDeprecatedAndSameName($schemaProperties));

        foreach ($schemaProperties as $property) {
            if ($mutable !== null) {
                $methods[] = $this->generateMutableSetterMethod($property, $mutable === 'chainable');
                if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
                    $methods[] = $this->generateMutableUnsetterMethod($property, $mutable === 'chainable');
                }
            } else {
                $methods[] = $this->generateSetterMethod($property);

                if ($property instanceof OptionalPropertyDecorator) {
                    $methods[] = $this->generateUnsetterMethod($property);
                }
            }
        }

        return $methods;
    }

    private function generateSetterMethod(PropertyInterface $property): MethodGenerator
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
                . "    \$validator->validate(\$$name, self::\$".PropertyNames::SCHEMA_PROP."['properties'][{$keyStr}]);\n"
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
            $body .= "\$clone->".PropertyNames::OPTIONALS_PROP."[{$keyStr}] = true;\n";
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

    private function generateMutableSetterMethod(PropertyInterface $property, bool $chainable): MethodGenerator
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
                "    \$validator->validate(\$$name, self::\$".PropertyNames::SCHEMA_PROP."['properties'][{$keyStr}]);\n" .
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
            $body .= "\n\$this->".PropertyNames::OPTIONALS_PROP."[{$keyStr}] = true;";
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

    private function generateMutableUnsetterMethod(PropertyInterface $property, bool $chainable): MethodGenerator
    {
        $key  = $property->key();
        $keyStr = var_export($key, true);
        $name = $property->name();
        $camelCaseName = $this->request->getOptions()->getPreservePropertyNames()
            ? StringUtils::pascalCasePreserveOuterUnderscores($name)
            : StringUtils::safePascalCase($name);

        $body = "\$this->{$name} = null;\n";
        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $body .= "unset(\$this->".PropertyNames::OPTIONALS_PROP."[{$keyStr}]);\n";
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

    private function generateUnsetterMethod(PropertyInterface $property): MethodGenerator
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
            $body .= "unset(\$clone->".PropertyNames::OPTIONALS_PROP."[{$keyStr}]);\n";
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

    /** 
     * @param MethodGenerator[] $methodGenerators 
     */
    private function ensureUniqueMethodNames(array $methodGenerators): void
    {
        $reservedMethodNames = [
            'buildFromInput',
            'toArray',
            'toStdClass',
            'validateInput',
            'clone',
            '__construct',
            '__destruct',
            '__get',
            '__set',
            '__call',
            '__isset',
            '__unset',
            '__sleep',
            '__wakeup',
            '__toString',
            '__invoke',
            '__debugInfo',
            '__clone',
        ];

        $reserved = array_map('strtolower', $reservedMethodNames);
        $used = [];

        foreach ($methodGenerators as $method) {
            $name   = $method->getName();
            $lcName = strtolower($name);

            if (!in_array($lcName, $used, true) && in_array($lcName, $reserved, true)) {
                $used[] = $lcName;
                continue;
            }

            $candidate = $name;
            $prefix    = '';
            $base      = $name;

            if (preg_match('/^(set|get|without|with)(.+)$/', $name, $m)) {
                $prefix = $m[1];
                $base   = $m[2];
            }

            $i = 1;
            while (in_array(strtolower($candidate), $used, true) || in_array(strtolower($candidate), $reserved, true)) {
                if ($prefix !== '') {
                    $suffix   = $i > 1 ? $base . '_' . ($i - 1) : $base;
                    $candidate = $prefix . '_' . $suffix;
                } else {
                    $candidate = $name . '_' . $i;
                }
                $i++;
            }

            if ($candidate !== $name) {
                $method->setName($candidate);
            }

            $used[] = strtolower($candidate);
        }
    }
}
