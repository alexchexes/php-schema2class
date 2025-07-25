<?php
declare(strict_types=1);

namespace Helmich\Schema2Class\Generator\Class;

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
class MethodFactory
{
    public function __construct(private GeneratorRequest $generatorRequest)
    {
    }

    public function generateConstructor(PropertyCollection $properties): ?MethodGenerator
    {
        $params      = [];
        $tags        = [];
        $assignments = [];

        $requiredProperties = $properties->filter(PropertyCollectionFilterFactory::required());

        foreach ($requiredProperties as $requiredProperty) {
            $paramName = $requiredProperty->name();
            $params[]  = new ParameterGenerator(
                $paramName,
                $requiredProperty->typeHint($this->generatorRequest->getTargetPHPVersion())
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

    public function generateBuildMethod(PropertyCollection $properties, array $defaults = [], bool $hasOptionalNullable = false): MethodGenerator
    {
        $inputArgName = 'input';
        $validateArgName = 'validate';
        $materializeArgName = 'materializeDefaults';

        $paramType = null;
        if ($this->generatorRequest->isAtLeastPHP('8.0')) {
            $paramType = 'array|object';
        }

        $inputParam = new ParameterGenerator($inputArgName, $paramType);
        $validationParam = new ParameterGenerator($validateArgName, 'bool', true);
        $materializeParam = $defaults ? new ParameterGenerator($materializeArgName, 'bool', false) : null;

        $params = [$inputParam, $validationParam];
        if ($defaults) {
            $params[] = $materializeParam;
        }

        $docBlockParams = [
            new ParamTag($inputArgName, ['array|object'], 'Input data'),
            new ParamTag($validateArgName, ['bool'], 'Set this to false to skip validation; use at own risk'),
        ];
        if ($defaults) {
            $docBlockParams[] = new ParamTag($materializeArgName, ['bool'], 'Apply defaults defined in schema when missing');
        }
        $docBlockParams[] = new ReturnTag([$this->generatorRequest->getTargetClass()], 'Created instance');
        $docBlockParams[] = new ThrowsTag('\\InvalidArgumentException');

        $docBlock = new DocBlockGenerator(
            'Builds a new instance from an input array',
            null,
            $docBlockParams
        );
        $docBlock->setWordWrap(false);

        $body = $this->generateBuildMethodBody(
            inputArgName: $inputArgName,
            validateArgName: $validateArgName,
            materializeArgName: $materializeArgName,
            defaults: $defaults,
            properties: $properties,
            hasOptionalNullable: $hasOptionalNullable,
        );

        $method = new MethodGenerator(
            'buildFromInput',
            $params,
            MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC,
            $body,
            $docBlock
        );

        if ($this->generatorRequest->isAtLeastPHP('7.0')) {
            $method->setReturnType(
                $this->generatorRequest->getTargetNamespace() . '\\' . $this->generatorRequest->getTargetClass()
            );
        }

        return $method;
    }

    private function generateBuildMethodBody(
        string $inputArgName,
        string $validateArgName,
        string $materializeArgName,
        array $defaults,
        PropertyCollection $properties,
        bool $hasOptionalNullable,
    ): string {
        $objVarName = 'obj';
        if ($properties->hasPropertyWithName($objVarName)) {
            $objVarName = '_obj';
            $i = 2;
            while ($properties->hasPropertyWithName($objVarName)) {
                $objVarName = '_obj' . $i;
                $i++;
            }
        }

        $optionalProperties = $properties->filter(PropertyCollectionFilterFactory::optional());
        $assignments = [];
        foreach ($optionalProperties as $optionalProperty) {
            $name = $optionalProperty->name();
            $assignments[] = "\${$objVarName}->{$name} = \${$name};";
        }

        $inputArgAlias = $inputArgName;
        if ($properties->hasPropertyWithKey($inputArgAlias)) {
            $inputArgAlias = '_input';
            $i = 2;
            while ($properties->hasPropertyWithKey($inputArgAlias)) {
                $inputArgAlias = '_input' . $i;
                $i++;
            }
        }

        $validateArgAlias = $validateArgName;
        if ($properties->hasPropertyWithName($validateArgAlias)) {
            $validateArgAlias = '_validate';
            $i = 2;
            while ($properties->hasPropertyWithName($validateArgAlias)) {
                $validateArgAlias = '_validate' . $i;
                $i++;
            }
        }

        $materializeArgAlias = $materializeArgName;
        if ($defaults && $properties->hasPropertyWithName($materializeArgAlias)) {
            $materializeArgAlias = '_materializeDefaults';
            $i = 2;
            while ($properties->hasPropertyWithName($materializeArgAlias)) {
                $materializeArgAlias = '_materializeDefaults' . $i;
                $i++;
            }
        }

        $this->generatorRequest->setCurrValidateArgAlias($validateArgAlias);
        $this->generatorRequest->setCurrMaterializeArgAlias($defaults ? $materializeArgAlias : null);

        $requiredProperties = $properties->filter(PropertyCollectionFilterFactory::required());
        $constructorParams = [];
        foreach ($requiredProperties as $requiredProperty) {
            $constructorParams[] = '$' . $requiredProperty->name();
        }

        $aliasesLines = '';
        if ($inputArgName !== $inputArgAlias) {
            $aliasesLines .= "\${$inputArgAlias} = \${$inputArgName};\nunset(\${$inputArgName});\n";
        }
        if ($validateArgName !== $validateArgAlias) {
            $aliasesLines .= "\${$validateArgAlias} = \${$validateArgName};\nunset(\${$validateArgName});\n";
        }
        if ($materializeArgName !== $materializeArgAlias) {
            $aliasesLines .= "\${$materializeArgAlias} = \${$materializeArgName};\nunset(\${$materializeArgName});\n";
        }
        if ($aliasesLines) {
            $aliasesLines .= "\n";
        }

        $inputGuard = '';
        if (!$this->generatorRequest->isAtLeastPHP('8.0')) {
            $inputGuard =
                "if (!is_array(\$$inputArgAlias) && !is_object(\$$inputArgAlias)) {\n" .
                "    throw new \\InvalidArgumentException(\n" .
                "        'Input to buildFromInput must be array or object, got ' . gettype(\$$inputArgAlias)\n" .
                "    );\n" .
                "}\n\n";
        }

        $materializeLine = '';

        if ($defaults) {
            $convertInputLine =
                "\$$inputArgAlias = is_array(\$$inputArgAlias)\n" .
                "    ? \\JsonSchema\\Validator::arrayToObjectRecursive(\$$inputArgAlias)\n" .
                "    : (\$$materializeArgAlias ? clone \$$inputArgAlias : \$$inputArgAlias);\n\n";

            $materializeLine =
                "if (\$$materializeArgAlias) {\n" .
                "    foreach (self::\$_defaults as \$__k => \$__v) {\n" .
                "        if (!property_exists(\$$inputArgAlias, (string) \$__k)) {\n" .
                "           \${$inputArgAlias}->{\$__k} = (\$__v['type'] ?? null) === 'object'\n" .
                "               ? \\JsonSchema\\Validator::arrayToObjectRecursive(\$__v['default'])\n" .
                "               : \$__v['default'];\n" .
                "        }\n" .
                "    }\n" .
                "}\n\n";
        } else {
            $convertInputLine =
                "\$$inputArgAlias = is_array(\$$inputArgAlias) ? \\JsonSchema\\Validator::arrayToObjectRecursive(\$$inputArgAlias) : \$$inputArgAlias;\n";
        }

        $body =
            $aliasesLines .
            $inputGuard .
            $convertInputLine .
            $materializeLine .
            "if (\${$validateArgAlias}) {\n" .
            "    static::validateInput(\$$inputArgAlias);\n" .
            "}\n\n" .
            ($hasOptionalNullable ? "\$__providedOptionals = [];\n" : '') .
            $properties->generateInputToTypeConversionCode($inputArgAlias, object: true) . "\n\n" .
            "\${$objVarName} = new self(" . join(", ", $constructorParams) . ");" . "\n" .
            join("\n", $assignments) . "\n" .
            ($hasOptionalNullable ? "\${$objVarName}->_providedOptionals = \$__providedOptionals;\n" : '') .
            "return \${$objVarName};";

        return $body;
    }

    public function generateToArrayMethod(PropertyCollection $properties, array $defaults = []): MethodGenerator
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
            $properties->generateTypeToArrayConversionCode('output') . "\n";

        if ($defaults) {
            $body .= "\nif (\$includeDefaults) {\n" .
            "    foreach (self::\$_defaults as \$k => \$v) {\n" .
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

        if ($this->generatorRequest->isAtLeastPHP('7.0')) {
            $method->setReturnType('array');
        }

        return $method;
    }

    public function generateToStdClassMethod(PropertyCollection $properties, array $defaults = []): MethodGenerator
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
            $properties->generateTypeToStdClassConversionCode('output') . "\n";

        if ($defaults) {
            $body .= "\nif (\$includeDefaults) {\n" .
            "    foreach (self::\$_defaults as \$k => \$v) {\n" .
            "        if (!property_exists(\$output, (string) \$k)) {\n" .
            "            \$output->{\$k} = (isset(\$v['type']) && \$v['type'] === 'object')\n" .
            "               ? \\JsonSchema\\Validator::arrayToObjectRecursive(\$v['default'])\n" .
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

        if ($this->generatorRequest->isAtLeastPHP('7.0')) {
            $method->setReturnType('\\stdClass');
        }

        return $method;
    }

    public function generateValidateMethod(): MethodGenerator
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

        $newValidatorClassExpr = $this->generatorRequest->getOptions()->getNewValidatorClassExpr();

        $method = new MethodGenerator(
            'validateInput',
            [
                new ParameterGenerator('input', $this->generatorRequest->isAtLeastPHP('8.0') ? 'array|object' : null),
                new ParameterGenerator('return', $this->generatorRequest->isAtLeastPHP('7.0') ? 'bool' : null, false),
            ],
            MethodGenerator::FLAG_PUBLIC | MethodGenerator::FLAG_STATIC,
            '$validator = ' . $newValidatorClassExpr . ";\n" .
            '$input = is_array($input) ? \\JsonSchema\\Validator::arrayToObjectRecursive($input) : $input;' . "\n" .
            '$validator->validate($input, self::$schema);' . "\n\n" .
            'if (!$validator->isValid() && !$return) {' . "\n" .
            (
                $this->generatorRequest->isAtLeastPHP('7.0')
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
        if ($this->generatorRequest->isAtLeastPHP('7.0')) {
            $method->setReturnType('bool');
        }

        return $method;
    }

    public function generateCloneMethod(PropertyCollection $properties): ?MethodGenerator
    {
        $clones = [];
        foreach ($properties as $property) {
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

    public function generateIsProvidedMethod(): MethodGenerator
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
            'return array_key_exists($propertyName, $this->_providedOptionals);',
            $doc
        );

        if ($this->generatorRequest->isAtLeastPHP('7.0')) {
            $method->setReturnType('bool');
        }

        return $method;
    }

    public function generateGetterMethods(PropertyCollection $properties): array
    {
        if ($this->generatorRequest->getNoGetters()) {
            return [];
        }

        $methods = [];

        $properties = $properties->filter(PropertyCollectionFilterFactory::withoutDeprecatedAndSameName($properties));

        foreach ($properties as $property) {
            $methods[] = $this->generateGetterMethod($property);
        }

        return $methods;
    }

    public function generateGetterMethod(PropertyInterface $property): MethodGenerator
    {
        $name           = $property->name();
        if ($this->generatorRequest->getOptions()->getPreservePropertyNames()) {
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

        if ($this->generatorRequest->isAtLeastPHP('7.0')) {
            $typeHint = $property->typeHint($this->generatorRequest->getTargetPHPVersion());
            if ($typeHint !== null) {
                $getMethod->setReturnType($typeHint);

                if ($typeHint[0] === '?') {
                    $getMethod->setBody("return \$this->{$name} ?? null;");
                }
            }
        }

        return $getMethod;
    }

    public function generateSetterMethods(PropertyCollection $properties): array
    {
        if ($this->generatorRequest->getNoSetters()) {
            return [];
        }

        $mutable = $this->generatorRequest->getMutableSetters();

        $methods    = [];
        $properties = $properties->filter(PropertyCollectionFilterFactory::withoutDeprecatedAndSameName($properties));

        foreach ($properties as $property) {
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

    public function generateSetterMethod(PropertyInterface $property): MethodGenerator
    {
        $key  = $property->key();
        $keyStr = var_export($key, true);
        $name = $property->name();
        $camelCaseName = $this->generatorRequest->getOptions()->getPreservePropertyNames()
            ? StringUtils::pascalCasePreserveOuterUnderscores($name)
            : StringUtils::safePascalCase($name);

        $requiredProperty = ($property instanceof OptionalPropertyDecorator) ? $property->unwrap() : $property;

        $annotatedType = $requiredProperty->typeAnnotation();
        $typeHint      = $requiredProperty->typeHint($this->generatorRequest->getTargetPHPVersion());

        $base = $property;
        while ($base instanceof PropertyDecoratorInterface) {
            /** @var PropertyDecoratorInterface $base */
            $base = $base->unwrap();
        }

        $isArray = $base instanceof PrimitiveArrayProperty
            || $base instanceof ObjectArrayProperty
            || $base instanceof ReferenceArrayProperty
            || $base instanceof TypedArrayProperty;

        $newValidatorClassExpr = $this->generatorRequest->getOptions()->getNewValidatorClassExpr();

        if ($property->isComplex() && !$isArray) {
            $setterValidation = '';
        } else {
            $setterValidation =
                "if (\$validate) {\n"
                . "    \$validator = {$newValidatorClassExpr};\n"
                . "    \$validator->validate(\$$name, self::\$schema['properties'][{$keyStr}]);\n"
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
            $body .= "\$clone->_providedOptionals[{$keyStr}] = true;\n";
        }

        $body .= "\nreturn \$clone;";

        $setMethod = new MethodGenerator(
            'with' . $camelCaseName,
            $parameters,
            MethodGenerator::FLAG_PUBLIC,
            $body,
            $docBlock
        );

        if ($this->generatorRequest->isAtLeastPHP('7.0')) {
            $setMethod->setReturnType('self');
        }

        return $setMethod;
    }

    private function generateMutableSetterMethod(PropertyInterface $property, bool $chainable): MethodGenerator
    {
        $key  = $property->key();
        $keyStr = var_export($key, true);
        $name = $property->name();
        $camelCaseName = $this->generatorRequest->getOptions()->getPreservePropertyNames()
            ? StringUtils::pascalCasePreserveOuterUnderscores($name)
            : StringUtils::safePascalCase($name);

        $requiredProperty = ($property instanceof OptionalPropertyDecorator) ? $property->unwrap() : $property;
        $annotatedType = $requiredProperty->typeAnnotation();
        $typeHint = $requiredProperty->typeHint($this->generatorRequest->getTargetPHPVersion());

        $base = $property;
        while ($base instanceof PropertyDecoratorInterface) {
            /** @var PropertyDecoratorInterface $base */
            $base = $base->unwrap();
        }

        $isArray = $base instanceof PrimitiveArrayProperty
            || $base instanceof ObjectArrayProperty
            || $base instanceof ReferenceArrayProperty
            || $base instanceof TypedArrayProperty;

        $newValidatorClassExpr = $this->generatorRequest->getOptions()->getNewValidatorClassExpr();

        if ($property->isComplex() && !$isArray) {
            $setterValidation = '';
        } else {
            $setterValidation =
                "if (\$validate) {\n" .
                "    \$validator = {$newValidatorClassExpr};\n" .
                "    \$validator->validate(\$$name, self::\$schema['properties'][{$keyStr}]);\n" .
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
            $body .= "\n\$this->_providedOptionals[{$keyStr}] = true;";
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

        if ($chainable && $this->generatorRequest->isAtLeastPHP('7.0')) {
            $setMethod->setReturnType('self');
        } elseif (!$chainable) {
            if ($this->generatorRequest->isAtLeastPHP('7.1')) {
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
        $camelCaseName = $this->generatorRequest->getOptions()->getPreservePropertyNames()
            ? StringUtils::pascalCasePreserveOuterUnderscores($name)
            : StringUtils::safePascalCase($name);

        $body = "\$this->{$name} = null;\n";
        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $body .= "unset(\$this->_providedOptionals[{$keyStr}]);\n";
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

        if ($chainable && $this->generatorRequest->isAtLeastPHP('7.0')) {
            $unsetMethod->setReturnType('self');
        } elseif (!$chainable && $this->generatorRequest->isAtLeastPHP('7.1')) {
            $unsetMethod->setReturnType('void');
        }

        return $unsetMethod;
    }

    public function generateUnsetterMethod(PropertyInterface $property): MethodGenerator
    {
        $name = $property->name();
        $key  = $property->key();
        $keyStr = var_export($key, true);
        $camelCasedName = $this->generatorRequest->getOptions()->getPreservePropertyNames()
            ? StringUtils::pascalCasePreserveOuterUnderscores($name)
            : StringUtils::safePascalCase($name);

        $body = "\$clone = clone \$this;\n";
        $body .= "unset(\$clone->$name);\n";

        if ($property instanceof OptionalPropertyDecorator && $property->isOptionalNullable()) {
            $body .= "unset(\$clone->_providedOptionals[{$keyStr}]);\n";
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

        if ($this->generatorRequest->isAtLeastPHP('7.0')) {
            $unsetMethod->setReturnType('self');
        }

        return $unsetMethod;
    }

    public function ensureUniqueMethodNames(array $methods): void
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

        foreach ($methods as $method) {
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
